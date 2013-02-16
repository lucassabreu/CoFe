SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `cofe` ;
CREATE SCHEMA IF NOT EXISTS `cofe` DEFAULT CHARACTER SET latin1 ;
USE `cofe` ;

-- -----------------------------------------------------
-- Table `cofe`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cofe`.`user` ;

CREATE  TABLE IF NOT EXISTS `cofe`.`user` (
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
ENGINE = InnoDB;

CREATE UNIQUE INDEX `idx_username` USING BTREE ON `cofe`.`user` (`username` ASC) ;


-- -----------------------------------------------------
-- Table `cofe`.`category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cofe`.`category` ;

CREATE  TABLE IF NOT EXISTS `cofe`.`category` (
  `user_id` INT UNSIGNED NOT NULL ,
  `code` CHAR(6) NOT NULL ,
  `description` VARCHAR(100) NOT NULL ,
  `flow_type` TINYINT(1) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`code`, `user_id`) ,
  CONSTRAINT `fk_category_user`
    FOREIGN KEY (`user_id` )
    REFERENCES `cofe`.`user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cofe`.`moviment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cofe`.`moviment` ;

CREATE  TABLE IF NOT EXISTS `cofe`.`moviment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `cat_code` CHAR(6) NOT NULL ,
  `user_id` INT UNSIGNED NOT NULL ,
  `value` DECIMAL(18,2) UNSIGNED NOT NULL ,
  `dt_emission` DATE NOT NULL ,
  `descritption` VARCHAR(50) NOT NULL ,
  `notes` TEXT NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_moviment_category`
    FOREIGN KEY (`cat_code` , `user_id` )
    REFERENCES `cofe`.`category` (`code` , `user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_moviment` ON `cofe`.`moviment` (`user_id` ASC, `cat_code` ASC, `id` ASC) ;

USE `cofe` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `cofe`.`user`
-- -----------------------------------------------------
START TRANSACTION;
USE `cofe`;
INSERT INTO `cofe`.`user` (`id`, `username`, `password`, `active`, `role`, `name`, `email`, `dt_criation`, `validated`) VALUES (NULL, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 'admin', 'Administrator', 'admin@localhost', '2000-01-01', 1);

COMMIT;
