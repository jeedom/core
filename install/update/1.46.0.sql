ALTER TABLE `jeedom`.`scenario` 
ADD COLUMN `isVisible` TINYINT(1) NULL DEFAULT 1 ,
ADD COLUMN `object_id` INT(11) NULL DEFAULT NULL ,
ADD INDEX `fk_scenario_object1_idx` (`object_id` ASC);


ALTER TABLE `jeedom`.`scenario` 
ADD CONSTRAINT `fk_scenario_object1`
  FOREIGN KEY (`object_id`)
  REFERENCES `jeedom`.`object` (`id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE;
