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

require_once dirname(__DIR__) . '/core/php/console.php';

echo "[START BACKUP]\n";
$starttime = strtotime('now');

try {
	require_once __DIR__ . '/../core/php/core.inc.php';
	echo "***************Start of Jeedom backup at " . date('Y-m-d H:i:s') . "***************\n";

	try {
		echo "Send begin backup event...";
		jeedom::event('begin_backup', true);
		echo "OK\n";
	} catch (Exception $e) {
		echo '***ERROR*** ' . $e->getMessage();
	}

	try {
		echo 'Checking files rights...';
		jeedom::cleanFileSystemRight();
		echo "OK\n";
	} catch (Exception $e) {
		echo "NOK\n";
	}

	global $CONFIG;
	$jeedom_dir = realpath(__DIR__ . '/..');
	$backup_dir = calculPath(config::byKey('backup::path'));
	if (!file_exists($backup_dir)) {
		mkdir($backup_dir, 0770, true);
	}
	if (!is_writable($backup_dir)) {
		throw new Exception('Can\'t access backup folder. Check rights: ' . $backup_dir);
	}
	$replace_name = array(
		'&' => '',
		' ' => '_',
		'#' => '',
		"'" => '',
		'"' => '',
		'+' => '',
		'-' => '',
	);
	$jeedom_name = str_replace(array_keys($replace_name), $replace_name, config::byKey('name', 'core', 'Jeedom'));
	$backup_name = str_replace(' ', '_', 'backup-' . $jeedom_name . '-' . jeedom::version() . '-' . date("Y-m-d-H\hi") . '.tar.gz');

	global $NO_PLUGIN_BACKUP;
	if (!isset($NO_PLUGIN_BACKUP) || $NO_PLUGIN_BACKUP === false) {
		foreach (plugin::listPlugin(true) as $plugin) {
			$plugin_id = $plugin->getId();
			if (method_exists($plugin_id, 'backup')) {
				echo 'Backing up plugin ' . $plugin_id . '...';
				$plugin_id::backup();
				echo "OK" . "\n";
			}
		}
	}

	
	if (isset($CONFIG['db']['unix_socket'])) {
		$str_db_connexion = "--socket=" . $CONFIG['db']['unix_socket'] . " --user=" . $CONFIG['db']['username'] . " --password='" . $CONFIG['db']['password'] . "' " . $CONFIG['db']['dbname'];
	} else {
		if ($CONFIG['db']['host'] == 'localhost' && $CONFIG['db']['port'] == 3306) {
			$str_db_connexion = "--user=" . $CONFIG['db']['username'] . " --password='" . $CONFIG['db']['password'] . "' " . $CONFIG['db']['dbname'];
		} else {
			$str_db_connexion = "--host=" . $CONFIG['db']['host'] . " --port=" . $CONFIG['db']['port'] . " --user=" . $CONFIG['db']['username'] . " --password='" . $CONFIG['db']['password'] . "' " . $CONFIG['db']['dbname'];
		}
	}
	$tables = DB::Prepare("SHOW TABLES", array(), DB::FETCH_TYPE_ALL);
	foreach ($tables as $table) {
		$table = array_values($table)[0];
		if($table == 'event'){
			continue;
		}
		echo "Checking  table ".$table."...";
		system("mysqlcheck " . $str_db_connexion . ' --auto-repair --silent --tables '.$table);
		echo "OK" . "\n";
	}
	
	echo 'Backing up database...';
	if (file_exists($jeedom_dir . "/DB_backup.sql")) {
		unlink($jeedom_dir . "/DB_backup.sql");
		if (file_exists($jeedom_dir . "/DB_backup.sql")) {
			system("sudo rm " . $jeedom_dir . "/DB_backup.sql");
		}
	}
	if (file_exists($jeedom_dir . "/DB_backup.sql")) {
		throw new Exception('can\'t delete database backup. Check rights');
	}
	system("mysqldump " . $str_db_connexion . "  > " . $jeedom_dir . "/DB_backup.sql", $rc);
	
	if ($rc != 0) {
		throw new Exception('Backing up database failed. Check mysqldump installation. Code: ' . $rc);
	}
	if (filemtime($jeedom_dir . "/DB_backup.sql") < (strtotime('now') - 1200)) {
		throw new Exception('Backing up database failed. Backup file too old, check rights.');
	}
	echo "OK" . "\n";

	echo "Cache persistence: \n";
	try {
		cache::persist();
		echo "OK" . "\n";
	} catch (Exception $e) {
		echo $e->getMessage();
	}

	echo "Creating archive...\n";

	$excludes = array(
		'tmp',
		'log',
		'docs',
		'doc',
		'tests',
		'support',
		'backup',
		'script/tunnel',
		'.git',
		'.gitignore',
		'node_modules',
		'.log',
		'core/config/common.config.php',
		'data/imgOs',
		'python_venv',
		'resources/venv',
		'/vendor',
		config::byKey('backup::path'),
	);

	if (config::byKey('recordDir', 'camera') != '') {
		$excludes[] = config::byKey('recordDir', 'camera');
	}

	if (!isset($NO_PLUGIN_BACKUP) || $NO_PLUGIN_BACKUP === false) {
		foreach (plugin::listPlugin(true) as $plugin) {
			$plugin_id = $plugin->getId();
			if (method_exists($plugin_id, 'backupExclude')) {
				$plugin_excludes = $plugin_id::backupExclude();
				if (isset($plugin_excludes) === true) {
					foreach ($plugin_excludes as $plugin_exclude) {
						$plugin_exclude = trim($plugin_exclude);
						if (isset($plugin_exclude) === true && $plugin_exclude !== '') {
							if (strpos($plugin_exclude, '..') === false) {
								$excludes[] = "plugins/" . $plugin_id . "/" . $plugin_exclude;
								echo "Plugin " . $plugin_id . " - Following subfolder will be excluded from the backup: " . $plugin_exclude . "\n";
							}
						}
					}
				}
			}
		}
	}


	$exclude = '';
	foreach ($excludes as $folder) {
		$exclude .= ' --exclude="' . $folder . '"';
	}
	system('cd ' . $jeedom_dir . ';tar cfz "' . $backup_dir . '/' . $backup_name . '" ' . $exclude . ' . > /dev/null');
	echo "OK" . "\n";

	if (!file_exists($backup_dir . '/' . $backup_name)) {
		throw new Exception('Backup failed. Can\'t find: ' . $backup_dir . '/' . $backup_name);
	}

	echo 'Cleaning old backup...';
	shell_exec('find "' . $backup_dir . '" -name "*.gz" -mtime +' . config::byKey('backup::keepDays') . ' -delete');
	echo "OK" . "\n";

	global $NO_CLOUD_BACKUP;
	if ((!isset($NO_CLOUD_BACKUP) || $NO_CLOUD_BACKUP === false)) {
		foreach (update::listRepo() as $key => $value) {
			if ($value['scope']['backup'] === false) {
				continue;
			}
			if (config::byKey($key . '::enable','core',0) == 0) {
				continue;
			}
			if (config::byKey($key . '::cloudUpload','core',0) == 0) {
				continue;
			}
			$class = 'repo_' . $key;
			echo 'Send backup ' . $value['name'] . '...';
			try {
				$class::backup_send($backup_dir . '/' . $backup_name);
			} catch (Exception $e) {
				log::add('backup', 'error', $e->getMessage());
				echo '/!\ ' . br2nl($e->getMessage()) . ' /!\\';
			}
			echo "OK" . "\n";
		}
	}

	echo 'Limiting backup size to ' . config::byKey('backup::maxSize') . " Mb...\n";
	$max_size = config::byKey('backup::maxSize') * 1024 * 1024;
	$i = 0;
	while (getDirectorySize($backup_dir) > $max_size) {
		$older = array('file' => null, 'datetime' => null);
		foreach (ls($backup_dir, '*') as $file) {
			if (count(ls($backup_dir, '*')) < 2) {
				break (2);
			}
			if (is_dir($backup_dir . '/' . $file)) {
				foreach (ls($backup_dir . '/' . $file, '*') as $file2) {
					if ($older['datetime'] === null) {
						$older['file'] = $backup_dir . '/' . $file . '/' . $file2;
						$older['datetime'] = filemtime($backup_dir . '/' . $file . '/' . $file2);
					}
					if ($older['datetime'] > filemtime($backup_dir . '/' . $file . '/' . $file2)) {
						$older['file'] = $backup_dir . '/' . $file . '/' . $file2;
						$older['datetime'] = filemtime($backup_dir . '/' . $file . '/' . $file2);
					}
				}
			}
			if (!is_file($backup_dir . '/' . $file)) {
				continue;
			}
			if ($older['datetime'] === null) {
				$older['file'] = $backup_dir . '/' . $file;
				$older['datetime'] = filemtime($backup_dir . '/' . $file);
			}
			if ($older['datetime'] > filemtime($backup_dir . '/' . $file)) {
				$older['file'] = $backup_dir . '/' . $file;
				$older['datetime'] = filemtime($backup_dir . '/' . $file);
			}
		}
		if ($older['file'] === null) {
			echo 'Error, no file to delete while folder size is: ' . getDirectorySize($backup_dir) . "\n";
		}
		echo "Delete: " . $older['file'] . "\n";
		if (!unlink($older['file'])) {
			$i = 50;
		}
		$i++;
		if ($i > 50) {
			echo "More than 50 backups deleted, stopping.\n";
			break;
		}
	}
	echo "OK" . "\n";

	echo "Backup name: " . $backup_dir . '/' . $backup_name . "\n";

	try {
		echo 'Checking files rights...';
		jeedom::cleanFileSystemRight();
		echo "OK\n";
	} catch (Exception $e) {
		echo "NOK\n";
	}

	try {
		echo 'Send end backup event...';
		jeedom::event('end_backup');
		echo "OK\n";
	} catch (Exception $e) {
		echo '***ERREUR*** ' . $e->getMessage();
	}
	echo "Backup operation duration: " . (strtotime('now') - $starttime) . "s\n";
	echo "***************Jeedom backup end***************\n";
	echo "[END BACKUP SUCCESS]\n";
} catch (Exception $e) {
	echo 'Error during backup: ' . br2nl($e->getMessage());
	echo 'Details : ' . print_r($e->getTrace(), true);
	echo "[END BACKUP ERROR]\n";
	throw $e;
}
