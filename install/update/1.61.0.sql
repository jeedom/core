ALTER TABLE `jeedom`.`cmd` 
ADD COLUMN `logicalId` VARCHAR(127) NULL DEFAULT NULL AFTER `eqLogic_id`;
