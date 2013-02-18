<?php

return array(
    'db' => array(
        'driver' => 'PDO',
        'dsn' => 'mysql:dbname=cofe_test;host=localhost',
        'username' => 'cofe',
        'password' => 'cofe',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'doctrine' => array(
        'connection' => array(
            'driver' => 'pdo_mysql',
            'host' => 'localhost',
            'port' => '3306',
            'user' => 'cofe',
            'password' => 'cofe',
            'dbname' => 'cofe_test'
        ),
    ),
);
?>
