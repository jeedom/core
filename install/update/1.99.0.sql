ALTER TABLE `jeedom`.`scenario` 
CHANGE COLUMN `trigger` `trigger` VARCHAR(255) NULL DEFAULT NULL ,
ADD INDEX `trigger` (`trigger` ASC),
ADD INDEX `mode` (`mode` ASC),
ADD INDEX `modeTriger` (`mode` ASC, `trigger` ASC);
