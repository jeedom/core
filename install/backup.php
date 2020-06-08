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

echo "[START BACKUP]\n";
$starttime = strtotime('now');

try {
	require_once __DIR__ . '/../core/php/core.inc.php';
	echo "***************Start of Jeedom backup at " . date('Y-m-d H:i:s') . "***************\n";
	
	try {
		echo "Envoi l'évènement de début de sauvegarde...";
		jeedom::event('begin_backup', true);
		echo "OK\n";
	} catch (Exception $e) {
		echo '***ERREUR*** ' . $e->getMessage();
	}
	
	try {
		echo 'Vérification des droits sur les fichiers...';
		jeedom::cleanFileSytemRight();
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
		throw new Exception('Impossible d\'accéder au dossier de sauvegarde. Veuillez vérifier les droits : ' . $backup_dir);
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
				echo 'Backup plugin ' . $plugin_id . '...';
				$plugin_id::backup();
				echo "OK" . "\n";
			}
		}
	}
	
	echo "Vérification de la base de données...";
	if(isset($CONFIG['db']['unix_socket'])) {
		system("mysqlcheck --socket=" . $CONFIG['db']['unix_socket'] . " --user=" . $CONFIG['db']['username'] . " --password='" . $CONFIG['db']['password'] . "' " . $CONFIG['db']['dbname'] . ' --auto-repair --silent');
	} else {
		system("mysqlcheck --host=" . $CONFIG['db']['host'] . " --port=" . $CONFIG['db']['port'] . " --user=" . $CONFIG['db']['username'] . " --password='" . $CONFIG['db']['password'] . "' " . $CONFIG['db']['dbname'] . ' --auto-repair --silent');
	}
	echo "OK" . "\n";
	
	echo 'Sauvegarde la base de données...';
	if (file_exists($jeedom_dir . "/DB_backup.sql")) {
		unlink($jeedom_dir . "/DB_backup.sql");
		if (file_exists($jeedom_dir . "/DB_backup.sql")) {
			system("sudo rm " . $jeedom_dir . "/DB_backup.sql");
		}
	}
	if (file_exists($jeedom_dir . "/DB_backup.sql")) {
		throw new Exception('Impossible de supprimer la sauvegarde de la base de données. Vérifiez les droits');
	}
	if(isset($CONFIG['db']['unix_socket'])) {
		system("mysqldump --socket=" . $CONFIG['db']['unix_socket'] . " --user=" . $CONFIG['db']['username'] . " --password='" . $CONFIG['db']['password'] . "' " . $CONFIG['db']['dbname'] . "  > " . $jeedom_dir . "/DB_backup.sql", $rc);
	} else {
		system("mysqldump --host=" . $CONFIG['db']['host'] . " --port=" . $CONFIG['db']['port'] . " --user=" . $CONFIG['db']['username'] . " --password='" . $CONFIG['db']['password'] . "' " . $CONFIG['db']['dbname'] . "  > " . $jeedom_dir . "/DB_backup.sql", $rc);
	}
	if ($rc != 0) {
		throw new Exception('Echec durant la sauvegarde de la base de données. Vérifiez que mysqldump est présent. Code retourné : ' . $rc);
	}
	if (filemtime($jeedom_dir . "/DB_backup.sql") < (strtotime('now') - 1200)) {
		throw new Exception('Echec durant la sauvegarde de la base de données. Date du fichier de sauvegarde de la base trop vieux. Vérifiez les droits');
	}
	echo "OK" . "\n";
	
	echo "Persistance du cache : \n";
	try {
		cache::persist();
		echo "OK" . "\n";
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
	echo 'Création de  l\'archive...';
	
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
		'.log',
		'core/config/common.config.php',
		config::byKey('backup::path'),
	);
	
	if (config::byKey('recordDir', 'camera') != '') {
		$excludes[] = config::byKey('recordDir', 'camera');
	}
	
	$exclude = '';
	foreach ($excludes as $folder) {
		$exclude .= ' --exclude="' . $folder . '"';
	}
	system('cd ' . $jeedom_dir . ';tar cfz "' . $backup_dir . '/' . $backup_name . '" ' . $exclude . ' . > /dev/null');
	echo "OK" . "\n";
	
	if (!file_exists($backup_dir . '/' . $backup_name)) {
		throw new Exception('Echec du backup. Impossible de trouver : ' . $backup_dir . '/' . $backup_name);
	}
	
	echo 'Nettoyage de l\'ancienne sauvegarde...';
	shell_exec('find "' . $backup_dir . '" -name "*.gz" -mtime +' . config::byKey('backup::keepDays') . ' -delete');
	echo "OK" . "\n";
	
	global $NO_CLOUD_BACKUP;
	if ((!isset($NO_CLOUD_BACKUP) || $NO_CLOUD_BACKUP === false)) {
		foreach (update::listRepo() as $key => $value) {
			if ($value['scope']['backup'] === false) {
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
				$class::backup_send($backup_dir . '/' . $backup_name);
			} catch (Exception $e) {
				log::add('backup', 'error', $e->getMessage());
				echo '/!\ ' . br2nl($e->getMessage()) . ' /!\\';
			}
			echo "OK" . "\n";
		}
	}
	
	echo 'Limitation de la taille des sauvegardes à ' . config::byKey('backup::maxSize') . " Mo...\n";
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
			echo 'Erreur : aucun fichier à supprimer quand le dossier fait : ' . getDirectorySize($backup_dir) . "\n";
		}
		echo "Supprime : " . $older['file'] . "\n";
		if (!unlink($older['file'])) {
			$i = 50;
		}
		$i++;
		if ($i > 50) {
			echo "Plus de 50 sauvegardes supprimées. J'arrête.\n";
			break;
		}
	}
	echo "OK" . "\n";
	
	echo "Nom de la sauvegarde : " . $backup_dir . '/' . $backup_name . "\n";
	
	try {
		echo 'Vérification des droits sur les fichiers...';
		jeedom::cleanFileSytemRight();
		echo "OK\n";
	} catch (Exception $e) {
		echo "NOK\n";
	}
	
	try {
		echo 'Envoi l\'évènement de fin de sauvegarde...';
		jeedom::event('end_backup');
		echo "OK\n";
	} catch (Exception $e) {
		echo '***ERREUR*** ' . $e->getMessage();
	}
	echo "Durée de la sauvegarde : " . (strtotime('now') - $starttime) . "s\n";
	echo "***************Fin de la sauvegarde de Jeedom***************\n";
	echo "[END BACKUP SUCCESS]\n";
} catch (Exception $e) {
	echo 'Erreur durant la sauvegarde : ' . br2nl($e->getMessage());
	echo 'Détails : ' . print_r($e->getTrace(), true);
	echo "[END BACKUP ERROR]\n";
	throw $e;
}
