<?php
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
try {
	foreach (object::all() as $object) {
		$object->save();
	}
} catch (Exception $exc) {
	echo $exc->getMessage();
}
?>