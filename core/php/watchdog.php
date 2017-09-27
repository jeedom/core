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
	header("Status: 404 Not Found");
	header('HTTP/1.0 404 Not Found');
	$_SERVER['REDIRECT_STATUS'] = 404;
	echo "<h1>404 Not Found</h1>";
	echo "The page that you have requested could not be found.";
	exit();
}
$datetime = date('Y-m-d H:i:s');
echo "Watchdog Jeedom at " . $datetime . "\n";

$update_in_progress = exec('ps ax | grep -E "dpkg|apt" | grep -v grep | wc -l');
if ($update_in_progress != 0) {
	echo 'Update (apt or dpkg) in progress, cancel watchdog';
	die();
}

/******************************Database***************************************/

/********************************MySQL****************************************/
echo 'Check MySql => ';
$output = array();
$rc = 0;
exec('systemctl is-enabled mysql 2>&1', $output, $rc);
if ($rc == 0) {
	$output = array();
	$rc = 0;
	exec('systemctl status mysql', $output, $rc);
	if ($rc == 0) {
		echo "OK\n";
	} else {
		echo "NOK\n";
		echo "Trying to restart MySql\n";
		shell_exec('sudo systemctl restart mysql');
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

/********************************Nginx****************************************/
echo 'Check Nginx => ';
$output = array();
$rc = 0;
exec('systemctl is-enabled nginx 2>&1', $output, $rc);
if ($rc == 0) {
	$output = array();
	$rc = 0;
	exec('systemctl status nginx', $output, $rc);
	if ($rc == 0) {
		echo "OK\n";
	} else {
		echo "NOK\n";
		echo "Trying to restart Nginx\n";
		shell_exec('sudo systemctl restart nginx');
		echo "Recheck Nginx => ";
		exec('systemctl status nginx', $output, $rc);
		if ($rc != 0) {
			echo "NOK. Please check manually why...\n";
		}
	}
} else {
	echo "NOT_ENABLED\n";
}

/********************************Apache****************************************/
echo 'Check Apache => ';
$output = array();
$rc = 0;
exec('systemctl is-enabled apache2 2>&1', $output, $rc);
if ($rc == 0) {
	$output = array();
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