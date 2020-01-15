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

	/*     * ***********************Methode static*************************** */

	public static function getDefaultConfiguration($_plugin = 'core') {
		if (!isset(self::$defaultConfiguration[$_plugin])) {
			if ($_plugin == 'core') {
				self::$defaultConfiguration[$_plugin] = parse_ini_file(__DIR__ . '/../../core/config/default.config.ini', true);
				if (file_exists(__DIR__ . '/../../data/custom/custom.config.ini')) {
					$custom =  parse_ini_file(__DIR__ . '/../../data/custom/custom.config.ini', true);
					self::$defaultConfiguration[$_plugin]['core'] = array_merge(self::$defaultConfiguration[$_plugin]['core'],$custom['core']);
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
	 * Ajoute une clef à la config
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

		$function = 'preConfig_' . str_replace(array('::', ':'), '_', $_key);
		if (method_exists($class, $function)) {
			$_value = $class::$function($_value);
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
	}

	/**
	 * Supprime une clef de la config
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
			return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
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
	}

	/**
	 * Retourne la valeur d'une clef
	 * @param string $_key nom de la clef dont on veut la valeur
	 * @return string valeur de la clef
	 */
	public static function byKey($_key, $_plugin = 'core', $_default = '', $_forceFresh = false) {
		if (!$_forceFresh && isset(self::$cache[$_plugin . '::' . $_key])) {
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
		if ($value['value'] === '' || $value['value'] === null) {
			if ($_default !== '') {
				self::$cache[$_plugin . '::' . $_key] = $_default;
			} else {
				$defaultConfiguration = self::getDefaultConfiguration($_plugin);
				if (isset($defaultConfiguration[$_plugin][$_key])) {
					self::$cache[$_plugin . '::' . $_key] = $defaultConfiguration[$_plugin][$_key];
				}
			}
		} else {
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
			$return[$value['key']] = $value['value'];
		}
		$defaultConfiguration = self::getDefaultConfiguration($_plugin);
		foreach ($_keys as $key) {
			if (isset($return[$key])) {
				$return[$key] = is_json($return[$key], $return[$key]);
			} elseif (isset($defaultConfiguration[$_plugin][$key])) {
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
			$result['value'] = is_json($result['value'], $result['value']);
		}
		return $results;
	}

	public static function genKey($_car = 32) {
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

	/*     * *********************Action sur config************************* */

	public static function postConfig_market_allowDNS($_value) {
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

	public static function preConfig_market_password($_value) {
		if (!is_sha1($_value)) {
			return sha1($_value);
		}
		return $_value;
	}
	
	public static function preConfig_info_latitude($_value){
		return str_replace(',','.',$_value);
	}
	
	public static function preConfig_info_longitude($_value){
		return str_replace(',','.',$_value);
	}

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */
}
