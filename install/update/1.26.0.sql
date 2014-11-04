ALTER TABLE `cmd` 
ADD COLUMN `order` INT(11) NULL DEFAULT NULL AFTER `eqLogic_id`,
ADD INDEX `order` (`order` ASC)
