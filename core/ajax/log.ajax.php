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
	require_once __DIR__ . '/../../core/php/core.inc.php';
	include_file('core', 'authentification', 'php');

	if (init('action') == 'getScTranslations') {
		ajax::success(json_encode($GLOBALS['JEEDOM_SCLOG_TEXT']));
	}

	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

	ajax::init();

	if (init('action') == 'clear') {
		unautorizedInDemo();
		log::clear(init('log'));
		ajax::success();
	}

	if (init('action') == 'clearAll') {
		unautorizedInDemo();
		log::clearAll();
		ajax::success();
	}

	if (init('action') == 'remove') {
		unautorizedInDemo();
		log::remove(init('log'));
		ajax::success();
	}

	if (init('action') == 'list') {
		ajax::success(log::liste());
	}

	if (init('action') == 'removeAll') {
		unautorizedInDemo();
		log::removeAll();
		ajax::success();
	}

	if (init('action') == 'get') {
		ajax::success(log::get(init('log'), init('start', 0), init('nbLine', 99999)));
	}

	throw new Exception(__('Aucune méthode correspondante à :', __FILE__) . ' ' . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}
