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
	echo "***************Lancement de la restauration de Jeedom***************\n";
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
			if ($file != "." && $file != "..") {
				$s = stat($backup_dir . '/' . $file);
				if ($backup == null || $mtime == null) {
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
		throw new Exception('Sauvegarde non trouvée.' . $backup);
	}

	try {
		echo __("Mise à plat des droits...", __FILE__);
		jeedom::cleanFileSytemRight();
		echo __("OK\n", __FILE__);
	} catch (Exception $e) {
		echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
	}

	$jeedom_dir = realpath(dirname(__FILE__) . '/../');

	echo "Restauration de Jeedom avec le fichier : " . $backup . "\n";

	echo "Sauvegarde du fichier de connexion à la base...";
	@copy(dirname(__FILE__) . '/../core/config/common.config.php', '/tmp/common.config.php');
	echo "OK\n";

	echo "Nettoyage des anciens fichiers...";
	$tmp = dirname(__FILE__) . '/../tmp/backup';
	rrmdir($tmp);
	echo "OK\n";
	if (!file_exists($tmp)) {
		mkdir($tmp, 0770, true);
	}
	echo "Décompression de la sauvegarde...";
	$return_var = 0;
	$output = array();
	exec('cd ' . $tmp . '; tar xfz ' . $backup . ' ', $output, $return_var);
	if ($return_var != 0) {
		throw new Exception('Impossible de décompresser l\'archive');
	}
	@unlink($tmp . '/core/config/apache_jeedom_dynamic_rules');
	echo "OK\n";
	if (!file_exists($tmp . "/DB_backup.sql")) {
		throw new Exception('Impossible de trouver le fichier de sauvegarde de la base de données dans l\'archive : DB_backup.sql');
	}
	jeedom::stop();
	echo "Suppression de toutes les tables";
	$tables = DB::Prepare("SHOW TABLES", array(), DB::FETCH_TYPE_ALL);
	echo "Désactivation des contraintes...";
	DB::Prepare("SET foreign_key_checks = 0", array(), DB::FETCH_TYPE_ROW);
	echo "OK\n";
	foreach ($tables as $table) {
		$table = array_values($table);
		$table = $table[0];
		echo "Suppression de la table : " . $table . ' ...';
		DB::Prepare('DROP TABLE IF EXISTS `' . $table . '`', array(), DB::FETCH_TYPE_ROW);
		echo "OK\n";
	}

	echo "Restauration de la base de donnees...";
	system("mysql --host=" . $CONFIG['db']['host'] . " --user=" . $CONFIG['db']['username'] . " --password=" . $CONFIG['db']['password'] . " " . $CONFIG['db']['dbname'] . "  < " . $tmp . "/DB_backup.sql");
	echo "OK\n";

	echo "Réactivation des contraintes...";
	try {
		DB::Prepare("SET foreign_key_checks = 1", array(), DB::FETCH_TYPE_ROW);
	} catch (Exception $e) {

	}
	echo "OK\n";

	echo "Restauration des fichiers...";
	if (!rcopy($tmp, dirname(__FILE__) . '/..', false, array('common.config.php'), true)) {
		echo "NOK\n";
	} else {
		echo "OK\n";
	}

	if (!file_exists(dirname(__FILE__) . '/../core/config/common.config.php')) {
		echo "Fichier de connexion a la base absent, restauration...";
		copy('/tmp/common.config.php', dirname(__FILE__) . '/../core/config/common.config.php');
		echo "OK\n";
	}

	if (!file_exists($jeedom_dir . '/install')) {
		mkdir($jeedom_dir . '/install');
		exec('cd ' . $jeedom_dir . '/install;wget https://raw.githubusercontent.com/jeedom/core/master/install/backup.php;wget https://raw.githubusercontent.com/jeedom/core/master/install/install.php;wget https://raw.githubusercontent.com/jeedom/core/master/install/restore.php');
	}

	foreach (plugin::listPlugin(true) as $plugin) {
		$plugin_id = $plugin->getId();
		if (method_exists($plugin_id, 'restore')) {
			echo 'Restauration specifique du plugin ' . $plugin_id . '...';
			if (file_exists($tmp . '/plugin_backup/' . $plugin_id)) {
				$plugin_id::restore($tmp . '/plugin_backup/' . $plugin_id);
			}
			echo "OK\n";
		}
	}

	jeedom::start();
	echo "***************Fin de la restauration de Jeedom***************\n";
	echo "[END RESTORE SUCCESS]\n";
} catch (Exception $e) {
	echo 'Erreur durant la sauvegarde : ' . $e->getMessage();
	echo 'Détails : ' . print_r($e->getTrace());
	echo "[END RESTORE ERROR]\n";
	jeedom::start();
	throw $e;
}
?>
