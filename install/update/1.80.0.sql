ALTER TABLE `jeedom`.`scenario` 
ADD COLUMN `display` TEXT NULL DEFAULT NULL AFTER `hlogs`;

ALTER TABLE `jeedom`.`cmd` 
ADD INDEX `logicalID` (`logicalId` ASC);

ALTER TABLE `jeedom`.`cmd` 
ADD INDEX `logicalID` (`logicalId` ASC, `eqLogic_id` ASC);
