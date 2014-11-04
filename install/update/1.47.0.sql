ALTER TABLE `jeedom`.`scenario` 
DROP INDEX `name` ,
ADD UNIQUE INDEX `name` (`name` ASC, `group` ASC, `object_id` ASC);
