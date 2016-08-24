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
require_once dirname(__FILE__) . '/../php/core.inc.php';

class translate {
	/*     * *************************Attributs****************************** */

	protected static $translation;
	protected static $language;
	private static $config = null;

	/*     * ***********************Methode static*************************** */

	public static function getConfig($_key, $_default = '') {
		if (self::$config == null) {
			self::$config = config::byKeys(array('language', 'generateTranslation'));
		}
		if (isset(self::$config[$_key])) {
			return self::$config[$_key];
		}
		return $_default;
	}

	public static function getTranslation() {
		if (!isset(static::$translation) || !isset(static::$translation[self::getLanguage()])) {
			static::$translation = array(
				self::getLanguage() => self::loadTranslation(),
			);
		}
		return static::$translation[self::getLanguage()];
	}

	public static function sentence($_content, $_name, $_backslash = false) {
		return self::exec("{{" . $_content . "}}", $_name, $_backslash);
	}

	public static function exec($_content, $_name = '', $_backslash = false) {
		if ($_content == '' || $_name == '') {
			return '';
		}
		$language = self::getLanguage();

		if ($language == 'fr_FR' && self::getConfig('generateTranslation') != 1) {
			return preg_replace("/{{(.*?)}}/s", '$1', $_content);
		}
		if (substr($_name, 0, 1) == '/') {
			if (strpos($_name, 'plugins') !== false) {
				$_name = substr($_name, strpos($_name, 'plugins'));
			} else {
				if (strpos($_name, 'core') !== false) {
					$_name = substr($_name, strpos($_name, 'core'));
				}
				if (strpos($_name, 'install') !== false) {
					$_name = substr($_name, strpos($_name, 'install'));
				}
			}
		}
		$modify = false;
		$translate = self::getTranslation();
		$replace = array();
		preg_match_all("/{{(.*?)}}/s", $_content, $matches);
		foreach ($matches[1] as $text) {
			if (trim($text) == '') {
				$replace["{{" . $text . "}}"] = $text;
			}
			if (isset($translate[$_name]) && isset($translate[$_name][$text])) {
				$replace["{{" . $text . "}}"] = $translate[$_name][$text];
			}
			if (!isset($replace["{{" . $text . "}}"]) && isset($translate['common']) && isset($translate['common'][$text])) {
				$replace["{{" . $text . "}}"] = $translate['common'][$text];
			}
			if (!isset($replace["{{" . $text . "}}"])) {
				if (strpos($_name, '#') === false) {
					$modify = true;
					if (!isset($translate[$_name])) {
						$translate[$_name] = array();
					}
					$translate[$_name][$text] = $text;
				}
			}
			if ($_backslash && isset($replace["{{" . $text . "}}"])) {
				$replace["{{" . $text . "}}"] = str_replace("'", "\'", str_replace("\'", "'", $replace["{{" . $text . "}}"]));
			}
			if (!isset($replace["{{" . $text . "}}"]) || is_array($replace["{{" . $text . "}}"])) {
				$replace["{{" . $text . "}}"] = $text;
			}
		}
		if ($language == 'fr_FR' && $modify) {
			static::$translation[self::getLanguage()] = $translate;
			self::saveTranslation($language);
		}
		return str_replace(array_keys($replace), $replace, $_content);
	}

	public static function getPathTranslationFile($_language) {
		return dirname(__FILE__) . '/../i18n/' . $_language . '.json';
	}

	public static function loadTranslation() {
		$return = array();
		$filename = self::getPathTranslationFile(self::getLanguage());
		if (file_exists($filename)) {
			$return = file_get_contents($filename);
			$return = is_json($return) ? json_decode($return, true) : array();
			foreach (plugin::listPlugin(false, false, false) as $plugin) {
				$return = array_merge($return, $plugin->getTranslation(self::getLanguage()));
			}
		}
		return $return;
	}

	public static function saveTranslation() {
		$core = array();
		$plugins = array();
		foreach (self::getTranslation(self::getLanguage()) as $page => $translation) {
			if (strpos($page, 'plugins/') === false) {
				$core[$page] = $translation;
			} else {
				$plugin = substr($page, strpos($page, 'plugins/') + 8);
				$plugin = substr($plugin, 0, strpos($plugin, '/'));
				if (!isset($plugins[$plugin])) {
					$plugins[$plugin] = array();
				}
				$plugins[$plugin][$page] = $translation;
			}
		}
		file_put_contents(self::getPathTranslationFile(self::getLanguage()), json_encode($core, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
		foreach ($plugins as $plugin_name => $translation) {
			try {
				$plugin = plugin::byId($plugin_name);
				$plugin->saveTranslation(self::getLanguage(), $translation);
			} catch (Exception $e) {

			} catch (Error $e) {

			}
		}
	}

	public static function getLanguage() {
		return self::getConfig('language', 'fr_FR');
	}

	/*     * *********************Methode d'instance************************* */
}

function __($_content, $_name, $_backslash = false) {
	return translate::sentence($_content, $_name, $_backslash = false);
}

?>
