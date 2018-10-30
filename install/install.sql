-- MySQL dump 10.16  Distrib 10.1.26-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: jeedom
-- ------------------------------------------------------
-- Server version       10.1.26-MariaDB-0+deb9u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ash_devices`
--

DROP TABLE IF EXISTS `ash_devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ash_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enable` tinyint(1) DEFAULT NULL,
  `link_type` varchar(127) DEFAULT NULL,
  `link_id` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `options` text,
  PRIMARY KEY (`id`),
  KEY `index` (`link_type`,`link_id`)
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `calendar_event`
--

DROP TABLE IF EXISTS `calendar_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendar_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eqLogic_id` int(11) NOT NULL,
  `cmd_param` text,
  `value` varchar(127) DEFAULT NULL,
  `startDate` datetime DEFAULT NULL,
  `endDate` datetime DEFAULT NULL,
  `until` datetime DEFAULT NULL,
  `repeat` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmd`
--

DROP TABLE IF EXISTS `cmd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eqLogic_id` int(11) NOT NULL,
  `eqType` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logicalId` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `configuration` text CHARACTER SET utf8 COLLATE utf8_bin,
  `template` text COLLATE utf8_unicode_ci,
  `isHistorized` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subType` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unite` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `display` text COLLATE utf8_unicode_ci,
  `isVisible` int(11) DEFAULT '1',
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `html` mediumtext COLLATE utf8_unicode_ci,
  `alert` text CHARACTER SET utf8,
  `generic_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`eqLogic_id`,`name`),
  KEY `isHistorized` (`isHistorized`),
  KEY `type` (`type`),
  KEY `name` (`name`),
  KEY `subtype` (`subType`),
  KEY `eqLogic_id` (`eqLogic_id`),
  KEY `value` (`value`),
  KEY `order` (`order`),
  KEY `logicalID` (`logicalId`),
  KEY `logicalId_eqLogicID` (`eqLogic_id`,`logicalId`),
  KEY `genericType_eqLogicID` (`logicalId`,`generic_type`),
  CONSTRAINT `fk_cmd_eqLogic1` FOREIGN KEY (`eqLogic_id`) REFERENCES `eqLogic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12162 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `plugin` varchar(127) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'core',
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`key`,`plugin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `conso_teleinfo`
--

DROP TABLE IF EXISTS `conso_teleinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conso_teleinfo` (
  `timestamp` bigint(10) NOT NULL DEFAULT '0',
  `rec_date` date NOT NULL,
  `rec_time` time NOT NULL DEFAULT '00:00:00',
  `hchp` bigint(9) NOT NULL DEFAULT '0',
  `hchc` bigint(9) NOT NULL DEFAULT '0',
  `ptec` varchar(2) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `inst1` tinyint(3) NOT NULL DEFAULT '0',
  `imax1` tinyint(3) NOT NULL DEFAULT '0',
  `pmax` int(5) NOT NULL DEFAULT '0',
  `papp` int(5) NOT NULL DEFAULT '0',
  `id_equipement` int(11) NOT NULL DEFAULT '0',
  `temp` float DEFAULT NULL,
  PRIMARY KEY (`timestamp`,`id_equipement`),
  UNIQUE KEY `timestamp` (`timestamp`),
  KEY `rec_date` (`rec_date`),
  KEY `rec_date_time` (`rec_date`,`rec_time`),
  KEY `rec_time` (`rec_time`),
  KEY `hchc` (`hchc`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cron`
--

DROP TABLE IF EXISTS `cron`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enable` int(11) DEFAULT NULL,
  `class` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `function` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  `schedule` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timeout` int(11) DEFAULT NULL,
  `deamon` int(11) DEFAULT '0',
  `deamonSleepTime` int(11) DEFAULT NULL,
  `option` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `once` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `class_function_option` (`class`,`function`,`option`),
  KEY `type` (`class`),
  KEY `logicalId_Type` (`class`),
  KEY `deamon` (`deamon`)
) ENGINE=InnoDB AUTO_INCREMENT=13456 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dataStore`
--

DROP TABLE IF EXISTS `dataStore`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dataStore` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  `link_id` int(11) NOT NULL,
  `key` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`type`,`link_id`,`key`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `eqLogic`
--

DROP TABLE IF EXISTS `eqLogic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eqLogic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  `logicalId` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `eqType_name` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  `configuration` text COLLATE utf8_unicode_ci,
  `isVisible` tinyint(1) DEFAULT NULL,
  `eqReal_id` int(11) DEFAULT NULL,
  `isEnable` tinyint(1) DEFAULT NULL,
  `status` text COLLATE utf8_unicode_ci,
  `timeout` int(11) DEFAULT NULL,
  `category` text COLLATE utf8_unicode_ci,
  `display` text COLLATE utf8_unicode_ci,
  `order` int(11) DEFAULT '1',
  `comment` text CHARACTER SET utf8,
  `generic_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tags` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`name`,`object_id`),
  KEY `eqTypeName` (`eqType_name`),
  KEY `name` (`name`),
  KEY `logical_id` (`logicalId`),
  KEY `logica_id_eqTypeName` (`logicalId`,`eqType_name`),
  KEY `object_id` (`object_id`),
  KEY `timeout` (`timeout`),
  KEY `eqReal_id` (`eqReal_id`),
  KEY `logicalId` (`logicalId`),
  KEY `generic_type` (`generic_type`),
  KEY `tags` (`tags`),
  CONSTRAINT `fk_eqLogic_jeenode1` FOREIGN KEY (`eqReal_id`) REFERENCES `eqReal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_eqLogic_object1` FOREIGN KEY (`object_id`) REFERENCES `object` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1609 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `eqReal`
--

DROP TABLE IF EXISTS `eqReal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eqReal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logicalId` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `configuration` text COLLATE utf8_unicode_ci,
  `cat` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `logicalId` (`logicalId`),
  KEY `type` (`type`),
  KEY `logicalId_Type` (`logicalId`,`type`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gsh_devices`
--

DROP TABLE IF EXISTS `gsh_devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gsh_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enable` tinyint(1) DEFAULT NULL,
  `link_type` varchar(127) DEFAULT NULL,
  `link_id` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `options` text,
  PRIMARY KEY (`id`),
  KEY `index` (`link_type`,`link_id`)
) ENGINE=InnoDB AUTO_INCREMENT=475 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `history` (
  `cmd_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `value` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  UNIQUE KEY `unique` (`datetime`,`cmd_id`),
  KEY `fk_history5min_commands1_idx` (`cmd_id`),
  CONSTRAINT `fk_history_cmd1` FOREIGN KEY (`cmd_id`) REFERENCES `cmd` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `historyArch`
--

DROP TABLE IF EXISTS `historyArch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `historyArch` (
  `cmd_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `value` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  UNIQUE KEY `unique` (`cmd_id`,`datetime`),
  KEY `cmd_id_index` (`cmd_id`),
  CONSTRAINT `fk_historyArch_cmd1` FOREIGN KEY (`cmd_id`) REFERENCES `cmd` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `interactDef`
--

DROP TABLE IF EXISTS `interactDef`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interactDef` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enable` int(11) DEFAULT '1',
  `query` text COLLATE utf8_unicode_ci,
  `reply` text COLLATE utf8_unicode_ci,
  `person` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `options` text COLLATE utf8_unicode_ci,
  `filtres` text COLLATE utf8_unicode_ci,
  `group` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `actions` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `interactQuery`
--

DROP TABLE IF EXISTS `interactQuery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interactQuery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `interactDef_id` int(11) NOT NULL,
  `query` text COLLATE utf8_unicode_ci,
  `actions` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_sarahQuery_sarahDef1_idx` (`interactDef_id`),
  FULLTEXT KEY `query` (`query`)
) ENGINE=MyISAM AUTO_INCREMENT=24152 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `listener`
--

DROP TABLE IF EXISTS `listener`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `listener` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `function` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `event` varchar(511) COLLATE utf8_unicode_ci DEFAULT NULL,
  `option` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `event` (`event`(255))
) ENGINE=InnoDB AUTO_INCREMENT=148 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `logicalId` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci,
  `action` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `plugin_logicalID` (`plugin`,`logicalId`)
) ENGINE=InnoDB AUTO_INCREMENT=255 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `note`
--

DROP TABLE IF EXISTS `note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `object`
--

DROP TABLE IF EXISTS `object`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `father_id` int(11) DEFAULT NULL,
  `isVisible` tinyint(1) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `configuration` text COLLATE utf8_unicode_ci,
  `display` text COLLATE utf8_unicode_ci,
  `image` mediumtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `fk_object_object1_idx1` (`father_id`),
  KEY `position` (`position`),
  CONSTRAINT `fk_object_object1` FOREIGN KEY (`father_id`) REFERENCES `object` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `plan`
--

DROP TABLE IF EXISTS `plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `planHeader_id` int(11) NOT NULL,
  `link_type` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link_id` int(11) DEFAULT NULL,
  `position` text COLLATE utf8_unicode_ci,
  `display` text COLLATE utf8_unicode_ci,
  `css` text COLLATE utf8_unicode_ci,
  `configuration` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `unique` (`link_type`,`link_id`),
  KEY `fk_plan_planHeader1_idx` (`planHeader_id`),
  CONSTRAINT `fk_plan_planHeader1` FOREIGN KEY (`planHeader_id`) REFERENCES `planHeader` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `plan3d`
--

DROP TABLE IF EXISTS `plan3d`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plan3d` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plan3dHeader_id` int(11) NOT NULL,
  `link_type` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link_id` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` text COLLATE utf8_unicode_ci,
  `display` text COLLATE utf8_unicode_ci,
  `css` text COLLATE utf8_unicode_ci,
  `configuration` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `link_type_link_id` (`link_type`,`link_id`),
  KEY `fk_3d_3dHeader1_idx` (`plan3dHeader_id`),
  CONSTRAINT `fk_3d_3dHeader1` FOREIGN KEY (`plan3dHeader_id`) REFERENCES `plan3dHeader` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `plan3dHeader`
--

DROP TABLE IF EXISTS `plan3dHeader`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plan3dHeader` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `configuration` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `planHeader`
--

DROP TABLE IF EXISTS `planHeader`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `planHeader` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` mediumtext COLLATE utf8_unicode_ci,
  `configuration` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `scenario`
--

DROP TABLE IF EXISTS `scenario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scenario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `isActive` tinyint(1) DEFAULT '1',
  `mode` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `schedule` text COLLATE utf8_unicode_ci,
  `scenarioElement` text COLLATE utf8_unicode_ci,
  `trigger` varchar(511) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timeout` int(11) DEFAULT NULL,
  `isVisible` tinyint(1) DEFAULT '1',
  `object_id` int(11) DEFAULT NULL,
  `display` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `configuration` text COLLATE utf8_unicode_ci,
  `type` varchar(127) COLLATE utf8_unicode_ci DEFAULT 'expert',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`group`,`object_id`,`name`),
  KEY `group` (`group`),
  KEY `fk_scenario_object1_idx` (`object_id`),
  KEY `trigger` (`trigger`(255)),
  KEY `mode` (`mode`),
  KEY `modeTriger` (`mode`,`trigger`(255)),
  CONSTRAINT `fk_scenario_object1` FOREIGN KEY (`object_id`) REFERENCES `object` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `scenarioElement`
--

DROP TABLE IF EXISTS `scenarioElement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scenarioElement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) NOT NULL,
  `type` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `options` text COLLATE utf8_unicode_ci,
  `log` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=228 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `scenarioExpression`
--

DROP TABLE IF EXISTS `scenarioExpression`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scenarioExpression` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) DEFAULT NULL,
  `scenarioSubElement_id` int(11) NOT NULL,
  `type` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtype` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expression` text COLLATE utf8_unicode_ci,
  `options` text COLLATE utf8_unicode_ci,
  `log` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_scenarioExpression_scenarioSubElement1_idx` (`scenarioSubElement_id`),
  CONSTRAINT `fk_scenarioExpression_scenarioSubElement1` FOREIGN KEY (`scenarioSubElement_id`) REFERENCES `scenarioSubElement` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=714 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `scenarioSubElement`
--

DROP TABLE IF EXISTS `scenarioSubElement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scenarioSubElement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) DEFAULT NULL,
  `scenarioElement_id` int(11) NOT NULL,
  `type` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtype` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `options` text COLLATE utf8_unicode_ci,
  `log` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_scenarioSubElement_scenarioElement1_idx` (`scenarioElement_id`),
  KEY `type` (`scenarioElement_id`,`type`),
  CONSTRAINT `fk_scenarioSubElement_scenarioElement1` FOREIGN KEY (`scenarioElement_id`) REFERENCES `scenarioElement` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=556 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `update`
--

DROP TABLE IF EXISTS `update`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `update` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logicalId` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `localVersion` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remoteVersion` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source` varchar(127) COLLATE utf8_unicode_ci DEFAULT 'market',
  `status` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `configuration` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profils` varchar(37) CHARACTER SET utf8 NOT NULL DEFAULT 'admin',
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `options` text COLLATE utf8_unicode_ci,
  `hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rights` text COLLATE utf8_unicode_ci,
  `enable` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `view`
--

DROP TABLE IF EXISTS `view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `view` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `display` text COLLATE utf8_unicode_ci,
  `order` int(11) DEFAULT NULL,
  `image` mediumtext COLLATE utf8_unicode_ci,
  `configuration` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='<double-click to overwrite multiple objects>';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `viewData`
--

DROP TABLE IF EXISTS `viewData`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `viewData` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) DEFAULT NULL,
  `viewZone_id` int(11) NOT NULL,
  `type` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link_id` int(11) DEFAULT NULL,
  `configuration` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`viewZone_id`,`link_id`,`type`),
  KEY `fk_data_zone1_idx` (`viewZone_id`),
  KEY `order` (`order`,`viewZone_id`),
  CONSTRAINT `fk_data_zone1` FOREIGN KEY (`viewZone_id`) REFERENCES `viewZone` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2951 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='<double-click to overwrite multiple objects>';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `viewZone`
--

DROP TABLE IF EXISTS `viewZone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `viewZone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `view_id` int(11) NOT NULL,
  `type` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `configuration` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_zone_view1` (`view_id`),
  CONSTRAINT `fk_zone_view1` FOREIGN KEY (`view_id`) REFERENCES `view` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1624 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='<double-click to overwrite multiple objects>';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-30 16:39:47
