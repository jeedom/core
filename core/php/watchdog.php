<?php
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/
if (php_sapi_name() != 'cli' || isset($_SERVER['REQUEST_METHOD']) || !isset($_SERVER['argc'])) {
	header("Statut: 404 Page non trouvée");
	header('HTTP/1.0 404 Not Found');
	$_SERVER['REDIRECT_STATUS'] = 404;
	echo "<h1>404 Non trouvé</h1>";
	echo "La page que vous demandez ne peut être trouvée.";
	exit();
}
$datetime = date('Y-m-d H:i:s');
echo "Watchdog Jeedom at " . $datetime . "\n";
$wathdog_in_progress = exec('ps ax | grep "core/php/watchdog.php" | grep -v grep | grep -v  "sh -c" |  wc -l');
if ($wathdog_in_progress > 1) {
	echo 'Watchdog in progress, cancel watchdog (' . $wathdog_in_progress . ')';
	die();
}
$update_in_progress = exec('ps -C apt,dpkg |  wc -l');
if ($update_in_progress > 1) {
	echo 'Update (apt or dpkg) in progress, cancel watchdog';
	die();
}
$output = array();

/********************************Date****************************************/
echo 'Check Date => ';
echo date('Y-m-d')."\n";
if(date('Y') < 2019 || date('Y') > 2040){
	echo 'Invalid date found, try correct it';
	exec('sudo service ntp stop;sudo ntpdate -s time.nist.gov;sudo service ntp start');
}

/********************************Free space****************************************/

$freespace = round(disk_free_space(__DIR__ . '/../../') / disk_total_space(__DIR__ . '/../../') * 100);
echo 'Check Free space ('.$freespace.'%) => ';
if($freespace <= 1){
	echo "NOK\n";
	echo "Trying cleaning\n";
	if(file_exists(__DIR__.'/../../tmp')){
		shell_exec('rm -rf '.__DIR__.'/../../tmp/*');
	}
	if(file_exists(__DIR__.'/../../log')){
		shell_exec('rm -rf '.__DIR__.'/../../log/*');
	}
	$freespace = round(disk_free_space(__DIR__ . '/../../') / disk_total_space(__DIR__ . '/../../') * 100);
	echo "Recheck Free space ('.$freespace.'%) => ";
	if($freespace <= 1){
		echo "NOK. Please do somethink manually...\n";
	}else{
		echo "OK\n";
	}
}else{
	echo "OK\n";
}

/********************************MySQL****************************************/
echo 'Check MySql => ';
$rc = 0;
$enable = false;
$enable = (shell_exec('ls -l /etc/rc[2-5].d/S0?mysql 2>/dev/null | wc -l') > 0);
if ($enable) {
	$rc = 0;
	exec('systemctl status mysql', $output, $rc);
	if ($rc == 0) {
		echo "OK\n";
	} else {
		echo "NOK\n";
		echo "Trying to restart MySql\n";
		shell_exec('systemctl restart mysql');
		echo "Recheck MySql => ";
		exec('systemctl status mysql', $output, $rc);
		if ($rc != 0) {
			echo "NOK. Please check manually why...\n";
			die();
		}
	}
} else {
	echo "NOT_ENABLED\n";
}
/******************************Web Server**************************************/
/********************************Apache****************************************/
echo 'Check Apache => ';
$rc = 0;
$enable = (shell_exec('ls -l /etc/rc[2-5].d/S0?apache2 2>/dev/null | wc -l') > 0);
if ($enable) {
	$rc = 0;
	exec('systemctl status apache2', $output, $rc);
	if ($rc == 0) {
		echo "OK\n";
	} else {
		echo "NOK\n";
		echo "Trying to restart Apache\n";
		shell_exec('sudo systemctl restart apache2');
		echo "Recheck Apache => ";
		exec('systemctl status apache2', $output, $rc);
		if ($rc != 0) {
			echo "NOK. Please check manually why...\n";
		}
	}
} else {
	echo "NOT_ENABLED\n";
}
