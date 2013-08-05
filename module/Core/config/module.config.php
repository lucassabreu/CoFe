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
            'Core\Service\Util\MailUtilService' => 'Core\Service\Util\MailUtilService',
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
            'formSelect' => 'Core\View\Helper\Elements\FormSelect',
            'stripContent' => 'Core\View\Helper\StripContentHelper',
            'ztbFormButton' => 'Core\View\Helper\ZTB\ZTBFormButton',
            'ztbFormPrepare' => 'Core\View\Helper\ZTB\ZTBFormPrepare',
        ),
    ),
);