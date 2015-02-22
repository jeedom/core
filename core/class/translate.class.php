<?php

/**
 * Description of config
 *
 * @author Antoine Bonnefoy & Gevrey Loïc
 */
/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../php/core.inc.php';

class translate {
	/*     * *************************Attributs****************************** */

	protected static $translation;
	protected static $language;

	/*     * ***********************Methode static*************************** */

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
		preg_match_all("/{{(.*?)}}/s", $_content, $matches);
		foreach ($matches[1] as $text) {
			$replace = false;
			if (isset($translate[$_name]) && isset($translate[$_name][$text])) {
				$replace = $translate[$_name][$text];
			}
			if ($replace === false && isset($translate['common']) && isset($translate['common'][$text])) {
				$replace = $translate['common'][$text];
			}
			if ($replace === false) {
				$modify = true;
				if (!isset($translate[$_name])) {
					$translate[$_name] = array();
				}
				$translate[$_name][$text] = $text;
			}
			if ($_backslash && $replace !== false) {
				$replace = str_replace("'", "\'", $replace);
			}
			if ($replace === false) {
				$replace = $text;
			}
			$_content = str_replace("{{" . $text . "}}", $replace, $_content);
		}
		if ($language == 'fr_FR' && $modify) {
			static::$translation[self::getLanguage()] = $translate;
			self::saveTranslation($language);
		}
		return $_content;
	}

	public static function getPathTranslationFile($_language) {
		return dirname(__FILE__) . '/../i18n/' . $_language . '.json';
	}

	public static function loadTranslation() {
		$return = array();
		if (file_exists(self::getPathTranslationFile(self::getLanguage()))) {
			$return = file_get_contents(self::getPathTranslationFile(self::getLanguage()));
			if (is_json($return)) {
				$return = json_decode($return, true);
			} else {
				$return = array();
			}
			foreach (plugin::listPlugin(true,false,false) as $plugin) {
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
		file_put_contents(self::getPathTranslationFile(self::getLanguage()), json_encode($core, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		foreach ($plugins as $plugin_name => $translation) {
			try {
				$plugin = plugin::byId($plugin_name);
				$plugin->saveTranslation(self::getLanguage(), $translation);
			} catch (Exception $e) {
				
			}
		}
	}

	public static function getLanguage() {
		if (!isset(static::$language)) {
			try {
				static::$language = config::byKey('language', 'core', 'fr_FR');
			} catch (Exception $e) {
				static::$language = 'fr_FR';
			}
		}
		return static::$language;
	}

	/*     * *********************Methode d'instance************************* */
}

function __($_content, $_name, $_backslash = false) {
	return translate::sentence($_content, $_name, $_backslash = false);
}

?>
