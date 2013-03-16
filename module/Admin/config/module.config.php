<?php

namespace Admin;

use Admin\Service\AuthService;
use Core\Authentification\Doctrine\DoctrineAdapter;

return array(
    'service_manager' => array(
        'controllers' => array(
            'invokables' => array(
                'Admin\Controller\Auth' => 'Admin\Controller\AuthController',
            ),
        ),
        'dao_factory' => array(
            'Admin\Service\UserDAOService' => array(
                'service' => 'Admin\Service\UserDAOService',
                'model' => 'Admin\Model\Doctrine\UserDAODoctrine',
            ),
        ),
        'factories' => array(
            'Admin\Service\AuthService' => function($sm) {
                /* @var $sm ServiceManager */
                $config = $sm->get('Config');
                $config['doctrine']['authentication']['objectManager'] = $sm->get('EntityManager');
                return new AuthService(new DoctrineAdapter($config['doctrine']['authentication']));
            },
        ),
    ),
    'doctrine' => array(
        'authentication' => array(
            'object_manager' => 'Doctrine\ORM\Entity\Manager',
            'identity_class' => 'Admin\Model\Entity\User',
            'identity_property' => 'username',
            'credential_property' => 'password',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Auth' => 'Admin\Controller\AuthController',
        ),
    ),
    'view_manager' => array(//the module can have a specific layout
        // 'template_map' => array(
        //     'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
        // ),
        'template_path_stack' => array(
            'admin' => __DIR__ . '/../view',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                        'controller' => 'Index',
                        'action' => 'index',
                        '__NAMESPACE__' => 'Admin\Controller',
                        'module' => 'admin'
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
);