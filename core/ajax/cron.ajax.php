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
	require_once dirname(__FILE__) . '/../php/core.inc.php';
	include_file('core', 'authentification', 'php');

	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

	if (init('action') == 'save') {
		utils::processJsonObject('cron', init('crons'));
		ajax::success();
	}

	if (init('action') == 'remove') {
		$cron = cron::byId(init('id'));
		if (!is_object($cron)) {
			throw new Exception(__('Cron id inconnu', __FILE__));
		}
		$cron->remove();
		ajax::success();
	}

	if (init('action') == 'all') {
		$results = array();
		$results['crons'] = utils::o2a(cron::all(true));
		$results['nbCronRun'] = cron::nbCronRun();
		$results['nbProcess'] = cron::nbProcess();
		$results['nbMasterCronRun'] = (cron::jeeCronRun()) ? 1 : 0;
		$results['loadAvg'] = cron::loadAvg();
		ajax::success($results);
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

	throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));

	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>
