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
echo "[START BACKUP]\n";
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
	echo "***************Start of Jeeodm backup at " . date('Y-m-d H:i:s') . "***************\n";

	try {
		echo "Send begin backup event...";
		jeedom::event('begin_backup', true);
		echo "OK\n";
	} catch (Exception $e) {
		echo '***ERREUR*** ' . $e->getMessage();
	}

	global $CONFIG;
	$jeedom_dir = realpath(dirname(__FILE__) . '/..');
	$backup_dir = calculPath(config::byKey('backup::path'));
	if (!file_exists($backup_dir)) {
		mkdir($backup_dir, 0770, true);
	}
	if (!is_writable($backup_dir)) {
		throw new Exception('Can not acces backup folder, please check right : ' . $backup_dir);
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
	$bakcup_name = str_replace(' ', '_', 'backup-' . $jeedom_name . '-' . jeedom::version() . '-' . date("Y-m-d-H\hi") . '.zip');

	global $NO_PLUGIN_BAKCUP;
	if (!isset($NO_PLUGIN_BAKCUP) || $NO_PLUGIN_BAKCUP == false) {
		foreach (plugin::listPlugin(true) as $plugin) {
			$plugin_id = $plugin->getId();
			if (method_exists($plugin_id, 'backup')) {
				echo 'Backup plugin ' . $plugin_id . '...';
				$plugin_id::backup();
				echo "OK" . "\n";
			}
		}
	}

	echo "Check database...";
	system("mysqlcheck --host=" . $CONFIG['db']['host'] . " --port=" . $CONFIG['db']['port'] . " --user=" . $CONFIG['db']['username'] . " --password='" . $CONFIG['db']['password'] . "' " . $CONFIG['db']['dbname'] . ' --auto-repair --silent');
	echo "OK" . "\n";

	echo 'Backup database...';
	$rc = system("mysqldump --host=" . $CONFIG['db']['host'] . " --port=" . $CONFIG['db']['port'] . " --user=" . $CONFIG['db']['username'] . " --password='" . $CONFIG['db']['password'] . "' " . $CONFIG['db']['dbname'] . "  > " . $jeedom_dir . "/DB_backup.sql");
	if ($rc != 0) {
		throw new Exception('Failed to save the BDD, verify that mysqldump is present. Return Code : ' . $rc);
	}
	echo "OK" . "\n";

	echo "Persist cache : \n";
	try {
		cache::persist();
		echo "OK" . "\n";
	} catch (Exception $e) {
		echo $e->getMessage();
	}

	echo 'Create archive...';

	$excludes = array(
		'tmp',
		'backup',
		'.git',
		'.log',
		'core/config/common.config.php',
		config::byKey('backup::path'),
	);
	create_zip($jeedom_dir, $backup_dir . '/' . $bakcup_name, $excludes);
	echo "OK" . "\n";
	if (!file_exists($backup_dir . '/' . $bakcup_name)) {
		throw new Exception('Backup failed.Can not find : ' . $backup_dir . '/' . $bakcup_name);
	}

	echo 'Clean old backup...';
	shell_exec('find "' . $backup_dir . '" -mtime +' . config::byKey('backup::keepDays') . ' -delete');
	echo "OK" . "\n";

	echo 'Limit the total size of backups to ' . config::byKey('backup::maxSize') . " Mo...\n";
	$max_size = config::byKey('backup::maxSize') * 1024 * 1024;
	$i = 0;
	while (getDirectorySize($backup_dir) > $max_size) {
		$older = array('file' => null, 'datetime' => null);
		foreach (ls($backup_dir, '*') as $file) {
			if (is_dir($backup_dir . '/' . $file)) {
				foreach (ls($backup_dir . '/' . $file, '*') as $file2) {
					if ($older['datetime'] == null) {
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
			if ($older['datetime'] == null) {
				$older['file'] = $backup_dir . '/' . $file;
				$older['datetime'] = filemtime($backup_dir . '/' . $file);
			}
			if ($older['datetime'] > filemtime($backup_dir . '/' . $file)) {
				$older['file'] = $backup_dir . '/' . $file;
				$older['datetime'] = filemtime($backup_dir . '/' . $file);
			}
		}
		if ($older['file'] == null) {
			echo 'Error no files to delete when the folder does : ' . getDirectorySize($backup_dir) . "\n";
		}
		echo "Remove : " . $older['file'] . "\n";
		if (!unlink($older['file'])) {
			$i = 50;
		}
		$i++;
		if ($i > 50) {
			echo "More than 50 backups deleted. I stop.\n";
			break;
		}
	}
	echo "OK" . "\n";
	global $NO_CLOUD_BAKCUP;
	if ((!isset($NO_CLOUD_BAKCUP) || $NO_CLOUD_BAKCUP == false)) {
		foreach (update::listRepo() as $key => $value) {
			if ($value['scope']['backup'] == false) {
				continue;
			}
			if (config::byKey($key . '::enable') == 0) {
				continue;
			}
			if (config::byKey($key . '::cloudUpload') == 0) {
				continue;
			}
			$class = 'repo_' . $key;
			echo 'Send backup ' . $value['name'] . '...';
			try {
				if ($class == 'repo_market') {
					repo_market::sendBackupCloud($backup_dir . '/' . $bakcup_name);
				} else {
					$class::sendBackup($backup_dir . '/' . $bakcup_name);
				}
			} catch (Exception $e) {
				log::add('backup', 'error', $e->getMessage());
				echo '/!\ ' . br2nl($e->getMessage()) . ' /!\\';
			}
			echo "OK" . "\n";
		}
	}
	echo "Name of backup : " . $backup_dir . '/' . $bakcup_name . "\n";

	try {
		echo 'Send end backup event...';
		jeedom::event('end_backup');
		echo "OK\n";
	} catch (Exception $e) {
		echo '***ERREUR*** ' . $e->getMessage();
	}
	echo "Backup duration : " . (strtotime('now') - $starttime) . "s\n";
	echo "***************Fin de la sauvegarde de Jeedom***************\n";
	echo "[END BACKUP SUCCESS]\n";
} catch (Exception $e) {
	echo 'Error during backup : ' . br2nl($e->getMessage());
	echo 'Details : ' . print_r($e->getTrace(), true);
	echo "[END BACKUP ERROR]\n";
	throw $e;
}
?>
