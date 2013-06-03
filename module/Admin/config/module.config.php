<?php

namespace Admin;

use Admin\Service\AuthService;
use Core\Authentification\Doctrine\DoctrineAdapter;
use Zend\ServiceManager\ServiceManager;

return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Auth' => 'Admin\Controller\AuthController',
            'Admin\Controller\User' => 'Admin\Controller\UserController',
        ),
    ),
    'service_manager' => array(
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
    'view_manager' => array(//the module can have a specific layout
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
                        'controller' => 'User',
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
                                'type' => 'Zend\Mvc\Router\Http\Wildcard',
                                'may_terminate' => true,
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);