ALTER TABLE `scenario` 
DROP COLUMN `type`,
ADD COLUMN `timeout` INT(11) NULL DEFAULT NULL AFTER `log`,
ADD INDEX `group` (`group` ASC);
