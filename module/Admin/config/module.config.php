<?php

namespace Admin;

use Admin\Service\AuthService;
use Core\Authentification\Doctrine\DoctrineAdapter;
use Zend\ServiceManager\ServiceManager;

return array(
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
);