<?php

namespace App\Model\Contato;

use Dvi\Adianti\Model\DviModel;

/**
 * Contato City
 *
 * @package    Contato
 * @subpackage Dvi
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @link https://github.com/DaviMenezes
 */
class City extends DviModel
{
    const TABLENAME = 'ctt_city';

    public function buildFieldTypes()
    {
        $this->addVarchar('name', 150, true, 'nome');
    }

    protected function buildStructureForm()
    {
        parent::setStructureForm([[$this->field_name]]);
    }
}
