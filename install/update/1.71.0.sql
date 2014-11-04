ALTER TABLE `jeedom`.`connection` 
ENGINE = MEMORY ,
CHANGE COLUMN `options` `options` VARCHAR(2048) NULL DEFAULT NULL ,
CHANGE COLUMN `informations` `informations` VARCHAR(2048) NULL DEFAULT NULL 

