<?php
try {
	foreach (object::all() as $object) {
		$object->save();
	}
} catch (Exception $exc) {
	echo $exc->getMessage();
}
?>