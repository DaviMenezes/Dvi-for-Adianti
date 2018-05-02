<?php

namespace App\Config;

use App\Control\Contato\CityForm;
use App\Control\Contato\ContatoForm;
use App\Control\Contato\ContatoList;
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

        $routes['ContatoForm'] = ContatoForm::class;
        $routes['ContatoList'] = ContatoList::class;
        $routes['CityForm'] = CityForm::class;

        return $routes;
    }
}
