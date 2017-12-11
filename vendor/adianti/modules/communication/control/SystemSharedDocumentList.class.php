<?php
namespace Adianti\Modules\Communication\Control;

use function Adianti\App\Lib\Util\_t;
use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Control\TWindow;
use Adianti\Database\TCriteria;
use Adianti\Database\TExpression;
use Adianti\Database\TFilter;
use Adianti\Database\TRepository;
use Adianti\Database\TTransaction;
use Adianti\Modules\Communication\Model\SystemDocument;
use Adianti\Modules\Communication\Model\SystemDocumentCategory;
use Adianti\Registry\TSession;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Datagrid\TDataGrid;
use Adianti\Widget\Datagrid\TDataGridAction;
use Adianti\Widget\Datagrid\TDataGridColumn;
use Adianti\Widget\Datagrid\TPageNavigation;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Util\TXMLBreadCrumb;
use Adianti\Widget\Wrapper\TDBCombo;
use Adianti\Wrapper\BootstrapDatagridWrapper;
use Adianti\Wrapper\BootstrapFormBuilder;
use Exception;

/**
 * SystemSharedDocumentList
 *
 * @version    1.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SystemSharedDocumentList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $formgrid;
    private $loaded;
    private $deleteButton;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_SystemDocument');
        $this->form->setFormTitle(_t('Shared with me'));
        
        // create the form fields
        $title       = new TEntry('title');
        $category_id = new TDBCombo('category_id', 'communication', SystemDocumentCategory::class, 'id', 'name');
        $filename    = new TEntry('filename');

        $this->form->addFields([new TLabel(_t('Title'))], [$title]);
        $this->form->addFields([new TLabel(_t('Category'))], [$category_id]);
        $this->form->addFields([new TLabel(_t('File'))], [$filename]);
        
        $title->setSize('70%');
        $category_id->setSize('70%');
        $filename->setSize('70%');
        
        // keep the form filled during navigation with session data
        $this->form->setData(TSession::getValue('SystemDocument_filter_data'));
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'), new TAction(array('SystemDocumentUploadForm', 'onNew')), 'bs:plus-sign green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->datatable='true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'center', 50);
        $column_title = new TDataGridColumn('title', _t('Title'), 'left');
        $column_category_id = new TDataGridColumn('category->name', _t('Category'), 'center');
        $column_submission_date = new TDataGridColumn('submission_date', _t('Date'), 'center');
        
        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_title);
        $this->datagrid->addColumn($column_category_id);
        $this->datagrid->addColumn($column_submission_date);
        
        if (TSession::getValue('login') == 'admin') {
            $column_user = new TDataGridColumn('system_user->name', _t('User'), 'left');
            $this->datagrid->addColumn($column_user);
        }
        
        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        
        $order_title = new TAction(array($this, 'onReload'));
        $order_title->setParameter('order', 'title');
        $column_title->setAction($order_title);
        
        $order_category_id = new TAction(array($this, 'onReload'));
        $order_category_id->setParameter('order', 'category_id');
        $column_category_id->setAction($order_category_id);
        
        // create DOWNLOAD action
        $action_download = new TDataGridAction(array($this, 'onDownload'));
        //$action_edit->setUseButton(TRUE);
        $action_download->setButtonClass('btn btn-default');
        $action_download->setLabel(_t('Download'));
        $action_download->setImage('fa:cloud-download green fa-lg');
        $action_download->setField('id');
        $this->datagrid->addAction($action_download);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        $panel = new TPanelGroup;
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);
        
        parent::add($container);
    }
    
    /**
     * Download file
     */
    public function onDownload($param)
    {
        try {
            if (isset($param['id'])) {
                $id = $param['id'];  // get the parameter $key
                TTransaction::open('communication'); // open a transaction
                $object = new SystemDocument($id); // instantiates the Active Record
                
                //system_user_id
                if ($object->hasUserAccess(TSession::getValue('userid')) or $object->hasGroupAccess(TSession::getValue('usergroupids'))) {
                    if (strtolower(substr($object->filename, -4)) == 'html') {
                        $win = TWindow::create($object->filename, 0.8, 0.8);
                        $win->add(file_get_contents("files/documents/{$id}/".$object->filename));
                        $win->show();
                    } else {
                        TPage::openFile("files/documents/{$id}/".$object->filename);
                    }
                } else {
                    new TMessage('error', _t('Permission denied'));
                }
                TTransaction::close(); // close the transaction
            } else {
                $this->form->clear();
            }
        } catch (Exception $e) { // in case of exception
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        
        // clear session filters
        TSession::setValue('SystemDocumentList_filter_title', null);
        TSession::setValue('SystemDocumentList_filter_category_id', null);
        TSession::setValue('SystemDocumentList_filter_filename', null);

        if (isset($data->title) and ($data->title)) {
            $filter = new TFilter('title', 'like', "%{$data->title}%"); // create the filter
            TSession::setValue('SystemDocumentList_filter_title', $filter); // stores the filter in the session
        }


        if (isset($data->category_id) and ($data->category_id)) {
            $filter = new TFilter('category_id', '=', "$data->category_id"); // create the filter
            TSession::setValue('SystemDocumentList_filter_category_id', $filter); // stores the filter in the session
        }

        if (isset($data->filename) and ($data->filename)) {
            $filter = new TFilter('filename', 'like', "%{$data->filename}%"); // create the filter
            TSession::setValue('SystemDocumentList_filter_filename', $filter); // stores the filter in the session
        }

        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('SystemDocument_filter_data', $data);
        
        $param=array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }
    
    /**
     * Load the datagrid with data
     */
    public function onReload($param = null)
    {
        try {
            // open a transaction with database 'communication'
            TTransaction::open('communication');
            
            // creates a repository for SystemDocument
            $repository = new TRepository(SystemDocument::class);
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order'])) {
                $param['order'] = 'id';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            
            $criteria->add(new TFilter('archive_date', 'is', null));
            
            // shared sub-criteria
            $userid = TSession::getValue('userid');
            $usergroups = implode(',', TSession::getValue('usergroupids'));
            $shared_criteria = new TCriteria;
            $shared_criteria->add(new TFilter('id', 'IN', "(SELECT document_id FROM system_document_user WHERE system_user_id='$userid')"), TExpression::OR_OPERATOR);
            $shared_criteria->add(new TFilter('id', 'IN', "(SELECT document_id FROM system_document_group WHERE system_group_id IN ($usergroups))"), TExpression::OR_OPERATOR);
            $criteria->add($shared_criteria);
            
            
            if (TSession::getValue('SystemDocumentList_filter_title')) {
                $criteria->add(TSession::getValue('SystemDocumentList_filter_title')); // add the session filter
            }


            if (TSession::getValue('SystemDocumentList_filter_category_id')) {
                $criteria->add(TSession::getValue('SystemDocumentList_filter_category_id')); // add the session filter
            }
            
            if (TSession::getValue('SystemDocumentList_filter_filename')) {
                $criteria->add(TSession::getValue('SystemDocumentList_filter_filename')); // add the session filter
            }

            
            // load the objects according to criteria
            $objects = $repository->load($criteria, false);
            
            if (is_callable($this->transformCallback)) {
                call_user_func($this->transformCallback, $objects, $param);
            }
            
            $this->datagrid->clear();
            if ($objects) {
                // iterate the collection of active records
                foreach ($objects as $object) {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        } catch (Exception $e) { // in case of exception
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    
    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded and (!isset($_GET['method']) or !(in_array($_GET['method'], array('onReload', 'onSearch'))))) {
            if (func_num_args() > 0) {
                $this->onReload(func_get_arg(0));
            } else {
                $this->onReload();
            }
        }
        parent::show();
    }
}