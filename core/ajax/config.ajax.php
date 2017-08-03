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

	ajax::init();

	if (init('action') == 'genApiKey') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		if (init('plugin') == 'core') {
			config::save('api', config::genKey());
			ajax::success(config::byKey('api'));
		} else if (init('plugin') == 'pro') {
			config::save('apipro', config::genKey());
			ajax::success(config::byKey('apipro'));
		} else {
			config::save('api', config::genKey(), init('plugin'));
			ajax::success(config::byKey('api', init('plugin')));
		}
	}

	if (init('action') == 'getKey') {
		$keys = init('key');
		if ($keys == '') {
			throw new Exception(__('Aucune clef demandée', __FILE__));
		}
		if (is_json($keys)) {
			$keys = json_decode($keys, true);
			$configs = config::byKeys(array_keys($keys), init('plugin', 'core'));
			$return = array();
			foreach ($keys as $key => $value) {
				$return[$key] = jeedom::toHumanReadable($configs[$key]);
			}
			if (init('convertToHumanReadable', 0)) {
				$return = jeedom::toHumanReadable($return);
			}
			ajax::success($return);
		} else {
			$return = config::byKey($keys, init('plugin', 'core'));
			if (init('convertToHumanReadable', 0)) {
				$return = jeedom::toHumanReadable($return);
			}
			ajax::success($return);
		}
	}

	if (init('action') == 'addKey') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$values = json_decode(init('value'), true);
		foreach ($values as $key => $value) {
			config::save($key, jeedom::fromHumanReadable($value), init('plugin', 'core'));
		}
		ajax::success();
	}

	if (init('action') == 'removeKey') {
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
		ajax::success();
	}

	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>
