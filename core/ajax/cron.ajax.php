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
		utils::processJsonObject('cron', init('crons'));
		ajax::success();
	}

	if (init('action') == 'remove') {
		unautorizedInDemo();
		$cron = cron::byId(init('id'));
		if (!is_object($cron)) {
			throw new Exception(__('Cron id inconnu', __FILE__));
		}
		$cron->remove();
		ajax::success();
	}

	if (init('action') == 'all') {
		$crons = cron::all(true);
		foreach ($crons as $cron) {
			$cron->refresh();
		}
		ajax::success(utils::o2a($crons));
	}

	if (init('action') == 'start') {
		$cron = cron::byId(init('id'));
		if (!is_object($cron)) {
			throw new Exception(__('Cron id inconnu', __FILE__));
		}
		$cron->run();
		sleep(1);
		ajax::success();
	}

	if (init('action') == 'stop') {
		$cron = cron::byId(init('id'));
		if (!is_object($cron)) {
			throw new Exception(__('Cron id inconnu', __FILE__));
		}
		$cron->halt();
		sleep(1);
		ajax::success();
	}

	throw new Exception(__('Aucune méthode correspondante à :', __FILE__) . ' ' . init('action'));

	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}
