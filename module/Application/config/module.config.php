<?php

namespace Application;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Category' => 'Application\Controller\CategoryController',
            'Application\Controller\Moviment' => 'Application\Controller\MovimentController',
        ),
    ),
    'service_manager' => array(
        'dao_factory' => array(
            'Application\Service\CategoryDAOService' => array(
                'service' => 'Application\Service\CategoryDAOService',
                'model' => 'Application\Model\Doctrine\CategoryDAODoctrine',
            ),
            'Application\Service\MovimentDAOService' => array(
                'service' => 'Application\Service\MovimentDAOService',
                'model' => 'Application\Model\Doctrine\MovimentDAODoctrine',
            ),
        ),
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
            'Doctrine\ORM\EntityManager' => function($sm) {
                $config = $sm->get('Configuration');

                $doctrineConfig = new Configuration();
                $cache = new $config['doctrine']['driver']['cache'];
                $doctrineConfig->setQueryCacheImpl($cache);
                $doctrineConfig->setProxyDir('/tmp');
                $doctrineConfig->setProxyNamespace('EntityProxy');
                $doctrineConfig->setAutoGenerateProxyClasses(true);

                $driver = new AnnotationDriver(
                        new AnnotationReader(), array($config['doctrine']['driver']['paths'])
                );
                $doctrineConfig->setMetadataDriverImpl($driver);
                $doctrineConfig->setMetadataCacheImpl($cache);
                AnnotationRegistry::registerFile(
                        getenv('PROJECT_ROOT') . '/vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'
                );
                $em = EntityManager::create(
                                $config['doctrine']['connection'], $doctrineConfig
                );
                return $em;
            },
            'EntityManager' => function ($sm) {
                return $sm->get('Doctrine\ORM\EntityManager');
            },
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'cache' => 'Doctrine\Common\Cache\ArrayCache',
            'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Model')
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../../../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'navigation' => array(
        // The DefaultNavigationFactory we configured in (1) uses 'default' as the sitemap key
        'default' => array(
            /*array(
                'label' => 'Home',
                'route' => 'home'
            ),*/
            array(
                'label' => 'Moviment',
                'route' => 'movimentList',
                'module' => 'application',
                'controller' => 'moviment',
                'action' => 'index',
                'page' => 1,
            ),
            array(
                'label' => 'Category',
                'route' => 'categoryList',
                'module' => 'application',
                'controller' => 'category',
                'action' => 'index',
                'page' => 1,
            ),
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/[page/:page]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Moviment',
                        'action' => 'index',
                        'module' => 'application',
                    ),
                ),
            ),
            'moviment' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/moviment[[/]:action[[/]:id]]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Moviment',
                        'action' => 'index',
                        'module' => 'application',
                    ),
                ),
            ),
            'movimentList' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/moviment/page[/[:page]]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Moviment',
                        'action' => 'index',
                        'module' => 'application',
                        'id' => 1,
                    ),
                ),
            ),
            'movimentsOfCategory' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/moviment/ofcategory[/:number[/page/[:page]]]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Moviment',
                        'action' => 'ofcategory',
                        'module' => 'application',
                        'id' => 1,
                    ),
                ),
            ),
            'category' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/category[/[:action[/[:number]]]]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Category',
                        'action' => 'index',
                        'module' => 'application',
                    ),
                ),
            ),
            'categoryList' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/category[/]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Category',
                        'action' => 'index',
                        'module' => 'application',
                    ),
                ),
            ),
            'application' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/application',
                    'defaults' => array(
                        'controller' => 'Index',
                        'action' => 'index',
                        '__NAMESPACE__' => 'Application\Controller',
                        'module' => 'application'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/[:action[/]]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                        'child_routes' => array(//permite mandar dados pela url 
                            'wildcard' => array(
                                'type' => 'Wildcard'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
