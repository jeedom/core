ALTER TABLE `jeedom`.`plan` 
DROP FOREIGN KEY `fk_plan_planHeader1`;

ALTER TABLE `jeedom`.`plan` 
ADD CONSTRAINT `fk_plan_planHeader1`
  FOREIGN KEY (`planHeader_id`)
  REFERENCES `jeedom`.`planHeader` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

