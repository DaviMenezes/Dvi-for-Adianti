<?php

namespace App;

use Adianti\Base\Lib\Control\TAction;
use Adianti\Base\Lib\Core\AdiantiApplicationConfig;
use Adianti\Base\Lib\Core\AdiantiCoreApplication;
use Adianti\Base\Lib\Registry\TSession;
use Adianti\Base\Lib\Widget\Dialog\TMessage;
use App\Control\Admin\LoginForm;
use Dvi\Adianti\Route;

/**
 *  TApplication
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 */
class TApplication extends AdiantiCoreApplication
{
    public static function run($debug = false)
    {
        new TSession;

        if ($_REQUEST) {
            $ini    = AdiantiApplicationConfig::get();
            $class  = isset($_REQUEST['class']) ? $_REQUEST['class'] : '';
            $class = Route::getClassName($class);
            $public = in_array($class, $ini['permission']['public_classes']);

            if (TSession::getValue('logged')) { // logged
                $programs = (array) TSession::getValue('programs'); // programs with permission
                $programs = array_merge($programs, self::getDefaultPermissions());

                if (isset($programs[$class]) or $public) {
                    //custom:dvi-davimenezes
                    if ($class != Route::getClassName(LoginForm::class)) {
                        TSession::setValue('last_url', $_REQUEST);
                    }
                    parent::run($debug);
                    //end-custom:dvi-davimenezes
                } else {
                    new TMessage('error', _t('Permission denied'));
                }
            } elseif ($class == 'LoginForm' or $public) {
                parent::run($debug);
            } else {
                new TMessage('error', _t('Permission denied'), new TAction(array(LoginForm::class,'onLogout')));
            }
        }
    }

    /**
     * Return default programs for logged users
     */
    public static function getDefaultPermissions()
    {
        return array('TStandardSeek' => true,
            'LoginForm' => true,
            'AdiantiMultiSearchService' => true,
            'AdiantiUploaderService' => true,
            'AdiantiAutocompleteService' => true,
            'EmptyPage' => true,
            'MessageList' => true,
            'SystemDocumentUploaderService' => true,
            'NotificationList' => true,
            'SearchBox' => true,
            'SearchInputBox' => true,
            'SystemPageService' => true,
            'SystemPageBatchUpdate' => true,
            'SystemPageUpdate' => true,
            'SystemMenuUpdate' => true);
    }
}
