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

set_time_limit(1800);
echo "[START UPDATE]\n";
$starttime = strtotime('now');

$update = false;
$backup_ok = false;
$update_begin = false;
try {
	require_once __DIR__ . '/../core/php/core.inc.php';
	echo "[PROGRESS][1]\n";
	if (count(system::ps('install/update.php', 'sudo')) > 1) {
		echo "Update in progress. I will wait 10s\n";
		sleep(10);
		if (count(system::ps('install/update.php', 'sudo')) > 1) {
			echo "Update in progress. You need to wait before update\n";
			json_encode(system::ps('install/update.php', 'sudo')) . "\n";
			echo "[END UPDATE]\n";
			die();
		}
	}
	echo "****Update from " . jeedom::version() . " (" . date('Y-m-d H:i:s') . ")****\n";
	echo "Parameters : " . json_encode($_GET) . "\n";
	$curentVersion = config::byKey('version');

	/*         * ************************MISE A JOUR********************************** */

	try {
		echo "Send begin of update event...";
		jeedom::event('begin_update', true);
		echo "OK\n";
	} catch (Exception $e) {
		if (init('force') != 1) {
			throw $e;
		} else {
			echo '***ERROR***' . $e->getMessage();
		}
	}

	try {
		if (init('plugins', 1) == 1 && init('force') != 1) {
			echo "Check update...";
			update::checkAllUpdate('', false);
			echo "OK\n";
		}
	} catch (Exception $e) {
		if (init('force') != 1) {
			throw $e;
		} else {
			echo '***ERROR***' . $e->getMessage();
		}
	}
	echo "[PROGRESS][5]\n";
	try {
		echo "Check rights...";
		jeedom::cleanFileSystemRight();
		echo "OK\n";
	} catch (Exception $e) {
		echo '***ERROR***' . $e->getMessage();
	}
	if (init('backup::before') == 1 && init('force') != 1) {
		try {
			global $NO_PLUGIN_BACKUP;
			$NO_PLUGIN_BACKUP = true;
			global $NO_CLOUD_BACKUP;
			$NO_CLOUD_BACKUP = true;
			jeedom::backup();
		} catch (Exception $e) {
			if (init('force') != 1) {
				throw $e;
			} else {
				echo '***ERROR***' . $e->getMessage();
			}
		}
		$backup_ok = true;
	}
	echo "[PROGRESS][10]\n";
	if (init('core', 1) == 1) {
		if (init('mode') == 'force') {
			echo "/!\ Force update /!\ \n";
		}
		echo "[PROGRESS][15]\n";
		if (init('update::reapply') == '' && config::byKey('update::allowCore', 'core', 1) != 0) {
			$tmp_dir = jeedom::getTmpFolder('install');
			$tmp = $tmp_dir . '/jeedom_update.zip';
			try {
				if (config::byKey('core::repo::provider') == 'default') {
					$url = 'https://github.com/jeedom/core/archive/' . config::byKey('core::branch') . '.zip';
					echo "Download url : " . $url . "\n";
					echo "Download in progress...";
					if (!is_writable($tmp_dir)) {
						throw new Exception('Can not write : ' . $tmp . '. Please execute : chmod 777 -R ' . $tmp_dir);
					}
					if (file_exists($tmp)) {
						unlink($tmp);
					}
					exec('wget --progress=dot --dot=mega ' . $url . ' -O ' . $tmp);
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
				echo "[PROGRESS][25]\n";
				if (filesize($tmp) < 100) {
					throw new Exception('Download failed please retry later');
				}
				echo "OK\n";
				echo "Cleaning folders...";
				$cibDir = '/tmp/jeedom_unzip';
				if (file_exists($cibDir)) {
					rrmdir($cibDir);
				}
				echo "OK\n";
				echo "[PROGRESS][30]\n";
				echo "Create temporary folder...";
				if (!file_exists($cibDir) && !mkdir($cibDir, 0777, true)) {
					throw new Exception('Can not write into  : ' . $cibDir . '.');
				}
				echo "OK\n";
				echo "[PROGRESS][35]\n";
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
				if (disk_free_space($cibDir) < 10) {
					throw new Exception('Error no more free space on ' . $cibDir . '. Free space : ' . disk_free_space($cibDir));
				}
				echo "[PROGRESS][40]\n";
				if (!file_exists($cibDir . '/core')) {
					$files = ls($cibDir, '*');
					if (count($files) == 1 && file_exists($cibDir . '/' . $files[0] . 'core')) {
						$cibDir = $cibDir . '/' . $files[0];
					}
				}

				if (init('preUpdate') == 1) {
					echo "Update updater...";
					rmove($cibDir . '/install/update.php', __DIR__ . '/update.php', false, array(), array('log' => true, 'ignoreFileSizeUnder' => 1));
					echo "OK\n";
					echo "Remove temporary files...";
					rrmdir($tmp_dir);
					echo "OK\n";
					echo "Wait 10s before relaunch update\n";
					sleep(10);
					$_GET['preUpdate'] = 0;
					jeedom::update($_GET);
					echo "[PROGRESS][100]\n";
					die();
				}
				try {
					echo 'Clean temporary files (tmp)...';
					shell_exec('rm -rf ' . __DIR__ . '/../install/update/*');
					shell_exec('rm -rf ' . __DIR__ . '/../doc');
					shell_exec('rm -rf ' . __DIR__ . '/../docs');
					shell_exec('rm -rf ' . __DIR__ . '/../support');
					shell_exec('rm -rf ' . __DIR__ . '/../core/template/*');
					shell_exec('rm -rf ' . __DIR__ . '/../core/themes/*');
					echo "OK\n";
				} catch (Exception $e) {
					echo '***ERROR*** ' . $e->getMessage() . "\n";
				}
				jeedom::stop();
				echo "[PROGRESS][45]\n";
				echo "Moving files...";
				$update_begin = true;
				$file_copy = array();
				rmove($cibDir . '/', __DIR__ . '/../', false, array(), true, array('log' => true, 'ignoreFileSizeUnder' => 1));
				echo "OK\n";
				echo "[PROGRESS][50]\n";
				echo "Remove temporary files...";
				rrmdir($tmp_dir);
				try {
					shell_exec('rm -rf ' . __DIR__ . '/../.travis.yml');
					shell_exec('rm -rf ' . __DIR__ . '/../phpunit.xml.dist');
				} catch (Exception $e) {
					echo '***ERROR*** ' . $e->getMessage() . "\n";
				}
				echo "OK\n";
				echo "[PROGRESS][52]\n";
				echo "Remove useless files...\n";
				foreach (array('3rdparty', 'desktop', 'mobile', 'core', 'docs', 'install', 'script', 'vendor') as $folder) {
					echo 'Cleaning ' . $folder . "\n";
					shell_exec('find ' . __DIR__ . '/../' . $folder . '/* -mtime +7 -type f ! -iname "custom.*" ! -iname "common.config.php" -delete');
				}
				try {
					$update = update::byLogicalId('jeedom');
					if (is_object($update) && method_exists($update, 'setUpdateDate')) {
						$update->setUpdateDate(date('Y-m-d H:i:s'));
						$update->save();
					}
				} catch (\Exception $e) {
				}
			} catch (Exception $e) {
				if (init('force') != 1) {
					throw $e;
				} else {
					echo '***ERROR***' . $e->getMessage();
				}
			}
		} else {
			jeedom::stop();
		}
		echo "[PROGRESS][55]\n";
		if (init('update::reapply') != '') {
			$updateScript = __DIR__ . '/update/' . init('update::reapply') . '.php';
			if (file_exists($updateScript)) {
				try {
					echo "Update system into : " . init('update::reapply') . "\n";
					echo exec(system::getCmdSudo() . ' php ' . $updateScript);
					echo "OK\n";
				} catch (Exception $e) {
					if (init('force') != 1) {
						throw $e;
					} else {
						echo '***ERROR***' . $e->getMessage();
					}
				}
			}
			$curentVersion = init('update::reapply');
		} else {
			while (version_compare(jeedom::version(), $curentVersion, '>')) {
				$nextVersion = incrementVersion($curentVersion);
				$updateScript = __DIR__ . '/update/' . $nextVersion . '.php';
				if (file_exists($updateScript)) {
					try {
						echo "Update system into : " . $nextVersion . "...";
						echo exec(system::getCmdSudo() . ' php ' . $updateScript);
						echo "OK\n";
					} catch (Exception $e) {
						if (init('force') != 1) {
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
			echo "Check jeedom consistency...\n";
			require_once __DIR__ . '/consistency.php';
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
	echo "[PROGRESS][75]\n";
	if (init('plugins', 1) == 1) {
		echo "***************Update plugins***************\n";
		update::updateAll();
		echo "***************Update plugin successfully***************\n";
	}
	echo "[PROGRESS][90]\n";
	try {
		message::removeAll('update', 'newUpdate');
		echo "Check update\n";
		update::checkAllUpdate();
		echo "OK\n";
	} catch (Exception $ex) {
		echo "***ERREUR*** " . $ex->getMessage() . "\n";
	}
	echo "[PROGRESS][95]\n";
	try {
		jeedom::start();
	} catch (Exception $ex) {
		echo "***ERREUR*** " . $ex->getMessage() . "\n";
	}
	config::save('version', jeedom::version());
	echo "[PROGRESS][100]\n";
} catch (Exception $e) {
	if ($update) {
		if ($backup_ok && $update_begin) {
			jeedom::restore();
		}
		jeedom::start();
	}
	echo 'Error during update : ' . $e->getMessage();
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
echo "Update duration : " . (strtotime('now') - $starttime) . "s\n";
echo "[END UPDATE SUCCESS]\n";

function incrementVersion($_version) {
	$version = explode('.', $_version);
	if ($version[2] < 100) {
		$version[2]++;
	} else {
		if ($version[1] < 100) {
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
