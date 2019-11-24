<?php

/** @entrypoint */
/** @ajax */

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

require_once __DIR__ . '/ajax.handler.inc.php';

ajaxHandle(function ()
{
    ajax::checkAccess('');
    if (init('action') == 'genApiKey') {
        ajax::checkAccess('admin');
		unautorizedInDemo();
		if (init('plugin') == 'core') {
			config::save('api', config::genKey());
			return config::byKey('api');
		} else if (init('plugin') == 'pro') {
			config::save('apipro', config::genKey());
			return config::byKey('apipro');
		} else {
			config::save('api', config::genKey(), init('plugin'));
			return config::byKey('api', init('plugin'));
		}
	}

	if (init('action') == 'getKey') {
		$keys = init('key');
		if ($keys == '') {
			throw new Exception(__('Aucune clef demandée', __FILE__));
		}
		if (is_json($keys)) {
			$keys = json_decode($keys, true);
			$return = config::byKeys(array_keys($keys), init('plugin', 'core'));
			if (init('convertToHumanReadable', 0)) {
				$return = jeedom::toHumanReadable($return);
			}
			return $return;
		} else {
			$return = config::byKey($keys, init('plugin', 'core'));
			if (init('convertToHumanReadable', 0)) {
				$return = jeedom::toHumanReadable($return);
			}
			return $return;
		}
	}

	if (init('action') == 'addKey') {
        ajax::checkAccess('admin');
		unautorizedInDemo();
		$values = json_decode(init('value'), true);
		foreach ($values as $key => $value) {
			config::save($key, jeedom::fromHumanReadable($value), init('plugin', 'core'));
		}
		return '';
	}

	if (init('action') == 'removeKey') {
		unautorizedInDemo();
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

	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
/*     * *********Catch exeption*************** */
});
