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
$starttime = strtotime('now');
if (isset($argv)) {
	foreach ($argv as $arg) {
		$argList = explode('=', $arg);
		if (isset($argList[0]) && isset($argList[1])) {
			$_GET[$argList[0]] = $argList[1];
		}
	}
}

require_once dirname(__FILE__) . '/../core/php/core.inc.php';

/*********************Step management*********************************/
if (!isset($_GET['step'])) {
	$default = array(
		'backup::defore' => 1,
		'backup::cloudSend' => 0,
		'plugins' => 1,
		'force' => 0,
		'version' => config::byKey('version'),
	);
	$_GET = array_merge($default, $_GET);
	$params = '';
	if (count($argv) > 0) {
		foreach ($argv as $key => $value) {
			if ($key == 'step') {
				continue;
			}
			$params .= '"' . $key . '"="' . $value . '"';
		}
	}
	echo "[START UPDATE]\n";
	echo "Parameters : " . print_r($_GET, true) . "\n";

	$rc = 0;
	if ($_GET['backup::defore'] == 1) {
		echo "Launch step 10 : backup\n";
		$output = array();
		exec('php ' . dirname(__FILE__) . '/update.php step=10 ' . $params, $output, $rc);
		testRc($rc, $output, 10);
	}
	echo "Launch step 20 : download jeedom zip and update updater\n";
	$output = array();
	exec('php ' . dirname(__FILE__) . '/update.php step=20 ' . $params, $output, $rc);
	testRc($rc, $output, 20);
	echo "Launch step 30 : update jeedom file and database\n";
	$output = array();
	exec('php ' . dirname(__FILE__) . '/update.php step=30 ' . $params, $output, $rc);
	testRc($rc, $output, 30);
	echo "Launch step 40 : update jeedom\n";
	$output = array();
	exec('php ' . dirname(__FILE__) . '/update.php step=40 ' . $params, $output, $rc);
	testRc($rc, $output, 40);
} else {
	testUpdateInProgress();
	$function = 'step' . $_GET['step'];
	if (function_exists($function)) {
		try {
			$function();
		} catch (Exception $e) {
			exit(1);
		}

	}
}

/***************************************************************/

//Step 10 do backup
function step10() {
	global $NO_PLUGIN_BACKUP;
	$NO_PLUGIN_BACKUP = true;
	global $NO_CLOUD_BACKUP;
	$NO_CLOUD_BACKUP = $_GET['backup::cloudSend'];
	try {
		jeedom::backup();
	} catch (Exception $e) {
		exit(1);
	}
}

//Step 20 download jeedom zip and update updater
function step20() {
	$tmp_dir = jeedom::getTmpFolder('install');
	$tmp = $tmp_dir . '/jeedom_update.zip';
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
	$cibDir = jeedom::getTmpFolder('install/unzip');
	if (file_exists($cibDir)) {
		rrmdir($cibDir);
	}
	echo "OK\n";
	echo "Create temporary folder...";
	if (!file_exists($cibDir) && !mkdir($cibDir, 0777, true)) {
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
	if (!file_exists($cibDir . '/core')) {
		$files = ls($cibDir, '*');
		if (count($files) == 1 && file_exists($cibDir . '/' . $files[0] . 'core')) {
			$cibDir = $cibDir . '/' . $files[0];
		}
	}
	echo 'Update updater...';
	if (!rename($cibDir . '/install/update.php', dirname(__FILE__) . '/update.php')) {
		throw new Exception('Unable to move updater : ' . $cibDir . '/install/update.php' . ' => ' . dirname(__FILE__) . '/update.php');
	}
	echo "OK\n";
}

//Step 30 update jeedom file and database
function step30() {
	echo 'Clean temporary file (tmp)...';
	shell_exec('rm -rf ' . dirname(__FILE__) . '/../install/update/*');
	echo "OK\n";
	jeedom::stop();
	$cibDir = jeedom::getTmpFolder('install/unzip');
	if (!file_exists($cibDir . '/core')) {
		$files = ls($cibDir, '*');
		if (count($files) == 1 && file_exists($cibDir . '/' . $files[0] . 'core')) {
			$cibDir = $cibDir . '/' . $files[0];
		}
	}
	echo "Moving file...";
	rmove($cibDir . '/', dirname(__FILE__) . '/../', false, array(), true);
	echo "OK\n";
	$curentVersion = $_GET['version'];
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

			}
			try {
				echo "Update database into : " . $nextVersion . "...";
				$sql = file_get_contents($updateSql);
				DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
				echo "OK\n";
			} catch (Exception $e) {
				manageExeption($e);
			}
			try {
				echo "Enable constraint...";
				$sql = "SET SQL_MODE=@OLD_SQL_MODE;
                                    SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
                                    SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;";
				DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
				echo "OK\n";
			} catch (Exception $e) {

			}
		}
		$curentVersion = $nextVersion;
		config::save('version', $curentVersion);
	}
}

//Step 40 update jeedom
function step40() {
	$curentVersion = $_GET['version'];
	while (version_compare(jeedom::version(), $curentVersion, '>')) {
		$nextVersion = incrementVersion($curentVersion);
		$updateSql = dirname(__FILE__) . '/update/' . $nextVersion . '.sql';
		$updateScript = dirname(__FILE__) . '/update/' . $nextVersion . '.php';
		if (file_exists($updateScript)) {
			try {
				echo "Update system into : " . $nextVersion . "...";
				require_once $updateScript;
				echo "OK\n";
			} catch (Exception $e) {
				manageExeption($e);
			}
		}
		$curentVersion = $nextVersion;
		config::save('version', $curentVersion);
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

	if (init('plugin', 1) == 1) {
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
}

/*********************Utils function**********************************/

function manageExeption($_e) {
	if (init('mode') != 'force') {
		throw $e;
	} else {
		echo '***ERROR***' . $e->getMessage();
	}
}

function testRc($_rc, $_output, $_step) {
	if ($_rc == 0) {
		return;
	}
	throw new Exception("Error Processing Request : " . print_r($_output, true));
}

function testUpdateInProgress() {
	$cmd = '(ps ax || ps w) | grep -ie "install/update.php" | grep -v "grep" | grep -v "sudo"';
	$results = explode("\n", trim(shell_exec($cmd)));
	if (count($results) > 2) {
		echo "Une mise a jour/installation est deja en cours. Vous devez attendre qu'elle soit finie avant d'en relancer une\n";
		print_r($results);
		echo "[END UPDATE]\n";
		die();
	}
}

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
