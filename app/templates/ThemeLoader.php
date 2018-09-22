<?php

namespace App\Templates;

use Adianti\Base\App\Lib\Menu\AdiantiMenuBuilder;
use Adianti\Base\App\Lib\Util\ApplicationTranslator;
use Adianti\Base\Lib\Control\TPage;
use Adianti\Base\Lib\Core\AdiantiApplicationConfig;
use Adianti\Base\Lib\Core\AdiantiCoreApplication;
use Adianti\Base\Lib\Registry\TSession;
use Adianti\Base\Lib\Widget\Dialog\TMessage;
use App\Control\Admin\LoginForm;
use App\TApplication;
use Dvi\Adianti\Control\DviControl;
use Dvi\Adianti\Route;
use Exception;

/**
 * App ThemeLoader
 *
 * @package    App
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @link https://github.com/DaviMenezes
 */
class ThemeLoader
{
    private $html_header;
    private $content_body;
    private $application_name;
    private $theme;
    private $class;
    private $public;
    private $token;

    public function __construct($ini)
    {
        $this->theme = $ini['general']['theme'];
        $this->class = isset($_REQUEST['class']) ? $_REQUEST['class'] : 'EmptyPage';
        $this->public = in_array($this->class, $ini['permission']['public_classes']);

        if (TSession::getValue('logged')) {
            $this->html_header = file_get_contents("app/templates/{$this->theme}/layout_header.html");
        } else {
            $this->html_header = file_get_contents("app/templates/{$this->theme}/login.html");
        }
    }

    public function loadLoginForm()
    {
        $this->createHeader();
        $this->showHeader();
        AdiantiCoreApplication::loadPage($this->class, '', $_REQUEST);
    }

    public function loadPage()
    {
        $this->createHeader();
        $this->showHeader();

        $this->setLayoutBody();

        if (isset($this->class)) {
            if ($this->accessDenied()) {
                $this->createApplication('<b>404 Página não encontrada</b>');
                $this->showContentPage();
                return;
            }

            if ($this->class != Route::getClassName(LoginForm::class)) {
                TSession::setValue('last_url', $_REQUEST);
            }
            $this->createApplication();
            $this->showContentPage();
        }
    }

    public function createFooter()
    {
        $this->content_body .= file_get_contents("app/templates/{$this->theme}/layout_footer.html");
    }

    public function createMenu()
    {
        $menu_string = AdiantiMenuBuilder::parse('menu.xml', $this->theme);
        $this->replaceContent('{MENU}', $menu_string);
    }

    public function replaceBodyBuilder()
    {
        if ((TSession::getValue('login') == 'admin') && isset($this->token)) {
            $this->replaceContent('{IF-BUILDER}', '');
            $this->replaceContent('{/IF-BUILDER}', '');
        }
    }

    public function createApplication($page_content = null)
    {
        $ini = AdiantiApplicationConfig::get();
        if ($ini['general']['environment'] == 'development') {
            $this->createTheme();
            $this->setThemeContent($page_content ?? $this->getContentBody());
            $this->replaceTags();
        } else {
            try {
                $this->createTheme();
                $this->setThemeContent($page_content ?? $this->getContentBody());
                $this->replaceTags();
            } catch (Exception $e) {
                $result = ob_get_contents();
                ob_clean();
                $result .= $e->getMessage();

                $this->content_body = str_replace('{ADIANTI_DIV_CONTENT}', $result ?? $e->getMessage(), $this->content_body);
            }
        }
    }

    public function getContentBody()
    {
        $class_path = Route::getPath($this->class);

        ob_start();
        /**@var DviControl $obj*/
        $obj = new $class_path($_REQUEST);
        $obj->show($_REQUEST);

        $result = ob_get_contents();
        ob_clean();
        return $result;
    }

    public function replaceTags()
    {
        $this->replaceContent('{IF-BUILDER}', '<!--');
        $this->replaceContent('{/IF-BUILDER}', '-->');
        $this->content_body = ApplicationTranslator::translateTemplate($this->content_body);

        $this->replaceContent('{class}', $this->class);
        $this->replaceContent('{template}', $this->theme);
        $this->replaceContent('{username}', TSession::getValue('username'));
        $this->replaceContent('{usermail}', TSession::getValue('usermail'));
        $this->replaceContent('{frontpage}', TSession::getValue('frontpage'));
        $this->replaceContent('{query_string}', $_SERVER["QUERY_STRING"]);
        $this->replaceContent('{APPNAME}', $this->application_name);
    }

    public function replaceAPPNAME()
    {
        $this->html_header = str_replace('{APPNAME}', APPLICATION_NAME, $this->html_header);
        return $this;
    }

    public function showHeader()
    {
        $this->html_header = ApplicationTranslator::translateTemplate($this->html_header);
        echo $this->html_header;
    }

    public function appName($application_name)
    {
        $this->application_name = $application_name;
        return $this;
    }

    public function theme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    public function class(string $class)
    {
        $this->class = $class;
        return $this;
    }

    private function replaceContent($search, $replace)
    {
        $this->content_body = str_replace($search, $replace, $this->content_body);
    }

    public function replaceHeaderContent($search, $replace)
    {
        $this->html_header = str_replace($search, $replace, $this->html_header);
    }

    public function load()
    {
        if (!TSession::getValue('logged') or $this->public) {
            $this->loadLoginForm();
            return;
        }
        TSession::setValue('last_url', $_REQUEST);
        $this->loadPage();
    }

    public function getTheme()
    {
        return $this->theme;
    }

    public function createHeader()
    {
        $libraries = file_get_contents('app/templates/' . $this->theme . '/libraries.html');
        $this->replaceHeaderContent('{LIBRARIES}', $libraries);
        $this->replaceHeaderContent('{template}', $this->theme);
        $this->replaceHeaderContent('{HEAD}', TPage::getLoadedCSS() . TPage::getLoadedJS());
        $this->replaceHeaderContent('{APPNAME}', APPLICATION_NAME);
    }

    public function showContentPage()
    {
        echo $this->content_body;
    }

    protected function createTheme()
    {
        $this->replaceBodyBuilder();

        $this->createMenu();

        $this->createFooter();
    }

    private function setThemeContent($content)
    {
        $this->content_body = str_replace('{ADIANTI_DIV_CONTENT}', $content, $this->content_body);
    }

    protected function accessDenied():bool
    {
        $programs = (array)TSession::getValue('programs'); // programs with permission

        $programs = array_merge($programs, TApplication::getDefaultPermissions());

        $ini = AdiantiApplicationConfig::get();

        $public = in_array($this->class, $ini['permission']['public_classes']);

        if (!isset($programs[$this->class]) and !$public) {
            return true;
        }
        return false;
    }

    protected function setLayoutBody()
    {
        $this->content_body = file_get_contents("app/templates/{$this->theme}/layout_body.html");
    }
}
