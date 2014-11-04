CREATE TABLE IF NOT EXISTS `planHeader` (
  `id` int(11) NOT NULL,
  `name` varchar(127) DEFAULT NULL,
  `image` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jeedom`.`start` (
  `key` VARCHAR(127) NOT NULL,
  `value` VARCHAR(127) NULL DEFAULT NULL,
  PRIMARY KEY (`key`))
ENGINE = MEMORY
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

ALTER IGNORE TABLE `jeedom`.`plan` 
DROP FOREIGN KEY `fk_plan_object1`;

ALTER IGNORE TABLE `jeedom`.`plan` 
DROP COLUMN `object_id`,
ADD COLUMN `planHeader_id` INT(11) NOT NULL AFTER `id`,
DROP INDEX `unique` ,
ADD INDEX `unique` (`link_type` ASC, `link_id` ASC),
ADD INDEX `fk_plan_planHeader1_idx` (`planHeader_id` ASC),
DROP INDEX `object_id` ,
DROP INDEX `fk_plan_object1_idx` ;

ALTER IGNORE TABLE `jeedom`.`plan` 
ADD CONSTRAINT `fk_plan_planHeader1`
  FOREIGN KEY (`planHeader_id`)
  REFERENCES `jeedom`.`planHeader` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER IGNORE TABLE `jeedom`.`planHeader` 
CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT ;

ALTER IGNORE TABLE `jeedom`.`cache` 
ENGINE = MyISAM ,
DROP INDEX `key_UNIQUE` ;




