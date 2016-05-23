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
header('Content-Type: application/json');

try {
	require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
	include_file('core', 'authentification', 'php');

	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

	if (init('action') == 'clear') {
		if (init('jeeNetwork_id') != '') {
			$jeeNetwork = jeeNetwork::byId(init('jeeNetwork_id'));
			if (is_object($jeeNetwork)) {
				$jeeNetwork->emptyLog(init('log'));
			}
		} else {
			log::clear(init('log'));
		}
		ajax::success();
	}

	if (init('action') == 'remove') {
		if (init('jeeNetwork_id') != '') {
			$jeeNetwork = jeeNetwork::byId(init('jeeNetwork_id'));
			if (is_object($jeeNetwork)) {
				$jeeNetwork->removeLog(init('log'));
			}
		} else {
			log::remove(init('log'));
		}
		ajax::success();
	}

	if (init('action') == 'removeAll') {
		log::removeAll();
		ajax::success();
	}

	if (init('action') == 'get') {
		if (init('jeeNetwork_id') != '') {
			$jeeNetwork = jeeNetwork::byId(init('jeeNetwork_id'));
			if (is_object($jeeNetwork)) {
				$jeeNetwork->getLog(init('log'), init('start', 0), init('nbLine', 99999));
			}
		} else {
			ajax::success(log::get(init('log'), init('start', 0), init('nbLine', 99999)));
		}
	}

	throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>
