ALTER TABLE `jeedom`.`user` 
ADD COLUMN `enable` INT(11) NULL DEFAULT NULL AFTER `rights`;

UPDATE `user` SET `enable` = 1; 
