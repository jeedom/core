ALTER TABLE `object` RENAME `jeeObject`;
ALTER TABLE `eqLogic` CHANGE object_id jeeObject_id INT;
ALTER TABLE `scenario` CHANGE object_id jeeObject_id INT;