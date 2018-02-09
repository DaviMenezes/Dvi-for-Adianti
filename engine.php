<?php


use Adianti\Base\App\Lib\Util\ApplicationTranslator;
use Adianti\Base\Lib\Core\TApplication;
use App\Init;

require_once 'init.php';
new Init();

function _t($msg, $param1 = null, $param2 = null, $param3 = null)
{
    return ApplicationTranslator::translate($msg, $param1, $param2, $param3);
}

TApplication::run(true);
