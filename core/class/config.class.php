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

class config {
	/*     * *************************Attributs****************************** */

	private static $defaultConfiguration = array();
	private static $cache = array();

	/*     * ***********************Methode static*************************** */

	public static function getDefaultConfiguration($_plugin = 'core') {
		if (!isset(self::$defaultConfiguration[$_plugin])) {
			if ($_plugin == 'core') {
				self::$defaultConfiguration[$_plugin] = parse_ini_file(dirname(__FILE__) . '/../../core/config/default.config.ini', true);
			} else {
				$filename = dirname(__FILE__) . '/../../plugins/' . $_plugin . '/core/config/' . $_plugin . '.config.ini';
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
	 * @param string $_key nom de la clef
	 * @param string $_value valeur de la clef
	 * @return boolean vrai si ok faux sinon
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
		$values = array(
			'plugin' => $_plugin,
			'key' => $_key,
			'value' => $_value,
		);
		$sql = 'REPLACE config
                SET `key`=:key,
                    `value`=:value,
                     plugin=:plugin';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
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
			$defaultConfiguration = self::getDefaultConfiguration($_plugin);
			if (isset($defaultConfiguration[$_plugin][$_key])) {
				self::$cache[$_plugin . '::' . $_key] = $defaultConfiguration[$_plugin][$_key];
			}
			if ($_default !== '') {
				self::$cache[$_plugin . '::' . $_key] = $_default;
			}
		} else {
			if (is_json($value['value'])) {
				$value['value'] = json_decode($value['value'], true);
			}
			self::$cache[$_plugin . '::' . $_key] = $value['value'];
		}
		return isset(self::$cache[$_plugin . '::' . $_key]) ? self::$cache[$_plugin . '::' . $_key] : '';
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
			if (is_json($result['value'])) {
				$result['value'] = json_decode($result['value'], true);
			}
		}
		return $results;
	}

	public static function genKey($_car = 48) {
		$key = '';
		$chaine = "abcdefghijklmnpqrstuvwxy1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		for ($i = 0; $i < $_car; $i++) {
			$key .= $chaine[random_int(0, strlen($chaine) - 1)];
		}
		return $key;
	}

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */
}

?>
