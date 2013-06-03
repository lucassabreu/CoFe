SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `cofe` ;
CREATE SCHEMA IF NOT EXISTS `cofe` DEFAULT CHARACTER SET latin1 ;
USE `cofe` ;

-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user` ;

CREATE  TABLE IF NOT EXISTS `user` (
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
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1;

CREATE UNIQUE INDEX `idx_username` USING BTREE ON `user` (`username` ASC) ;


-- -----------------------------------------------------
-- Table `category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `category` ;

CREATE  TABLE IF NOT EXISTS `category` (
  `user_id` INT(10) UNSIGNED NOT NULL ,
  `code` CHAR(6) NOT NULL ,
  `description` VARCHAR(100) NOT NULL ,
  `flow_type` TINYINT(1) NOT NULL DEFAULT '0' ,
  `user_id_parent` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `code_parent` CHAR(6) NULL DEFAULT NULL ,
  PRIMARY KEY (`user_id`, `code`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `moviment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `moviment` ;

CREATE  TABLE IF NOT EXISTS `moviment` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `cat_code` CHAR(6) NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  `value` DECIMAL(18,2) UNSIGNED NOT NULL ,
  `dt_emission` DATE NOT NULL ,
  `descritption` VARCHAR(50) NOT NULL ,
  `notes` TEXT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `idx_moviment` ON `moviment` (`user_id` ASC, `cat_code` ASC, `id` ASC) ;

USE `cofe` ;
USE `cofe`;

DELIMITER $$

USE `cofe`$$
DROP TRIGGER IF EXISTS `category_BUPD` $$
USE `cofe`$$


CREATE TRIGGER `category_BUPD` BEFORE UPDATE ON `category`
 FOR EACH ROW BEGIN
    IF (NEW.user_id <> NEW.user_id_parent) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Can not relate categories from diferent users!';
    END IF;

    IF (NEW.flow_type <> (SELECT p.flow_type FROM category p
                            WHERE p.user_id = NEW.user_id_parent
                            AND   p.code    = NEW.code_parent)) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Can not relate categories with diferent flow types!';
    END IF;

    IF (NEW.flow_type <> OLD.flow_type) THEN
        UPDATE category SET category.flow_type = NEW.flow_type
            WHERE category.user_id_parent = NEW.user_id
            AND   category.code_parent    = NEW.code;
    END IF;
END
$$


USE `cofe`$$
DROP TRIGGER IF EXISTS `category_BUIN` $$
USE `cofe`$$
CREATE TRIGGER `category_BUIN` BEFORE INSERT ON `category`
 FOR EACH ROW BEGIN
    IF (NEW.user_id <> NEW.user_id_parent) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Can not relate categories from diferent users!';
    END IF;

    IF (NEW.flow_type <> (SELECT p.flow_type FROM category p
                            WHERE p.user_id = NEW.user_id_parent
                            AND   p.code    = NEW.code_parent)) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Can not relate categories with diferent flow types!';
    END IF;
END
$$


DELIMITER ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `user`
-- -----------------------------------------------------
START TRANSACTION;
USE `cofe`;
INSERT INTO `user` (`id`, `username`, `password`, `active`, `role`, `name`, `email`, `dt_criation`) VALUES (NULL, 'admin', md5('admin'), 1, 'admin', 'Administrator', 'admin@localhost.net', curdate());

COMMIT;
