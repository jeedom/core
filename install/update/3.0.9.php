<?php
try {
	echo 'Remove phpsysinfo and adminer';
	shell_exec('sudo rm -rf ' . dirname(__FILE__) . '/../../../adminer*');
	shell_exec('sudo rm -rf ' . dirname(__FILE__) . '/../../../sysinfo*');
	echo "OK\n";
	shell_exec('sudo mv /home/jeedomtmp /tmp');
	shell_exec('sudo rm -rf /home/jeedomtmp');
	echo 'Move cache and tmp jeedom to new folder (/tmp/jeedom). It can take some times....\n';
	jeedom::stop();
	shell_exec('sudo mkdir -p /tmp/jeedom');
	shell_exec('sudo rm -rf  /tmp/jeedom/cache;sudo mv /tmp/jeedom-cache /tmp/jeedom/cache');
	shell_exec('sudo touch /tmp/jeedom/started');
	shell_exec('sudo chmod 777 -R /tmp/jeedom');
	jeedom::start();
	echo "OK\n";
	echo "***************Jeedom is up to date in 3.0.9***************\n";
	jeedom::event('end_update');
	die();
} catch (Exception $e) {
	echo $e->getMessage();
}
?>