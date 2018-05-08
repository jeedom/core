<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
try {
	$sql = "ALTER TABLE `user`
	ADD `profils` varchar(37) COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'admin' AFTER `login`;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $e) {
	echo $e->getMessage();
}
try {
	foreach (user::all() as $user) {
		$rights = rights::byUserId($user->getId());
		if (count($rights) == 0) {
			continue;
		}
		foreach ($rights as $right) {
			if (strpos($right->getEntity(), 'eqLogic') !== false) {
				if (strpos($right->getEntity(), 'view') !== false) {
					$user->setRights('eqLogic' . str_replace(array('eqLogic', 'view'), array('', ''), $right->getEntity()), 'r');
				}
				if (strpos($right->getEntity(), 'action') !== false) {
					$user->setRights('eqLogic' . str_replace(array('eqLogic', 'action'), array('', ''), $right->getEntity()), 'rx');
				}
			}
			if (strpos($right->getEntity(), 'scenario') !== false) {
				if (strpos($right->getEntity(), 'view') !== false) {
					$user->setRights('scenario' . str_replace(array('scenario', 'view'), array('', ''), $right->getEntity()), 'r');
				}
				if (strpos($right->getEntity(), 'action') !== false) {
					$user->setRights('scenario' . str_replace(array('scenario', 'action'), array('', ''), $right->getEntity()), 'rx');
				}
			}
		}
		$user->save();
	}
} catch (Exception $e) {

}
try {
	$sql = "DROP TABLE `rights`;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $e) {

}
try {
	$sql = "DROP TABLE `connection`;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $e) {

}
?>