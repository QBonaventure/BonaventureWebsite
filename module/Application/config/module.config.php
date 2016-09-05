<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'curriculum-vitae' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/curriculum-vitae',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'curriculum-vitae',
                    ),
                ),
                'may_terminate' => true,
            ),
            'contact' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/contact',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'contact',
                    ),
                ),
                'may_terminate' => true,
            ),
            'index' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/index',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
//     	'invokables' => array(
// //     		'Application\Service\MailServiceInterface'	=> 'Application\Service\MailService',
//     	),
        'factories' => array(
         	'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
    		'mailing'	=> 'Application\Service\MailServiceFactory',	
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
//         'invokables' => array(
//             'Application\Controller\Index' => Controller\IndexController::class,
//         ),
        'factories' => array(
            'Application\Controller\Index' => 'Application\Factory\IndexControllerFactory',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/403'               => __DIR__ . '/../view/error/403.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    	'view_helpers' => array(
      		'invokables' => array(
        		'MainClass' => 'Application\View\Helper\RouteMatch',
      ),
   ),

    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
	'mail' => array(
		'host'              => 'imap.bonaventure.xyz',
		'port'				=> 587,
		'connection_class'  => 'plain',
		'connection_config' => array(
				'username' => 'quentin@bonaventure.xyz',
				'password' => 'bNMpz2kjEf8nlH71sHESXEWf2sSrQp',
				'ssl'	=> 'tls',
		),
	),
);
