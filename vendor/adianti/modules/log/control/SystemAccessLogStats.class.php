<?php
namespace Adianti\Modules\Log\Control;

use function Adianti\App\Lib\Util\_t;
use Adianti\Control\TPage;
use Adianti\Modules\Log\Model\SystemAccessLog;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Template\THtmlRenderer;
use Adianti\Widget\Util\TXMLBreadCrumb;

/**
 * SystemAccessLogStats
 *
 * @version    1.0
 * @package    control
 * @subpackage log
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SystemAccessLogStats extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    public function __construct()
    {
        parent::__construct();
        
        $html = new THtmlRenderer('app/resources/google_bar_chart.html');
        
        $accesses = SystemAccessLog::getStatsByDay();
        
        $data = array();
        $data[] = [ _t('Day'), _t('Accesses') ];
        foreach ($accesses as $day => $access) {
            $data[] = [ _t('Day') . ' ' . $day, $access ];
        }
        
        $panel = new TPanelGroup(_t('Access Stats'));
        $panel->add($html);
        
        // replace the main section variables
        $html->enableSection('main', array('data' => json_encode($data),
                                           'width'  => '100%',
                                           'height'  => '300px',
                                           'title'  => 'Accesses by day',
                                           'ytitle' => 'Accesses',
                                           'xtitle' => 'Day'));
        
        // add the template to the page
        $container = new TVBox;
        $container->style = 'width: 97%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($panel);
        parent::add($container);
    }
}
