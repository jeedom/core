ALTER TABLE `cron` 
ADD COLUMN `option` TEXT NULL DEFAULT NULL AFTER `deamonSleepTime`,
ADD COLUMN `once` INT(11) NULL DEFAULT NULL AFTER `option`;

ALTER TABLE `cron` 
CHANGE COLUMN `option` `option` VARCHAR(255) NULL DEFAULT NULL ,
DROP INDEX `class_function` ,
ADD UNIQUE INDEX `class_function` (`class` ASC, `function` ASC, `option` ASC);

