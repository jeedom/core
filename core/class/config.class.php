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

class config {
	/*     * *************************Attributs****************************** */

	private static $defaultConfiguration = array();
	private static $cache = array();
	private static $encryptKey = array('apipro', 'apitts', 'apimarket', 'samba::backup::password', 'samba::backup::ip', 'samba::backup::username', 'ldap:password', 'ldap:host', 'ldap:username', 'dns::token', 'api');
	private static $nocache = array('enableScenario');

	/*     * ***********************Methode static*************************** */

	public static function getDefaultConfiguration($_plugin = 'core') {
		if (!isset(self::$defaultConfiguration[$_plugin])) {
			if ($_plugin == 'core') {
				self::$defaultConfiguration[$_plugin] = parse_ini_file(__DIR__ . '/../../core/config/default.config.ini', true);
				if (file_exists(__DIR__ . '/../../data/custom/custom.config.ini')) {
					$custom =  parse_ini_file(__DIR__ . '/../../data/custom/custom.config.ini', true);
					self::$defaultConfiguration[$_plugin]['core'] = array_merge(self::$defaultConfiguration[$_plugin]['core'], $custom['core']);
				}
			} else {
				$filename = __DIR__ . '/../../plugins/' . $_plugin . '/core/config/' . $_plugin . '.config.ini';
				if (file_exists($filename)) {
					self::$defaultConfiguration[$_plugin] = parse_ini_file($filename, true);
				}
			}
		}
		if (!isset(self::$defaultConfiguration[$_plugin])) {
			self::$defaultConfiguration[$_plugin] = array();
		}
		return self::$defaultConfiguration[$_plugin];
	}
	/**
	 * Save key to config
	 * @param string $_key
	 * @param string | object | array $_value
	 * @param string $_plugin
	 * @return boolean
	 */
	public static function save($_key, $_value, $_plugin = 'core') {
		if (is_object($_value) || is_array($_value)) {
			$_value = json_encode($_value, JSON_UNESCAPED_UNICODE);
		}
		if (isset(self::$cache[$_plugin . '::' . $_key])) {
			unset(self::$cache[$_plugin . '::' . $_key]);
		}
		$defaultConfiguration = self::getDefaultConfiguration($_plugin);
		if (isset($defaultConfiguration[$_plugin][$_key]) && $_value == $defaultConfiguration[$_plugin][$_key]) {
			self::remove($_key, $_plugin);
			return true;
		}
		if ($_plugin == 'core') {
			$jeedomConfig = jeedom::getConfiguration($_key, true);
			if ($jeedomConfig != '' && $jeedomConfig == $_value) {
				self::remove($_key);
				return true;
			}
		}

		$class = ($_plugin == 'core') ? 'config' : $_plugin;

		$function = 'preConfig_' . str_replace(array('::', ':', '-'), '_', $_key);
		if (method_exists($class, $function)) {
			$_value = $class::$function($_value);
		}
		if ($_plugin == 'core' && in_array($_key, self::$encryptKey)) {
			$_value = utils::encrypt($_value);
		} else if ($_plugin != 'core' && class_exists($class) && property_exists($class, '_encryptConfigKey') && in_array($_key, $class::$_encryptConfigKey)) {
			$_value = utils::encrypt($_value);
		} else if ($_key == 'api') {
			$_value = utils::encrypt($_value);
		}
		$values = array(
			'plugin' => $_plugin,
			'key' => $_key,
			'value' => $_value,
		);
		$sql = 'REPLACE config
		SET `key`=:key,
		`value`=:value,
		plugin=:plugin';
		DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);

		$function = 'postConfig_' . str_replace(array('::', ':'), '_', $_key);
		if (method_exists($class, $function)) {
			$class::$function($_value);
		}
		return true;
	}

	/**
	 * Delete key from config
	 * @param string $_key nom de la clef à supprimer
	 * @return boolean vrai si ok faux sinon
	 */
	public static function remove($_key, $_plugin = 'core') {
		if ($_key == "*" && $_plugin != 'core') {
			$values = array(
				'plugin' => $_plugin,
			);
			$sql = 'DELETE FROM config
			WHERE plugin=:plugin';
			DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
		} else {
			$values = array(
				'plugin' => $_plugin,
				'key' => $_key,
			);
			$sql = 'DELETE FROM config
			WHERE `key`=:key
			AND plugin=:plugin';
			DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
			if (isset(self::$cache[$_plugin . '::' . $_key])) {
				unset(self::$cache[$_plugin . '::' . $_key]);
			}
		}
		return true;
	}

	/**
	 * Get config by key
	 * @param string $_key nom de la clef dont on veut la valeur
	 * @return string valeur de la clef
	 */
	public static function byKey($_key, $_plugin = 'core', $_default = '', $_forceFresh = false) {
		if (!$_forceFresh && isset(self::$cache[$_plugin . '::' . $_key]) && !in_array($_key, self::$nocache)) {
			return self::$cache[$_plugin . '::' . $_key];
		}
		$values = array(
			'plugin' => $_plugin,
			'key' => $_key,
		);
		$sql = 'SELECT `value`
		FROM config
		WHERE `key`=:key
		AND plugin=:plugin';
		$value = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
		if (!is_array($value) || !isset($value['value']) || $value['value'] === '' || $value['value'] === null) {
			$defaultConfiguration = self::getDefaultConfiguration($_plugin);
			if (isset($defaultConfiguration[$_plugin][$_key])) {
				$defaultConfiguration[$_plugin][$_key] = is_json($defaultConfiguration[$_plugin][$_key], $defaultConfiguration[$_plugin][$_key]);
				self::$cache[$_plugin . '::' . $_key] = $defaultConfiguration[$_plugin][$_key];
			} else if ($_default !== '') {
				self::$cache[$_plugin . '::' . $_key] = $_default;
			}
		} else {
			if ($_plugin == 'core' && in_array($_key, self::$encryptKey)) {
				$value['value'] = utils::decrypt($value['value']);
			} else	if ($_plugin != 'core' && class_exists($_plugin) && property_exists($_plugin, '_encryptConfigKey') && in_array($_key, $_plugin::$_encryptConfigKey)) {
				$value['value'] = utils::decrypt($value['value']);
			} else if ($_key == 'api') {
				$value['value'] = utils::decrypt($value['value']);
			}
			self::$cache[$_plugin . '::' . $_key] = is_json($value['value'], $value['value']);
		}
		return isset(self::$cache[$_plugin . '::' . $_key]) ? self::$cache[$_plugin . '::' . $_key] : '';
	}

	public static function byKeys($_keys, $_plugin = 'core', $_default = '') {
		if (!is_array($_keys) || count($_keys) == 0) {
			return array();
		}
		$values = array(
			'plugin' => $_plugin,
		);
		$keys = '(\'' . implode('\',\'', $_keys) . '\')';
		$sql = 'SELECT `key`,`value`
		FROM config
		WHERE `key` IN ' . $keys . '
		AND plugin=:plugin';
		$values = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
		$return = array();
		foreach ($values as $value) {
			if ($_plugin == 'core' && in_array($value['key'], self::$encryptKey)) {
				$value['value'] = utils::decrypt($value['value']);
			} else	if ($_plugin != 'core' && class_exists($_plugin) && property_exists($_plugin, '_encryptConfigKey') && in_array($value['key'], $_plugin::$_encryptConfigKey)) {
				$value['value'] = utils::decrypt($value['value']);
			} else if ($value['key'] == 'api') {
				$value['key'] = utils::decrypt($value['key']);
			}
			$return[$value['key']] = $value['value'];
		}
		$defaultConfiguration = self::getDefaultConfiguration($_plugin);
		foreach ($_keys as $key) {
			if (isset($return[$key])) {
				$return[$key] = is_json($return[$key], $return[$key]);
			} elseif (isset($defaultConfiguration[$_plugin][$key])) {
				$defaultConfiguration[$_plugin][$key] = is_json($defaultConfiguration[$_plugin][$key], $defaultConfiguration[$_plugin][$key]);
				$return[$key] = $defaultConfiguration[$_plugin][$key];
			} else {
				if (is_array($_default)) {
					if (isset($_default[$key])) {
						$return[$key] = $_default[$key];
					} else {
						$return[$key] = '';
					}
				} else {
					$return[$key] = $_default;
				}
			}
			self::$cache[$_plugin . '::' . $key] = $return[$key];
		}
		return $return;
	}

	public static function searchKey($_key, $_plugin = 'core') {
		$values = array(
			'plugin' => $_plugin,
			'key' => '%' . $_key . '%',
		);
		$sql = 'SELECT *
		FROM config
		WHERE `key` LIKE :key
		AND plugin=:plugin';
		$results = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
		foreach ($results as &$result) {
			if ($_plugin == 'core' && in_array($result['key'], self::$encryptKey)) {
				$result['value'] = utils::decrypt($result['value']);
			} else	if ($_plugin != 'core' && class_exists($_plugin) && property_exists($_plugin, '_encryptConfigKey') && in_array($result['key'], $_plugin::$_encryptConfigKey)) {
				$result['value'] = utils::decrypt($result['value']);
			} else if ($result['key'] == 'api') {
				$result['value'] = utils::decrypt($result['value']);
			}
			$result['value'] = is_json($result['value'], $result['value']);
		}
		return $results;
	}

	public static function genKey($_car = 64) {
		$key = '';
		$chaine = "abcdefghijklmnpqrstuvwxy1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		for ($i = 0; $i < $_car; $i++) {
			if (function_exists('random_int')) {
				$key .= $chaine[random_int(0, strlen($chaine) - 1)];
			} else {
				$key .= $chaine[rand(0, strlen($chaine) - 1)];
			}
		}
		return $key;
	}

	public static function getPluginEnable() {
		$sql = 'SELECT `value`,`plugin`
		FROM config
		WHERE `key`=\'active\'';
		$values = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
		$return = array();
		foreach ($values as $value) {
			$return[$value['plugin']] = $value['value'];
		}
		return $return;
	}

	public static function getLogLevelPlugin() {
		$sql = 'SELECT `value`,`key`
		FROM config
		WHERE `key` LIKE \'log::level::%\'';
		$values = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
		$return = array();
		foreach ($values as $value) {
			$return[$value['key']] = is_json($value['value'], $value['value']);
		}
		return $return;
	}

	public static function getGenericTypes($_coreOnly = false) {
		$types = array(
			'byType',
			'byFamily
		'
		);

		foreach ((jeedom::getConfiguration('cmd::generic_type')) as $key => $info) {
			$types['byType'][$key] = $info;
			$types['byFamily'][$info['familyid']] = $info['family'];
		}

		if (!$_coreOnly) {
			foreach (plugin::listPlugin(true) as $plugin) {
				if (method_exists($plugin->getId(), 'pluginGenericTypes')) {
					try {
						$generics = $plugin->getId()::pluginGenericTypes();
						foreach ($generics as $key => $info) {
							//check data:
							if (!isset($info['familyid']) || !isset($info['family']) || !isset($info['name']) || !isset($info['type'])) {
								unset($generics[$key]);
								continue;
							}
							//Do not overide Core Family/id:
							if (!isset($types['byFamily'][$info['familyid']])) {
								$types['byFamily'][$info['familyid']] = $info['family'];
							} else {
								$generics[$key]['family'] = $types['byFamily'][$info['familyid']];
							}
						}
						$types['byType'] = array_merge($types['byType'], $generics);
					} catch (Exception $e) {
					}
				}
			}
		}
		asort($types['byFamily'], SORT_STRING | SORT_FLAG_CASE);
		return $types;
	}

	/*     * *********************Generic check value************************* */

	public static function checkValueBetween($_value, $_min = null, $_max = null) {
		if ($_min !== null && $_value < $_min) {
			return $_min;
		}
		if ($_max !== null && $_value > $_max) {
			return $_max;
		}
		if (is_nan($_value) || $_value === '') {
			return ($_min !== 0) ? $_min : 0;
		}
		return $_value;
	}

	/*     * *********************Action sur config************************* */

	public static function postConfig_market_allowDns($_value) {
		if ($_value == 1) {
			if (!network::dns_run()) {
				network::dns_start();
			}
		} else {
			if (network::dns_run()) {
				network::dns_stop();
			}
		}
	}

	public static function postConfig_interface_advance_vertCentering($_value) {
		cache::flushWidget();
	}

	public static function postConfig_theme_start_day_hour($_value) {
		event::add('checkThemechange', array('theme_start_day_hour' => $_value));
	}
	public static function postConfig_theme_end_day_hour($_value) {
		event::add('checkThemechange', array('theme_end_day_hour' => $_value));
	}

	public static function postConfig_object_summary($_value) {
		try {
			foreach (jeeObject::all() as $object) {
				$object->setChanged(true);
				$object->cleanSummary();
			}

			//force refresh all summaries:
			$global = array();
			$objects = jeeObject::all(true);
			foreach ($objects as $object) {
				$summaries = $object->getConfiguration('summary');
				if (!is_array($summaries)) continue;
				$event = array('object_id' => $object->getId(), 'keys' => array(), 'force' => 1);
				foreach ($summaries as $key => $summary) {
					$value = $object->getSummary($key);
					$event['keys'][$key] = array('value' => $value);
					$global[$key] = 1;
				}
				$events[] = $event;
			}
			if (count($global) > 0) {
				$event = array('object_id' => 'global', 'keys' => array(), 'force' => 1);
				foreach ($global as $key => $value) {
					try {
						$result = jeeObject::getGlobalSummary($key);
						if ($result === null) continue;
						$event['keys'][$key] = array('value' => $result);
					} catch (Exception $e) {
					}
				}
				$events[] = $event;
			}
			if (count($events) > 0) {
				event::adds('jeeObject::summary::update', $events);
			}
		} catch (\Exception $e) {
		}
	}

	public static function preConfig_historyArchivePackage($_value) {
		return self::checkValueBetween($_value, 1);
	}

	public static function preConfig_historyArchiveTime($_value) {
		return self::checkValueBetween($_value, 2);
	}

	public static function preConfig_market_password($_value) {
		if (!is_sha1($_value)) {
			return sha1($_value);
		}
		return $_value;
	}

	public static function preConfig_widget_margin($_value) {
		return self::checkValueBetween($_value, 0);
	}

	public static function preConfig_widget_step_width($_value) {
		return self::checkValueBetween($_value, 1);
	}

	public static function preConfig_widget_step_height($_value) {
		return self::checkValueBetween($_value, 1);
	}

	public static function preConfig_css_background_opacity($_value) {
		return self::checkValueBetween($_value, 0, 1);
	}

	public static function preConfig_css_border_radius($_value) {
		return self::checkValueBetween($_value, 0, 1);
	}

	public static function preConfig_name($_value) {
		return str_replace(array('\\', '/', "'", '"'), '', $_value);
	}

	public static function preConfig_info_latitude($_value) {
		return str_replace(',', '.', $_value);
	}

	public static function preConfig_info_longitude($_value) {
		return str_replace(',', '.', $_value);
	}

	public static function preConfig_tts_engine($_value) {
		try {
			if ($_value != config::byKey('tts::engine')) {
				rrmdir(jeedom::getTmpFolder('tts'));
			}
		} catch (\Exception $e) {
		}
		return $_value;
	}

	/*     * *********************Stats************************************* */
	public static function getHistorizedCmdNum() {
		$sql = 'SELECT COUNT(*) FROM `cmd` WHERE `isHistorized` = 1';
		$result = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
		return $result[0]['COUNT(*)'];
	}

	public static function getTimelinedCmdNum() {
		$sql = 'SELECT COUNT(*) FROM `cmd` WHERE `configuration` LIKE \'%"timeline::enable":"1"%\'';
		$result = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
		return $result[0]['COUNT(*)'];
	}

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */
}
