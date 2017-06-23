<?php
if (config::byKey('update3.09first', 'core', 0) == 0) {
	echo 'Remove phpsysinfo and adminer';
	shell_exec('sudo rm -rf ' . dirname(__FILE__) . '/../../../adminer*');
	shell_exec('sudo rm -rf ' . dirname(__FILE__) . '/../../../sysinfo*');
	echo "OK\n";
	echo 'Relance de la mise à jour (normal). A partir de là il faut encore attendre 5 minutes.';
	echo 'La log peut etre illisible pour suivre l\'avancement allez dans log puis choississez update.';
	echo 'Relance de la mise à jour (normal). A partir de là il faut encore attendre 5 minutes.';
	echo 'La log peut etre illisible pour suivre l\'avancement allez dans log puis choississez update.';
	echo 'Relance de la mise à jour (normal). A partir de là il faut encore attendre 5 minutes.';
	echo 'La log peut etre illisible pour suivre l\'avancement allez dans log puis choississez update.';
	echo 'Relance de la mise à jour (normal). A partir de là il faut encore attendre 5 minutes.';
	echo 'La log peut etre illisible pour suivre l\'avancement allez dans log puis choississez update.';
	sleep(20);
	config::save('update3.09first', 1);
	jeedom::update();
	die();
} else {
	echo "OK\n";
}
?>