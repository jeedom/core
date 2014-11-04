-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Lun 09 Décembre 2013 à 11:25
-- Version du serveur: 5.5.33a-MariaDB-1~saucy-log
-- Version de PHP: 5.5.3-1ubuntu2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `jeedom`
--

ALTER TABLE `jeedom`.`scenario` 
ADD COLUMN `scenarioElement` TEXT NULL DEFAULT NULL AFTER `pid`;
ADD COLUMN `trigger` TEXT NULL DEFAULT NULL AFTER `scenarioElement`;
ADD COLUMN `log` TEXT NULL DEFAULT NULL AFTER `trigger`;

DROP TABLE `jeedom`.`scenarioAction`; 
DROP TABLE `jeedom`.`scenarioCondition`; 
-- --------------------------------------------------------

--
-- Structure de la table `scenarioElement`
--

CREATE TABLE IF NOT EXISTS `scenarioElement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) NOT NULL,
  `type` varchar(127) DEFAULT NULL,
  `name` varchar(127) DEFAULT NULL,
  `options` text,
  `log` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Structure de la table `scenarioExpression`
--

CREATE TABLE IF NOT EXISTS `scenarioExpression` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) DEFAULT NULL,
  `scenarioSubElement_id` int(11) NOT NULL,
  `type` varchar(127) DEFAULT NULL,
  `subtype` varchar(127) DEFAULT NULL,
  `expression` text,
  `options` text,
   `log` text,
  PRIMARY KEY (`id`),
  KEY `fk_scenarioExpression_scenarioSubElement1_idx` (`scenarioSubElement_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ;

-- --------------------------------------------------------

--
-- Structure de la table `scenarioSubElement`
--

CREATE TABLE IF NOT EXISTS `scenarioSubElement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) DEFAULT NULL,
  `scenarioElement_id` int(11) NOT NULL,
  `type` varchar(127) DEFAULT NULL,
  `name` varchar(127) DEFAULT NULL,
  `subtype` varchar(127) DEFAULT NULL,
  `options` text,
  `log` text,
  PRIMARY KEY (`id`),
  KEY `fk_scenarioSubElement_scenarioElement1_idx` (`scenarioElement_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=89 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `scenarioExpression`
--
ALTER TABLE `scenarioExpression`
  ADD CONSTRAINT `fk_scenarioExpression_scenarioSubElement1` FOREIGN KEY (`scenarioSubElement_id`) REFERENCES `scenarioSubElement` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `scenarioSubElement`
--
ALTER TABLE `scenarioSubElement`
  ADD CONSTRAINT `fk_scenarioSubElement_scenarioElement1` FOREIGN KEY (`scenarioElement_id`) REFERENCES `scenarioElement` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
