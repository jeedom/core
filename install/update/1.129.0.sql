CREATE TABLE IF NOT EXISTS `jeedom`.`rights` (
  `id` INT(11) NOT NULL,
  `entity` VARCHAR(127) NULL DEFAULT NULL,
  `user_id` INT(11) NOT NULL,
  `right` INT(11) NULL DEFAULT NULL,
  `options` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_rights_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_rights_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `jeedom`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

ALTER TABLE `jeedom`.`rights` 
ADD UNIQUE INDEX `entityUser` (`entity` ASC, `user_id` ASC);


ALTER TABLE `jeedom`.`cache` 
CHANGE COLUMN `options` `options` MEDIUMTEXT NULL DEFAULT NULL ;