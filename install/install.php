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
	echo "****Install/update jeedom from " . jeedom::version() . " (" . date('Y-m-d H:i:s') . ")****\n";
	echo "Configuration : level : " . init('level', -1) . ", mode : " . init('mode') . ", version : " . init('version', 'no') . ", onlyThisVersion : " . init('onlyThisVersion', 'no') . " \n";

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
			echo "Send begin of update event...";
			jeedom::event('begin_update', true);
			echo "OK\n";
		} catch (Exception $e) {
			if (init('mode') != 'force') {
				throw $e;
			} else {
				echo '***ERROR***' . $e->getMessage();
			}
		}

		try {
			if (init('level', -1) > -1 && init('mode') != 'force') {
				echo "Check update...";
				update::checkAllUpdate('', false);
				echo "OK\n";
			}
		} catch (Exception $e) {
			if (init('mode') != 'force') {
				throw $e;
			} else {
				echo '***ERROR***' . $e->getMessage();
			}
		}

		try {
			echo "Check rights...";
			jeedom::cleanFileSytemRight();
			echo "OK\n";
		} catch (Exception $e) {
			echo '***ERROR***' . $e->getMessage();
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
						echo '***ERROR***' . $e->getMessage();
					}
				}
				$backup_ok = true;
			}
			if (init('mode') == 'force') {
				echo "/!\ Force update /!\ \n";
			}
			jeedom::stop();
			if (init('version') == '') {
				try {
					echo 'Clean temporary file (tmp)...';
					exec('rm -rf ' . dirname(__FILE__) . '/../tmp/*.zip');
					exec('rm -rf ' . dirname(__FILE__) . '/../tmp/backup');
					exec('rm -rf ' . dirname(__FILE__) . '/../install/update/*');
					echo "OK\n";
				} catch (Exception $e) {
					echo '***ERROR*** ' . $e->getMessage() . "\n";
				}
				$tmp_dir = dirname(__FILE__) . '/../tmp';
				$tmp = $tmp_dir . '/jeedom_update.zip';
				try {
					if (config::byKey('core::repo::provider') == 'default') {
						$url = 'https://github.com/jeedom/core/archive/stable.zip';
						echo "Download url : " . $url . "\n";
						echo "Download in progress...";
						if (!is_writable($tmp_dir)) {
							throw new Exception('Can not write : ' . $tmp . '. Please execute : chmod 777 -R ' . $tmp_dir);
						}
						if (file_exists($tmp)) {
							unlink($tmp);
						}
						exec('wget --no-check-certificate --progress=dot --dot=mega ' . $url . ' -O ' . $tmp);
					} else {
						$class = 'repo_' . config::byKey('core::repo::provider');
						if (!class_exists($class)) {
							throw new Exception('Unable to find repo class : ' . $class);
						}
						if (!method_exists($class, 'downloadCore')) {
							throw new Exception('Unable to find method : ' . $class . '::downloadCore');
						}
						if (config::byKey(config::byKey('core::repo::provider') . '::enable') != 1) {
							throw new Exception('Repo is disable : ' . $class);
						}
						$class::downloadCore($tmp);
					}
					if (filesize($tmp) < 100) {
						throw new Exception('Download failed please retry later');
					}
					echo "OK\n";
					echo "Cleaning folder...";
					$cibDir = dirname(__FILE__) . '/../tmp/jeedom';
					if (file_exists($cibDir)) {
						rrmdir($cibDir);
					}
					echo "OK\n";
					echo "Cleaning adminer...";
					foreach (ls(dirname(__FILE__) . '/../', 'adminer*') as $file) {
						@rrmdir(dirname(__FILE__) . '/../' . $file);
					}
					echo "OK\n";
					echo "Cleaning sysinfo...";
					foreach (ls(dirname(__FILE__) . '/../', 'sysinfo*') as $file) {
						@rrmdir(dirname(__FILE__) . '/../' . $file);
					}
					echo "OK\n";
					echo "Création des dossiers temporaire...";
					if (!file_exists($cibDir) && !mkdir($cibDir, 0775, true)) {
						throw new Exception('Can not write into  : ' . $cibDir . '.');
					}
					echo "OK\n";
					echo "Unzip in progress...";
					$zip = new ZipArchive;
					if ($zip->open($tmp) === TRUE) {
						if (!$zip->extractTo($cibDir)) {
							throw new Exception('Can not unzip file');
						}
						$zip->close();
					} else {
						throw new Exception('Unable to unzip file : ' . $tmp);
					}
					echo "OK\n";
					echo "Copying file...";
					$update_begin = true;
					if (!file_exists($cibDir . '/core')) {
						$files = ls($cibDir, '*');
						if (count($files) == 1 && file_exists($cibDir . '/' . $files[0] . 'core')) {
							$cibDir = $cibDir . '/' . $files[0];
						}
					}
					rcopy($cibDir . '/', dirname(__FILE__) . '/../', false, array(), true);
					echo "OK\n";
					echo "Remove temporary file...";
					rrmdir($cibDir);
					unlink($tmp);
					echo "OK\n";
					config::save('update::lastDateCore', date('Y-m-d H:i:s'));
				} catch (Exception $e) {
					if (init('mode') != 'force') {
						throw $e;
					} else {
						echo '***ERROR***' . $e->getMessage();
					}
				}
			}

			if (init('version') != '') {
				$updateSql = dirname(__FILE__) . '/update/' . init('version') . '.sql';
				if (file_exists($updateSql)) {
					try {
						echo "Disable constraint...";
						$sql = "SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
                                SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
                                SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';";
						DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
						echo "OK\n";
					} catch (Exception $e) {
						if (init('mode') != 'force') {
							throw $e;
						} else {
							echo '***ERROR***' . $e->getMessage();
						}
					}
					try {
						echo "Update database into : " . init('version') . "\n";
						$sql = file_get_contents($updateSql);
						DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
						echo "OK\n";
					} catch (Exception $e) {
						if (init('mode') != 'force') {
							throw $e;
						} else {
							echo '***ERROR***' . $e->getMessage();
						}
					}
					try {
						echo "Enable constraint...";
						$sql = "SET SQL_MODE=@OLD_SQL_MODE;
                                SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
                                SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;";
						DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
						echo "OK\n";
					} catch (Exception $e) {
						if (init('mode') != 'force') {
							throw $e;
						} else {
							echo '***ERROR***' . $e->getMessage();
						}
					}
				}
				$updateScript = dirname(__FILE__) . '/update/' . init('version') . '.php';
				if (file_exists($updateScript)) {
					try {
						echo "Update system into : " . init('version') . "\n";
						require_once $updateScript;
						echo "OK\n";
					} catch (Exception $e) {
						if (init('mode') != 'force') {
							throw $e;
						} else {
							echo '***ERROR***' . $e->getMessage();
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
							echo "Disable constraint...";
							$sql = "SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
                                    SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
                                    SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';";
							DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
							echo "OK\n";
						} catch (Exception $e) {
							if (init('mode') != 'force') {
								throw $e;
							} else {
								echo '***ERROR***' . $e->getMessage();
							}
						}
						try {
							echo "Update database into : " . $nextVersion . "...";
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
							echo "Enable constraint...";
							$sql = "SET SQL_MODE=@OLD_SQL_MODE;
                                    SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
                                    SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;";
							DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
							echo "OK\n";
						} catch (Exception $e) {
							if (init('mode') != 'force') {
								throw $e;
							} else {
								echo '***ERROR***' . $e->getMessage();
							}
						}
					}
					$updateScript = dirname(__FILE__) . '/update/' . $nextVersion . '.php';
					if (file_exists($updateScript)) {
						try {
							echo "Update system into : " . $nextVersion . "...";
							require_once $updateScript;
							echo "OK\n";
						} catch (Exception $e) {
							if (init('mode') != 'force') {
								throw $e;
							} else {
								echo '***ERROR***' . $e->getMessage();
							}
						}
					}
					$curentVersion = $nextVersion;
					config::save('version', $curentVersion);
				}
			}
			try {
				echo "Check jeedom consistency...";
				require_once dirname(__FILE__) . '/consistency.php';
				echo "OK\n";
			} catch (Exception $ex) {
				echo "***ERREUR*** " . $ex->getMessage() . "\n";
			}
			try {
				echo "Check update...";
				update::checkAllUpdate('core', false);
				config::save('version', jeedom::version());
				echo "OK\n";
			} catch (Exception $ex) {
				echo "***ERREUR*** " . $ex->getMessage() . "\n";
			}
			echo "***************Jeedom is up to date in " . jeedom::version() . "***************\n";
		}
		if (init('level', -1) > -1) {
			echo "***************Update plugins***************\n";
			update::updateAll();
			echo "***************Update plugin successfully***************\n";
		}
		try {
			message::removeAll('update', 'newUpdate');
			echo "Check update\n";
			update::checkAllUpdate();
			echo "OK\n";
		} catch (Exception $ex) {
			echo "***ERREUR*** " . $ex->getMessage() . "\n";
		}
		try {
			jeedom::start();
		} catch (Exception $ex) {
			echo "***ERREUR*** " . $ex->getMessage() . "\n";
		}
	} else {

		/*         * ***************************INSTALLATION************************** */
		if (version_compare(PHP_VERSION, '5.6.0', '<')) {
			throw new Exception('Jeedom need php 5.6 or upper (current : ' . PHP_VERSION . ')');
		}
		echo "\nInstall of Jeedom " . jeedom::version() . "\n";
		$sql = file_get_contents(dirname(__FILE__) . '/install.sql');
		echo "Install of database...";
		DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		echo "OK\n";
		echo "Post install...\n";
		config::save('api', config::genKey());
		require_once dirname(__FILE__) . '/consistency.php';
		echo "Add user (admin,admin)\n";
		$user = new user();
		$user->setLogin('admin');
		$user->setPassword(sha1('admin'));
		$user->setRights('admin', 1);
		$user->save();
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

try {
	echo "Launch cron dependancy plugins...";
	$cron = cron::byClassAndFunction('plugin', 'checkDeamon');
	if (is_object($cron)) {
		$cron->start();
	}
	echo "OK\n";
} catch (Exception $e) {

}

try {
	echo "Send end of update event...";
	jeedom::event('end_update');
	echo "OK\n";
} catch (Exception $e) {

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
