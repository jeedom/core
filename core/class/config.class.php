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

use Jeedom\Core\Api\Security\ApiKey;
use Jeedom\Core\Infrastructure\Configuration\ConfigurationFactory;
use Jeedom\Core\Log\Log;
use Jeedom\Core\Plugin\Plugin;

require_once __DIR__ . '/../../core/php/core.inc.php';

class config {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	public static function getDefaultConfiguration($_plugin = 'core') {
		return ConfigurationFactory::getDefaultConfiguration($_plugin)->search('');
	}
	/**
	 * Ajoute une clef à la config
	 * @param string $_key
	 * @param string | object | array $_value
	 * @param string $_plugin
	 * @return boolean
	 */
	public static function save($_key, $_value, $_plugin = 'core') {
        self::getConfiguration($_plugin)->set($_key, $_value);

        return true;
	}

	/**
	 * Supprime une clef de la config
	 * @param string $_key nom de la clef à supprimer
	 * @return boolean vrai si ok faux sinon
	 */
	public static function remove($_key, $_plugin = 'core') {
        self::getConfiguration($_plugin)->remove($_key);

        return true;
	}

	/**
	 * Retourne la valeur d'une clef
	 * @param string $_key nom de la clef dont on veut la valeur
	 * @return string valeur de la clef
	 */
	public static function byKey($_key, $_plugin = 'core', $_default = '', $_forceFresh = false) {
        return self::getConfiguration($_plugin)->get($_key, $_default);
	}

	public static function byKeys($_keys, $_plugin = 'core', $_default = '') {
        return self::getConfiguration($_plugin)->multiGet($_keys, $_default);
	}

	public static function searchKey($_key, $_plugin = 'core') {
        return self::getConfiguration($_plugin)->search($_key);
	}

	public static function genKey($_car = 32) {
		return ApiKey::generate($_car);
	}

	public static function getPluginEnable() {
		return Plugin::getEnable();
	}

	public static function getLogLevelPlugin() {
	    return Log::getLevels();
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

    /**
     * @param $_plugin
     *
     * @return mixed
     */
    private static function getConfiguration($_plugin)
    {
        return ConfigurationFactory::build($_plugin);
    }

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */
}
