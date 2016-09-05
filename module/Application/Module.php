<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
 
use Application\Model\Message;
use Application\Model\MessageTable;
 
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;

use Zend\I18n\Translator\Translator;
use Zend\Validator\AbstractValidator;

use Zend\Form\View\Helper\FormRow;

use Zend\Db\Sql\TableIdentifier;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $e->getApplication()->getServiceManager()->get('viewhelpermanager')->setFactory('routeMatch', function($sm) use ($e) {
        	$viewHelper = new View\Helper\RouteMatch($e->getRouteMatch());
        	return $viewHelper;
        });
    $config = $e->getApplication()->getServiceManager()->get('Config');
    $phpSettings = isset($config['phpSettings']) ? $config['phpSettings'] : array();
    if(!empty($phpSettings)) {
        foreach($phpSettings as $key => $value) {
            ini_set($key, $value);
        }
    }
	}


    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }


    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getViewHelperConfig()
    {
    	return array(
    			'factories' => array(
    					'formRow' => function($sm) {
    					$helper = new FormRow();
    					$helper->setRenderErrors(false);
    					return $helper;
    				}
    			),
    		);
    }


    public function getServiceConfig() {
		return array(
			'factories' => array(
				'Application\Model\MessageTable'=>function($sm){
	    			$tableGateway = $sm->get('MessageTableGateway');
	    			$table = new MessageTable($tableGateway);
	    			return $table;
    			},
    					//Instantiating TableGateway to inject to StudentTable class
    					'MessageTableGateway'=>function($sm){
    					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    					$resultSetPrototype = new ResultSet();
    					$resultSetPrototype->setArrayObjectPrototype(new Message());
    					return new TableGateway('messages', $dbAdapter,null,$resultSetPrototype);
    					}
    					)
    			);
    }
}
