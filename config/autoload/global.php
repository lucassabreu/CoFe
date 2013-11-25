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
    'email_sending' => array(
        'from' => 'noreplaycofe@gmail.com',
        'fromName' => "NoReply CoFe",
        'transport_options' => array(
            'host' => 'smtp.gmail.com',
            'connection_class' => 'login',
            'connection_config' => array(
                "ssl" => "tls",
            ),
            'port' => 587,
        ),
    ),
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
            'Admin\Controller\User.index',
            'Admin\Controller\User.detail',
            'Admin\Controller\User.create',
            'Admin\Controller\User.update',
            'Admin\Controller\User.remove',
            'Admin\Controller\User.lock',
            'Admin\Controller\User.unlock',
            'Admin\Controller\User.detailProfile',
            'Admin\Controller\User.updateProfile',
            'Admin\Controller\User.changePassword',
            'Admin\Controller\User.resetPassword',
            'Application\Controller\Category.index',
            'Application\Controller\Category.detail',
            'Application\Controller\Category.create',
            'Application\Controller\Category.update',
            'Application\Controller\Category.moveMoviments',
            'Application\Controller\Category.remove',
            'Application\Controller\Moviment.index',
            'Application\Controller\Moviment.detail',
            'Application\Controller\Moviment.create',
            'Application\Controller\Moviment.update',
            'Application\Controller\Moviment.remove',
            'Application\Controller\Moviment.ofcategory',
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
                    'Admin\Controller\User.detailProfile',
                    'Admin\Controller\User.updateProfile',
                    'Admin\Controller\User.changePassword',
                    'Application\Controller\Category.index',
                    'Application\Controller\Category.detail',
                    'Application\Controller\Category.create',
                    'Application\Controller\Category.update',
                    'Application\Controller\Category.remove',
                    'Application\Controller\Category.moveMoviments',
                    'Application\Controller\Moviment.index',
                    'Application\Controller\Moviment.detail',
                    'Application\Controller\Moviment.create',
                    'Application\Controller\Moviment.update',
                    'Application\Controller\Moviment.remove',
                    'Application\Controller\Moviment.ofcategory',
                ),
            ),
            'admin' => array(
                'allow' => array(
                    'Admin\Controller\Index.index',
                    'Admin\Controller\User.index',
                    'Admin\Controller\User.detail',
                    'Admin\Controller\User.create',
                    'Admin\Controller\User.update',
                    'Admin\Controller\User.lock',
                    'Admin\Controller\User.unlock',
                    'Admin\Controller\User.remove',
                    'Admin\Controller\User.resetPassword',
                ),
            ),
        ),
    ),
    'doctrine' => array(
        'connection' => array(
            'driver' => 'pdo_mysql',
            //'host' => $OPENSHIFT_MYSQL_DB_HOST,
            //'port' => $OPENSHIFT_MYSQL_DB_PORT,
            'dbname' => 'cofe',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
    'db' => array(
        'driver' => 'PDO',
        //'dsn' => "mysql:dbname=cofe;host=$OPENSHIFT_MYSQL_DB_HOST",
        //'port' => $OPENSHIFT_MYSQL_DB_PORT,
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    
);
