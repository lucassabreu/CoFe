<?php

namespace Core;

return array(
    'service_manager' => array(
        'factories' => array(
            'DbAdapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
        'abstract_factories' => array(
            'Core\Service\Factory\DAOServiceFactory'
        ),
        'invokables' => array(
            'Core\Acl\Builder' => 'Core\Acl\Builder',
        ),
        'dao_services' => array(
            'Admin\Service\UserDAOService' => array(
                'service' => 'Admin\Service\UserDAOService',
                'model' => 'Admin\Model\Doctrine\UserDAODoctrine',
            ),
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'stripContent' => 'Core\View\Helper\StripContentHelper',
        ),
    ),
);