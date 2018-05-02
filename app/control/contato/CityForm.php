<?php

namespace App\Control\Contato;

use App\Model\Contato\City;
use Dvi\Adianti\Control\DviSearchFormList;

/**
 * Contato CityForm
 *
 * @package    Contato
 * @subpackage Dvi
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @link https://github.com/DaviMenezes
 */
class CityForm extends DviSearchFormList
{
    protected $objectClass = City::class;
    protected $pageTitle = 'Cidades';
}
