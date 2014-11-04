ALTER TABLE `jeedom`.`scenario` 
ADD COLUMN `configuration` TEXT NULL DEFAULT NULL AFTER `description`;

ALTER TABLE `jeedom`.`cache` 
ADD INDEX `lifetime` (`lifetime` ASC);

ALTER TABLE `jeedom`.`internalEvent` 
ADD INDEX `datetime` (`datetime` ASC);

ALTER TABLE `jeedom`.`cmd` 
ADD INDEX `type_eventOnly` (`type` ASC, `eventOnly` ASC);

ALTER TABLE `jeedom`.`connection` 
ADD INDEX `datetime` (`datetime` ASC);

ALTER TABLE `jeedom`.`connection` 
ADD INDEX `status_datetime` (`status` ASC, `datetime` ASC);  

ALTER TABLE `jeedom`.`cron` 
ADD COLUMN `priority` INT(11) NULL DEFAULT NULL AFTER `id`;