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
	echo __("***************Lancement de la sauvegarde de Jeedom le ", __FILE__) . date('Y-m-d H:i:s') . "***************\n";

	try {
		echo "Envoi de l'événement de début de backup...";
		jeedom::event('begin_backup', true);
		echo "OK\n";
	} catch (Exception $e) {
		echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
	}

	global $CONFIG;
	$jeedom_dir = realpath(dirname(__FILE__) . '/..');
	$backup_dir = calculPath(config::byKey('backup::path'));
	if (!file_exists($backup_dir)) {
		mkdir($backup_dir, 0770, true);
	}
	if (!is_writable($backup_dir)) {
		throw new Exception(__('Le dossier des sauvegardes n\'est pas accessible en écriture. Vérifiez les droits : ', __FILE__) . $backup_dir);
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
	$bakcup_name = str_replace(' ', '_', 'backup-' . $jeedom_name . '-' . jeedom::version() . '-' . date("Y-m-d-H\hi") . '.tar.gz');

	global $NO_PLUGIN_BAKCUP;
	if (!isset($NO_PLUGIN_BAKCUP) || $NO_PLUGIN_BAKCUP === false) {
		foreach (plugin::listPlugin(true) as $plugin) {
			$plugin_id = $plugin->getId();
			if (method_exists($plugin_id, 'backup')) {
				echo __('Sauvegarde spécifique pour le plugin ' . $plugin_id . '...', __FILE__);
				$plugin_id::backup();
				echo __("OK", __FILE__) . "\n";
			}
		}
	}

	echo __("Vérification de la base de données : \n", __FILE__);
	system("mysqlcheck --host=" . $CONFIG['db']['host'] . " --port=" . $CONFIG['db']['port'] . " --user=" . $CONFIG['db']['username'] . " --password='" . $CONFIG['db']['password'] . "' " . $CONFIG['db']['dbname'] . ' --auto-repair --silent');

	echo __('Sauvegarde de la base de données...', __FILE__);
	$rc = system("mysqldump --host=" . $CONFIG['db']['host'] . " --port=" . $CONFIG['db']['port'] . " --user=" . $CONFIG['db']['username'] . " --password='" . $CONFIG['db']['password'] . "' " . $CONFIG['db']['dbname'] . "  > " . $jeedom_dir . "/DB_backup.sql");
	if ($rc != 0) {
		throw new Exception('Echec lors de la sauvegarde de la BDD, verifier que mysqldump est bien présent. Code retour : ' . $rc);
	}
	echo __("OK", __FILE__) . "\n";

	echo __("Persistance du cache : \n", __FILE__);
	try {
		cache::persist();
		echo __("OK", __FILE__) . "\n";
	} catch (Exception $e) {
		echo $e->getMessage();
	}

	echo __('Création de l\'archive...', __FILE__);

	$excludes = array(
		'tmp',
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
	system('cd ' . $jeedom_dir . '; tar cfz "' . $backup_dir . '/' . $bakcup_name . '" * ' . $exclude . ' > /dev/null 2>&1');
	echo __("OK", __FILE__) . "\n";

	if (!file_exists($backup_dir . '/' . $bakcup_name)) {
		throw new Exception(__('Echec lors de la compression de la sauvegarde. Sauvegarde introuvable : ', __FILE__) . $backup_dir . '/' . $bakcup_name);
	}

	echo __('Nettoyage des anciennes sauvegardes...', __FILE__);
	shell_exec('find "' . $backup_dir . '" -mtime +' . config::byKey('backup::keepDays') . ' -delete');
	echo __("OK", __FILE__) . "\n";

	echo __('Limite de la taille totale des sauvegardes à ', __FILE__) . config::byKey('backup::maxSize') . ' Mo...';
	$max_size = config::byKey('backup::maxSize') * 1024 * 1024;
	$i = 0;
	while (getDirectorySize($backup_dir) > $max_size) {
		$older = array('file' => null, 'datetime' => null);
		foreach (ls($backup_dir, '*') as $file) {
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
			echo __('Erreur aucun fichier à supprimer alors que le dossier fait : ' . getDirectorySize($backup_dir), __FILE__);
		}
		echo __("\n - Suppression de : ", __FILE__) . $older['file'] . "\n";
		if (!unlink($older['file'])) {
			$i = 50;
		}
		$i++;
		if ($i > 50) {
			echo __("Plus de 50 sauvegardes supprimées. Je m'arrête.\n", __FILE__);
			break;
		}
	}
	echo __("OK", __FILE__) . "\n";
	global $NO_CLOUD_BAKCUP;
	if ((!isset($NO_CLOUD_BAKCUP) || $NO_CLOUD_BAKCUP === false)) {
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
			echo __('Envoi de la sauvegarde dans le cloud', __FILE__) . ' ' . $value['name'];
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
			echo __("OK", __FILE__) . "\n";
		}
	}
	echo __("Nom du backup : ", __FILE__) . $backup_dir . '/' . $bakcup_name . "\n";

	try {
		echo 'Envoi de l\'événement de fin de backup...';
		jeedom::event('end_backup');
		echo "OK\n";
	} catch (Exception $e) {
		echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
	}

	echo __("***************Fin de la sauvegarde de Jeedom***************\n", __FILE__);
	echo "[END BACKUP SUCCESS]\n";
} catch (Exception $e) {
	echo __('Erreur durant la sauvegarde : ', __FILE__) . br2nl($e->getMessage());
	echo __('Détails : ', __FILE__) . print_r($e->getTrace(), true);
	echo "[END BACKUP ERROR]\n";
	throw $e;
}

