<?php
if (config::byKey('update1.309first', 'core', 0) == 0) {
	echo 'Remove phpsysinfo and adminer';
	shell_exec('sudo rm -rf ' . dirname(__FILE__) . '/../../../adminer*');
	shell_exec('sudo rm -rf ' . dirname(__FILE__) . '/../../../sysinfo*');
	echo "OK\n";
	echo 'Relance de la mise à jour (normal). La log peut etre illisible pour suivre l\'avancement allez sur dans log puis choississez update';
	sleep(20);
	config::save('update1.309first', 1);
	jeedom::update();
	die();
} else {
	echo "OK\n";
}
?>