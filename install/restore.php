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

echo "[START RESTORE]\n";
$starttime = strtotime('now');

try {
	require_once __DIR__ . '/../core/php/core.inc.php';
	echo "***************Begin Jeedom restore " . date('Y-m-d H:i:s') . "***************\n";
	
	try {
		echo "Send begin restore event...";
		jeedom::event('begin_restore', true);
		echo "OK\n";
	} catch (Exception $e) {
		echo '***ERROR*** ' . $e->getMessage();
	}
	
	global $CONFIG;
	global $BACKUP_FILE;
	if (isset($BACKUP_FILE)) {
		$_GET['backup'] = $BACKUP_FILE;
	}
	if (!isset($_GET['backup']) || $_GET['backup'] == '') {
		if (substr(config::byKey('backup::path'), 0, 1) != '/') {
			$backup_dir = __DIR__ . '/../' . config::byKey('backup::path');
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
		$backup = __DIR__ . '/../' . $backup;
	}
	
	if (!file_exists($backup)) {
		throw new Exception('Backup not found.' . $backup);
	}
	
	try {
		echo "Checking rights...";
		jeedom::cleanFileSystemRight();
		echo "OK\n";
	} catch (Exception $e) {
		echo '***ERROR*** ' . $e->getMessage();
	}
	
	$jeedom_dir = realpath(__DIR__ . '/../');
	
	echo "Restore from file : " . $backup . "\n";
	
	echo "Backup database access configuration...";
	
	if (!copy(__DIR__ . '/../core/config/common.config.php', '/tmp/common.config.php')) {
		echo 'Cannot copy ' . __DIR__ . "/../core/config/common.config.php\n";
	}
	
	echo "OK\n";
	
	try {
		jeedom::stop();
	} catch (Exception $e) {
		$e->getMessage();
	}
	
	echo "Unpacking backup...";
	$excludes = array(
		'tmp',
		'log',
		'backup',
		'script/tunnel',
		'.git',
		'.log',
		'core/config/common.config.php',
		'/vendor',
		config::byKey('backup::path'),
	);
	$exclude = '';
	foreach ($excludes as $folder) {
		$exclude .= ' --exclude="' . $folder . '"';
	}
	$rc = 0;
	system('cd ' . $jeedom_dir . '; tar xfz "' . $backup . '" ' . $exclude);
	echo "OK\n";

	
	if (exec('which composer | wc -l') == 0) {
		echo "\nNeed to install composer...";
		echo shell_exec(system::getCmdSudo().' ' . __DIR__ . '/../resources/install_composer.sh');
		echo "OK\n";
	}
	echo "Update composer file...\n";
	if (exec('which composer | wc -l') > 0) {
		shell_exec(system::getCmdSudo(). ' rm '. __DIR__ . '/../composer.lock');
		shell_exec('export COMPOSER_HOME="/tmp/composer";export COMPOSER_ALLOW_SUPERUSER=1;'.system::getCmdSudo().' composer self-update > /dev/null 2>&1');
		shell_exec('cd ' . __DIR__ . '/../;export COMPOSER_ALLOW_SUPERUSER=1;export COMPOSER_HOME="/tmp/composer";'.system::getCmdSudo().' composer update --no-interaction --no-plugins --no-scripts --no-ansi --no-dev --no-progress --optimize-autoloader --with-all-dependencies --no-cache > /dev/null 2>&1');
		shell_exec(system::getCmdSudo().' rm /tmp/composer 2>/dev/null');
		if(method_exists('jeedom','cleanFileSystemRight')){
			jeedom::cleanFileSystemRight();
		}
	}
	echo "OK\n";
	echo "[PROGRESS][58]\n";

	if (!file_exists($jeedom_dir . "/DB_backup.sql")) {
		throw new Exception('Cannot find database backup file : DB_backup.sql');
	}
	echo "Deleting database...";
	$tables = DB::Prepare("SHOW TABLES", array(), DB::FETCH_TYPE_ALL);
	echo "Disabling constraints...";
	DB::Prepare("SET foreign_key_checks = 0", array(), DB::FETCH_TYPE_ROW);
	echo "OK\n";
	foreach ($tables as $table) {
		$table = array_values($table)[0];
		echo "Deleting table : " . $table . ' ...';
		DB::Prepare('DROP TABLE IF EXISTS `' . $table . '`', array(), DB::FETCH_TYPE_ROW);
		echo "OK\n";
	}
	
	echo "Restoring database from backup...";

	if (isset($CONFIG['db']['unix_socket'])) {
		$str_db_connexion = "--socket=" . $CONFIG['db']['unix_socket'] . " --user=" . $CONFIG['db']['username'] . " --password='" . $CONFIG['db']['password'] . "' " . $CONFIG['db']['dbname'];
	} else {
		if ($CONFIG['db']['host'] == 'localhost' && $CONFIG['db']['port'] == 3306) {
			$str_db_connexion = "--user=" . $CONFIG['db']['username'] . " --password='" . $CONFIG['db']['password'] . "' " . $CONFIG['db']['dbname'];
		} else {
			$str_db_connexion = "--host=" . $CONFIG['db']['host'] . " --port=" . $CONFIG['db']['port'] . " --user=" . $CONFIG['db']['username'] . " --password='" . $CONFIG['db']['password'] . "' " . $CONFIG['db']['dbname'];
		}
	}
	if(isset($CONFIG['db']['unix_socket'])) {
		shell_exec("mysql ". $str_db_connexion . "  < " . $jeedom_dir . "/DB_backup.sql");
	} else {
		shell_exec("mysql ". $str_db_connexion . "  < " . $jeedom_dir . "/DB_backup.sql");
	}
	echo "OK\n";
	
	echo "Enable back constraints...";
	try {
		DB::Prepare("SET foreign_key_checks = 1", array(), DB::FETCH_TYPE_ROW);
	} catch (Exception $e) {
		
	}
	echo "OK\n";
	
	if (!file_exists(__DIR__ . '/../core/config/common.config.php')) {
		echo "Restoring database configuration file...";
		copy('/tmp/common.config.php', __DIR__ . '/../core/config/common.config.php');
		echo "OK\n";
	}
	
	echo "Restoring cache...";
	try {
		cache::restore();
	} catch (Exception $e) {
		
	}
	echo "OK\n";
	
	foreach (plugin::listPlugin(true) as $plugin) {
		try {
			$plugin_id = $plugin->getId();
			$dependancy_info = $plugin->dependancy_info(true);
			if (method_exists($plugin_id, 'restore')) {
				echo 'Restoring Plugin : ' . $plugin_id . '...';
				$plugin_id::restore();
				echo "OK\n";
			}
		} catch (\Exception $e) {
			echo '[error] on plugin : '.$plugin_id. ' => '.$e->getMessage();
		}
	}
	config::save('hardware_name', '');
	$cache = cache::byKey('jeedom::isCapable::sudo');
	$cache->remove();
	
	try {
		echo "Check jeedom consistency...";
		require_once __DIR__ . '/consistency.php';
		echo "OK\n";
	} catch (Exception $ex) {
		echo "***ERROR*** " . $ex->getMessage() . "\n";
	}
	
	try {
		jeedom::start();
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
	try {
		echo "Sending end restore event...";
		jeedom::event('end_restore');
		echo "OK\n";
	} catch (Exception $e) {
		echo '***ERROR*** ' . $e->getMessage();
	}
	echo "Restore duration : " . (strtotime('now') - $starttime) . "s\n";
	echo "***************Jeedom Restore End***************\n";
	echo "[END RESTORE SUCCESS]\n";
} catch (Exception $e) {
	echo 'Error during restore : ' . $e->getMessage();
	echo 'Details : ' . print_r($e->getTrace(), true);
	echo "[END RESTORE ERROR]\n";
	jeedom::start();
	throw $e;
}
