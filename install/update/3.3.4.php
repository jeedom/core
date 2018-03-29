<?php
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
$sqls = array();
$sqls[] = "ALTER TABLE `object` RENAME `jeeObject`;";
$sqls[] = "ALTER TABLE `eqLogic` ADD `jeeObject_id` INT(11) NULL DEFAULT NULL;";
$sqls[] = "UPDATE `eqLogic` SET `jeeObject_id`=`object_id`;";
$sqls[] = "ALTER TABLE `scenario` ADD `jeeObject_id` INT(11) NULL DEFAULT NULL;";
$sqls[] = "UPDATE `scenario` SET `jeeObject_id`=`object_id`;";
$sqls[] = "CREATE INDEX `fk_scenario_jeeObject1_idx` ON `scenario` (`jeeObject_id` ASC);";
$sqls[] = "CREATE UNIQUE INDEX `nameJeeObject` ON `scenario` (`group` ASC, `jeeObject_id` ASC, `name` ASC);";
$sqls[] = "CREATE INDEX `jeeObject_id` ON `eqLogic` (`jeeObject_id` ASC);";
$sqls[] = "CREATE UNIQUE INDEX `uniqueJeeObject` ON `eqLogic` (`name` ASC, `jeeObject_id` ASC);";
$sqls[] = "UPDATE `scenario` SET `object_id`=NULL;";
$sqls[] = "UPDATE `eqLogic` SET `object_id`=NULL;";
$sqls[] = "ALTER TABLE `scenario` DROP INDEX `name`;";
$sqls[] = "ALTER TABLE `scenario` DROP INDEX `fk_scenario_object1_idx`;";
$sqls[] = "ALTER TABLE `scenario` DROP `object_id`;";
$sqls[] = "ALTER TABLE `eqLogic` DROP `object_id`;";
foreach ($sqls as $sql) {
	try {
		DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
	} catch (Exception $e) {
		echo $e->getMessage();
	}
}
?>