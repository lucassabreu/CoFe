<?php

/**
 * @ignore
 * 
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
return array(
    'acl' => array(
        'roles' => array(
            'guest' => null,
            'common' => 'guest',
            'admin' => 'common'
        ),
        'resources' => array(
            'Application\Controller\Index.index',
            'Admin\Controller\Index.index',
            'Admin\Controller\Auth.index',
            'Admin\Controller\Auth.login',
            'Admin\Controller\Auth.logout',
        ),
        'privilege' => array(
            'guest' => array(
                'allow' => array(
                    'Admin\Controller\Auth.index', 
                    'Admin\Controller\Auth.login',
                    'Admin\Controller\Auth.logout',
                ),
            ),
            'common' => array(
                'allow' => array(
                    'Application\Controller\Index.index',
                ),
            ),
            'admin' => array(
                'allow' => array(
                    'Admin\Controller\Index.index',
                ),
            ),
        ),
    ),
    'doctrine' => array(
        'connection' => array(
            'driver' => 'pdo_mysql',
            'host' => 'localhost',
            'port' => '3306',
            'dbname' => 'cofe'
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
    'db' => array(
        'driver' => 'PDO',
        'dsn' => 'mysql:dbname=cofe;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
);
