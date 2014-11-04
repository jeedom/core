ALTER TABLE `jeedom`.`viewData` 
ADD COLUMN `order` INT(11) NULL DEFAULT NULL AFTER `id`,
ADD INDEX `order` (`order` ASC, `viewZone_id` ASC);
