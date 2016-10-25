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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';

class jeedom {
	/*     * *************************Attributs****************************** */

	private static $jeedomConfiguration;

	/*     * ***********************Methode static*************************** */

	public static function sick() {
		$cmd = dirname(__FILE__) . '/../../sick.php';
		$cmd .= ' >> ' . log::getPathToLog('sick') . ' 2>&1';
		system::php($cmd);
	}
	
	public static function getApiKey($_plugin = 'core') {
		if (config::byKey('api', $_plugin) == '') {
			config::save('api', config::genKey(), $_plugin);
		}
		return config::byKey('api', $_plugin);
	}

	public static function apiAccess($_apikey = '',$_plugin = 'core') {
		if ($_apikey == '') {
			return false;
		}
		if (self::getApiKey($_plugin) == $_apikey) {
			@session_start();
			$_SESSION['apimaster'] = true;
			@session_write_close();
			return true;
		}
		@session_start();
		$_SESSION['apimaster'] = false;
		@session_write_close();
		$user = user::byHash($_apikey);
		if (is_object($user)) {
			if ($user->getOptions('localOnly', 0) == 1 && network::getUserLocation() != 'internal') {
				sleep(5);
				return false;
			}
			@session_start();
			$_SESSION['user'] = $user;
			@session_write_close();
			log::add('connection', 'info', __('Connexion par API de l\'utilisateur : ', __FILE__) . $user->getLogin());
			return true;
		}
		sleep(5);
		return false;
	}

	public static function isOk() {
		if (!jeedom::isStarted()) {
			return false;
		}
		if (!jeedom::isDateOk()) {
			return false;
		}
		if (config::byKey('enableScenario') == 0 && count(scenario::all()) > 0) {
			return false;
		}
		if (!jeedom::isCapable('sudo')) {
			return false;
		}
		if (config::byKey('enableCron', 'core', 1, true) == 0) {
			return false;
		}
		return true;
	}

	/*************************************************USB********************************************************/

	public static function getUsbMapping($_name = '', $_getGPIO = false) {
		$cache = cache::byKey('jeedom::usbMapping');
		if (!is_json($cache->getValue()) || $_name == '') {
			$usbMapping = array();
			foreach (ls('/dev/', 'ttyUSB*') as $usb) {
				$vendor = '';
				$model = '';
				foreach (explode("\n", shell_exec('/sbin/udevadm info --name=/dev/' . $usb . ' --query=all')) as $line) {
					if (strpos($line, 'E: ID_MODEL=') !== false) {
						$model = trim(str_replace(array('E: ID_MODEL=', '"'), '', $line));
					}
					if (strpos($line, 'E: ID_VENDOR=') !== false) {
						$vendor = trim(str_replace(array('E: ID_VENDOR=', '"'), '', $line));
					}
				}
				if ($vendor == '' && $model == '') {
					$usbMapping['/dev/' . $usb] = '/dev/' . $usb;
				} else {
					$name = trim($vendor . ' ' . $model);
					$number = 2;
					while (isset($usbMapping[$name])) {
						$name = trim($vendor . ' ' . $model . ' ' . $number);
						$number++;
					}
					$usbMapping[$name] = '/dev/' . $usb;
				}
			}
			if ($_getGPIO) {
				if (file_exists('/dev/ttyAMA0')) {
					$usbMapping['Raspberry pi'] = '/dev/ttyAMA0';
				}
				if (file_exists('/dev/ttymxc0')) {
					$usbMapping['Jeedom board'] = '/dev/ttymxc0';
				}
				if (file_exists('/dev/S2')) {
					$usbMapping['Banana PI'] = '/dev/S2';
				}
				if (file_exists('/dev/ttyS2')) {
					$usbMapping['Banana PI (2)'] = '/dev/ttyS2';
				}
				if (file_exists('/dev/ttyS0')) {
					$usbMapping['Cubiboard'] = '/dev/ttyS0';
				}
				if (file_exists('/dev/ttyS3')) {
					$usbMapping['Orange PI'] = '/dev/ttyS3';
				}
				if (file_exists('/dev/ttyS1')) {
					$usbMapping['Odroid C2'] = '/dev/ttyS1';
				}
				foreach (ls('/dev/', 'ttyACM*') as $value) {
					$usbMapping['/dev/' . $value] = '/dev/' . $value;
				}
			}
			cache::set('jeedom::usbMapping', json_encode($usbMapping));
		} else {
			$usbMapping = json_decode($cache->getValue(), true);
		}
		if ($_name != '') {
			if (isset($usbMapping[$_name])) {
				return $usbMapping[$_name];
			}
			$usbMapping = self::getUsbMapping('', true);
			if (isset($usbMapping[$_name])) {
				return $usbMapping[$_name];
			}
			if (file_exists($_name)) {
				return $_name;
			}
			return '';
		}
		return $usbMapping;
	}

	public static function getBluetoothMapping($_name = '') {
		$cache = cache::byKey('jeedom::bluetoothMapping');
		if (!is_json($cache->getValue()) || $_name == '') {
			$bluetoothMapping = array();
			foreach (explode("\n", shell_exec('hcitool dev')) as $line) {
				if (strpos($line, 'hci') === false || trim($line) == '') {
					continue;
				}
				$infos = explode("\t", $line);
				$bluetoothMapping[$infos[2]] = $infos[1];
			}
			cache::set('jeedom::bluetoothMapping', json_encode($bluetoothMapping));
		} else {
			$bluetoothMapping = json_decode($cache->getValue(), true);
		}
		if ($_name != '') {
			if (isset($bluetoothMapping[$_name])) {
				return $bluetoothMapping[$_name];
			}
			$bluetoothMapping = self::getBluetoothMapping('', true);
			if (isset($bluetoothMapping[$_name])) {
				return $bluetoothMapping[$_name];
			}
			if (file_exists($_name)) {
				return $_name;
			}
			return '';
		}
		return $bluetoothMapping;
	}

	/********************************************BACKUP*****************************************************************/

	public static function backup($_background = false) {
		if ($_background) {
			log::clear('backup');
			$cmd = dirname(__FILE__) . '/../../install/backup.php';
			$cmd .= ' >> ' . log::getPathToLog('backup') . ' 2>&1 &';
			system::php($cmd);
		} else {
			require_once dirname(__FILE__) . '/../../install/backup.php';
		}
	}

	public static function listBackup() {
		if (substr(config::byKey('backup::path'), 0, 1) != '/') {
			$backup_dir = dirname(__FILE__) . '/../../' . config::byKey('backup::path');
		} else {
			$backup_dir = config::byKey('backup::path');
		}
		$backups = ls($backup_dir, '*.tar.gz', false, array('files', 'quiet', 'datetime_asc'));
		$return = array();
		foreach ($backups as $backup) {
			$return[$backup_dir . '/' . $backup] = $backup;
		}
		return $return;
	}

	public static function removeBackup($_backup) {
		if (file_exists($_backup)) {
			unlink($_backup);
		} else {
			throw new Exception('Impossible de trouver le fichier : ' . $_backup);
		}
	}

	public static function restore($_backup = '', $_background = false) {
		if ($_background) {
			log::clear('restore');
			$cmd = dirname(__FILE__) . '/../../install/restore.php backup=' . $_backup;
			$cmd .= ' >> ' . log::getPathToLog('restore') . ' 2>&1 &';
			system::php($cmd);
		} else {
			global $BACKUP_FILE;
			$BACKUP_FILE = $_backup;
			require_once dirname(__FILE__) . '/../../install/restore.php';
		}
	}

	/****************************UPDATE*****************************************************************/

	public static function update($_mode = '', $_level = -1, $_version = '', $__onlyThisVersion = '') {
		log::clear('update');
		$cmd = dirname(__FILE__) . '/../../install/install.php mode=' . $_mode . ' level=' . $_level . ' version=' . $_version . ' onlyThisVersion=' . $__onlyThisVersion;
		$cmd .= ' >> ' . log::getPathToLog('update') . ' 2>&1 &';
		system::php($cmd);
	}

	/****************************CONFIGURATION MANAGEMENT*****************************************************************/

	public static function getConfiguration($_key = '', $_default = false) {
		global $JEEDOM_INTERNAL_CONFIG;
		if ($_key == '') {
			return $JEEDOM_INTERNAL_CONFIG;
		}
		if (!is_array(self::$jeedomConfiguration)) {
			self::$jeedomConfiguration = array();
		}
		if (!$_default && isset(self::$jeedomConfiguration[$_key])) {
			return self::$jeedomConfiguration[$_key];
		}
		$keys = explode(':', $_key);

		$result = $JEEDOM_INTERNAL_CONFIG;
		foreach ($keys as $key) {
			if (isset($result[$key])) {
				$result = $result[$key];
			}
		}
		if ($_default) {
			return $result;
		}
		self::$jeedomConfiguration[$_key] = self::checkValueInconfiguration($_key, $result);
		return self::$jeedomConfiguration[$_key];
	}

	private static function checkValueInconfiguration($_key, $_value) {
		if (!is_array(self::$jeedomConfiguration)) {
			self::$jeedomConfiguration = array();
		}
		if (isset(self::$jeedomConfiguration[$_key])) {
			return self::$jeedomConfiguration[$_key];
		}
		if (is_array($_value)) {
			foreach ($_value as $key => $value) {
				$_value[$key] = self::checkValueInconfiguration($_key . ':' . $key, $value);
			}
			self::$jeedomConfiguration[$_key] = $_value;
			return $_value;
		} else {
			$config = config::byKey($_key);
			return ($config == '') ? $_value : $config;
		}
	}

	public static function version() {
		if (file_exists(dirname(__FILE__) . '/../config/version')) {
			return trim(file_get_contents(dirname(__FILE__) . '/../config/version'));
		}
		return '';
	}

	/**********************START AND DATE MANAGEMENT*************************************************************/

	public static function stop() {
		echo "Desactivation de toutes les tâches";
		config::save('enableCron', 0);
		foreach (cron::all() as $cron) {
			if ($cron->running()) {
				try {
					$cron->halt();
					echo '.';
				} catch (Exception $e) {
					sleep(5);
					$cron->halt();
				} catch (Error $e) {
					sleep(5);
					$cron->halt();
				}

			}
		}
		echo " OK\n";

		/*         * **********Arret des crons********************* */

		if (cron::jeeCronRun()) {
			echo "Arret du cron master ";
			$pid = cron::getPidFile();
			system::kill($pid);
			echo " OK\n";
		}

		/*         * *********Arrêt des scénarios**************** */

		echo "Désactivation de tous les scénarios";
		config::save('enableScenario', 0);
		foreach (scenario::all() as $scenario) {
			try {
				$scenario->stop();
				echo '.';
			} catch (Exception $e) {
				sleep(5);
				$scenario->stop();
			} catch (Error $e) {
				sleep(5);
				$scenario->stop();
			}
		}
		echo " OK\n";
	}

	public static function start() {
		try {
			/*             * *********Réactivation des scénarios**************** */
			echo "Réactivation des scénarios : ";
			config::save('enableScenario', 1);
			echo "OK\n";
			/*             * *********Réactivation des tâches**************** */
			echo "Réactivation des tâches : ";
			config::save('enableCron', 1);
			echo "OK\n";
		} catch (Exception $e) {
			if (!isset($_GET['mode']) || $_GET['mode'] != 'force') {
				throw $e;
			} else {
				echo '***ERREUR*** ' . $e->getMessage();
			}
		} catch (Error $e) {
			if (!isset($_GET['mode']) || $_GET['mode'] != 'force') {
				throw $e;
			} else {
				echo '***ERREUR*** ' . $e->getMessage();
			}
		}
	}

	public static function isStarted() {
		return file_exists('/tmp/jeedom_start');
	}

	public static function isDateOk() {
		if (config::byKey('ignoreHourCheck') == 1) {
			return true;
		}
		$maxdate = strtotime('2019-01-01 00:00:00');
		$mindate = strtotime('2016-01-01 00:00:00');
		if (strtotime('now') < $mindate || strtotime('now') > $maxdate) {
			jeedom::forceSyncHour();
			sleep(3);
			if (strtotime('now') < $mindate || strtotime('now') > $maxdate) {
				log::add('core', 'error', __('La date du système est incorrect (avant 2016-01-01 ou après 2019-01-01) : ', __FILE__) . date('Y-m-d H:i:s'), 'dateCheckFailed');
				return false;
			}
		}
		return true;
	}

	public static function event($_event, $_forceSyncMode = false) {
		scenario::check($_event, $_forceSyncMode);
	}

	/*****************************************CRON JEEDOM****************************************************************/

	public static function cron5() {
		try {
			network::cron5();
		} catch (Exception $e) {
			log::add('network', 'error', 'network::cron : ' . $e->getMessage());
		} catch (Error $e) {
			log::add('network', 'error', 'network::cron : ' . $e->getMessage());
		}
		try {
			eqLogic::checkAlive();
			if (config::byKey('jeeNetwork::mode') != 'slave') {
				jeeNetwork::pull();
			}
		} catch (Exception $e) {

		} catch (Error $e) {

		}
	}

	public static function cron() {
		if (!self::isStarted()) {
			echo date('Y-m-d H:i:s') . ' starting Jeedom';
			log::add('starting', 'debug', __('Démarrage de jeedom', __FILE__));

			try {
				log::add('starting', 'debug', __('Arret des crons', __FILE__));
				foreach (cron::all() as $cron) {
					if ($cron->running() && $cron->getClass() != 'jeedom' && $cron->getFunction() != 'cron') {
						try {
							$cron->halt();
						} catch (Exception $e) {
							log::add('starting', 'error', __('Erreur sur l\'arret d\'une tâche cron : ', __FILE__) . log::exception($e));
						} catch (Error $e) {
							log::add('starting', 'error', __('Erreur sur l\'arret d\'une tâche cron : ', __FILE__) . log::exception($e));
						}
					}
				}
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur l\'arret des tâches crons : ', __FILE__) . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur l\'arret des tâches crons : ', __FILE__) . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Restauration du cache', __FILE__));
				cache::restore();
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur la restoration du cache : ', __FILE__) . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur la restoration du cache : ', __FILE__) . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Nettoyage du cache des péripheriques USB', __FILE__));
				$cache = cache::byKey('jeedom::usbMapping');
				$cache->remove();
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur le nettoyage du cache des péripheriques USB : ', __FILE__) . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur le nettoyage du cache des péripheriques USB : ', __FILE__) . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Nettoyage du cache des péripheriques Bluetooth', __FILE__));
				$cache = cache::byKey('jeedom::bluetoothMapping');
				$cache->remove();
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur le nettoyage du cache des péripheriques Bluetooth : ', __FILE__) . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur le nettoyage du cache des péripheriques Bluetooth : ', __FILE__) . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Démarrage des processus internet de jeedom', __FILE__));
				jeedom::start();
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur le démarrage interne de jeedom : ', __FILE__) . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur le démarrage interne de jeedom : ', __FILE__) . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Ecriture du fichier /tmp/jeedom_start', __FILE__));
				if (!touch('/tmp/jeedom_start')) {
					log::add('starting', 'debug', __('Impossible d\'écrire /tmp/jeedom_start, tentative en shell', __FILE__));
					com_shell::execute('sudo touch /tmp/jeedom_start;sudo chmod 777 /tmp/jeedom_start');
				}
			} catch (Exception $e) {
				log::add('starting', 'error', __('Impossible d\'écrire /tmp/jeedom_start : ', __FILE__) . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Impossible d\'écrire /tmp/jeedom_start : ', __FILE__) . log::exception($e));
			}

			if (!file_exists('/tmp/jeedom_start')) {
				log::add('starting', 'critical', __('Impossible d\'écrire /tmp/jeedom_start pour une raison inconnue. Jeedom ne peut démarrer', __FILE__));
				return;
			}

			try {
				log::add('starting', 'debug', __('Envoi de l\'evenement de démarrage', __FILE__));
				self::event('start');
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur l\'envoi de l\'evenement de démarrage : ', __FILE__) . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur l\'envoi de l\'evenement de démarrage : ', __FILE__) . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Démarrage des plugins', __FILE__));
				plugin::start();
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur le démarrage des plugins : ', __FILE__) . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur la démarrage des plugins : ', __FILE__) . log::exception($e));
			}

			try {
				if (config::byKey('market::enable') == 1) {
					log::add('starting', 'debug', __('Test de connexion au market', __FILE__));
					repo_market::test();
				}
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur la connexion au market : ', __FILE__) . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur la connexion au market : ', __FILE__) . log::exception($e));
			}
			log::add('starting', 'debug', __('Démarrage de jeedom fini avec succès', __FILE__));
			event::add('refresh');
		}
		self::isDateOk();
		if (config::byKey('update::autocheck', 'core', 1) == 1) {
			$isDue = true;
			try {
				if (config::byKey('update::check') != '') {
					$c = new Cron\CronExpression(config::byKey('update::check'), new Cron\FieldFactory);
					$isDue = $c->isDue();
				}
			} catch (Exception $e) {

			} catch (Error $e) {

			}
			try {
				if ($isDue) {
					if (config::byKey('update::lastCheck') == '' || (strtotime('now') - strtotime(config::byKey('update::lastCheck'))) > 3600) {
						update::checkAllUpdate();
						$updates = update::byStatus('update');
						if (count($updates) > 0) {
							$toUpdate = '';
							foreach ($updates as $update) {
								$toUpdate .= $update->getLogicalId() . ',';
							}
						}
						$updates = update::byStatus('update');
						if (count($updates) > 0) {
							message::add('update', __('De nouvelles mises à jour sont disponibles : ', __FILE__) . trim($toUpdate, ','), '', 'newUpdate');
						}
						config::save('update::check', rand(1, 59) . ' ' . rand(6, 7) . ' * * *');
					}
				}
			} catch (Exception $e) {

			} catch (Error $e) {

			}
		}
	}

	public static function cronDaily() {
		try {
			scenario::cleanTable();
			scenario::consystencyCheck();
			log::chunk();
			cron::clean();
			DB::optimize();
		} catch (Exception $e) {
			log::add('jeedom', 'error', $e->getMessage());
		} catch (Error $e) {
			log::add('jeedom', 'error', $e->getMessage());
		}
	}

	public static function cronHourly() {
		try {
			foreach (update::listRepo() as $name => $repo) {
				$class = 'repo_' . $name;
				if (class_exists($class) && method_exists($class, 'cronHourly') && config::byKey($name . '::enable') == 1) {
					$class::cronHourly();
				}
			}
		} catch (Exception $e) {
			log::add('jeedom', 'error', $e->getMessage());
		} catch (Error $e) {
			log::add('jeedom', 'error', $e->getMessage());
		}
	}

	/*************************************************************************************/

	public static function replaceTag(array $_replaces) {
		$datas = array();
		foreach ($_replaces as $key => $value) {
			$datas = array_merge($datas, cmd::searchConfiguration($key));
			$datas = array_merge($datas, eqLogic::searchConfiguration($key));
			$datas = array_merge($datas, scenario::byUsedCommand($key));
			$datas = array_merge($datas, scenarioExpression::searchExpression($key, $key, false));
			$datas = array_merge($datas, scenarioExpression::searchExpression('variable(' . str_replace('#', '', $key) . ')'));
			$datas = array_merge($datas, scenarioExpression::searchExpression('variable', str_replace('#', '', $key), true));
		}
		if (count($datas) > 0) {
			foreach ($datas as $data) {
				utils::a2o($data, json_decode(str_replace(array_keys($_replaces), $_replaces, json_encode(utils::o2a($data))), true));
				$data->save();
			}
		}
	}

	/***************************************THREAD MANGEMENT**********************************************/

	public static function checkOngoingThread($_cmd) {
		return shell_exec('(ps ax || ps w) | grep "' . $_cmd . '$" | grep -v "grep" | wc -l');
	}

	public static function retrievePidThread($_cmd) {
		return shell_exec('(ps ax || ps w) | grep "' . $_cmd . '$" | grep -v "grep" | awk \'{print $1}\'');
	}

	/******************************************UTILS******************************************************/

	public static function versionAlias($_version, $_lightMode = true) {
		if (!$_lightMode) {
			if ($_version == 'dplan') {
				return 'plan';
			} else if ($_version == 'dview') {
				return 'view';
			} else if ($_version == 'mview') {
				return 'view';
			}
		}
		$alias = array(
			'mview' => 'mobile',
			'dview' => 'dashboard',
			'dplan' => 'dashboard',
		);
		return (isset($alias[$_version])) ? $alias[$_version] : $_version;
	}

	public static function toHumanReadable($_input) {
		return scenario::toHumanReadable(eqLogic::toHumanReadable(cmd::cmdToHumanReadable($_input)));
	}

	public static function fromHumanReadable($_input) {
		return scenario::fromHumanReadable(eqLogic::fromHumanReadable(cmd::humanReadableToCmd($_input)));
	}

	public static function evaluateExpression($_input) {
		try {
			$_scenario = null;
			$_input = scenarioExpression::setTags($_input, $_scenario, true);
			$result = evaluate($_input);
			if (is_bool($result) || is_numeric($result)) {
				return $result;
			}
			return $_input;
		} catch (Exception $exc) {
			return $_input;
		}
	}

	public static function calculStat($_calcul, $_values) {
		switch ($_calcul) {
			case 'sum':
				return array_sum($_values);
				break;
			case 'avg':
				return array_sum($_values) / count($_values);
				break;
		}
		return null;
	}

	/******************************SYSTEM MANAGEMENT**********************************************************/

	public static function haltSystem() {
		plugin::stop();
		cache::persist();
		exec('sudo shutdown -h now');
	}

	public static function rebootSystem() {
		plugin::stop();
		cache::persist();
		exec('sudo reboot');
	}

	public static function forceSyncHour() {
		shell_exec('sudo service ntp stop;sudo ntpdate -s ' . config::byKey('ntp::optionalServer', 'core', '0.debian.pool.ntp.org') . ';sudo service ntp start');
	}

	public static function cleanFileSytemRight() {
		$processUser = posix_getpwuid(posix_geteuid());
		$processGroup = posix_getgrgid(posix_getegid());
		$path = dirname(__FILE__) . '/../../*';
		exec('sudo chown -R ' . $processUser['name'] . ':' . $processGroup['name'] . ' ' . $path . ';sudo chmod 775 -R ' . $path);
	}

	public static function checkSpaceLeft() {
		$path = dirname(__FILE__) . '/../../';
		$free = disk_free_space($path);
		$total = disk_total_space($path);
		return round($free / $total * 100);
	}

/*     * ****************************SQL BUDDY*************************** */

	public static function getCurrentAdminerFolder() {
		$dir = dirname(__FILE__) . '/../../';
		$ls = ls($dir, 'adminer*');
		if (count($ls) != 1) {
			return '';
		}
		return $ls[0];
	}

	public static function renameAdminerFolder() {
		$folder = self::getCurrentAdminerFolder();
		if ($folder != '') {
			rename(dirname(__FILE__) . '/../../' . $folder, dirname(__FILE__) . '/../../adminer' . config::genKey());
		}
	}

/*     * ****************************SYSINFO*************************** */

	public static function getCurrentSysInfoFolder() {
		$dir = dirname(__FILE__) . '/../../';
		$ls = ls($dir, 'sysinfo*');
		if (count($ls) != 1) {
			return '';
		}
		return $ls[0];
	}

	public static function renameSysInfoFolder() {
		$folder = self::getCurrentSysInfoFolder();
		if ($folder != '') {
			rename(dirname(__FILE__) . '/../../' . $folder, dirname(__FILE__) . '/../../sysinfo' . config::genKey());
		}
	}

/*     * ******************hardware management*************************** */

	public static function getHardwareKey() {
		$return = config::byKey('jeedom::installKey');
		if ($return == '') {
			$return = sha1(microtime() . config::genKey());
			config::save('jeedom::installKey', $return);
		}
		return $return;
	}

	public static function getHardwareName() {
		if (config::byKey('hardware_name') != '') {
			return config::byKey('hardware_name');
		}
		$result = 'DIY';
		$uname = shell_exec('uname -a');
		if (file_exists('/.dockerinit')) {
			$result = 'Docker';
		} else if (file_exists('/usr/bin/raspi-config')) {
			$result = 'RPI/RPI2';
		} else if (strpos($uname, 'cubox') !== false) {
			$result = 'Jeedomboard';
		}
		config::save('hardware_name', $result);
		return config::byKey('hardware_name');
	}

	public static function isCapable($_function) {
		global $JEEDOM_COMPATIBILIY_CONFIG;
		if ($_function == 'sudo') {
			return (shell_exec('sudo -l > /dev/null 2>&1; echo $?') == 0) ? true : false;
		}
		$hardware = self::getHardwareName();
		if (!isset($JEEDOM_COMPATIBILIY_CONFIG[$hardware])) {
			return false;
		}
		if (in_array($_function, $JEEDOM_COMPATIBILIY_CONFIG[$hardware])) {
			return true;
		}
		return false;
	}

	/*     * ******************Benchmark*************************** */

	public static function benchmark() {
		$return = array();

		$param = array('cache_write' => 5000, 'cache_read' => 5000, 'database_write_delete' => 1000, 'database_update' => 1000, 'database_replace' => 1000, 'database_read' => 50000, 'subprocess' => 200);

		$starttime = getmicrotime();
		for ($i = 0; $i < $param['cache_write']; $i++) {
			cache::set('jeedom_benchmark', $i);
		}
		$return['cache_write_' . $param['cache_write']] = getmicrotime() - $starttime;

		$starttime = getmicrotime();
		for ($i = 0; $i < $param['cache_read']; $i++) {
			$cache = cache::byKey('jeedom_benchmark');
			$cache->getValue();
		}
		$return['cache_read_' . $param['cache_read']] = getmicrotime() - $starttime;

		$starttime = getmicrotime();
		for ($i = 0; $i < $param['database_write_delete']; $i++) {
			$sql = 'DELETE FROM config
                	WHERE `key`="jeedom_benchmark"
                    	AND plugin="core"';
			try {
				DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
			} catch (Exception $e) {

			}
			$sql = 'INSERT INTO config
                	SET `key`="jeedom_benchmark",plugin="core",`value`="' . $i . '"';
			try {
				DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
			} catch (Exception $e) {

			}
		}
		$return['database_write_delete_' . $param['database_write_delete']] = getmicrotime() - $starttime;

		$sql = 'INSERT INTO config
                SET `key`="jeedom_benchmark",plugin="core",`value`="0"';
		try {
			DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		} catch (Exception $e) {

		}
		$starttime = getmicrotime();
		for ($i = 0; $i < $param['database_update']; $i++) {
			$sql = 'UPDATE config
                	SET `value`="' . $i . '"
                	WHERE `key`="jeedom_benchmark"
                		AND plugin="core"';
			try {
				DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
			} catch (Exception $e) {

			}
		}
		$return['database_update_' . $param['database_update']] = getmicrotime() - $starttime;

		$starttime = getmicrotime();
		for ($i = 0; $i < $param['database_replace']; $i++) {
			config::save('jeedom_benchmark', $i);
		}
		$return['database_replace_' . $param['database_replace']] = getmicrotime() - $starttime;

		$starttime = getmicrotime();
		for ($i = 0; $i < $param['database_read']; $i++) {
			config::byKey('jeedom_benchmark');
		}
		$return['database_read_' . $param['database_read']] = getmicrotime() - $starttime;

		$starttime = getmicrotime();
		for ($i = 0; $i < $param['subprocess']; $i++) {
			shell_exec('echo ' . $i);
		}
		$return['subprocess_' . $param['subprocess']] = getmicrotime() - $starttime;

		$total = 0;
		foreach ($return as $value) {
			$total += $value;
		}
		$return['total'] = $total;
		return $return;
	}

}

?>
