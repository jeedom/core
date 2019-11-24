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
    ajax::checkAccess('admin');
	if (init('action') == 'save') {
		unautorizedInDemo();
		utils::processJsonObject('listener', init('listeners'));
		return '';
	}

	if (init('action') == 'remove') {
		unautorizedInDemo();
		$listener = listener::byId(init('id'));
		if (!is_object($listener)) {
			throw new Exception(__('Listerner id inconnu', __FILE__));
		}
		$listener->remove();
		return '';
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
		return $listeners;
	}

	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));

	/*     * *********Catch exeption*************** */
});
