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
echo "[START RESTORE]\n";
$starttime = strtotime('now');
if (isset($argv)) {
	foreach ($argv as $arg) {
		$argList = explode('=', $arg);
		if (isset($argList[0]) && isset($argList[1])) {
			$_GET[$argList[0]] = $argList[1];
		}
	}
}

try {
	require_once dirname(__FILE__) . '/../core/php/core.inc.php';
	echo "***************Start of Jeedom restoration " . date('Y-m-d H:i:s') . "***************\n";

	try {
		echo "Send begin restoration event...";
		jeedom::event('begin_restore', true);
		echo "OK\n";
	} catch (Exception $e) {
		echo '***ERREUR*** ' . $e->getMessage();
	}

	global $CONFIG;
	global $BACKUP_FILE;
	if (isset($BACKUP_FILE)) {
		$_GET['backup'] = $BACKUP_FILE;
	}
	if (!isset($_GET['backup']) || $_GET['backup'] == '') {
		if (substr(config::byKey('backup::path'), 0, 1) != '/') {
			$backup_dir = dirname(__FILE__) . '/../' . config::byKey('backup::path');
		} else {
			$backup_dir = config::byKey('backup::path');
		}
		if (!file_exists($backup_dir)) {
			mkdir($backup_dir, 0770, true);
		}
		$backup = null;
		$mtime = null;
		foreach (scandir($backup_dir) as $file) {
			if ($file != "." && $file != ".." && $file != ".htaccess" && strpos($file, '.tar.gz') !== false) {
				$s = stat($backup_dir . '/' . $file);
				if ($backup === null || $mtime === null) {
					$backup = $backup_dir . '/' . $file;
					$mtime = $s['mtime'];
				}
				if ($mtime < $s['mtime']) {
					$backup = $backup_dir . '/' . $file;
					$mtime = $s['mtime'];
				}
			}
		}
	} else {
		$backup = $_GET['backup'];
	}
	if (substr($backup, 0, 1) != '/') {
		$backup = dirname(__FILE__) . '/../' . $backup;
	}

	if (!file_exists($backup)) {
		throw new Exception('SBackup not found.' . $backup);
	}

	try {
		echo "Check rights...";
		jeedom::cleanFileSytemRight();
		echo "OK\n";
	} catch (Exception $e) {
		echo '***ERREUR*** ' . $e->getMessage();
	}

	$jeedom_dir = realpath(dirname(__FILE__) . '/../');

	echo "File use for restoration : " . $backup . "\n";

	echo "Backup databse acces configuration...";

	if (copy(dirname(__FILE__) . '/../core/config/common.config.php', '/tmp/common.config.php')) {
		echo 'Can not copy ' . dirname(__FILE__) . "/../core/config/common.config.php\n";
	}

	echo "OK\n";

	try {
		jeedom::stop();
	} catch (Exception $e) {
		$e->getMessage();
	}

	echo "Decompression of backup...";
	$excludes = array(
		'tmp',
		'log',
		'backup',
		'.git',
		'.log',
		'core/config/common.config.php',
		config::byKey('backup::path'),
	);
	$exclude = '';
	foreach ($excludes as $folder) {
		$exclude .= ' --exclude="' . $folder . '"';
	}
	$rc = 0;
	system('cd ' . $jeedom_dir . '; tar xfz "' . $backup . '" ' . $exclude);
	echo "OK\n";
	if (!file_exists($jeedom_dir . "/DB_backup.sql")) {
		throw new Exception('Cannot find backup database file : DB_backup.sql');
	}
	echo "Remove databse table";
	$tables = DB::Prepare("SHOW TABLES", array(), DB::FETCH_TYPE_ALL);
	echo "Disable constrainte...";
	DB::Prepare("SET foreign_key_checks = 0", array(), DB::FETCH_TYPE_ROW);
	echo "OK\n";
	foreach ($tables as $table) {
		$table = array_values($table);
		$table = $table[0];
		echo "Remove table : " . $table . ' ...';
		DB::Prepare('DROP TABLE IF EXISTS `' . $table . '`', array(), DB::FETCH_TYPE_ROW);
		echo "OK\n";
	}

	echo "Restoration of database...";
	shell_exec("mysql --host=" . $CONFIG['db']['host'] . " --port=" . $CONFIG['db']['port'] . " --user=" . $CONFIG['db']['username'] . " --password=" . $CONFIG['db']['password'] . " " . $CONFIG['db']['dbname'] . "  < " . $jeedom_dir . "/DB_backup.sql");
	echo "OK\n";

	echo "Enable constrainte...";
	try {
		DB::Prepare("SET foreign_key_checks = 1", array(), DB::FETCH_TYPE_ROW);
	} catch (Exception $e) {

	}
	echo "OK\n";

	if (!file_exists(dirname(__FILE__) . '/../core/config/common.config.php')) {
		echo "Restoration of database configuration file...";
		copy('/tmp/common.config.php', dirname(__FILE__) . '/../core/config/common.config.php');
		echo "OK\n";
	}

	echo "Restoration of cache...";
	try {
		cache::restore();
	} catch (Exception $e) {

	}
	echo "OK\n";

	foreach (plugin::listPlugin(true) as $plugin) {
		$plugin_id = $plugin->getId();
		$dependancy_info = $plugin->dependancy_info(true);
		if (method_exists($plugin_id, 'restore')) {
			echo 'Plugin restoration : ' . $plugin_id . '...';
			$plugin_id::restore();
			echo "OK\n";
		}
	}
	config::save('hardware_name', '');
	$cache = cache::byKey('jeedom::isCapable::sudo');
	$cache->remove();

	try {
		echo 'Check jeedom file right...';
		jeedom::cleanFileSytemRight();
		echo "OK\n";
	} catch (Exception $e) {
		echo "NOK\n";
	}

	try {
		jeedom::start();
	} catch (Exception $e) {
		echo $e->getMessage();
	}

	try {
		echo "Send end of backup event...";
		jeedom::event('end_restore');
		echo "OK\n";
	} catch (Exception $e) {
		echo '***ERREUR*** ' . $e->getMessage();
	}
	echo "Restoration duration : " . (strtotime('now') - $starttime) . "s\n";
	echo "***************Fin de la restauration de Jeedom***************\n";
	echo "[END RESTORE SUCCESS]\n";
} catch (Exception $e) {
	echo 'Error during restoration : ' . $e->getMessage();
	echo 'Details : ' . print_r($e->getTrace(), true);
	echo "[END RESTORE ERROR]\n";
	jeedom::start();
	throw $e;
}
