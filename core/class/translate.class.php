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

/*
//DEBUG ONLY
require_once  'utils.class.php';
require_once  'scenarioExpression.class.php';
require_once  'log.class.php';
require_once  'message.class.php';

//log::add('debug_translate', 'error', 'loadTranslation: '.json_encode($test));
*/

class translate {
	/*     * *************************Attributs****************************** */

	protected static array $translation = array();
	protected static ?string $language = null;
	private static ?string $config = null;
	private static array $pluginLoad = array();
	private static array $widgetLoad = array();

	/*     * ***********************Methode static*************************** */

    /**
     * @param $_key
     * @param string $_default
     * @return string
     * @throws Exception
     */
    public static function getConfig($_key, $_default = ''): string
    {
		if (self::$config === null) {
			self::$config = config::byKeys(array('language'));
		}
		if (isset(self::$config[$_key])) {
			return self::$config[$_key];
		}
		return $_default;
	}

    /**
     * @param $_plugin
     * @return array
     * @throws Exception
     */
    public static function getTranslation($_plugin): array
    {
		if (!isset(self::$translation[self::getLanguage()])) {
			self::$translation[self::getLanguage()] = array();
		}
		if (!isset(self::$pluginLoad[$_plugin])) {
			self::$pluginLoad[$_plugin] = true;
			self::$translation[self::getLanguage()] = array_merge(self::$translation[self::getLanguage()], self::loadTranslation($_plugin));
		}
		return self::$translation[self::getLanguage()];
	}

    /**
     * @param $_widget
     * @return mixed
     * @throws Exception
     */
    public static function getWidgetTranslation($_widget) {
		if (!isset(self::$translation[self::getLanguage()]['core/template/widgets.html'])) {
			self::$translation[self::getLanguage()]['core/template/widgets.html'] = array();
		}
		if (!isset(self::$widgetLoad[$_widget])) {
			self::$widgetLoad[$_widget][$_widget] = array_merge(self::$translation[self::getLanguage()]['core/template/widgets.html'], self::loadTranslation($_widget));
		}
		return self::$widgetLoad[$_widget];
	}

    /**
     * @param $_content
     * @param $_name
     * @param false $_backslash
     * @return string|string[]|null
     */
    public static function sentence($_content, $_name, $_backslash = false) {
		return self::exec("{{" . $_content . "}}", $_name, $_backslash);
	}

    /**
     * @param $_name
     * @return string
     */
    public static function getPluginFromName($_name): string
    {
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

    /**
     * @param $_content
     * @param string $_name
     * @param false $_backslash
     * @return string|string[]|null
     * @throws Exception
     */
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

		//is a custom user widget:
		if (substr($_name, 0, 12) == 'customtemp::') {
			$translate = self::getWidgetTranslation($_name);
			if (empty($translate[$_name])) {
				return preg_replace("/{{(.*?)}}/s", '$1', $_content);
			}
		} else {
			$translate = self::getTranslation(self::getPluginFromName($_name));
		}

		//replacing {{content parts}} by $translate parts:
		$replace = array();
		preg_match_all("/{{(.*?)}}/s", $_content, $matches);
		foreach ($matches[1] as $text) {
			if (trim($text) == '') {
				$replace['{{' . $text . '}}'] = $text;
			}
			if (isset($translate[$_name]) && isset($translate[$_name][$text]) && $translate[$_name][$text] != '') {
				$replace['{{' . $text . '}}'] = ltrim($translate[$_name][$text],'##');
			}else if(strpos($text,"'") !== false && isset($translate[$_name]) && isset($translate[$_name][str_replace("'","\'",$text)]) && $translate[$_name][str_replace("'","\'",$text)] != ''){
				$replace["{{" . $text . "}}"] = ltrim($translate[$_name][str_replace("'","\'",$text)],'##');
			}
			if (!isset($replace['{{' . $text . '}}']) && isset($translate['common']) && isset($translate['common'][$text])) {
				$replace['{{' . $text . '}}'] = $translate['common'][$text];
			}
			if (!isset($replace['{{' . $text . '}}'])) {
				if (strpos($_name, '#') === false) {
					if (!isset($translate[$_name])) {
						$translate[$_name] = array();
					}
					$translate[$_name][$text] = $text;
				}
			}
			if ($_backslash && isset($replace['{{' . $text . '}}'])) {
				$replace['{{' . $text . '}}'] = str_replace("'", "\'", str_replace("\'", "'", $replace['{{' . $text . '}}']));
			}
			if (!isset($replace['{{' . $text . '}}']) || is_array($replace['{{' . $text . '}}'])) {
				$replace['{{' . $text . '}}'] = $text;
			}
		}
		return str_replace(array_keys($replace), $replace, $_content);
	}

    /**
     * @param $_language
     * @return string
     */
    public static function getPathTranslationFile($_language): string
    {
		return __DIR__ . '/../i18n/' . $_language . '.json';
	}

    /**
     * @param $_widgetName
     * @return string
     */
    public static function getWidgetPathTranslationFile($_widgetName): string
    {
		return __DIR__ . '/../../data/customTemplates/i18n/' . $_widgetName . '.json';
	}

    /**
     * @param null $_plugin
     * @return array|array[]|bool|mixed
     * @throws Exception
     */
    public static function loadTranslation($_plugin=null) {
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
			//is non core widget:
			if (substr($_plugin, 0, 12) == 'customtemp::') {
				$filename = self::getWidgetPathTranslationFile(str_replace('customtemp::', '', $_plugin));
				if (file_exists($filename)) {
					$content = file_get_contents($filename);
					return is_json($content, array())[self::getLanguage()];
				} else {
					return array([self::getLanguage()] => array());
				}
			} else {
				return array_merge($return, plugin::getTranslation($_plugin, self::getLanguage()));
			}
		}

		return $return;
	}

    /**
     * @return string|null
     * @throws Exception
     */
    public static function getLanguage(): ?string
    {
		if (self::$language == null) {
			self::$language = self::getConfig('language', 'fr_FR');
		}
		return self::$language;
	}

    /**
     * @param $_langage
     */
    public static function setLanguage($_langage) {
		self::$language = $_langage ;
	}

	/*     * *********************Methode d'instance************************* */
}

function __($_content, $_name, $_backslash = false) {
	return translate::sentence($_content, $_name, $_backslash);
}
