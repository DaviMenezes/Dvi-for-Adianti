<?php
/**
 * Created by PhpStorm.
 * User: davi
 * Date: 21/11/17
 * Time: 09:49
 */

namespace Dvi\Adianti;

use Adianti\App\Service\SystemDocumentUploaderService;
use Adianti\Base\TStandardSeek;
use Adianti\Modules\Admin\Control\EmptyPage;
use Adianti\Modules\Admin\Control\LoginForm;
use Adianti\Modules\Admin\Control\SystemDatabaseExplorer;
use Adianti\Modules\Admin\Control\SystemDataBrowser;
use Adianti\Modules\Admin\Control\SystemGroupForm;
use Adianti\Modules\Admin\Control\SystemGroupList;
use Adianti\Modules\Admin\Control\SystemPHPErrorLogView;
use Adianti\Modules\Admin\Control\SystemPHPInfoView;
use Adianti\Modules\Admin\Control\SystemPreferenceForm;
use Adianti\Modules\Admin\Control\SystemProgramForm;
use Adianti\Modules\Admin\Control\SystemProgramList;
use Adianti\Modules\Admin\Control\SystemSQLPanel;
use Adianti\Modules\Admin\Control\SystemTableList;
use Adianti\Modules\Admin\Control\SystemUnitForm;
use Adianti\Modules\Admin\Control\SystemUnitList;
use Adianti\Modules\Admin\Control\SystemUserForm;
use Adianti\Modules\Admin\Control\SystemUserList;
use Adianti\Modules\Available\Control\PublicView;
use Adianti\Modules\Common\Control\MessageList;
use Adianti\Modules\Common\Control\NotificationList;
use Adianti\Modules\Common\Control\SearchBox;
use Adianti\Modules\Common\Control\WelcomeView;
use Adianti\Modules\Communication\Control\SystemDocumentCategoryFormList;
use Adianti\Modules\Communication\Control\SystemDocumentForm;
use Adianti\Modules\Communication\Control\SystemDocumentList;
use Adianti\Modules\Communication\Control\SystemDocumentUploadForm;
use Adianti\Modules\Communication\Control\SystemMessageForm;
use Adianti\Modules\Communication\Control\SystemMessageFormView;
use Adianti\Modules\Communication\Control\SystemMessageList;
use Adianti\Modules\Communication\Control\SystemSharedDocumentList;
use Adianti\Modules\Log\Control\SystemAccessLogList;
use Adianti\Modules\Log\Control\SystemAccessLogStats;
use Adianti\Modules\Log\Control\SystemChangeLogView;
use Adianti\Modules\Log\Control\SystemSqlLogList;
use Adianti\Modules\Log\Model\SystemAccessLog;
use Adianti\Service\AdiantiMultiSearchService;
use Adianti\Widget\Dialog\TMessage;
use Dvi\Module\Contact\Control\ContactAddressForm;
use Dvi\Module\Contact\Control\HumanForm;
use Dvi\Module\Office\Project\Control\ProjectFormList;
use Dvi\Module\Office\Project\Control\ProjectPostForm;
use Dvi\Module\Office\Project\Control\ProjectPostList;
use Dvi\Module\Office\Task\Control\TaskForm;
use Dvi\Module\Office\Task\Control\TaskList;
use Dvi\Module\Office\Url\Control\UrlForm;
use Dvi\Module\Office\Url\Control\UrlFormList;
use Dvi\Module\Office\Workspace\Control\WorkspaceFormList;
use Dvi\Modules\Officee\Task\Control\TaskWorkForm;
use Exception;

class Route
{
    /**
     * @throws Exception
     */
    public static function getPath($class)
    {
        try {
            $route = self::getRoutes();
            if (array_key_exists($class, $route)) {
                return $route[$class];
            } elseif (in_array($class, $route)) {
                return $class;
            }

            $class_name = self::getClassName($class);
            $error_msg = 'O arquivo ' . $class_name . ' não tem sua rota mapeada.<br>';
            $error_msg .= 'Adicione a linha abaixo no arquivo Dvi\Adianti\Route.php <hr>';
            $error_msg .= '$routes[\''.$class_name.'\'] = '.$class_name.'::class;';

            throw new Exception($error_msg);
        } catch (Exception $e) {
            new TMessage('info', $e->getMessage());
            die();
        }
    }

    public static function getClassName($class)
    {
        foreach (self::getRoutes() as $key => $route) {
            if ($class == $route) {
                return $key;
                break;
            }
        }
        $class_name = explode('\\', $class);
        $class_name = array_pop($class_name);
        return $class_name;
    }

    private static function getRoutes()
    {
        $routes['EmptyPage'] = EmptyPage::class;
        $routes['LoginForm'] = LoginForm::class;
        $routes['WelcomeView'] = WelcomeView::class;
        $routes['SearchBox'] = SearchBox::class;
        $routes['MessageList'] = MessageList::class;
        $routes['TStandardSeek'] = TStandardSeek::class;
        $routes['NotificationList'] = NotificationList::class;
        $routes['SystemProgramList'] = SystemProgramList::class;
        $routes['SystemAccessLog'] = SystemAccessLog::class;
        $routes['SystemUnitList'] = SystemUnitList::class;
        $routes['SystemUnitForm'] =  SystemUnitForm::class;
        $routes['SystemUserList'] = SystemUserList::class;
        $routes['SystemUserForm'] = SystemUserForm::class;
        $routes['SystemProgramForm'] = SystemProgramForm::class;
        $routes['SystemGroupList'] = SystemGroupList::class;
        $routes['SystemGroupForm'] = SystemGroupForm::class;
        $routes['SystemDatabaseExplorer'] = SystemDatabaseExplorer::class;
        $routes['SystemTableList'] = SystemTableList::class;
        $routes['SystemDataBrowser'] = SystemDataBrowser::class;
        $routes['SystemSQLPanel'] = SystemSQLPanel::class;
        $routes['SystemPHPInfoView'] = SystemPHPInfoView::class;
        $routes['SystemPreferenceForm'] = SystemPreferenceForm::class;
        $routes['SystemDocumentUploadForm'] = SystemDocumentUploadForm::class;
        $routes['SystemDocumentList'] = SystemDocumentList::class;
        $routes['SystemDocumentForm'] = SystemDocumentForm::class;
        $routes['SystemSharedDocumentList'] = SystemSharedDocumentList::class;
        $routes['SystemDocumentCategoryFormList'] = SystemDocumentCategoryFormList::class;
        $routes['SystemAccessLogStats'] = SystemAccessLogStats::class;
        $routes['SystemAccessLogList'] = SystemAccessLogList::class;
        $routes['SystemChangeLogView'] = SystemChangeLogView::class;
        $routes['SystemPHPErrorLogView'] = SystemPHPErrorLogView::class;
        $routes['SystemSqlLogList'] = SystemSqlLogList::class;
        $routes['SystemMessageList'] = SystemMessageList::class;
        $routes['SystemMessageFormView'] = SystemMessageFormView::class;
        $routes['SystemMessageForm'] = SystemMessageForm::class;
        $routes['SystemDocumentUploaderService'] = SystemDocumentUploaderService::class;
        $routes['AdiantiMultiSearchService'] = AdiantiMultiSearchService::class;
        $routes['PublicView'] = PublicView::class;
        
        return $routes;
    }
}
