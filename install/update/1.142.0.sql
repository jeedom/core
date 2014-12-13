ALTER TABLE `scenario` 
ADD COLUMN `type` VARCHAR(127) NULL DEFAULT 'expert' AFTER `configuration`;
