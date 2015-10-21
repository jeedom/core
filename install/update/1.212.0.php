<?php
if (config::byKey('update1.212first', 'core', 0) == 0) {
	echo 'Relance de la mise à jour (normal) pour passer au nouveau systeme de cache. La log peut etre illisible pour suivre l\'avancement allez sur dans log puis choissiez update';
	sleep(20);
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