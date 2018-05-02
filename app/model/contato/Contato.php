<?php

namespace App\Model\Contato;

use Dvi\Adianti\Model\DviModel;

/**
 * Contato Contato
 *
 * @package    Contato
 * @subpackage Dvi
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @link https://github.com/DaviMenezes
 */
class Contato extends DviModel
{
    const TABLENAME = 'ctt_contact';

    public function buildFieldTypes()
    {
        $this->addVarchar('name', 150, true, 'nome');
        $this->addVarchar('cpf', 14)->mask('999.999.999-99');
        $this->addVarchar('rg', 12);
        $this->addDate('birthday', 'data de nascimento');
        $this->addText('observation', 0, 80, false, 'observações');
        $this->addCombo('city_id', 'cidade')->model(City::class);
    }

    protected function buildStructureForm()
    {
        $form_structure = [
            [$this->field_name],
            [$this->field_cpf, $this->field_rg, $this->field_birthday, $this->field_city_id],
            [$this->field_observation]
        ];
        parent::setStructureForm($form_structure);
    }
}
