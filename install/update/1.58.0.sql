ALTER TABLE `jeedom`.`object` 
ADD COLUMN `position` INT(11) NULL DEFAULT NULL AFTER `isVisible`,
ADD COLUMN `configuration` TEXT NULL DEFAULT NULL AFTER `position`,
ADD INDEX `position` (`position` ASC);