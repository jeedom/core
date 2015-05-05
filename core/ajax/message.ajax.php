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
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

	if (init('action') == 'clearMessage') {
		message::removeAll(init('plugin'));
		ajax::success();
	}

	if (init('action') == 'nbMessage') {
		ajax::success(message::nbMessage());
	}

	if (init('action') == 'all') {
		if (init('plugin') == '') {
			$messages = utils::o2a(message::all());
		} else {
			$messages = utils::o2a(message::byPlugin(init('plugin')));
		}
		foreach ($messages as &$message) {
			$message['message'] = htmlentities($message['message']);
		}
		ajax::success($messages);
	}

	if (init('action') == 'removeMessage') {
		$message = message::byId(init('id'));
		if (!is_object($message)) {
			throw new Exception(__('Message inconnu verifié l\'id', __FILE__));
		}
		$message->remove();
		ajax::success();
	}

	throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>
