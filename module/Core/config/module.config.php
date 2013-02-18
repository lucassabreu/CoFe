<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'DbAdapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
        'abstract_factories' => array(
            'Core\Service\Factory\DAOServiceFactory'
        ),
        'dao_services' => array(
            'Admin\Service\UserDAOService' => 'Admin\Model\DAO\Doctrine\UserDoctrineDAO',
        ),
    ),
);