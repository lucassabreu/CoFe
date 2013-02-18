<?php

//queries used by tests
return array(
    'user' => array(
        'create' => array(
            "CREATE  TABLE `user` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
                `username` VARCHAR(50) NOT NULL ,
                `password` VARCHAR(20) NOT NULL ,
                `active` TINYINT(1) NOT NULL DEFAULT 0 ,
                `role` VARCHAR(10) NOT NULL DEFAULT 'common' ,
                `name` VARCHAR(60) NOT NULL ,
                `email` VARCHAR(45) NOT NULL ,
                `dt_criation` DATE NOT NULL ,
                `validated` TINYINT(1) NOT NULL DEFAULT 0 ,
                PRIMARY KEY (`id`) )
            ENGINE = InnoDB;",
            "CREATE UNIQUE INDEX `idx_username` USING BTREE ON `user` (`username` ASC) ;",
        ),
        'drop' => "DROP TABLE `user`;"
    ),
);