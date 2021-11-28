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
	require_once __DIR__ . '/../php/core.inc.php';
	include_file('core', 'authentification', 'php');

	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

	ajax::init();

	if (init('action') == 'save') {
		unautorizedInDemo();
		utils::processJsonObject('listener', init('listeners'));
		ajax::success();
	}

	if (init('action') == 'remove') {
		unautorizedInDemo();
		$listener = listener::byId(init('id'));
		if (!is_object($listener)) {
			throw new Exception(__('Listener id inconnu', __FILE__));
		}
		$listener->remove();
		ajax::success();
	}

	if (init('action') == 'all') {
		$listeners = utils::o2a(listener::all(true));
		foreach ($listeners as &$listener) {
			$listener['event_str'] = '';
			foreach ($listener['event'] as $event) {
				$listener['event_str'] .= $event . ',';
			}
			$listener['event_str'] = jeedom::toHumanReadable(trim($listener['event_str'], ','));
		}
		ajax::success($listeners);
	}

	throw new Exception(__('Aucune méthode correspondante à :', __FILE__) . ' ' . init('action'));

	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}
