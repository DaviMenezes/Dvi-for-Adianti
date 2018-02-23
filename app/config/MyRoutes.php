<?php

namespace App\Config;

use Dvi\Adianti\Route;

/**
 * Config MyRoutes
 *
 * @version    Dvi 1.0
 * @package    Config
 * @subpackage App
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @link https://github.com/DaviMenezes
 */
class MyRoutes extends Route
{
    public static function getRoutes()
    {
        $routes = parent::getRoutes();

        //$routes['test'] = 'path/TestClass'; //Test::class;

        return $routes;
    }
}
