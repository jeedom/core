<?php
if (config::byKey('update1.212first', 'core', 0) == 0) {
	config::save('update1.212first', 1);
	jeedom::update();
	die();
} else {
	$sql = 'SELECT *
        FROM cache';
	$caches = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, 'cache');
	foreach ($caches as $cache) {
		$cache->save();
	}
}
?>