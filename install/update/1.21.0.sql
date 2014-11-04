ALTER TABLE `eqLogic` 
DROP INDEX `logicalID` ,
ADD INDEX `logical_id` (`logicalId` ASC),
DROP INDEX `logicalD_eqTypeName` ,
ADD INDEX `logica_id_eqTypeName` (`logicalId` ASC, `eqType_name` ASC),
DROP INDEX `fk_eqLogic_object_idx` ,
ADD INDEX `object_id` (`object_id` ASC),
DROP INDEX `fk_eqLogic_jeenode1_idx` ,
ADD INDEX `eqReal_id` (`eqReal_id` ASC),
ADD INDEX `name` (`name` ASC);

ALTER TABLE `cmd` 
DROP INDEX `fk_commands_eqLogic_idx` ,
ADD INDEX `eqLogic_id` (`eqLogic_id` ASC),
DROP INDEX `fk_cmd_cmd1_idx` ,
ADD INDEX `value` (`value` ASC),
ADD INDEX `isHistorized` (`isHistorized` ASC),
ADD INDEX `type` (`type` ASC),
ADD INDEX `eventOnly` (`eventOnly` ASC),
ADD INDEX `name` (`name` ASC),
ADD INDEX `subtype` (`subType` ASC),
ADD INDEX `collect` (`collect` ASC);

ALTER TABLE `eqReal` 
ADD INDEX `name` (`name` ASC);

ALTER TABLE `chatHistory` 
CHANGE COLUMN `message` `message` VARCHAR(1023) NULL DEFAULT NULL,
ENGINE = MEMORY;

ALTER TABLE `internalEvent`
CHANGE COLUMN `options` `options` VARCHAR(511) NULL DEFAULT NULL,
ENGINE = MEMORY;

CREATE TABLE IF NOT EXISTS `cache` (
  `key` VARCHAR(127) NOT NULL,
  `datetime` DATETIME NULL DEFAULT NULL,
  `lifetime` VARCHAR(127) NULL DEFAULT NULL,
  `value` VARCHAR(5119) NULL DEFAULT NULL,
  `options` VARCHAR(5119) NULL DEFAULT NULL,
  PRIMARY KEY (`key`),
  UNIQUE INDEX `key_UNIQUE` (`key` ASC))
ENGINE = MEMORY
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

