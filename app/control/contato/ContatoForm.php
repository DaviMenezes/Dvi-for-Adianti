<?php

namespace App\Control\Contato;

use App\Model\Contato\Contato;
use Dvi\Adianti\Control\DviStandardForm;

/**
 * Contato ContatoForm
 *
 * @package    Contato
 * @subpackage Dvi
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @link https://github.com/DaviMenezes
 */
class ContatoForm extends DviStandardForm
{
    protected $objectClass = Contato::class;
    protected $pageTitle = 'Contato';

    protected function createActions()
    {
        parent::createActions();

        $this->panel->addCustomActionLink([ContatoList::class], 'fa:bars', 'Listagem');
    }
}
