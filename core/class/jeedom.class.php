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
require_once __DIR__ . '/../../core/php/core.inc.php';
global $JEEDOM_INTERNAL_CONFIG;
class jeedom {
	/*     * *************************Attributs****************************** */

	private static $jeedomConfiguration;
	private static $jeedom_encryption = null;

	/*     * ***********************Methode static*************************** */

	public static function minify() {
		$folders = array('/../../desktop/js', '/../../core/js', '/../../mobile/js');
		foreach ($folders as $folder) {
			foreach (ls(__DIR__ . $folder, '*.jeemin.js') as $file) {
				unlink(__DIR__ . $folder . '/' . $file);
			}
			foreach (ls(__DIR__ . $folder, '*.js') as $file) {
				$path = __DIR__ . $folder . '/' . $file;
				$md5 = md5_file($path);
				$path_min =	__DIR__ . $folder . '/' . $md5 . '.' . translate::getLanguage() . '.jeemin.js';
				$tmp = '/tmp/jeedom/' . $file;
				file_put_contents($tmp, translate::exec(file_get_contents($path), $folder . $file, true));
				exec('python -m jsmin ' . $tmp . ' > ' . $path_min);
				unlink($tmp);
			}
		}
	}

	public static function getThemeConfig() {
		$key = array(
			'default_bootstrap_theme',
			'default_bootstrap_theme_night',
			'product_name',
			'product_icon',
			'product_image',
			'enableCustomCss',
			'mobile_theme_color',
			'mobile_theme_color_night',
			'theme_start_day_hour',
			'theme_end_day_hour',
			'theme_changeAccordingTime',
			'mobile_theme_useAmbientLight',
			'showBackgroundImg',
			'widget::step::width',
			'widget::step::height',
			'widget::margin',
			'widget::shadow',
			'interface::advance::enable',
			'interface::advance::coloredIcons',
			'interface::advance::coloredcats',
			'logo_light',
			'logo_dark',
			'logo_mobile_light',
			'logo_mobile_dark',
			'objectBackgroundBlur',
			'theme_displayAsTable',
			'interface::toast::position',
			'interface::toast::duration',
			'interface::background::dashboard',
			'interface::background::analysis',
			'interface::background::tools',
			'interface::background::opacitylight',
			'interface::background::opacitydark'
		);

		$return = config::byKeys($key);
		$return['current_desktop_theme'] = $return['default_bootstrap_theme'];
		$return['current_mobile_theme'] = $return['mobile_theme_color'];
		if ($return['theme_changeAccordingTime'] == 1 && (date('Gi') < intval(str_replace(':', '', $return['theme_start_day_hour'])) || date('Gi') > intval(str_replace(':', '', $return['theme_end_day_hour'])))) {
			$return['current_desktop_theme'] = $return['default_bootstrap_theme_night'];
			$return['current_mobile_theme'] = $return['mobile_theme_color_night'];
		}

		$css_convert = array();
		$return['css'] = array();
		if ($return['interface::advance::enable'] == 1) {
			$css_convert['css::background-opacity'] = '--opacity';
			$css_convert['css::border-radius'] = '--border-radius';
		}
		$css_convert['css::objectBackgroundBlur'] = '--objectBackgroundBlur';

		$css = config::byKeys(array_keys($css_convert));
		foreach ($css as $key => $value) {
			if ($value == '') {
				continue;
			}
			if (isset($css_convert[$key])) {
				$return['css'][$css_convert[$key]] = $value;
			}
		}
		if (count($return['css']) > 0) {
			foreach ($return['css'] as $key => &$value) {
				switch ($key) {
					case '--border-radius':
						if ($value == '') {
							$value = 0;
						} else if ($value > 1) {
							$value = 1;
						}
						$value .= 'rem';
						break;
					case '--objectBackgroundBlur':
						if ($value == '') {
							$value = 0;
						}
						$value .= 'px';
						break;
				}
			}
		}
		return $return;
	}

	public static function addRemoveHistory($_data) {
		try {
			$remove_history = array();
			if (file_exists(__DIR__ . '/../../data/remove_history.json')) {
				$remove_history = json_decode(file_get_contents(__DIR__ . '/../../data/remove_history.json'), true);
			}
			$remove_history[] = $_data;
			$remove_history = array_slice($remove_history, -200, 200);
			file_put_contents(__DIR__ . '/../../data/remove_history.json', json_encode($remove_history));
		} catch (Exception $e) {
		}
	}

	public static function deadCmd() {
		global $JEEDOM_INTERNAL_CONFIG;
		$return = array();
		$cmd = config::byKey('interact::warnme::defaultreturncmd', 'core', '');
		if ($cmd != '') {
			if (!cmd::byId(str_replace('#', '', $cmd))) {
				$return[] = array('detail' => __('Administration', __FILE__), 'help' => __('Commande retour interactions', __FILE__), 'who' => $cmd);
			}
		}
		foreach ($JEEDOM_INTERNAL_CONFIG['alerts'] as $level => $value) {
			$cmds = config::byKey('alert::' . $level . 'Cmd', 'core', '');
			preg_match_all("/#([0-9]*)#/", $cmds, $matches);
			foreach ($matches[1] as $cmd_id) {
				if (!cmd::byId($cmd_id)) {
					$return[] = array('detail' => __('Administration', __FILE__), 'help' => __('Commande sur', __FILE__) . ' ' . $value['name'], 'who' => '#' . $cmd_id . '#');
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
			'key' => 'uptodate'
		);

		$state = (config::byKey('enableCron', 'core', 1, true) != 0) ? true : false;
		$return[] = array(
			'name' => __('Cron actif', __FILE__),
			'state' => $state,
			'result' => ($state) ? __('OK', __FILE__) : __('NOK', __FILE__),
			'comment' => ($state) ? '' : __('Erreur cron : les crons sont désactivés. Allez dans Réglages -> Système -> Moteur de tâches pour les réactiver', __FILE__),
			'key' => 'cron::enable'
		);

		$state = (config::byKey('enableScenario') == 0 && count(scenario::all()) > 0) ? false : true;
		$return[] = array(
			'name' => __('Scénario actif', __FILE__),
			'state' => $state,
			'result' => ($state) ? __('OK', __FILE__) : __('NOK', __FILE__),
			'comment' => ($state) ? '' : __('Erreur scénario : tous les scénarios sont désactivés. Allez dans Outils -> Scénarios pour les réactiver', __FILE__),
			'key' => 'scenario::enable'
		);

		$state = self::isStarted();
		$return[] = array(
			'name' => __('Démarré', __FILE__),
			'state' => $state,
			'result' => ($state) ? __('OK', __FILE__) . ' ' . file_get_contents(self::getTmpFolder() . '/started') : __('NOK', __FILE__),
			'comment' => '',
			'key' => 'isStarted'
		);

		$state = self::isDateOk();
		$cache = cache::byKey('hour');
		$lastKnowDate = $cache->getValue();
		$return[] = array(
			'name' => __('Date système (dernière heure enregistrée)', __FILE__),
			'state' => $state,
			'result' => ($state) ? __('OK', __FILE__) . ' ' . date('Y-m-d H:i:s') . ' (' . gmdate('Y-m-d H:i:s', $lastKnowDate) . ')' : date('Y-m-d H:i:s'),
			'comment' => ($state) ? '' : __('Si la derniere heure enregistrée est fausse, il faut la remettre à zéro', __FILE__),
			'key' => 'hour'
		);

		$state = self::isCapable('sudo', true);
		$return[] = array(
			'name' => __('Droits sudo', __FILE__),
			'state' => ($state) ? 1 : 2,
			'result' => ($state) ? __('OK', __FILE__) : __('NOK', __FILE__),
			'comment' => ($state) ? '' : __('Appliquez les droits root à Jeedom', __FILE__),
			'key' => 'sudo::right'
		);

		$return[] = array(
			'name' => __('Version Jeedom', __FILE__),
			'state' => true,
			'result' => self::version(),
			'comment' => '',
			'key' => 'jeedom::version'
		);

		$return[] = array(
			'name' => __('Version OS', __FILE__),
			'state' => (system::getDistrib() != 'debian' || version_compare(system::getOsVersion(), '10', '>=')),
			'result' => system::getDistrib() . ' ' . system::getOsVersion(),
			'comment' => '',
			'key' => 'os::version'
		);

		$state = version_compare(phpversion(), '5.5', '>=');
		$return[] = array(
			'name' => __('Version PHP', __FILE__),
			'state' => $state,
			'result' => phpversion(),
			'comment' => ($state) ? '' : __('Si vous êtes en version 5.4.x on vous indiquera quand la version 5.5 sera obligatoire', __FILE__),
			'key' => 'php::version'
		);

		$apaches = count(system::ps('apache2'));
		$return[] = array(
			'name' => __('Apache', __FILE__),
			'state' => ($apaches > 0),
			'result' => $apaches,
			'comment' => '',
			'key' => 'apache'
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
			'comment' => ($state) ? '' : __('Vous n\'êtes pas sur un OS officiellement supporté par l\'équipe Jeedom (toute demande de support pourra donc être refusée). Les OS officiellement supportés sont Debian Strech et Debian Buster', __FILE__),
		);

		$version = DB::Prepare('select version()', array(), DB::FETCH_TYPE_ROW);
		$return[] = array(
			'name' => __('Version database', __FILE__),
			'state' => true,
			'result' => $version['version()'],
			'comment' => '',
			'key' => 'database::version'
		);

		$value = self::checkSpaceLeft();
		$return[] = array(
			'name' => __('Espace disque libre', __FILE__),
			'state' => ($value > 10),
			'result' => $value . ' %',
			'comment' => '',
			'key' => 'space::root'
		);

		$value = self::checkSpaceLeft(self::getTmpFolder());
		$return[] = array(
			'name' => __('Espace disque libre tmp', __FILE__),
			'state' => ($value > 10),
			'result' => $value . ' %',
			'comment' => ($value > 10) ? '' : __('En cas d\'erreur essayez de redémarrer. Si le problème persiste, testez en désactivant les plugins un à un jusqu\'à trouver le coupable', __FILE__),
			'key' => 'space::tmp'
		);

		$values = getSystemMemInfo();
		$value = round(($values['MemAvailable'] / $values['MemTotal']) * 100);
		$return[] = array(
			'name' => __('Mémoire disponible', __FILE__),
			'state' => ($value > 15),
			'result' => $value . ' % (' . __('Total', __FILE__) . ' ' . round($values['MemTotal'] / 1024) . ' Mo)',
			'comment' => '',
		);

		$value = shell_exec('sudo dmesg | grep oom | grep -v deprecated | wc -l');
		$return[] = array(
			'name' => __('Mémoire suffisante', __FILE__),
			'state' => ($value == 0),
			'result' => $value,
			'comment' => ($value == 0) ? '' : __('Nombre de processus tués par le noyau pour manque de mémoire. Votre système manque de mémoire. Essayez de reduire le nombre de plugins ou de scénarios', __FILE__),
		);

		$value = shell_exec('sudo dmesg | grep "CRC error" | grep "mmcblk0" | grep "card status" | wc -l');
		if (!is_numeric($value)) {
			$value = 0;
		}
		$value2 = @shell_exec('sudo dmesg | grep "I/O error" | wc -l');
		if (is_numeric($value2)) {
			$value += $value2;
		}
		$return[] = array(
			'name' => __('Erreur I/O', __FILE__),
			'state' => ($value == 0),
			'result' => $value,
			'comment' => ($value == 0) ? '' : __('Il y a des erreurs disque, cela peut indiquer un soucis avec le disque ou un problème d\'alimentation', __FILE__),
			'key' => 'io_error'
		);

		if ($values['SwapTotal'] != 0 && $values['SwapTotal'] !== null) {
			$value = round(($values['SwapFree'] / $values['SwapTotal']) * 100);
			$ok = ($value > 15);
			if ($ok && ($values['MemTotal']  + $values['SwapTotal']) < (1900 * 1024)) {
				$ok = false;
			}
			$return[] = array(
				'name' => __('Swap disponible', __FILE__),
				'state' => $ok,
				'result' => $value . ' % (' . __('Total', __FILE__) . ' ' . round($values['SwapTotal'] / 1024) . ' Mo)',
				'comment' => ($ok) ? '' : __('Le swap libre n\'est pas suffisant ou il y a moins de 2Go de mémoire sur le système et un swap inférieure à 1Go', __FILE__),
				'key' => 'swap'
			);
		} else {
			$return[] = array(
				'name' => __('Swap disponible', __FILE__),
				'state' => 2,
				'result' => __('Inconnue', __FILE__),
				'comment' => '',
				'key' => 'swap'
			);
		}

		$value = shell_exec('sudo cat /proc/sys/vm/swappiness');
		$ok = ($value <= 20);
		if ($values['MemTotal'] >= (1024 * 1024)) {
			$ok = true;
		}
		$return[] = array(
			'name' => __('Swapiness', __FILE__),
			'state' => $ok,
			'result' => $value . '%',
			'comment' => ($ok) ? '' : __('Pour des performances optimales le swapiness ne doit pas dépasser 20% si vous avez 1Go ou moins de mémoire', __FILE__),
			'key' => 'swapiness'
		);

		$values = sys_getloadavg();
		$return[] = array(
			'name' => __('Charge', __FILE__),
			'state' => ($values[2] < 20),
			'result' => $values[0] . ' - ' . $values[1] . ' - ' . $values[2],
			'comment' => '',
			'key' => 'load'
		);

		$state = network::test('internal');
		$return[] = array(
			'name' => __('Configuration réseau interne', __FILE__),
			'state' => $state,
			'result' => ($state) ? __('OK', __FILE__) : __('NOK', __FILE__),
			'comment' => ($state) ? '' : __('Allez sur Réglages -> Système -> Configuration -> onglet Réseaux, puis configurez correctement la partie réseau', __FILE__),
			'key' => 'network::internal'
		);

		$state = network::test('external');
		$return[] = array(
			'name' => __('Configuration réseau externe', __FILE__),
			'state' => $state,
			'result' => ($state) ? __('OK', __FILE__) : __('NOK', __FILE__),
			'comment' => ($state) ? '' : __('Allez sur Réglages -> Système -> Configuration -> onglet Réseaux, puis configurez correctement la partie réseau', __FILE__),
			'key' => 'network::external'
		);

		$cache_health = array('comment' => '', 'name' => __('Persistance du cache', __FILE__), 'key' => 'cache::persit');
		if (cache::isPersistOk()) {
			if (config::byKey('cache::engine') != 'FilesystemCache' && config::byKey('cache::engine') != 'PhpFileCache') {
				$cache_health['state'] = true;
				$cache_health['result'] = __('OK', __FILE__);
			} else {
				$filename = __DIR__ . '/../../cache.tar.gz';
				$cache_health['state'] = true;
				$cache_health['result'] = __('OK', __FILE__) . ' (' . date('Y-m-d H:i:s', filemtime($filename)) . ')';
			}
		} else {
			$cache_health['state'] = false;
			$cache_health['result'] = __('NOK', __FILE__);
			$cache_health['comment'] = __('Votre cache n\'est pas sauvegardé. En cas de redémarrage, certaines informations peuvent être perdues. Essayez de lancer (à partir du moteur de tâches) la tâche cache::persist.', __FILE__);
		}
		$return[] = $cache_health;

		$state = shell_exec('systemctl show apache2 | grep  PrivateTmp | grep yes | wc -l');
		$return[] = array(
			'name' => __('Apache private tmp', __FILE__),
			'state' => $state,
			'result' => ($state) ? __('OK', __FILE__) : __('NOK', __FILE__),
			'comment' => ($state) ? '' : __('Veuillez désactiver le private tmp d\'Apache (Jeedom ne peut marcher avec).', __FILE__) . '</a>',
			'key' => 'apache2::privateTmp'
		);

		foreach ((update::listRepo()) as $repo) {
			if (!$repo['enable']) {
				continue;
			}
			$class = $repo['class'];
			if (!class_exists($class) || !method_exists($class, 'health')) {
				continue;
			}
			$return += array_merge($return, $class::health());
		}

		return $return;
	}

	public static function sick() {
		$cmd = __DIR__ . '/../../sick.php';
		$cmd .= ' >> ' . log::getPathToLog('sick') . ' 2>&1';
		system::php($cmd);
	}

	public static function getApiKey($_plugin = 'core') {
		if ($_plugin == 'core') {
			if (config::byKey('api') == '') {
				config::save('api', config::genKey());
			}
			return config::byKey('api');
		}
		if ($_plugin == 'apipro') {
			if (config::byKey('apipro') == '') {
				config::save('apipro', config::genKey());
			}
			return config::byKey('apipro');
		}
		if ($_plugin == 'apitts') {
			if (config::byKey('apitts') == '') {
				config::save('apitts', config::genKey());
			}
			return config::byKey('apitts');
		}
		if ($_plugin == 'apimarket') {
			if (config::byKey('apimarket') == '') {
				config::save('apimarket', config::genKey());
			}
			return config::byKey('apimarket');
		}
		if (config::byKey('api', $_plugin) == '') {
			try {
				plugin::byId($_plugin);
			} catch (\Throwable $th) {
				return '';
			}
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
		if (trim($_apikey) == '' || strlen($_apikey) < 16) {
			return false;
		}
		$user = user::byHash($_apikey);
		if (is_object($user)) {
			if ($user->getEnable() == 0 || !self::apiModeResult($user->getOptions('api::mode', 'enable'))) {
				return false;
			}
			if ($user->getOptions('localOnly', 0) == 1 && !self::apiModeResult('whiteip')) {
				return false;
			}
			global $_USER_GLOBAL;
			$_USER_GLOBAL = $user;
			log::add('connection', 'info', __('Connexion par API de l\'utilisateur :', __FILE__) . ' ' . $user->getLogin());
			return true;
		}
		if (!self::apiModeResult(config::byKey('api::' . $_plugin . '::mode', 'core', 'enable'))) {
			return false;
		}
		$apikey = self::getApiKey($_plugin);
		if (trim($apikey) != '' && $apikey === $_apikey) {
			global $_RESTRICTED;
			$_RESTRICTED = config::byKey('api::' . $_plugin . '::restricted', 'core', 0);
			return true;
		}
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
				foreach (ls('/dev/', 'ttyAMA*') as $value) {
					$usbMapping['/dev/' . $value] = '/dev/' . $value;
				}
				if (file_exists('/dev/ttymxc0')) {
					$usbMapping['Jeedom board'] = '/dev/ttymxc0';
				}
				if (file_exists('/dev/S2')) {
					$usbMapping['Banana PI'] = '/dev/S2';
				}
				if (file_exists('/dev/ttyS2')) {
					$usbMapping['Jeedom Atlas'] = '/dev/ttyS2';
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
				if (file_exists('/dev/ttyAML1')) {
					$usbMapping['Odroid ARMBIAN (Buster)'] = '/dev/ttyAML1';
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
			$bluetoothMapping = self::getBluetoothMapping('');
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

	public static function consistency() {
		log::clear('consistency');
		$cmd = __DIR__ . '/../../install/consistency.php';
		$cmd .= ' >> ' . log::getPathToLog('consistency') . ' 2>&1 &';
		system::php($cmd, true);
	}

	/********************************************BACKUP*****************************************************************/

	public static function backup($_background = false) {
		if ($_background) {
			log::clear('backup');
			$cmd = __DIR__ . '/../../install/backup.php';
			$cmd .= ' >> ' . log::getPathToLog('backup') . ' 2>&1 &';
			system::php($cmd, true);
		} else {
			require_once __DIR__ . '/../../install/backup.php';
		}
	}

	public static function listBackup() {
		if (substr(config::byKey('backup::path'), 0, 1) != '/') {
			$backup_dir = __DIR__ . '/../../' . config::byKey('backup::path');
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
			throw new Exception(__('Impossible de trouver le fichier :', __FILE__) . ' ' . $_backup);
		}
	}

	public static function restore($_backup = '', $_background = false) {
		if ($_background) {
			log::clear('restore');
			$cmd = __DIR__ . '/../../install/restore.php "backup=' . $_backup . '"';
			$cmd .= ' >> ' . log::getPathToLog('restore') . ' 2>&1 &';
			system::php($cmd, true);
		} else {
			global $BACKUP_FILE;
			$BACKUP_FILE = $_backup;
			require_once __DIR__ . '/../../install/restore.php';
		}
	}

	/****************************UPDATE*****************************************************************/

	public static function update($_options = array()) {
		log::clear('update');
		$params = '';
		if (count($_options) > 0) {
			foreach ($_options as $key => $value) {
				$params .= '"' . $key . '"="' . $value . '" ';
			}
		}
		$cmd = __DIR__ . '/../../install/update.php ' . $params;
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
		if (file_exists(__DIR__ . '/../config/version')) {
			return trim(file_get_contents(__DIR__ . '/../config/version'));
		}
		return '';
	}

	/**********************START AND DATE MANAGEMENT*************************************************************/

	public static function stop() {
		echo "Disable all task";
		config::save('enableCron', 0);
		foreach ((cron::all()) as $cron) {
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

		/*         * **********arrêt des crons********************* */

		if (cron::jeeCronRun()) {
			echo "Stop cron master...";
			$pid = cron::getPidFile();
			system::kill($pid);
			echo " OK\n";
		}

		/*         * *********Arrêt des scénarios**************** */

		echo "Disable all scenario";
		config::save('enableScenario', 0);
		foreach ((scenario::all()) as $scenario) {
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
		$cache = cache::byKey('hour');
		$lastKnowDate = $cache->getValue();
		if ($lastKnowDate > strtotime('UTC')) {
			self::forceSyncHour();
			sleep(3);
			if ($lastKnowDate > strtotime('UTC')) {
				return false;
			}
		}
		$minDateValue = new \DateTime('2020-01-01');
		$mindate = strtotime($minDateValue->format('Y-m-d 00:00:00'));
		$maxDateValue = $minDateValue->modify('+6 year')->format('Y-m-d 00:00:00');
		$maxdate = strtotime($maxDateValue);
		if (strtotime('now') < $mindate || strtotime('now') > $maxdate) {
			self::forceSyncHour();
			sleep(3);
			if (strtotime('now') < $mindate || strtotime('now') > $maxdate) {
				log::add('core', 'error', __('La date du système est incorrecte (avant ' . $minDateValue . ' ou après ' . $maxDateValue . ') :', __FILE__) . ' ' . (new \DateTime())->format('Y-m-d H:i:s'), 'dateCheckFailed');
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
			foreach ((update::listRepo()) as $name => $repo) {
				$class = 'repo_' . $name;
				if (class_exists($class) && method_exists($class, 'cron5') && config::byKey($name . '::enable') == 1) {
					$class::cron5();
				}
			}
		} catch (Exception $e) {
			log::add('jeedom', 'error', $e->getMessage());
		} catch (Error $e) {
			log::add('jeedom', 'error', $e->getMessage());
		}
		try {
			eqLogic::checkAlive();
		} catch (Exception $e) {
		} catch (Error $e) {
		}
	}

	public static function cron10() {
		try {
			foreach ((update::listRepo()) as $name => $repo) {
				$class = 'repo_' . $name;
				if (class_exists($class) && method_exists($class, 'cron10') && config::byKey($name . '::enable') == 1) {
					$class::cron10();
				}
			}
		} catch (Exception $e) {
			log::add('jeedom', 'error', $e->getMessage());
		} catch (Error $e) {
			log::add('jeedom', 'error', $e->getMessage());
		}
	}

	public static function cron() {
		if (!self::isStarted()) {
			echo date('Y-m-d H:i:s') . ' starting Jeedom';
			log::add('starting', 'debug', __('Démarrage de jeedom', __FILE__));
			try {
				log::add('starting', 'debug', __('Arrêt des crons', __FILE__));
				foreach ((cron::all()) as $cron) {
					if ($cron->running() && $cron->getClass() != 'jeedom' && $cron->getFunction() != 'cron') {
						try {
							$cron->halt();
						} catch (Exception $e) {
							log::add('starting', 'error', __('Erreur sur l\'arrêt d\'une tâche cron :', __FILE__) . ' ' . log::exception($e));
						} catch (Error $e) {
							log::add('starting', 'error', __('Erreur sur l\'arrêt d\'une tâche cron :', __FILE__) . ' ' . log::exception($e));
						}
					}
				}
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur l\'arrêt des tâches crons :', __FILE__) . ' ' . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur l\'arrêt des tâches crons :', __FILE__) . ' ' . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Restauration du cache', __FILE__));
				cache::restore();
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur la restauration du cache :', __FILE__) . ' ' . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur la restauration du cache :', __FILE__) . ' ' . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Consolidation de l\'historique', __FILE__));
				history::checkCurrentValueAndHistory();
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur la consolidation de l\'historique :', __FILE__) . ' ' . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur la consolidation de l\'historique :', __FILE__) . ' ' . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Nettoyage du cache des péripheriques USB', __FILE__));
				$cache = cache::byKey('jeedom::usbMapping');
				$cache->remove();
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur le nettoyage du cache des péripheriques USB :', __FILE__) . ' ' . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur le nettoyage du cache des péripheriques USB :', __FILE__) . ' ' . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Nettoyage du cache des péripheriques Bluetooth', __FILE__));
				$cache = cache::byKey('jeedom::bluetoothMapping');
				$cache->remove();
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur le nettoyage du cache des péripheriques Bluetooth :', __FILE__) . ' ' . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur le nettoyage du cache des péripheriques Bluetooth :', __FILE__) . ' ' . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Démarrage des processus Internet de Jeedom', __FILE__));
				self::start();
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur le démarrage interne de Jeedom :', __FILE__) . ' ' . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur le démarrage interne de Jeedom :', __FILE__) . ' ' . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Ecriture du fichier', __FILE__) . ' ' . self::getTmpFolder() . '/started');
				if (file_put_contents(self::getTmpFolder() . '/started', date('Y-m-d H:i:s')) === false) {
					log::add('starting', 'error', __('Impossible d\'écrire', __FILE__) . ' ' . self::getTmpFolder() . '/started');
				}
			} catch (Exception $e) {
				log::add('starting', 'error', __('Impossible d\'écrire', __FILE__) . ' ' . self::getTmpFolder() . '/started : ' . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Impossible d\'écrire', __FILE__) . ' ' . self::getTmpFolder() . '/started : ' . log::exception($e));
			}

			if (!file_exists(self::getTmpFolder() . '/started')) {
				log::add('starting', 'critical', __('Impossible d\'écrire', __FILE__) . ' ' . self::getTmpFolder() . __('/started pour une raison inconnue. Jeedom ne peut démarrer', __FILE__));
				return;
			}

			try {
				log::add('starting', 'debug', __('Vérification de la configuration réseau interne', __FILE__));
				if (!network::test('internal')) {
					network::checkConf('internal');
				}
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur la configuration réseau interne :', __FILE__) . ' ' . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur la configuration réseau interne :', __FILE__) . ' ' . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Envoi de l\'événement de démarrage', __FILE__));
				self::event('start');
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur l\'envoi de l\'événement de démarrage :', __FILE__) . ' ' . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur l\'envoi de l\'événement de démarrage :', __FILE__) . ' ' . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Démarrage des plugins', __FILE__));
				plugin::start();
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur le démarrage des plugins :', __FILE__) . ' ' . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur la démarrage des plugins :', __FILE__) . ' ' . log::exception($e));
			}

			try {
				if (config::byKey('market::enable') == 1) {
					log::add('starting', 'debug', __('Test de connexion au market', __FILE__));
					repo_market::test();
				}
			} catch (Exception $e) {
				log::add('starting', 'error', __('Erreur sur la connexion au market :', __FILE__) . ' ' . log::exception($e));
			} catch (Error $e) {
				log::add('starting', 'error', __('Erreur sur la connexion au market :', __FILE__) . ' ' . log::exception($e));
			}
			log::add('starting', 'debug', __('Démarrage de jeedom fini avec succès', __FILE__));
		}
		self::isDateOk();
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
			listener::clean();
			user::regenerateHash();
		} catch (Exception $e) {
			log::add('jeedom', 'error', $e->getMessage());
		} catch (Error $e) {
			log::add('jeedom', 'error', $e->getMessage());
		}
		try {
			foreach ((update::listRepo()) as $name => $repo) {
				$class = 'repo_' . $name;
				if (class_exists($class) && method_exists($class, 'cronDaily') && config::byKey($name . '::enable') == 1) {
					$class::cronDaily();
				}
			}
		} catch (Exception $e) {
			log::add('jeedom', 'error', $e->getMessage());
		} catch (Error $e) {
			log::add('jeedom', 'error', $e->getMessage());
		}
	}

	public static function cronHourly() {
		try {
			cache::set('hour', strtotime('UTC'));
		} catch (Exception $e) {
			log::add('jeedom', 'error', $e->getMessage());
		} catch (Error $e) {
			log::add('jeedom', 'error', $e->getMessage());
		}
		try {
			//Check for updates every 24h according to config
			if (config::byKey('update::autocheck', 'core', 1) == 1 && (config::byKey('update::lastCheck') == '' || (strtotime('now') - strtotime(config::byKey('update::lastCheck'))) > (23 * 3600) || strtotime('now') < strtotime(config::byKey('update::lastCheck')))) {
				update::checkAllUpdate();
				$updates = update::byStatus('update');
				if (count($updates) > 0) {
					$toUpdate = '';
					foreach ($updates as $update) {
						if ($update->getConfiguration('doNotUpdate', 0) == 0) {
							$toUpdate .= $update->getLogicalId() . ',';
						}
					}
					if ($toUpdate != '') {
						//set $_logicalId so update function can remove such messages. Bypassed by message::save to notify different updates instead of new occurence.
						message::add('update', __('De nouvelles mises à jour sont disponibles', __FILE__) . ' : ' . trim($toUpdate, ','), '', 'newUpdate');
					}
				}
			}
		} catch (Exception $e) {
			log::add('jeedom', 'error', $e->getMessage());
		} catch (Error $e) {
			log::add('jeedom', 'error', $e->getMessage());
		}
		try {
			foreach ((update::listRepo()) as $name => $repo) {
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
			$datas = array_merge($datas, jeeObject::searchConfiguration($key));
			$datas = array_merge($datas, scenario::searchByUse(array(array('action' => $key))));
			$datas = array_merge($datas, scenarioExpression::searchExpression($key, $key, false));
			$datas = array_merge($datas, scenarioExpression::searchExpression('variable(' . str_replace('#', '', $key) . ')'));
			$datas = array_merge($datas, scenarioExpression::searchExpression('variable', str_replace('#', '', $key), true));
			$datas = array_merge($datas, scenarioExpression::searchExpression('genericType(' . str_replace('#', '', $key) . ')'));
			$datas = array_merge($datas, scenarioExpression::searchExpression('genericType', str_replace('#', '', $key), true));
			$datas = array_merge($datas, viewData::searchByConfiguration($key));
			$datas = array_merge($datas, plan::searchByConfiguration($key));
			$datas = array_merge($datas, plan3d::searchByConfiguration($key));
			$datas = array_merge($datas, listener::searchEvent($key));
			$datas = array_merge($datas, user::searchByOptions($key));
		}
		if (count($datas) > 0) {
			foreach ($datas as $data) {
				try {
					utils::a2o($data, json_decode(str_replace(array_keys($_replaces), $_replaces, json_encode(utils::o2a($data))), true));
					$data->save(true);
				} catch (\Exception $e) {
				}
			}
		}
		foreach ($_replaces as $key => $value) {
			$viewDatas = viewData::byTypeLinkId('cmd', str_replace('#', '', $key));
			if (count($viewDatas)  > 0) {
				foreach ($viewDatas as $viewData) {
					try {
						$viewData->setLink_id(str_replace('#', '', $value));
						$viewData->save();
					} catch (\Exception $e) {
					}
				}
			}
			$plans = plan::byLinkTypeLinkId('cmd', str_replace('#', '', $key));
			if (count($plans)  > 0) {
				foreach ($plans as $plan) {
					try {
						$plan->setLink_id(str_replace('#', '', $value));
						$plan->save();
					} catch (\Exception $e) {
					}
				}
			}
			$plan3ds = plan3d::byLinkTypeLinkId('cmd', str_replace('#', '', $key));
			if (count($plan3ds)  > 0) {
				foreach ($plan3ds as $plan3d) {
					try {
						$plan3d->setLink_id(str_replace('#', '', $value));
						$plan3d->save();
					} catch (\Exception $e) {
					}
				}
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
		if ($_version == 'mview') {
			return 'mobile';
		}
		if ($_version == 'dview' || $_version == 'dplan' || $_version == 'plan' || $_version == 'view') {
			return 'dashboard';
		}
		return $_version;
	}

	public static function toHumanReadable($_input) {
		return jeeObject::toHumanReadable(scenario::toHumanReadable(eqLogic::toHumanReadable(cmd::cmdToHumanReadable($_input))));
	}

	public static function fromHumanReadable($_input) {
		return jeeObject::fromHumanReadable(scenario::fromHumanReadable(eqLogic::fromHumanReadable(cmd::humanReadableToCmd($_input))));
	}

	public static function evaluateExpression($_input, $_scenario = null) {
		try {
			return evaluate(scenarioExpression::setTags($_input, $_scenario, true));
		} catch (Exception $exc) {
			return $_input;
		}
	}

	public static function calculStat($_calcul, $_values) {
		switch ($_calcul) {
			case 'sum':
				return array_sum($_values);
			case 'avg':
				return array_sum($_values) / count($_values);
			case 'text':
				return trim(implode(',', $_values), ',');
		}
		return null;
	}

	public static function getTypeUse($_string = '') {
		$return = array('cmd' => array(), 'scenario' => array(), 'eqLogic' => array(), 'dataStore' => array(), 'plan' => array(), 'plan3d' => array(), 'view' => array());
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
		preg_match_all('/"plan3d_id":"([0-9]*)"/', $_string, $matches);
		foreach ($matches[1] as $plan3d_id) {
			if (isset($return['plan3d'][$plan3d_id])) {
				continue;
			}
			$plan3d = plan3dHeader::byId($plan3d_id);
			if (!is_object($plan3d)) {
				continue;
			}
			$return['plan3d'][$plan3d_id] = $plan3d;
		}
		return $return;
	}

	public static function getRemovehistory() {
		if (file_exists(__DIR__ . '/../../data/remove_history.json')) {
			$remove_history = json_decode(file_get_contents(__DIR__ . '/../../data/remove_history.json'), true);
		}
		if (!is_array($remove_history)) {
			$remove_history = array();
		}
		return $remove_history;
	}

	/******************************SYSTEM MANAGEMENT**********************************************************/

	public static function haltSystem() {
		plugin::stop();
		cache::persist();
		if (self::isCapable('sudo')) {
			exec(system::getCmdSudo() . 'shutdown -fh now');
		} else {
			throw new Exception(__('Vous pouvez arrêter le système', __FILE__));
		}
	}

	public static function rebootSystem() {
		plugin::stop();
		cache::persist();
		if (self::isCapable('sudo')) {
			exec(system::getCmdSudo() . 'shutdown -fr now');
		} else {
			throw new Exception(__('Vous pouvez lancer le redémarrage du système', __FILE__));
		}
	}

	public static function forceSyncHour() {
		if (config::byKey('disable_ntp', 'core', 0) == 1) {
			return;
		}
		shell_exec(system::getCmdSudo() . 'service ntp stop;' . system::getCmdSudo() . 'ntpdate -s ' . config::byKey('ntp::optionalServer', 'core', '0.debian.pool.ntp.org') . ';' . system::getCmdSudo() . 'service ntp start');
	}

	public static function cleanDatabase() {
		log::clear('cleaningdb');
		$cmd = __DIR__ . '/../../install/cleaning.php';
		$cmd .= ' >> ' . log::getPathToLog('cleaningdb') . ' 2>&1 &';
		system::php($cmd, true);
	}

	public static function cleanFileSytemRight() {
		self::cleanFileSystemRight();
	}

	public static function cleanFileSystemRight() {
		$cmd = system::getCmdSudo() . 'chown -R ' . system::get('www-uid') . ':' . system::get('www-gid') . ' ' . __DIR__ . '/../../*;';
		$cmd .= system::getCmdSudo() . 'chmod 775 -R ' . __DIR__ . '/../../*;';
		$cmd .= system::getCmdSudo() . 'find ' . __DIR__ . '/../../log -type f -exec chmod 665 {} +;';
		$cmd .= system::getCmdSudo() . 'chmod 775 -R ' . __DIR__ . '/../../.* ;';
		exec($cmd);
	}

	public static function checkSpaceLeft($_dir = null) {
		if ($_dir == null) {
			$path = __DIR__ . '/../../';
		} else {
			$path = $_dir;
		}
		return round(disk_free_space($path) / disk_total_space($path) * 100);
	}

	public static function getTmpFolder($_plugin = null) {
		$return = '/' . trim(config::byKey('folder::tmp'), '/');
		if ($_plugin !== null) {
			$return .= '/' . $_plugin;
		}
		if (!file_exists($return)) {
			mkdir($return, 0774, true);
			$cmd = system::getCmdSudo() . 'chown -R ' . system::get('www-uid') . ':' . system::get('www-gid') . ' ' . $return . ';';
			com_shell::execute($cmd);
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
		$result = 'diy';
		$uname = shell_exec('uname -a');
		if (file_exists('/.dockerinit')) {
			$result = 'docker';
		} else if (file_exists('/usr/bin/raspi-config')) {
			$result = 'rpi';
			$hardware_revision = strtolower(shell_exec('cat /proc/cpuinfo | grep Revision'));
			global $JEEDOM_RPI_HARDWARE;
			foreach ($JEEDOM_RPI_HARDWARE as $key => $values) {
				foreach ($values as $value) {
					if (strpos($hardware_revision, $value) !== false) {
						$result = $key;
					}
				}
			}
		} else if (strpos($uname, 'cubox') !== false || strpos($uname, 'imx6') !== false) {
			$result = 'miniplus';
		} else if (file_exists('/usr/bin/grille-pain')) {
			$result = 'freeboxDelta';
		} else if (file_exists('/media/boot/multiboot/meson64_odroidc2.dtb.linux')) {
			$result = 'smart';
		} else if (file_exists('/etc/update-motd.d/10-armbian-header-jeedomatlas')) {
			$result = 'Atlas';
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
			cache::set('jeedom::isCapable::sudo', $result, 3600 * 24);
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
