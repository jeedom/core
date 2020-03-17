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
require_once __DIR__ . '/../php/core.inc.php';

class translate {
	/*     * *************************Attributs****************************** */
	
	protected static $translation = array();
	protected static $language = null;
	private static $config = null;
	private static $pluginLoad = array();
	
	/*     * ***********************Methode static*************************** */
	
	public static function getConfig($_key, $_default = '') {
		if (self::$config === null) {
			self::$config = config::byKeys(array('language'));
		}
		if (isset(self::$config[$_key])) {
			return self::$config[$_key];
		}
		return $_default;
	}
	
	public static function getTranslation($_plugin) {
		if (!isset(self::$translation[self::getLanguage()])) {
			self::$translation[self::getLanguage()] = array();
		}
		if (!isset(self::$pluginLoad[$_plugin])) {
			self::$pluginLoad[$_plugin] = true;
			self::$translation[self::getLanguage()] = array_merge(self::$translation[self::getLanguage()], self::loadTranslation($_plugin));
		}
		return self::$translation[self::getLanguage()];
	}
	
	public static function sentence($_content, $_name, $_backslash = false) {
		return self::exec("{{" . $_content . "}}", $_name, $_backslash);
	}
	
	public static function getPluginFromName($_name) {
		if (strpos($_name, 'plugins/') === false) {
			return 'core';
		}
		preg_match_all('/plugins\/(.*?)\//m', $_name, $matches, PREG_SET_ORDER, 0);
		if(isset($matches[0]) && isset($matches[0][1])){
			return $matches[0][1];
		}
		if (!isset($matches[1])) {
			return 'core';
		}
		return $matches[1];
	}
	
	public static function exec($_content, $_name = '', $_backslash = false) {
		if ($_content == '' || $_name == '') {
			return $_content;
		}
		$language = self::getLanguage();
		
		if ($language == 'fr_FR') {
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
		$translate = self::getTranslation(self::getPluginFromName($_name));
		$replace = array();
		preg_match_all("/{{(.*?)}}/s", $_content, $matches);
		foreach ($matches[1] as $text) {
			if (trim($text) == '') {
				$replace["{{" . $text . "}}"] = $text;
			}
			if (isset($translate[$_name]) && isset($translate[$_name][$text]) && $translate[$_name][$text] != '') {
				$replace["{{" . $text . "}}"] = $translate[$_name][$text];
			}else if(strpos($text,"'") !== false && isset($translate[$_name]) && isset($translate[$_name][str_replace("'","\'",$text)]) && $translate[$_name][str_replace("'","\'",$text)] != ''){
				$replace["{{" . $text . "}}"] = ltrim($translate[$_name][str_replace("'","\'",$text)],'##');
            		}
			if (!isset($replace["{{" . $text . "}}"]) && isset($translate['common']) && isset($translate['common'][$text])) {
				$replace["{{" . $text . "}}"] = $translate['common'][$text];
			}
			if (!isset($replace["{{" . $text . "}}"])) {
				if (strpos($_name, '#') === false) {
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
		return str_replace(array_keys($replace), $replace, $_content);
	}
	
	public static function getPathTranslationFile($_language) {
		return __DIR__ . '/../i18n/' . $_language . '.json';
	}
	
	public static function loadTranslation($_plugin = null) {
		$return = array();
		if ($_plugin == null || $_plugin == 'core') {
			$filename = self::getPathTranslationFile(self::getLanguage());
			if (file_exists($filename)) {
				$content = file_get_contents($filename);
				$return = is_json($content, array());
			}
		}
		if ($_plugin == null) {
			foreach (plugin::listPlugin(true, false, false, true) as $plugin) {
				$return = array_merge($return, plugin::getTranslation($plugin, self::getLanguage()));
			}
		} else {
			$return = array_merge($return, plugin::getTranslation($_plugin, self::getLanguage()));
		}
		
		return $return;
	}
	
	public static function getLanguage() {
		if (self::$language == null) {
			self::$language = self::getConfig('language', 'fr_FR');
		}
		return self::$language;
		
	}
	
	public static function setLanguage($_langage) {
		self::$language = $_langage;
	}
	
	/*     * *********************Methode d'instance************************* */
}

function __($_content, $_name, $_backslash = false) {
	return translate::sentence($_content, $_name, $_backslash);
}
