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

use Jeedom\Core\Translator\TranslatorFactory;

require_once __DIR__ . '/../php/core.inc.php';

class translate {
	/*     * *************************Attributs****************************** */

	protected static $language = null;

	/*     * ***********************Methode static*************************** */

	public static function getConfig($_key, $_default = '') {
	    if ($_key === 'language') {
	        return self::getTranslator()->getLanguage();
        }
        if ($_key === 'generateTranslation') {
            return \Jeedom\Core\Configuration\ConfigurationFactory::build('core')->get('generateTranslation');
        }

        return $_default;
	}

	public static function getTranslation($_plugin) {
        trigger_error(__CLASS__ . '::' . __METHOD__ . ' is deprecated.');
	}

	public static function sentence($_content, $_name, $_backslash = false) {
	    return self::getTranslator()->translate($_content, $_name);
	}

	public static function getPluginFromName($_name) {
        trigger_error(__CLASS__ . '::' . __METHOD__ . ' is deprecated.');
	}

	public static function exec($_content, $_name = '', $_backslash = false) {
        return self::getTranslator()->exec($_content, $_name);
	}

	public static function getPathTranslationFile($_language) {
        return dirname(__DIR__) . '/i18n/' . $_language . '.json';
	}

	public static function loadTranslation($_plugin = null) {
        trigger_error(__CLASS__ . '::' . __METHOD__ . ' is deprecated.');
	}

	public static function saveTranslation() {
	    trigger_error(__CLASS__ . '::' . __METHOD__ . ' is deprecated. Use Symfony Translator cache instead.');
	}

	public static function getLanguage() {
        return self::getTranslator()->getLanguage();
	}

	public static function setLanguage($_langage) {
		self::$language = $_langage;
	}

    private static function getTranslator($language = null)
    {
        if (null === $language) {
            $language = self::$language;
        }
        return TranslatorFactory::build($language);
    }
    /*     * *********************Methode d'instance************************* */
}

function __($_content, $_name, $_backslash = false) {
	return translate::sentence($_content, $_name, $_backslash);
}
