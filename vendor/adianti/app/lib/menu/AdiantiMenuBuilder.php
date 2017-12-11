<?php
namespace Adianti\App\Lib\Menu;

use Adianti\Modules\Admin\Model\SystemPermission;
use Adianti\Widget\Base\TElement;
use Adianti\Widget\Menu\TMenu;
use Adianti\Widget\Menu\TMenuBar;
use SimpleXMLElement;

class AdiantiMenuBuilder
{
    public static function parse($file, $theme)
    {
        switch ($theme)
        {
            case 'theme1':
                ob_start();
                $callback = array(SystemPermission::class, 'checkPermission');
                $menu = TMenuBar::newFromXML('menu.xml', $callback);
                $menu->show();
                $menu_string = ob_get_clean();
                return $menu_string;
                break;
            case 'theme2':
                ob_start();
                $callback = array(SystemPermission::class, 'checkPermission');
                $xml = new SimpleXMLElement(file_get_contents('menu.xml'));
                $menu = new TMenu($xml, $callback, 1, 'nav collapse', '');
                $menu->class = 'nav';
                $menu->id    = 'side-menu';
                $menu->show();
                $menu_string = ob_get_clean();
                return $menu_string;
                break;
            case 'theme3':
                ob_start();
                $callback = array(SystemPermission::class, 'checkPermission');
                $xml = new SimpleXMLElement(file_get_contents('menu.xml'));
                $menu = new TMenu($xml, $callback, 1, 'treeview-menu', 'treeview', '');
                $menu->class = 'sidebar-menu';
                $menu->id    = 'side-menu';
                $menu->show();
                $menu_string = ob_get_clean();
                return $menu_string;
                break;
            default:
                ob_start();
                $callback = array(SystemPermission::class, 'checkPermission');
                $xml = new SimpleXMLElement(file_get_contents('menu.xml'));
                $menu = new TMenu($xml, $callback, 1, 'ml-menu', 'x', 'menu-toggle waves-effect waves-block');
                
                $li = new TElement('li');
                $li->{'class'} = 'active';
                $menu->add($li);
                
                $li = new TElement('li');
                $li->add('MENU');
                $li->{'class'} = 'header';
                $menu->add($li);
                
                $menu->class = 'list';
                $menu->style = 'overflow: hidden; width: auto; height: 390px;';
                $menu->show();
                $menu_string = ob_get_clean();
                return $menu_string;
                break;
        }
    }
}