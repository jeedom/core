ALTER TABLE `jeedom`.`object` 
DROP FOREIGN KEY `fk_object_object1`;

ALTER TABLE `jeedom`.`object` 
ADD CONSTRAINT `fk_object_object1`
  FOREIGN KEY (`father_id`)
  REFERENCES `jeedom`.`object` (`id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE;

