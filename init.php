<?php
use Adianti\Base\App\Lib\Util\ApplicationTranslator;
use Adianti\Base\Lib\Core\AdiantiApplicationConfig;
use Adianti\Base\Lib\Core\AdiantiCoreTranslator;
use Adianti\Base\Lib\Registry\TSession;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

require 'vendor/autoload.php';

new TSession;

// read configurations
$ini = parse_ini_file('app/config/application.ini', true);
date_default_timezone_set($ini['general']['timezone']);
AdiantiCoreTranslator::setLanguage($ini['general']['language']);
ApplicationTranslator::setLanguage($ini['general']['language']);
AdiantiApplicationConfig::load($ini);

// define constants
define('APPLICATION_NAME', $ini['general']['application']);
define('OS', strtoupper(substr(PHP_OS, 0, 3)));
define('PATH', dirname(__FILE__));
define('LANG', $ini['general']['language']);
define('ENVIRONMENT', $ini['general']['environment']);

if (version_compare(PHP_VERSION, '7.0') == -1) {
    die(AdiantiCoreTranslator::translate('The minimum version required for PHP is ^1', '7.0'));
}

$ini = AdiantiApplicationConfig::get();
/**@var Run $whoops*/
$whoops = null;
/**@var PrettyPageHandler $handler*/
$handler = null;
if ($ini['general']['environment'] == 'development') {
    $whoops = new Run();
    $handler = new PrettyPageHandler;
    $whoops->pushHandler($handler);
    $whoops->register();
    $handler->setEditor("sublime");
}

function _t($msg, $param1 = null, $param2 = null, $param3 = null)
{
    return ApplicationTranslator::translate($msg, $param1, $param2, $param3);
}