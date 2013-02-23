<?php

use Admin\Service\AuthService;
use Zend\Session\Container;

return array(
    'service_manager' => array(
        'dao_factory' => array(
            'Admin\Service\UserDAOService' => array(
                'service' => 'Admin\Service\UserDAOService',
                'model' => 'Admin\Model\Doctrine\UserDAODoctrine',
            ),
        ),
        'factories' => array(
            'Session' => function($sm) {
                return new Container('cofe');
            },
            'Admin\Service\AuthService' => function($sm) {
                return new AuthService($sm->get('DbAdapter'));
            },
        ),
    ),
);