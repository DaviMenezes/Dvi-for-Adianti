<?php

namespace App\Control\Contato;

use Adianti\Base\Lib\Control\TAction;
use Adianti\Base\Lib\Widget\Datagrid\TDataGridAction;
use App\Model\Contato\Contato;
use Dvi\Adianti\Control\DviSearchList;
use Dvi\Adianti\Widget\Base\DGridColumn;
use Dvi\Adianti\Widget\Form\DEntry;

/**
 * Contato ContatoList
 *
 * @package    Contato
 * @subpackage Dvi
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @link https://github.com/DaviMenezes
 */
class ContatoList extends DviSearchList
{
    protected $objectClass = Contato::class;
    protected $pageTitle = 'Contatos';

    protected function mountModelFields()
    {
        $name = new DEntry('Entity_name', 'nome', 150);
        $this->panel->addRow([new DGridColumn($name)]);
    }

    protected function createDatagridColumns($showId = false)
    {
        $this->datagrid->useDeleteAction(self::class);

        parent::createDatagridColumns($showId);

        $this->datagrid->useEditAction(ContatoForm::class);
    }
}
