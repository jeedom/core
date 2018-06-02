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

	if (!isConnect()) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

	ajax::init();

	if (init('action') == 'remove') {
		$dataStore = dataStore::byId(init('id'));
		if (!is_object($dataStore)) {
			throw new Exception(__('Dépôt de données inconnu. Vérifiez l\'ID : ', __FILE__) . init('id'));
		}
		$dataStore->remove();
		ajax::success();
	}

	if (init('action') == 'save') {
		if (init('id') == '') {
			$dataStore = new dataStore();
			$dataStore->setKey(init('key'));
			$dataStore->setLink_id(init('link_id'));
			$dataStore->setType(init('type'));
		} else {
			$dataStore = dataStore::byId(init('id'));
		}
		if (!is_object($dataStore)) {
			throw new Exception(__('Dépôt de données inconnu. Vérifiez l\'ID : ', __FILE__) . init('id'));
		}
		$dataStore->setValue(init('value'));
		$dataStore->save();
		ajax::success();
	}

	if (init('action') == 'all') {
		$dataStores = dataStore::byTypeLinkId(init('type'));
		$return = array();
		if (init('usedBy') == 1) {
			foreach ($dataStores as $datastore) {
				$info_datastore = utils::o2a($datastore);
				$info_datastore['usedBy'] = array(
					'scenario' => array(),
					'eqLogic' => array(),
					'cmd' => array(),
					'interactDef' => array(),
				);
				$usedBy = $datastore->getUsedBy();
				foreach ($usedBy['scenario'] as $scenario) {
					$info_datastore['usedBy']['scenario'][] = $scenario->getHumanName();
				}
				foreach ($usedBy['eqLogic'] as $eqLogic) {
					$info_datastore['usedBy']['eqLogic'][] = $eqLogic->getHumanName();
				}
				foreach ($usedBy['cmd'] as $cmd) {
					$info_datastore['usedBy']['cmd'][] = $cmd->getHumanName();
				}
				foreach ($usedBy['interactDef'] as $interactDef) {
					$info_datastore['usedBy']['interactDef'][] = $interactDef->getHumanName();
				}
				$return[] = $info_datastore;
			}
		} else {
			$return = utils::o2a($dataStore);
		}
		ajax::success($return);
	}

	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}
?>
