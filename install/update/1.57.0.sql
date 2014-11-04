-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 06 Mai 2014 à 17:29
-- Version du serveur: 5.6.16-1~exp1
-- Version de PHP: 5.5.9-1ubuntu4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `jeedom`
--

-- --------------------------------------------------------

--
-- Structure de la table `update`
--

CREATE TABLE IF NOT EXISTS `update` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(127) DEFAULT NULL,
  `name` varchar(127) DEFAULT NULL,
  `logicalId` varchar(127) DEFAULT NULL,
  `localVersion`  varchar(127) DEFAULT NULL,
  `remoteVersion`  varchar(127) DEFAULT NULL,
  `status` varchar(127) DEFAULT NULL,
  `configuration` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
