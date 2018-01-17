<?php
namespace Adianti\Core;

use Adianti\Registry\TSession;
use Adianti\Widget\Dialog\TMessage;

class TApplication extends AdiantiCoreApplication
{
    public static function run($debug = false)
    {
        new TSession();

        if ($_REQUEST) {
            $ini    = AdiantiApplicationConfig::get();
            $class  = isset($_REQUEST['class']) ? $_REQUEST['class'] : '';

            $public = in_array($class, $ini['permission']['public_classes']);

            if (TSession::getValue('logged')) { // logged
                $programs = (array) TSession::getValue('programs'); // programs with permission
                $programs = array_merge($programs, array('TStandardSeek' => true,
                    'LoginForm' => true,
                    'AdiantiMultiSearchService' => true,
                    'AdiantiUploaderService' => true,
                    'AdiantiAutocompleteService' => true,
                    'EmptyPage' => true,
                    'MessageList' => true,
                    'SystemDocumentUploaderService' => true,
                    'NotificationList' => true,
                    'SearchBox' => true,
                    'SearchInputBox' => true,
                    'SystemPageService' => true,
                    'SystemPageBatchUpdate' => true,
                    'SystemPageUpdate' => true));

                if (isset($programs[$class]) or $public) {
                    parent::run($debug);
                } else {
                    new TMessage('error', _t('Permission denied'));
                }
            } elseif ($class == 'LoginForm' or $public) {
                parent::run($debug);
            } else {
                new TMessage('error', _t('Permission denied'), new TAction(array('LoginForm','onLogout')));
            }
        }
    }
}
