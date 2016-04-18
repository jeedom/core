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

try {
	require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
	include_file('core', 'authentification', 'php');

	if (!isConnect()) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__), -1234);
	}
	$action = init('action');
	if (!method_exists('ajax_config',$action))
	{
		throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
	}
	ajax::succes(ajax_config::$action());
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
	
class ajax_config
{
	public static function genKeyAPI() {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		config::save('api', config::genKey());
		
		return config::byKey('api');
	}

	public static function getKey() {
		$keys = init('key');
		if ($keys == '') {
			throw new Exception(__('Aucune clef demandée', __FILE__));
		}
		if (is_json($keys)) {
			$keys = json_decode($keys, true);
			$return = array();
			foreach ($keys as $key => $value) {
				$return[$key] = jeedom::toHumanReadable(config::byKey($key, init('plugin', 'core')));
			}
			
			return $return;
		}
		
		return config::byKey(init('key'), init('plugin', 'core'));
	}

	public static function addKey() {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$values = json_decode(init('value'), true);
		foreach ($values as $key => $value) {
			if ($key == 'market::password' && !preg_match('/^[0-9a-f]{40}$/i', $value)) {
				$value = sha1($value);
			}
			config::save($key, jeedom::fromHumanReadable($value), init('plugin', 'core'));
			if ($key == 'internalAddr') {
				jeeNetwork::pull();
			}
			if ($key == 'market::allowDNS') {
				if ($value == 1) {
					if (!network::dns_run()) {
						network::dns_start();
					}
				} else {
					if (network::dns_run()) {
						network::dns_stop();
					}
				}
			}
		}
		
		return '';
	}

	public static function removeKey() {
		$keys = init('key');
		if ($keys == '') {
			throw new Exception(__('Aucune clef demandée', __FILE__));
		}
		if (is_json($keys)) {
			$keys = json_decode($keys, true);
			$return = array();
			foreach ($keys as $key => $value) {
				config::remove($key, init('plugin', 'core'));
			}
		} else {
			config::remove(init('key'), init('plugin', 'core'));
		}
		
		return '';
	}
}