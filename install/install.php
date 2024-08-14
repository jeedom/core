<?php

/** @entrypoint */
/** @console */

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

require_once dirname(__DIR__).'/core/php/console.php';

set_time_limit(1800);
echo "[START INSTALL]\n";
$starttime = strtotime('now');

try {
	date_default_timezone_set('Europe/Brussels');
	require_once __DIR__ . '/../vendor/autoload.php';
	require_once __DIR__ . '/../core/config/common.config.php';
	require_once __DIR__ . '/../core/class/DB.class.php';
	require_once __DIR__ . '/../core/class/system.class.php';
	if (count(system::ps('install/install.php', 'sudo')) > 1) {
		echo "An update/installation is already in progress. You must wait for it to finish before restarting another one.\n";
		print_r(system::ps('install/install.php', 'sudo'));
		echo "[END INSTALL]\n";
		die();
	}
	echo "****Install jeedom at (" . date('Y-m-d H:i:s') . ")****\n";
	/*         * ***************************INSTALLATION************************** */
	if (version_compare(PHP_VERSION, '7', '<')) {
		throw new Exception('Jeedom nécessite PHP 7 ou plus (actuellement : ' . PHP_VERSION . ')');
	}
	echo "\nInstallation of Jeedom\n";
	echo "Installating database...";
	try {
		DB::compareAndFix(json_decode(file_get_contents(__DIR__.'/database.json'),true));
	} catch (\Exception $e) {
		echo "***ERROR*** " . $e->getMessage() . "\n";
	}
	echo "OK\n";
	require_once __DIR__ . '/../core/php/core.inc.php';
	echo "Post install...\n";
	config::save('api', config::genKey());
	require_once __DIR__ . '/consistency.php';
	echo "Creating user (admin,admin)\n";
	try{
		$user = new user();
		$user->setLogin('admin');
		$user->setPassword(sha512('admin'));
		$user->setProfils('admin');
		$user->save();
	}catch (\Exception $e) {
		echo "***ERROR*** " . $e->getMessage() . "\n";
	}
	config::save('log::level', 400);
	echo "OK\n";
	config::save('version', jeedom::version());
} catch (Exception $e) {
	echo 'Error during install : ' . $e->getMessage();
	echo 'Details : ' . print_r($e->getTrace(), true);
	echo "[END INSTALL ERROR]\n";
	throw $e;
}

echo "Install duration : " . (strtotime('now') - $starttime) . "s\n";
echo "[END INSTALL SUCCESS]\n";
