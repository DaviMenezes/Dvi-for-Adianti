<?php
namespace App;

// define the autoloader
//require_once 'lib/adianti/core/AdiantiCoreLoader.php';
//spl_autoload_register(array('Adianti\Core\AdiantiCoreLoader', 'autoload'));
//Adianti\Core\AdiantiCoreLoader::loadClassMap();

use Adianti\Base\App\Lib\Util\ApplicationTranslator;
use Adianti\Base\Lib\Core\AdiantiApplicationConfig;
use Adianti\Base\Lib\Core\AdiantiCoreTranslator;

class Init
{
    private $ini;

    public function __construct()
    {

        $loader = require 'vendor/autoload.php';
        $loader->register();

        // read configurations
        $this->ini = parse_ini_file('app/config/application.ini', true);
        date_default_timezone_set($this->ini['general']['timezone']);
        AdiantiCoreTranslator::setLanguage($this->ini['general']['language']);
        ApplicationTranslator::setLanguage($this->ini['general']['language']);
        AdiantiApplicationConfig::load($this->ini);

        // define constants
        define('APPLICATION_NAME', $this->ini['general']['application']);
        define('OS', strtoupper(substr(PHP_OS, 0, 3)));
        define('PATH', dirname(__FILE__));
        define('LANG', $this->ini['general']['language']);

        if (version_compare(PHP_VERSION, '5.5.0') == -1) {
            die(AdiantiCoreTranslator::translate('The minimum version required for PHP is ^1', '5.5.0'));
        }
    }

    public function getIni()
    {
        return $this->ini;
    }
}


