ALTER TABLE `jeedom`.`user` 
ADD COLUMN `rights` TEXT NULL DEFAULT NULL AFTER `hash`;
