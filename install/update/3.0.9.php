<?php
if (config::byKey('update3.09firstupd', 'core', 0) == 0) {
	try {
		echo 'Remove phpsysinfo and adminer';
		shell_exec('sudo rm -rf ' . dirname(__FILE__) . '/../../../adminer*');
		shell_exec('sudo rm -rf ' . dirname(__FILE__) . '/../../../sysinfo*');
		echo "OK\n";
		shell_exec('sudo mv /home/jeedomtmp/jeedom* /tmp');
		shell_exec('sudo rm -rf /home/jeedomtmp');
		echo 'Move cache and tmp jeedom to new folder (/tmp/jeedom). It can take some times....\n';
		jeedom::stop();
		sleep(5);
		shell_exec('sudo mkdir -p /tmp/jeedom');
		shell_exec('sudo rm -rf  /tmp/jeedom/cache;sudo mv /tmp/jeedom-cache /tmp/jeedom/cache');
		shell_exec('sudo touch /tmp/jeedom/started');
		shell_exec('sudo chmod 777 -R /tmp/jeedom');
		jeedom::start();
		echo "OK\n";
		try {
			echo "Check jeedom consistency...";
			require_once dirname(__FILE__) . '/../consistency.php';
			echo "OK\n";
		} catch (Exception $ex) {
			echo "***ERREUR*** " . $ex->getMessage() . "\n";
		}
		config::save('update3.09firstupd', 1);
		jeedom::event('end_update');
		sleep(5);
		echo "***************Jeedom is up to date in 3.0.9***************\n";
		die();
	} catch (Exception $e) {
		echo $e->getMessage();
	}
} else {
	echo "OK\n";
}
?>