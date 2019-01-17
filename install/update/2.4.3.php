<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
try {
	foreach (jeeObject::all() as $object) {
		$object->save();
	}
} catch (Exception $exc) {
	echo $exc->getMessage();
}
