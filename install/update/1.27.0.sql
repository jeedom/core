ALTER TABLE `scenario` 
DROP COLUMN `isRepeat`,
CHANGE COLUMN `schedule` `schedule` TEXT NULL DEFAULT NULL ;
