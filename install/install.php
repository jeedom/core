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
set_time_limit(1800);
echo "[START UPDATE]\n";

if (isset($argv)) {
	foreach ($argv as $arg) {
		$argList = explode('=', $arg);
		if (isset($argList[0]) && isset($argList[1])) {
			$_GET[$argList[0]] = $argList[1];
		}
	}
}

$update = false;
$backup_ok = false;
$update_begin = false;
try {
	require_once dirname(__FILE__) . '/../core/php/core.inc.php';
	if (count(system::ps('install/install.php', 'sudo')) > 1) {
		echo "Une mise a jour/installation est deja en cours. Vous devez attendre qu'elle soit finie avant d'en relancer une\n";
		print_r(system::ps('install/install.php', 'sudo'));
		echo "[END UPDATE]\n";
		die();
	}
	echo "****Installation/Mise à jour de Jeedom " . jeedom::version() . " (" . date('Y-m-d H:i:s') . ")****\n";
	echo "Paramètres de la mise à jour : level : " . init('level', -1) . ", mode : " . init('mode') . ", version : " . init('version', 'no') . ", onlyThisVersion : " . init('onlyThisVersion', 'no') . " \n";

	try {
		$curentVersion = config::byKey('version');
		if ($curentVersion != '') {
			$update = true;
		}
	} catch (Exception $e) {

	}
	if (init('version') != '') {
		$update = true;
	}

	if ($update) {

		/*         * ************************MISE A JOUR********************************** */
		try {
			if (init('level', -1) > -1 && init('mode') != 'force') {
				echo __("Vérification des mises à jour...", __FILE__);
				update::checkAllUpdate('', false);
				echo __("OK\n", __FILE__);
			}
		} catch (Exception $e) {
			if (init('mode') != 'force') {
				throw $e;
			} else {
				echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
			}
		}

		try {
			echo __("Mise à plat des droits...", __FILE__);
			jeedom::cleanFileSytemRight();
			echo __("OK\n", __FILE__);
		} catch (Exception $e) {
			echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
		}

		if (init('level', -1) < 1) {
			if (config::byKey('update::backupBefore') == 1 && init('mode') != 'force') {
				try {
					global $NO_PLUGIN_BAKCUP;
					$NO_PLUGIN_BAKCUP = true;
					global $NO_CLOUD_BAKCUP;
					$NO_CLOUD_BAKCUP = true;
					jeedom::backup();
				} catch (Exception $e) {
					if (init('mode') != 'force') {
						throw $e;
					} else {
						echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
					}
				}
				$backup_ok = true;
			}
			if (init('mode') == 'force') {
				echo __("/!\ Mise à jour en mode forcé /!\ \n", __FILE__);
			}
			jeedom::stop();
			if (init('version') == '') {
				try {
					echo __('Nettoyage du dossier temporaire (tmp)...', __FILE__);
					exec('rm -rf /tmp/backup');
					exec('rm -rf ' . dirname(__FILE__) . '/../install/update/*');
					echo __("OK\n", __FILE__);
				} catch (Exception $e) {
					echo __('***ERREUR*** ', __FILE__) . $e->getMessage() . "\n";
				}
				$tmp_dir = '/tmp';
				$tmp = $tmp_dir . '/jeedom_update.zip';
				try {
					if (config::byKey('core::repo::provider') == 'default') {
						$url = 'https://github.com/jeedom/core/archive/stable.zip';
						echo __("Adresse de téléchargement : " . $url . "\n", __FILE__);
						echo __("Téléchargement en cours...", __FILE__);
						if (!is_writable($tmp_dir)) {
							throw new Exception(__('Impossible d\'écrire dans le dossier : ', __FILE__) . $tmp . __('. Exécuter la commande suivante en SSH : chmod 777 -R ', __FILE__) . $tmp_dir);
						}
						if (file_exists($tmp)) {
							unlink($tmp);
						}
						exec('wget --no-check-certificate --progress=dot --dot=mega ' . $url . ' -O ' . $tmp);
					} else {
						$class = 'repo_' . config::byKey('core::repo::provider');
						if (!class_exists($class)) {
							throw new Exception(__('Classe repo introuvable : ', __FILE__) . $class);
						}
						if (!method_exists($class, 'downloadCore')) {
							throw new Exception(__('Méthode repo introuvable : ', __FILE__) . $class . '::downloadCore');
						}
						if (config::byKey(config::byKey('core::repo::provider') . '::enable') != 1) {
							throw new Exception(__('Repo désactivé : ', __FILE__) . $class);
						}
						$class::downloadCore($tmp);
					}
					if (filesize($tmp) < 100) {
						throw new Exception(__('Echec lors du téléchargement du fichier. Veuillez réessayer plus tard (taille inférieure à 100 octets)', __FILE__));
					}
					echo __("OK\n", __FILE__);
					echo __("Nettoyage des dossiers en cours...", __FILE__);
					$cibDir = '/tmp/jeedom_update';
					if (file_exists($cibDir)) {
						rrmdir($cibDir);
					}
					echo __("OK\n", __FILE__);
					echo __("Nettoyage adminer en cours...", __FILE__);
					foreach (ls(dirname(__FILE__) . '/../', 'sqlbuddy*') as $file) {
						@rrmdir(dirname(__FILE__) . '/../' . $file);
					}
					foreach (ls(dirname(__FILE__) . '/../', 'adminer*') as $file) {
						@rrmdir(dirname(__FILE__) . '/../' . $file);
					}
					echo __("OK\n", __FILE__);
					echo __("Nettoyage sysinfo en cours...", __FILE__);
					foreach (ls(dirname(__FILE__) . '/../', 'sysinfo*') as $file) {
						@rrmdir(dirname(__FILE__) . '/../' . $file);
					}
					echo __("OK\n", __FILE__);
					echo __("Création des dossiers temporaire...", __FILE__);
					if (!file_exists($cibDir) && !mkdir($cibDir, 0775, true)) {
						throw new Exception(__('Impossible de créer le dossier  : ' . $cibDir . '. Problème de droits ?', __FILE__));
					}
					echo __("OK\n", __FILE__);
					echo __("Décompression en cours...", __FILE__);
					$zip = new ZipArchive;
					if ($zip->open($tmp) === TRUE) {
						if (!$zip->extractTo($cibDir)) {
							throw new Exception(__('Impossible d\'installer la mise à jour. Les fichiers n\'ont pas pu être décompressés', __FILE__));
						}
						$zip->close();
					} else {
						throw new Exception(__('Impossible de décompresser l\'archive zip : ', __FILE__) . $tmp);
					}
					echo __("OK\n", __FILE__);
					echo __("Copie des fichiers en cours...", __FILE__);
					$update_begin = true;
					if (!file_exists($cibDir . '/core')) {
						$files = ls($cibDir, '*');
						if (count($files) == 1 && file_exists($cibDir . '/' . $files[0] . 'core')) {
							$cibDir = $cibDir . '/' . $files[0];
						}
					}
					rcopy($cibDir . '/', dirname(__FILE__) . '/../', false, array(), true);
					echo __("OK\n", __FILE__);
					echo __("Suppression des fichiers temporaire...", __FILE__);
					rrmdir($cibDir);
					unlink($tmp);
					echo __("OK\n", __FILE__);
					config::save('update::lastDateCore', date('Y-m-d H:i:s'));
				} catch (Exception $e) {
					if (init('mode') != 'force') {
						throw $e;
					} else {
						echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
					}
				}
			}

			if (init('version') != '') {
				$updateSql = dirname(__FILE__) . '/update/' . init('version') . '.sql';
				if (file_exists($updateSql)) {
					try {
						echo __("Désactivation des contraintes...", __FILE__);
						$sql = "SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
                                SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
                                SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';";
						DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
						echo __("OK\n", __FILE__);
					} catch (Exception $e) {
						if (init('mode') != 'force') {
							throw $e;
						} else {
							echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
						}
					}
					try {
						echo __("Mise à jour de la base de données en version : ", __FILE__) . init('version') . "\n";
						$sql = file_get_contents($updateSql);
						DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
						echo "OK\n";
					} catch (Exception $e) {
						if (init('mode') != 'force') {
							throw $e;
						} else {
							echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
						}
					}
					try {
						echo __("Réactivation des contraintes...", __FILE__);
						$sql = "SET SQL_MODE=@OLD_SQL_MODE;
                                SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
                                SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;";
						DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
						echo __("OK\n", __FILE__);
					} catch (Exception $e) {
						if (init('mode') != 'force') {
							throw $e;
						} else {
							echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
						}
					}
				}
				$updateScript = dirname(__FILE__) . '/update/' . init('version') . '.php';
				if (file_exists($updateScript)) {
					try {
						echo __("Mise à jour du système en version : ", __FILE__) . init('version') . "\n";
						require_once $updateScript;
						echo __("OK\n", __FILE__);
					} catch (Exception $e) {
						if (init('mode') != 'force') {
							throw $e;
						} else {
							echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
						}
					}
				}
				$curentVersion = init('version');
			}

			if (init('version') == '' || init('onlyThisVersion', 'no') == 'no') {
				while (version_compare(jeedom::version(), $curentVersion, '>')) {
					$nextVersion = incrementVersion($curentVersion);
					$updateSql = dirname(__FILE__) . '/update/' . $nextVersion . '.sql';
					if (file_exists($updateSql)) {
						try {
							echo __("Désactivation des contraintes...", __FILE__);
							$sql = "SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
                                    SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
                                    SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';";
							DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
							echo __("OK\n", __FILE__);
						} catch (Exception $e) {
							if (init('mode') != 'force') {
								throw $e;
							} else {
								echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
							}
						}
						try {
							echo __("Mise à jour de la base de données en version : ", __FILE__) . $nextVersion . "...";
							$sql = file_get_contents($updateSql);
							DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
							echo "OK\n";
						} catch (Exception $e) {
							if (init('mode') != 'force') {
								throw $e;
							} else {
								echo '***ERREUR*** ' . $e->getMessage();
							}
						}
						try {
							echo __("Réactivation des contraintes...", __FILE__);
							$sql = "SET SQL_MODE=@OLD_SQL_MODE;
                                    SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
                                    SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;";
							DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
							echo __("OK\n", __FILE__);
						} catch (Exception $e) {
							if (init('mode') != 'force') {
								throw $e;
							} else {
								echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
							}
						}
					}
					$updateScript = dirname(__FILE__) . '/update/' . $nextVersion . '.php';
					if (file_exists($updateScript)) {
						try {
							echo __("Mise à jour du système en version : ", __FILE__) . $nextVersion . "...";
							require_once $updateScript;
							echo __("OK\n", __FILE__);
						} catch (Exception $e) {
							if (init('mode') != 'force') {
								throw $e;
							} else {
								echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
							}
						}
					}
					$curentVersion = $nextVersion;
					config::save('version', $curentVersion);
				}
			}
			try {
				echo __("Vérification de la consistence de Jeedom...", __FILE__);
				require_once dirname(__FILE__) . '/consistency.php';
				echo __("OK\n", __FILE__);
			} catch (Exception $ex) {
				echo __("***ERREUR*** ", __FILE__) . $ex->getMessage() . "\n";
			}
			try {
				echo __("Vérification de la mise à jour...", __FILE__);
				update::checkAllUpdate('core', false);
				config::save('version', jeedom::version());
				echo __("OK\n", __FILE__);
			} catch (Exception $ex) {
				echo __("***ERREUR*** ", __FILE__) . $ex->getMessage() . "\n";
			}
			echo __("***************Jeedom est à jour en version ", __FILE__) . jeedom::version() . "***************\n";
		}
		if (init('level', -1) > -1) {
			echo __("***************Mise à jour des plugins***************\n", __FILE__);
			update::updateAll();
			echo __("***************Mise à jour des plugins réussie***************\n", __FILE__);
		}
		try {
			message::removeAll('update', 'newUpdate');
			echo __("Vérification des mises à jour\n", __FILE__);
			update::checkAllUpdate();
			echo __("OK\n", __FILE__);
		} catch (Exception $ex) {
			echo __("***ERREUR*** ", __FILE__) . $ex->getMessage() . "\n";
		}
		try {
			jeedom::start();
		} catch (Exception $ex) {
			echo __("***ERREUR*** ", __FILE__) . $ex->getMessage() . "\n";
		}
	} else {

		/*         * ***************************INSTALLATION************************** */
		if (version_compare(PHP_VERSION, '5.6.0', '<')) {
			throw new Exception('Jeedom need php 5.6 or upper (current : ' . PHP_VERSION . ')');
		}
		if (init('mode') != 'force') {
			echo "Jeedom va être installé. Voulez-vous continuer ? [o/N] ";
			if (trim(fgets(STDIN)) !== 'o') {
				echo "L'installation de Jeedom est annulée\n";
				echo "[END UPDATE SUCCESS]\n";
				exit(0);
			}
		}
		echo "\nInstallation de Jeedom " . jeedom::version() . "\n";
		$sql = file_get_contents(dirname(__FILE__) . '/install.sql');
		echo "Installation de la base de données...";
		DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		echo "OK\n";
		echo "Post installe...\n";
		config::save('api', config::genKey());
		require_once dirname(__FILE__) . '/consistency.php';
		echo "Ajout de l'utilisateur (admin,admin)\n";
		$user = new user();
		$user->setLogin('admin');
		$user->setPassword(sha1('admin'));
		$user->setRights('admin', 1);
		$user->save();
		config::save('cronSleepTime', 60);
		config::save('log::level', 400);
		echo "OK\n";
	}

	config::save('version', jeedom::version());
} catch (Exception $e) {
	if ($update) {
		if ($backup_ok && $update_begin) {
			jeedom::restore();
		}
		jeedom::start();
	}
	echo 'Error during install : ' . $e->getMessage();
	echo 'Details : ' . print_r($e->getTrace(), true);
	echo "[END UPDATE ERROR]\n";
	throw $e;
}
echo "[END UPDATE SUCCESS]\n";

function incrementVersion($_version) {
	$version = explode('.', $_version);
	if ($version[2] < 500) {
		$version[2]++;
	} else {
		if ($version[1] < 500) {
			$version[1]++;
			$version[2] = 0;
		} else {
			$version[0]++;
			$version[1] = 0;
			$version[2] = 0;
		}
	}
	return $version[0] . '.' . $version[1] . '.' . $version[2];
}
