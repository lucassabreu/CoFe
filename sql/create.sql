SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


DROP SCHEMA IF EXISTS `cofe` ;
CREATE SCHEMA IF NOT EXISTS `cofe` DEFAULT CHARACTER SET latin1 ;
USE `cofe` ;

-- -----------------------------------------------------
-- Table `cofe`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cofe`.`user` ;

CREATE  TABLE IF NOT EXISTS `cofe`.`user` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(50) NOT NULL ,
  `password` CHAR(32) NOT NULL ,
  `active` TINYINT(1) NOT NULL DEFAULT '0' ,
  `role` VARCHAR(10) NOT NULL DEFAULT 'common' ,
  `name` VARCHAR(60) NOT NULL ,
  `email` VARCHAR(45) NOT NULL ,
  `dt_criation` DATE NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE UNIQUE INDEX `idx_username` USING BTREE ON `cofe`.`user` (`username` ASC) ;


-- -----------------------------------------------------
-- Table `cofe`.`category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cofe`.`category` ;

CREATE  TABLE IF NOT EXISTS `cofe`.`category` (
  `num_category` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  `code` CHAR(6) NOT NULL ,
  `description` VARCHAR(100) NOT NULL ,
  `flow_type` TINYINT(1) NOT NULL DEFAULT '0' ,
  `num_parent` INT UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`num_category`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE UNIQUE INDEX `idx_category_rpk` ON `cofe`.`category` (`user_id` ASC, `code` ASC) ;


-- -----------------------------------------------------
-- Table `cofe`.`moviment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cofe`.`moviment` ;

CREATE  TABLE IF NOT EXISTS `cofe`.`moviment` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `num_category` INT UNSIGNED NOT NULL ,
  `value` DECIMAL(18,2) UNSIGNED NOT NULL ,
  `dt_emission` DATE NOT NULL ,
  `descritption` VARCHAR(50) NOT NULL ,
  `notes` TEXT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `idx_moviment` ON `cofe`.`moviment` (`id` ASC) ;

CREATE INDEX `fk_moviment_category` ON `cofe`.`moviment` (`num_category` ASC) ;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `cofe`.`user`
-- -----------------------------------------------------
START TRANSACTION;
USE `cofe`;
INSERT INTO `cofe`.`user` (`id`, `username`, `password`, `active`, `role`, `name`, `email`, `dt_criation`) VALUES (1, 'admin', md5('admin'), 1, 'admin', 'Administrator', 'admin@localhost.net', curdate());

COMMIT;

-- -----------------------------------------------------
-- Data for table `cofe`.`category`
-- -----------------------------------------------------
START TRANSACTION;
USE `cofe`;
INSERT INTO `cofe`.`category` (`num_category`, `user_id`, `code`, `description`, `flow_type`, `num_parent`) VALUES (1, 1, 'GAST', 'Gastos', 0, NULL);
INSERT INTO `cofe`.`category` (`num_category`, `user_id`, `code`, `description`, `flow_type`, `num_parent`) VALUES (2, 1, 'GANH', 'Ganhos', 1, NULL);
INSERT INTO `cofe`.`category` (`num_category`, `user_id`, `code`, `description`, `flow_type`, `num_parent`) VALUES (3, 1, 'ALIM', 'Alimentacao', 0, 1);

COMMIT;
