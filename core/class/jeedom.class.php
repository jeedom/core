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
global $JEEDOM_INTERNAL_CONFIG;
class jeedom {
	/*     * *************************Attributs****************************** */

	private static $jeedomConfiguration;

	/*     * ***********************Methode static*************************** */

	public static function addTimelineEvent($_event) {
		file_put_contents(dirname(__FILE__) . '/../../data/timeline.json', json_encode($_event) . "\n", FILE_APPEND);
	}

	public static function getTimelineEvent() {
		$path = dirname(__FILE__) . '/../../data/timeline.json';
		if (!file_exists($path)) {
			return array();
		}
		com_shell::execute(system::getCmdSudo() . 'chmod 777 ' . $path . ' > /dev/null 2>&1;echo "$(tail -n ' . config::byKey('timeline::maxevent') . ' ' . $path . ')" > ' . $path);
		$lines = explode("\n", trim(file_get_contents($path)));
		$result = array();
		foreach ($lines as $line) {
			$result[] = json_decode($line, true);
		}
		return $result;
	}

	public static function removeTimelineEvent() {
		$path = dirname(__FILE__) . '/../../data/timeline.json';
		com_shell::execute(system::getCmdSudo() . 'chmod 777 ' . $path . ' > /dev/null 2>&1;');
		unlink($path);
	}

	public static function deadCmd() {
		global $JEEDOM_INTERNAL_CONFIG;
		$return = array();
		$cmd = config::byKey('interact::warnme::defaultreturncmd', 'core', '');
		if ($cmd != '') {
			if (!cmd::byId(str_replace('#', '', $cmd))) {
				$return[] = array('detail' => 'Administration', 'help' => 'Commande retour interactions', 'who' => $cmd);
			}
		}
		$cmd = config::byKey('emailAdmin', 'core', '');
		if ($cmd != '') {
			if (!cmd::byId(str_replace('#', '', $cmd))) {
				$return[] = array('detail' => 'Administration', 'help' => 'Commande information utilisateur', 'who' => $cmd);
			}
		}
		foreach ($JEEDOM_INTERNAL_CONFIG['alerts'] as $level => $value) {
			$cmds = config::byKey('alert::' . $level . 'Cmd', 'core', '');
			preg_match_all("/#([0-9]*)#/", $cmds, $matches);
			foreach ($matches[1] as $cmd_id) {
				if (!cmd::byId($cmd_id)) {
					$return[] = array('detail' => 'Administration', 'help' => 'Commande sur ' . $value['name'], 'who' => '#' . $cmd_id . '#');
				}
			}
		}
		return $return;
	}

	public static function health() {
		$return = array();
		$nbNeedUpdate = update::nbNeedUpdate();
		$state = ($nbNeedUpdate == 0) ? true : false;
		$return[] = array(
			'name' => __('Système à jour', __FILE__),
			'state' => $state,
			'result' => ($state) ? __('OK', __FILE__) : $nbNeedUpdate,
			'comment' => '',
		);

		$state = (config::byKey('enableCron', 'core', 1, true) != 0) ? true : false;
		$return[] = array(
			'name' => __('Cron actif', __FILE__),
			'state' => $state,
			'result' => ($state) ? __('OK', __FILE__) : __('NOK', __FILE__),
			'comment' => ($state) ? '' : __('Erreur cron : les crons sont désactivés. Allez dans Administration -> Moteur de tâches pour les réactiver', __FILE__),
		);

		$state = (config::byKey('enableScenario') == 0 && count(scenario::all()) > 0) ? false : true;
		$return[] = array(
			'name' => __('Scénario actif', __FILE__),
			'state' => $state,
			'result' => ($state) ? __('OK', __FILE__) : __('NOK', __FILE__),
			'comment' => ($state) ? '' : __('Erreur scénario : tous les scénarios sont désactivés. Allez dans Outils -> Scénarios pour les réactiver', __FILE__),
		);

		$state = self::isStarted();
		$return[] = array(
			'name' => __('Démarré', __FILE__),
			'state' => $state,
			'result' => ($state) ? __('OK', __FILE__) . ' (' . file_get_contents(self::getTmpFolder() . '/started') . ')' : __('NOK', __FILE__),
			'comment' => '',
		);

		$state = self::isDateOk();
		$return[] = array(
			'name' => __('Date système', __FILE__),
			'state' => $state,
			'result' => ($state) ? __('OK', __FILE__) : date('Y-m-d H:i:s'),
			'comment' => '',
		);

		$state = !user::hasDefaultIdentification();
		$return[] = array(
			'name' => __('Authentification par défaut', __FILE__),
			'state' => $state,
			'result' => ($state) ? __('OK', __FILE__) : __('NOK', __FILE__),
			'comment' => ($state) ? '' : __('Attention vous avez toujours l\'utilisateur admin/admin de configuré, cela représente une grave faille de sécurité, aller <a href=\'index.php?v=d&p=user\'>ici</a> pour modifier le mot de passe de l\'utilisateur admin', __FILE__),
		);

		$state = self::isCapable('sudo', true);
		$return[] = array(
			'name' => __('Droits sudo', __FILE__),
			'state' => ($state) ? 1 : 2,
			'result' => ($state) ? __('OK', __FILE__) : __('NOK', __FILE__),
			'comment' => ($state) ? '' : __('Appliquer <a href="https://www.jeedom.com/doc/documentation/installation/fr_FR/doc-installation.html#_etape_4_définition_des_droits_root_à_jeedom" targe="_blank">cette étape</a> de l\'installation', __FILE__),
		);

		$return[] = array(
			'name' => __('Version Jeedom', __FILE__),
			'state' => true,
			'result' => self::version(),
			'comment' => '',
		);

		$state = version_compare(phpversion(), '5.5', '>=');
		$return[] = array(
			'name' => __('Version PHP', __FILE__),
			'state' => $state,
			'result' => phpversion(),
			'comment' => ($state) ? '' : __('Si vous êtes en version 5.4.x on vous indiquera quand la version 5.5 sera obligatoire', __FILE__),
		);

		$state = true;
		$version = '';
		$uname = shell_exec('uname -a');
		if (system::getDistrib() != 'debian') {
			$state = false;
		} else {
			$version = trim(strtolower(file_get_contents('/etc/debian_version')));
			if (version_compare($version, '8', '<')) {
				if (strpos($version, 'jessie') === false && strpos($version, 'stretch') === false) {
					$state = false;
				}
			}
		}
		$return[] = array(
			'name' => __('Version OS', __FILE__),
			'state' => $state,
			'result' => ($state) ? $uname . ' [' . $version . ']' : $uname,
			'comment' => ($state) ? '' : __('Vous n\'êtes pas sur un OS officiellement supporté par l\'équipe Jeedom (toute demande de support pourra donc être refusée). Les OS officiellement supporté sont Debian Jessie et Debian Strech (voir <a href="https://github.com/jeedom/documentation/blob/master/compatibility/fr_FR/software.asciidoc" target="_blank">ici</a>)', __FILE__),
		);

		$version = DB::Prepare('select version()', array(), DB::FETCH_TYPE_ROW);
		$return[] = array(
			'name' => __('Version database', __FILE__),
			'state' => true,
			'result' => $version['version()'],
			'comment' => '',
		);

		$value = self::checkSpaceLeft();
		$return[] = array(
			'name' => __('Espace disque libre', __FILE__),
			'state' => ($value > 10),
			'result' => $value . ' %',
			'comment' => '',
		);

		$values = getSystemMemInfo();
		$value = round(($values['MemAvailable'] / $values['MemTotal']) * 100);
		$return[] = array(
			'name' => __('Mémoire disponible', __FILE__),
			'state' => ($value > 15),
			'result' => $value . ' %',
			'comment' => '',
		);

		if ($values['SwapTotal'] != 0 && $values['SwapTotal'] != null) {
			$value = round(($values['SwapFree'] / $values['SwapTotal']) * 100);
			$return[] = array(
				'name' => __('Swap disponible', __FILE__),
				'state' => ($value > 15),
				'result' => $value . ' %',
				'comment' => '',
			);
		} else {
			$return[] = array(
				'name' => __('Swap disponible', __FILE__),
				'state' => 2,
				'result' => __('Inconnue', __FILE__),
				'comment' => '',
			);
		}

		$values = sys_getloadavg();
		$return[] = array(
			'name' => __('Charge', __FILE__),
			'state' => ($values[2] < 20),
			'result' => $values[0] . ' - ' . $values[1] . ' - ' . $values[2],
			'comment' => '',
		);

		$state = network::test('internal');
		$return[] = array(
			'name' => __('Configuration réseau interne', __FILE__),
			'state' => $state,
			'result' => ($state) ? __('OK', __FILE__) : __('NOK', __FILE__),
			'comment' => ($state) ? '' : __('Allez sur Administration -> Configuration puis configurez correctement la partie réseau', __FILE__),
		);

		$state = network::test('external');
		$return[] = array(
			'name' => __('Configuration réseau externe', __FILE__),
			'state' => $state,
			'result' => ($state) ? __('OK', __FILE__) : __('NOK', __FILE__),
			'comment' => ($state) ? '' : __('Allez sur Administration -> Configuration puis configurez correctement la partie réseau', __FILE__),
		);

		$cache_health = array('comment' => '', 'name' => __('Persistance du cache', __FILE__));
		if (cache::isPersistOk()) {
			if (config::byKey('cache::engine') != 'FilesystemCache' && config::byKey('cache::engine') != 'PhpFileCache') {
				$cache_health['state'] = true;
				$cache_health['result'] = __('OK', __FILE__);
			} else {
				$filename = dirname(__FILE__) . '/../../cache.tar.gz';
				$cache_health['state'] = true;
				$cache_health['result'] = __('OK', __FILE__) . ' (' . date('Y-m-d H:i:s', filemtime($filename)) . ')';
			}
		} else {
			$cache_health['state'] = false;
			$cache_health['result'] = __('NOK', __FILE__);
			$cache_health['comment'] = __('Votre cache n\'est pas sauvegardé. En cas de redémarrage, certaines informations peuvent être perdues. Essayez de lancer (à partir du moteur de tâches) la tâche cache::persist.', __FILE__);
			$state = network::test('external');
		}
		$return[] = $cache_health;

		$state = (count(ls('/tmp/', '*private*apache*')) == 0);
		$return[] = array(
			'name' => __('Private tmp', __FILE__),
			'state' => $state,
			'result' => ($state) ? __('OK', __FILE__) : __('NOK', __FILE__),
			'comment' => ($state) ? '' : 'https://github.com/jeedom/documentation/blob/master/howto/fr_FR/debian.trucs_et_astuces.asciidoc#private-tmp',
		);

		return $return;
	}

	public static function sick() {
		$cmd = dirname(__FILE__) . '/../../sick.php';
		$cmd .= ' >> ' . log::getPathToLog('sick') . ' 2>&1';
		system::php($cmd);
	}

	public static function getApiKey($_plugin = 'core') {
		if ($_plugin == 'proapi') {
			if (config::byKey('proapi') == '') {
				config::save('proapi', config::genKey());
			}
			return config::byKey('proapi');
		}
		if (config::byKey('api', $_plugin) == '') {
			config::save('api', config::genKey(), $_plugin);
		}
		return config::byKey('api', $_plugin);
	}

	public static function apiModeResult($_mode = 'enable') {
		switch ($_mode) {
			case 'disable':
				return false;
			case 'whiteip':
				$ip = getClientIp();
				$find = false;
				$whiteIps = explode(';', config::byKey('security::whiteips'));
				if (config::byKey('security::whiteips') != '' && count($whiteIps) > 0) {
					foreach ($whiteIps as $whiteip) {
						if (netMatch($whiteip, $ip)) {
							$find = true;
						}
					}
					if (!$find) {
						return false;
					}
				}
				break;
			case 'localhost':
				if (getClientIp() != '127.0.0.1') {
					return false;
				}
				break;
		}
		return true;
	}

	public static function apiAccess($_apikey = '', $_plugin = 'core') {
		if (trim($_apikey) == '') {
			sleep(5);
			return false;
		}
		if ($_plugin != 'core' && $_plugin != 'proapi' && !self::apiModeResult(config::byKey('api::' . $_plugin . '::mode', 'core', 'enable'))) {
			sleep(5);
			return false;
		}
		$apikey = self::getApiKey($_plugin);

		if (trim($apikey) != '' && $apikey == $_apikey) {
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
			if ($user->getOptions('localOnly', 0) == 1 && !self::apiModeResult('whiteip')) {
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
		if (!self::isStarted()) {
			return false;
		}
		if (!self::isDateOk()) {
			return false;
		}
		if (config::byKey('enableScenario') == 0 && count(scenario::all()) > 0) {
			return false;
		}
		if (!self::isCapable('sudo')) {
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
			$cmd = dirname(__FILE__) . '/../../install/restore.php "backup=' . $_backup . '"';
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
		$cmd = dirname(__FILE__) . '/../../install/install.php "mode=' . $_mode . '" "level=' . $_level . '" "version=' . $_version . '" "onlyThisVersion=' . $__onlyThisVersion . '"';
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
		echo "Disable all task";
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
			echo "Stop cron master...";
			$pid = cron::getPidFile();
			system::kill($pid);
			echo " OK\n";
		}

		/*         * *********Arrêt des scénarios**************** */

		echo "Disable all scenario";
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
			echo "Enable scenario : ";
			config::save('enableScenario', 1);
			echo "OK\n";
			/*             * *********Réactivation des tâches**************** */
			echo "Enable task : ";
			config::save('enableCron', 1);
			echo "OK\n";
		} catch (Exception $e) {
			if (!isset($_GET['mode']) || $_GET['mode'] != 'force') {
				throw $e;
			} else {
				echo '***ERROR*** ' . $e->getMessage();
			}
		} catch (Error $e) {
			if (!isset($_GET['mode']) || $_GET['mode'] != 'force') {
				throw $e;
			} else {
				echo '***ERROR*** ' . $e->getMessage();
			}
		}
	}

	public static function isStarted() {
		return file_exists(self::getTmpFolder() . '/started');
	}

	/**
	 *
	 * @return boolean
	 */
	public static function isDateOk() {
		if (config::byKey('ignoreHourCheck') == 1) {
			return true;
		}
		$minDateValue = new \DateTime('2017-01-01');
		$mindate = strtotime($minDateValue->format('Y-m-d 00:00:00'));
		$maxDateValue = $minDateValue->modify('+6 year')->format('Y-m-d 00:00:00');
		$maxdate = strtotime($maxDateValue);

		if (strtotime('now') < $mindate || strtotime('now') > $maxdate) {
			self::forceSyncHour();
			sleep(3);
			if (strtotime('now') < $mindate || strtotime('now') > $maxdate) {
				log::add('core', 'error', __('La date du système est incorrect (avant ' . $minDateValue . ' ou après ' . $maxDateValue . ') : ', __FILE__) . (new \DateTime())->format('Y-m-d H:i:s'), 'dateCheckFailed');
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
				self::start();
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur le démarrage interne de jeedom : ', __FILE__) . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur le démarrage interne de jeedom : ', __FILE__) . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Ecriture du fichier ', __FILE__) . self::getTmpFolder() . '/started');
				if (file_put_contents(self::getTmpFolder() . '/started', date('Y-m-d H:i:s')) === false) {
					log::add('starting', 'error', __('Impossible d\'écrire ' . self::getTmpFolder() . '/started', __FILE__));
				}
			} catch (Exception $e) {
				log::add('starting', 'error', __('Impossible d\'écrire ' . self::getTmpFolder() . '/started : ', __FILE__) . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Impossible d\'écrire ' . self::getTmpFolder() . '/started : ', __FILE__) . log::exception($e));
			}

			if (!file_exists(self::getTmpFolder() . '/started')) {
				log::add('starting', 'critical', __('Impossible d\'écrire ' . self::getTmpFolder() . '/started pour une raison inconnue. Jeedom ne peut démarrer', __FILE__));
				return;
			}

			try {
				log::add('starting', 'debug', __('Vérification de la configuration réseau interne', __FILE__));
				if (!network::test('internal')) {
					network::checkConf('internal');
				}
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur la configuration réseau interne : ', __FILE__) . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur la configuration réseau interne : ', __FILE__) . log::exception($e));
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
			report::clean();
			DB::optimize();
			cache::clean();
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
			$datas = array_merge($datas, object::searchConfiguration($key));
			$datas = array_merge($datas, scenario::searchByUse(array(array('action' => '#' . $key . '#'))));
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

	public static function getTypeUse($_string = '') {
		$return = array('cmd' => array(), 'scenario' => array(), 'eqLogic' => array(), 'dataStore' => array(), 'plan' => array(), 'view' => array());
		preg_match_all("/#([0-9]*)#/", $_string, $matches);
		foreach ($matches[1] as $cmd_id) {
			if (isset($return['cmd'][$cmd_id])) {
				continue;
			}
			$cmd = cmd::byId($cmd_id);
			if (!is_object($cmd)) {
				continue;
			}
			$return['cmd'][$cmd_id] = $cmd;
		}
		preg_match_all('/"scenario_id":"([0-9]*)"/', $_string, $matches);
		foreach ($matches[1] as $scenario_id) {
			if (isset($return['scenario'][$scenario_id])) {
				continue;
			}
			$scenario = scenario::byId($scenario_id);
			if (!is_object($scenario)) {
				continue;
			}
			$return['scenario'][$scenario_id] = $scenario;
		}
		preg_match_all("/#scenario([0-9]*)#/", $_string, $matches);
		foreach ($matches[1] as $scenario_id) {
			if (isset($return['scenario'][$scenario_id])) {
				continue;
			}
			$scenario = scenario::byId($scenario_id);
			if (!is_object($scenario)) {
				continue;
			}
			$return['scenario'][$scenario_id] = $scenario;
		}
		preg_match_all("/#eqLogic([0-9]*)#/", $_string, $matches);
		foreach ($matches[1] as $eqLogic_id) {
			if (isset($return['eqLogic'][$eqLogic_id])) {
				continue;
			}
			$eqLogic = eqLogic::byId($eqLogic_id);
			if (!is_object($eqLogic)) {
				continue;
			}
			$return['eqLogic'][$eqLogic_id] = $eqLogic;
		}
		preg_match_all('/"eqLogic":"([0-9]*)"/', $_string, $matches);
		foreach ($matches[1] as $eqLogic_id) {
			if (isset($return['eqLogic'][$eqLogic_id])) {
				continue;
			}
			$eqLogic = eqLogic::byId($eqLogic_id);
			if (!is_object($eqLogic)) {
				continue;
			}
			$return['eqLogic'][$eqLogic_id] = $eqLogic;
		}
		preg_match_all('/variable\((.*?)\)/', $_string, $matches);
		foreach ($matches[1] as $variable) {
			if (isset($return['dataStore'][$variable])) {
				continue;
			}
			$dataStore = dataStore::byTypeLinkIdKey('scenario', -1, trim($variable));
			if (!is_object($dataStore)) {
				continue;
			}
			$return['dataStore'][$variable] = $dataStore;
		}
		preg_match_all('/"view_id":"([0-9]*)"/', $_string, $matches);
		foreach ($matches[1] as $view_id) {
			if (isset($return['view'][$view_id])) {
				continue;
			}
			$view = view::byId($view_id);
			if (!is_object($view)) {
				continue;
			}
			$return['view'][$view_id] = $view;
		}
		preg_match_all('/"plan_id":"([0-9]*)"/', $_string, $matches);
		foreach ($matches[1] as $plan_id) {
			if (isset($return['plan'][$plan_id])) {
				continue;
			}
			$plan = planHeader::byId($plan_id);
			if (!is_object($plan)) {
				continue;
			}
			$return['plan'][$plan_id] = $plan;
		}
		return $return;
	}

	/******************************SYSTEM MANAGEMENT**********************************************************/

	public static function haltSystem() {
		plugin::stop();
		cache::persist();
		if (self::isCapable('sudo')) {
			exec(system::getCmdSudo() . 'shutdown -h now');
		} else {
			throw new Exception(__('Vous pouvez arreter le système', __FILE__));
		}
	}

	public static function rebootSystem() {
		plugin::stop();
		cache::persist();
		if (self::isCapable('sudo')) {
			exec(system::getCmdSudo() . 'reboot');
		} else {
			throw new Exception(__('Vous pouvez lancer le reboot du système', __FILE__));
		}
	}

	public static function forceSyncHour() {
		shell_exec(system::getCmdSudo() . 'service ntp stop;' . system::getCmdSudo() . 'ntpdate -s ' . config::byKey('ntp::optionalServer', 'core', '0.debian.pool.ntp.org') . ';' . system::getCmdSudo() . 'service ntp start');
	}

	public static function cleanFileSytemRight() {
		$processUser = posix_getpwuid(posix_geteuid());
		$processGroup = posix_getgrgid(posix_getegid());
		$path = dirname(__FILE__) . '/../../*';
		exec(system::getCmdSudo() . 'chown -R ' . $processUser['name'] . ':' . $processGroup['name'] . ' ' . $path . ';' . system::getCmdSudo() . 'chmod 775 -R ' . $path);
	}

	public static function checkSpaceLeft() {
		$path = dirname(__FILE__) . '/../../';
		return round(disk_free_space($path) / disk_total_space($path) * 100);
	}

	public static function getTmpFolder($_plugin = null) {
		$return = '/' . trim(config::byKey('folder::tmp'), '/');
		if ($_plugin !== null) {
			$return .= '/' . $_plugin;
		}
		if (!file_exists($return)) {
			mkdir($return, 0777, true);
		}
		return $return;
	}

/*     * ******************hardware management*************************** */

	public static function getHardwareKey() {
		$return = config::byKey('jeedom::installKey');
		if ($return == '') {
			$return = substr(sha512(microtime() . config::genKey()), 0, 63);
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
		} else if (strpos($uname, 'cubox') !== false || strpos($uname, 'imx6') !== false || file_exists('/media/boot/multiboot/meson64_odroidc2.dtb.linux')) {
			$result = 'Jeedomboard';
		}
		config::save('hardware_name', $result);
		return config::byKey('hardware_name');
	}

	public static function isCapable($_function, $_forceRefresh = false) {
		global $JEEDOM_COMPATIBILIY_CONFIG;
		if ($_function == 'sudo') {
			if (!$_forceRefresh) {
				$cache = cache::byKey('jeedom::isCapable::sudo');
				if ($cache->getValue(0) == 1) {
					return true;
				}
			}
			$result = (shell_exec('sudo -l > /dev/null 2>&1; echo $?') == 0) ? true : false;
			cache::set('jeedom::isCapable::sudo', $result);
			return $result;
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
                	SET `value`=:value
                	WHERE `key`="jeedom_benchmark"
                		AND plugin="core"';
			try {
				DB::Prepare($sql, array('value' => $i), DB::FETCH_TYPE_ROW);
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
