-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 01/07/2013 às 15:55:11
-- Versão do Servidor: 5.5.27
-- Versão do PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `cofe`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `user_id` int(10) unsigned NOT NULL,
  `code` char(6) NOT NULL,
  `description` varchar(100) NOT NULL,
  `flow_type` tinyint(1) NOT NULL DEFAULT '0',
  `user_id_parent` int(10) unsigned DEFAULT NULL,
  `code_parent` char(6) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`code`),
  KEY `fk_category_parent` (`user_id_parent`,`code_parent`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gatilhos `category`
--
DROP TRIGGER IF EXISTS `category_BUIN`;
DELIMITER //
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
//
DELIMITER ;
DROP TRIGGER IF EXISTS `category_BUPD`;
DELIMITER //
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
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `moviment`
--

CREATE TABLE IF NOT EXISTS `moviment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_code` char(6) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `value` decimal(18,2) unsigned NOT NULL,
  `dt_emission` date NOT NULL,
  `descritption` varchar(50) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_moviment` (`user_id`,`cat_code`,`id`),
  KEY `fk_moviment_category` (`user_id`,`cat_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `role` varchar(10) NOT NULL DEFAULT 'common',
  `name` varchar(60) NOT NULL,
  `email` varchar(45) NOT NULL,
  `dt_criation` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_username` (`username`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Extraindo dados da tabela `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `active`, `role`, `name`, `email`, `dt_criation`) VALUES
(NULL, 'admin', md5('admin'), 1, 'admin', 'Administrator', 'admin@localhost.net', curdate());

--
-- Restrições para as tabelas dumpadas
--

--
-- Restrições para a tabela `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `fk_category_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_category_parent` FOREIGN KEY (`user_id_parent`, `code_parent`) REFERENCES `category` (`user_id`, `code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `moviment`
--
ALTER TABLE `moviment`
  ADD CONSTRAINT `fk_moviment_category` FOREIGN KEY (`user_id`, `cat_code`) REFERENCES `category` (`user_id`, `code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;