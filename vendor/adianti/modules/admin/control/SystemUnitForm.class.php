<?php
namespace Adianti\Modules\Admin\Control;

use function Adianti\App\Lib\Util\_t;
use Adianti\Base\TStandardForm;
use Adianti\Control\TAction;
use Adianti\Modules\Admin\Model\SystemUnit;
use Adianti\Validator\TRequiredValidator;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Util\TXMLBreadCrumb;
use Adianti\Wrapper\BootstrapFormBuilder;

/**
 * SystemUnitForm
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SystemUnitForm extends TStandardForm
{
    protected $form; // form

    /**
     * Class constructor
     * Creates the page and the registration form
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('permission');              // defines the database
        $this->setActiveRecord(SystemUnit::class);     // defines the active record
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_SystemUnit');
        $this->form->setFormTitle(_t('Unit'));
        
        // create the form fields
        $id = new TEntry('id');
        $name = new TEntry('name');
        
        // add the fields
        $this->form->addFields([new TLabel('Id')], [$id]);
        $this->form->addFields([new TLabel(_t('Name'))], [$name]);
        $id->setEditable(false);
        $id->setSize('30%');
        $name->setSize('70%');
        $name->addValidation(_t('Name'), new TRequiredValidator);
        
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        $this->form->addAction(_t('Back'), new TAction(array('SystemUnitList','onReload')), 'fa:arrow-circle-o-left blue');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'SystemUnitList'));
        $container->add($this->form);
        
        parent::add($container);
    }
}
