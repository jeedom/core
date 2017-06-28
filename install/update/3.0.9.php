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
	shell_exec('sudo mv /home/jeedomtmp /tmp');
	shell_exec('sudo rm -rf /home/jeedomtmp');
	echo 'Move cache and tmp jeedom to new folder (/tmp/jeedom). It can take some times....';
	jeedom::stop();
	shell_exec('sudo mkdir -p /tmp/jeedom');
	shell_exec('sudo rm -rf  /tmp/jeedom/cache;sudo mv /tmp/jeedom-cache /tmp/jeedom/cache');
	shell_exec('sudo touch /tmp/jeedom/started');
	shell_exec('sudo chmod 777 -R /tmp/jeedom');
	jeedom::start();
	echo "OK\n";
}
?>