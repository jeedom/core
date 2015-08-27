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

	public static function stop() {
		try {
			echo "Desactivation de toutes les tâches";
			config::save('enableCron', 0);
			foreach (cron::all() as $cron) {
				if ($cron->running() && $cron->getClass() != 'jeedom' && $cron->getFunction() != 'cron') {
					try {
						$cron->halt();
						echo '.';
					} catch (Exception $e) {
						sleep(5);
						$cron->halt();
					}

				}
			}
			echo " OK\n";
		} catch (Exception $e) {
			if (!isset($_GET['mode']) || $_GET['mode'] != 'force') {
				throw $e;
			} else {
				echo "\n***ERREUR*** " . $e->getMessage();
			}
		}
		/*         * **********Arret des crons********************* */

		try {
			if (cron::jeeCronRun()) {
				echo "Arret du cron master ";
				$pid = cron::getPidFile();
				$kill = posix_kill($pid, 15);
				if (!$kill) {
					$kill = posix_kill($pid, 9);
					if (!$kill) {
						throw new Exception('Impossible d\'arrêter le cron master : ' . $pid);
					}
				}
				echo " OK\n";
			}
		} catch (Exception $e) {
			if (!isset($_GET['mode']) || $_GET['mode'] != 'force') {
				throw $e;
			} else {
				echo '***ERREUR*** ' . $e->getMessage();
			}
		}

		/*         * *********Arrêt des scénarios**************** */
		try {
			echo "Désactivation de tous les scénarios";
			config::save('enableScenario', 0);
			foreach (scenario::all() as $scenario) {
				try {
					$scenario->stop();
					echo '.';
				} catch (Exception $e) {
					sleep(5);
					$scenario->stop();
				}
			}
			echo " OK\n";
		} catch (Exception $e) {
			if (!isset($_GET['mode']) || $_GET['mode'] != 'force') {
				throw $e;
			} else {
				echo '***ERREUR*** ' . $e->getMessage();
			}
		}
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
		}
	}

	public static function sick() {
		$cmd = 'php ' . dirname(__FILE__) . '/../../sick.php';
		$cmd .= ' >> ' . log::getPathToLog('sick') . ' 2>&1';
		shell_exec($cmd);
	}

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
				foreach (ls('/dev/', 'ttyACM*') as $value) {
					$usbMapping['/dev/' . $value] = '/dev/' . $value;
				}
			}
			cache::set('jeedom::usbMapping', json_encode($usbMapping), 0);
		} else {
			$usbMapping = json_decode($cache->getValue(), true);
		}
		if ($_name != '') {
			if (isset($usbMapping[$_name])) {
				return $usbMapping[$_name];
			}
			$usbMapping = self::getUsbMapping('', $_getGPIO);
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

	public static function backup($_background = false, $_noCloudUpload = 0) {
		if ($_background) {
			log::clear('backup');
			$cmd = 'php ' . dirname(__FILE__) . '/../../install/backup.php noCloudUpload=' . $_noCloudUpload;
			$cmd .= ' >> ' . log::getPathToLog('backup') . ' 2>&1 &';
			exec($cmd);
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
			$cmd = 'php ' . dirname(__FILE__) . '/../../install/restore.php backup=' . $_backup;
			$cmd .= ' >> ' . log::getPathToLog('restore') . ' 2>&1 &';
			exec($cmd);
		} else {
			global $BACKUP_FILE;
			$BACKUP_FILE = $_backup;
			require_once dirname(__FILE__) . '/../../install/restore.php';
		}
	}

	public static function update($_mode = '', $_level = -1, $_version = '', $__onlyThisVersion = '') {
		log::clear('update');
		$cmd = 'php ' . dirname(__FILE__) . '/../../install/install.php mode=' . $_mode . ' level=' . $_level . ' version=' . $_version . ' onlyThisVersion=' . $__onlyThisVersion;
		$cmd .= ' >> ' . log::getPathToLog('update') . ' 2>&1 &';
		exec($cmd);
	}

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

	public static function whatDoYouKnow($_object = null) {
		$result = array();
		if (is_object($_object)) {
			$objects = array($_object);
		} else {
			$objects = object::all();
		}
		foreach ($objects as $object) {
			foreach ($object->getEqLogic() as $eqLogic) {
				if ($eqLogic->getIsEnable() == 1) {
					foreach ($eqLogic->getCmd() as $cmd) {
						if ($cmd->getIsVisible() == 1 && $cmd->getType() == 'info') {
							try {
								$value = $cmd->execCmd();
								if (!isset($result[$object->getId()])) {
									$result[$object->getId()] = array();
									$result[$object->getId()]['name'] = $object->getName();
									$result[$object->getId()]['eqLogic'] = array();
								}
								if (!isset($result[$object->getId()]['eqLogic'][$eqLogic->getId()])) {
									$result[$object->getId()]['eqLogic'][$eqLogic->getId()] = array();
									$result[$object->getId()]['eqLogic'][$eqLogic->getId()]['name'] = $eqLogic->getName();
									$result[$object->getId()]['eqLogic'][$eqLogic->getId()]['cmd'] = array();
								}

								$result[$object->getId()]['eqLogic'][$eqLogic->getId()]['cmd'][$cmd->getId()] = array();
								$result[$object->getId()]['eqLogic'][$eqLogic->getId()]['cmd'][$cmd->getId()]['name'] = $cmd->getName();
								$result[$object->getId()]['eqLogic'][$eqLogic->getId()]['cmd'][$cmd->getId()]['unite'] = $cmd->getUnite();
								$result[$object->getId()]['eqLogic'][$eqLogic->getId()]['cmd'][$cmd->getId()]['value'] = $value;
							} catch (Exception $exc) {

							}
						}
					}
				}
			}
		}
		return $result;
	}

	public static function version() {
		if (file_exists(dirname(__FILE__) . '/../config/version')) {
			return trim(file_get_contents(dirname(__FILE__) . '/../config/version'));
		}
		return '';
	}

	public static function needUpdate($_refresh = false) {
		$return = array();
		$return['currentVersion'] = market::getJeedomCurrentVersion($_refresh);
		$return['version'] = jeedom::version();
		if (version_compare($return['currentVersion'], $return['version'], '>')) {
			$return['needUpdate'] = true;
		} else {
			$return['needUpdate'] = false;
		}
		return $return;
	}

	public static function isStarted() {
		return file_exists('/tmp/jeedom_start');
	}

	public static function isDateOk() {
		if (file_exists('/tmp/jeedom_dateOk')) {
			return true;
		}
		if (strtotime('now') < strtotime('2015-01-01 00:00:00') || strtotime('now') > strtotime('2019-01-01 00:00:00')) {
			shell_exec('sudo sntp ' . config::byKey('ntp::optionalServer', 'core', '0.debian.pool.ntp.org'));
			sleep(3);
		}
		if (strtotime('now') < strtotime('2015-01-01 00:00:00') || strtotime('now') > strtotime('2019-01-01 00:00:00')) {
			shell_exec('sudo sntp 1.debian.pool.ntp.org');
			sleep(3);
		}
		if (strtotime('now') < strtotime('2015-01-01 00:00:00') || strtotime('now') > strtotime('2019-01-01 00:00:00')) {
			jeedom::forceSyncHour();
			sleep(3);
		}
		if (strtotime('now') < strtotime('2015-01-01 00:00:00') || strtotime('now') > strtotime('2019-01-01 00:00:00')) {
			log::add('core', 'error', __('La date du système est incorrect (avant 2014-01-01 ou après 2019-01-01) : ', __FILE__) . date('Y-m-d H:i:s'), 'dateCheckFailed');
			return false;
		}
		touch('/tmp/jeedom_dateOk');
		return true;
	}

	public static function event($_event) {
		scenario::check($_event);
	}

	public static function checkAndCollect() {
		try {
			if (date('Gi') >= 500 && date('Gi') < 505) {
				history::archive();
			}
		} catch (Exception $e) {
			log::add('history', 'error', 'history::archive : ' . $e->getMessage());
		}
		try {
			network::cron();
		} catch (Exception $e) {
			log::add('network', 'error', 'network::cron : ' . $e->getMessage());
		}
		try {
			eqLogic::checkAlive();
			connection::cron();
			if (config::byKey('jeeNetwork::mode') != 'slave') {
				jeeNetwork::pull();
			}
		} catch (Exception $e) {

		}
		try {
			cmd::collect();
		} catch (Exception $e) {
			log::add('cmd', 'error', 'cmd::collect : ' . $e->getMessage());
		}
		try {
			history::historize();
		} catch (Exception $e) {
			log::add('history', 'error', 'history::archive : ' . $e->getMessage());
		}
	}

	public static function cron() {
		if (!self::isStarted()) {
			config::save('enableScenario', 1);
			config::save('enableCron', 1);
			$cache = cache::byKey('jeedom::usbMapping');
			$cache->remove();
			foreach (cron::all() as $cron) {
				if ($cron->running() && $cron->getClass() != 'jeedom' && $cron->getFunction() != 'cron') {
					try {
						$cron->halt();
					} catch (Exception $e) {

					}
				}
			}
			try {
				jeedom::start();
			} catch (Exception $e) {

			}
			try {
				plugin::start();
			} catch (Exception $e) {

			}
			touch('/tmp/jeedom_start');
			self::event('start');
			log::add('core', 'info', 'Démarrage de Jeedom OK');
		}
		self::isDateOk();
		try {
			$c = new Cron\CronExpression(config::byKey('update::check'), new Cron\FieldFactory);
			if ($c->isDue()) {
				$lastCheck = strtotime(config::byKey('update::lastCheck'));
				if ((strtotime('now') - $lastCheck) > 3600) {
					update::checkAllUpdate();
					$updates = update::byStatus('update');
					if (count($updates) > 0) {
						$toUpdate = '';
						foreach ($updates as $update) {
							$toUpdate .= $update->getLogicalId() . ',';
						}
					}
					if (config::byKey('update::auto') == 1) {
						if (count($updates) > 0) {
							message::add('update', __('J\'ai appliqué les mises à jour suivantes : ', __FILE__) . trim($toUpdate, ','), '', 'newUpdate');
						}
						jeedom::update('', 0);
					} else {
						$updates = update::byStatus('update');
						if (count($updates) > 0) {
							message::add('update', __('De nouvelles mises à jour sont disponibles : ', __FILE__) . trim($toUpdate, ','), '', 'newUpdate');
						}
					}
					config::save('update::check', rand(1, 59) . ' ' . rand(6, 7) . ' * * *');
				}
			}
			$c = new Cron\CronExpression('35 00 * * 0', new Cron\FieldFactory);
			if ($c->isDue()) {
				cache::clean();
				DB::optimize();
			}

			$c = new Cron\CronExpression('02 02 * * *', new Cron\FieldFactory);
			if ($c->isDue()) {
				try {
					log::chunk();
					cron::clean();
				} catch (Exception $e) {
					log::add('log', 'error', $e->getMessage());
				}
			}
			$c = new Cron\CronExpression('21 23 * * *', new Cron\FieldFactory);
			if ($c->isDue()) {
				try {
					scenario::cleanTable();
					user::cleanOutdatedUser();
					scenario::consystencyCheck();
				} catch (Exception $e) {
					log::add('scenario', 'error', $e->getMessage());
				}
			}
		} catch (Exception $e) {

		}
	}

	public static function checkOngoingThread($_cmd) {
		return shell_exec('ps ax | grep "' . $_cmd . '$" | grep -v "grep" | wc -l');
	}

	public static function retrievePidThread($_cmd) {
		return shell_exec('ps ax | grep "' . $_cmd . '$" | grep -v "grep" | awk \'{print $1}\'');
	}

	public static function getHardwareKey() {
		$return = config::byKey('jeedom::installKey');
		if ($return == '' || $return == '0a648c4a615e13680d2d6d23d15ea3b959d6ca30' || $return == '50967e5266d480d1363794dc6777fd0b5a6df30d') {
			$return = sha1(microtime() . config::genKey());
			config::save('jeedom::installKey', $return);
		}
		return $return;
	}

	public static function versionAlias($_version) {
		$alias = array(
			'mview' => 'mobile',
			'dview' => 'dashboard',
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
			$_input = scenarioExpression::setTags($_input);
			$result = evaluate($_input);
			if (is_bool($result) || is_numeric($result)) {
				return $result;
			}
			return $_input;
		} catch (Exception $exc) {
			return $_input;
		}
	}

	public static function haltSystem() {
		plugin::stop();
		exec('sudo shutdown -h now');
	}

	public static function rebootSystem() {
		plugin::stop();
		exec('sudo reboot');
	}

	public static function updateSystem() {
		log::clear('update');
		$cmd = 'sudo chown wwww-data:www-data ' . dirname(__FILE__) . '/../../install/update_system.sh;';
		$cmd .= 'sudo chmod +x ' . dirname(__FILE__) . '/../../install/update_system.sh;';
		$cmd .= 'sudo ' . dirname(__FILE__) . '/../../install/update_system.sh';
		$cmd .= ' >> ' . log::getPathToLog('update') . ' 2>&1 &';
		exec($cmd);
	}

	public static function forceSyncHour() {
		exec('sudo service ntp restart');
	}

	public static function checkFilesystem() {
		$result = exec('dmesg | grep "I/O error" | wc -l');
		if ($result != 0) {
			log::add('core', 'error', __('Erreur : corruption sur le filesystem detecter (I/O error sur dmesg)', __FILE__));
			return false;
		}
		return true;
	}

	public static function cleanFileSytemRight() {
		$processUser = posix_getpwuid(posix_geteuid());
		$processGroup = posix_getgrgid(posix_getegid());
		$user = $processUser['name'];
		$group = $processGroup['name'];
		$path = dirname(__FILE__) . '/../../';
		exec('sudo chown -R ' . $user . ':' . $group . ' ' . $path);
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

/*     * ******************harware restriction*************************** */

	public static function getHardwareName() {
		if (config::byKey('hardware_name') != '') {
			return config::byKey('hardware_name');
		}
		$result = 'DIY';
		$uname = shell_exec('uname -a');
		if (strpos($uname, 'cubox') !== false) {
			$result = 'Jeedomboard';
		} else if (file_exists('/.dockerinit')) {
			$result = 'Docker';
		} else if (file_exists('/usr/bin/raspi-config')) {
			$result = 'RPI/RPI2';
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

/*     * *********************Methode d'instance************************* */

/*     * **********************Getteur Setteur*************************** */
}

?>
