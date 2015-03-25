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

	if (init('action') == 'getEqLogicObject') {
		$object = object::byId(init('object_id'));

		if (!is_object($object)) {
			throw new Exception(__('Objet inconnu verifié l\'id', __FILE__));
		}
		$return = utils::o2a($object);
		$return['eqLogic'] = array();
		foreach ($object->getEqLogic() as $eqLogic) {
			if ($eqLogic->getIsVisible() == '1') {
				$info_eqLogic = array();
				$info_eqLogic['id'] = $eqLogic->getId();
				$info_eqLogic['type'] = $eqLogic->getEqType_name();
				$info_eqLogic['object_id'] = $eqLogic->getObject_id();
				$info_eqLogic['html'] = $eqLogic->toHtml(init('version'));
				$return['eqLogic'][] = $info_eqLogic;
			}
		}
		ajax::success($return);
	}

	if (init('action') == 'byId') {
		$eqLogic = eqLogic::byId(init('id'));
		if (!is_object($eqLogic)) {
			throw new Exception(__('EqLogic inconnu verifié l\'id', __FILE__));
		}
		ajax::success(utils::o2a($eqLogic));
	}

	if (init('action') == 'toHtml') {
		$eqLogic = eqLogic::byId(init('id'));
		if (!is_object($eqLogic)) {
			throw new Exception(__('Eqlogic inconnu verifié l\'id', __FILE__));
		}
		$info_eqLogic = array();
		$info_eqLogic['id'] = $eqLogic->getId();
		$info_eqLogic['type'] = $eqLogic->getEqType_name();
		$info_eqLogic['object_id'] = $eqLogic->getObject_id();
		$info_eqLogic['html'] = $eqLogic->toHtml(init('version'));
		ajax::success($info_eqLogic);
	}

	if (init('action') == 'listByType') {
		ajax::success(utils::a2o(eqLogic::byType(init('type'))));
	}

	if (init('action') == 'listByObjectAndCmdType') {
		$object_id = (init('object_id') != -1) ? init('object_id') : null;
		ajax::success(eqLogic::listByObjectAndCmdType($object_id, init('typeCmd'), init('subTypeCmd')));
	}

	if (init('action') == 'listByObject') {
		$object_id = (init('object_id') != -1) ? init('object_id') : null;
		ajax::success(utils::o2a(eqLogic::byObjectId($object_id, init('onlyEnable', true))));
	}

	if (init('action') == 'listByTypeAndCmdType') {
		$results = eqLogic::listByTypeAndCmdType(init('type'), init('typeCmd'), init('subTypeCmd'));
		$return = array();
		foreach ($results as $result) {
			$eqLogic = eqLogic::byId($result['id']);
			$info['eqLogic'] = utils::o2a($eqLogic);
			$info['object'] = array('name' => 'Aucun');
			if (is_object($eqLogic)) {
				$object = $eqLogic->getObject();
				if (is_object($object)) {
					$info['object'] = utils::o2a($eqLogic->getObject());
				}
			}
			$return[] = $info;
		}
		ajax::success($return);
	}

	if (init('action') == 'setIsEnable') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$eqLogic = eqLogic::byId(init('id'));
		if (!is_object($eqLogic)) {
			throw new Exception(__('EqLogic inconnu verifié l\'id', __FILE__));
		}
		$eqLogic->setIsEnable(init('isEnable'));
		$eqLogic->save();
		ajax::success();
	}

	/*     * **************************Gloabl Method******************************** */

	if (init('action') == 'copy') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$eqLogic = eqLogic::byId(init('id'));
		if (!is_object($eqLogic)) {
			throw new Exception(__('EqLogic inconnu verifié l\'id', __FILE__));
		}
		if (init('name') == '') {
			throw new Exception(__('Le nom de la copie de l\'équipement ne peut être vide', __FILE__));
		}
		ajax::success(utils::o2a($eqLogic->copy(init('name'))));
	}

	if (init('action') == 'remove') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$eqLogic = eqLogic::byId(init('id'));
		if (!is_object($eqLogic)) {
			throw new Exception(__('EqLogic inconnu verifié l\'id : ', __FILE__) . init('id'));
		}
		if (!$eqLogic->hasRight('w')) {
			throw new Exception('Vous n\'etês pas autorisé à faire cette action');
		}
		$eqLogic->remove();
		ajax::success();
	}

	if (init('action') == 'get') {
		$typeEqLogic = init('type');
		if ($typeEqLogic == '' || !class_exists($typeEqLogic)) {
			throw new Exception(__('Type incorrect (classe équipement inexistante) : ', __FILE__) . $typeEqLogic);
		}
		$eqLogic = $typeEqLogic::byId(init('id'));
		if (!is_object($eqLogic)) {
			throw new Exception(__('EqLogic inconnu verifié l\'id : ', __FILE__) . init('id'));
		}
		$return = utils::o2a($eqLogic);
		if (init('status') == 1) {
			$return['status'] = array(
				'state' => 'ok',
				'lastCommunication' => $eqLogic->getStatus('lastCommunication'),
			);
			if ($eqLogic->getTimeout() > 0 && $eqLogic->getStatus('lastCommunication', date('Y-m-d H:i:s')) < date('Y-m-d H:i:s', strtotime('-' . $eqLogic->getTimeout() . ' minutes' . date('Y-m-d H:i:s')))) {
				$return['status']['state'] = 'timeout';
			}
		}
		$return['cmd'] = utils::o2a($eqLogic->getCmd());
		ajax::success(jeedom::toHumanReadable($return));
	}

	if (init('action') == 'save') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}

		$eqLogicsSave = json_decode(init('eqLogic'), true);

		foreach ($eqLogicsSave as $eqLogicSave) {
			if (!is_array($eqLogicSave)) {
				throw new Exception(__('Informations recues incorrecte', __FILE__));
			}
			$typeEqLogic = init('type');
			$typeCmd = $typeEqLogic . 'Cmd';
			if ($typeEqLogic == '' || !class_exists($typeEqLogic) || !class_exists($typeCmd)) {
				throw new Exception(__('Type incorrect (classe commande inexistante)', __FILE__) . $typeCmd);
			}
			$eqLogic = null;
			if (isset($eqLogicSave['id'])) {
				$eqLogic = $typeEqLogic::byId($eqLogicSave['id']);
			}
			if (!is_object($eqLogic)) {
				$eqLogic = new $typeEqLogic();
				$eqLogic->setEqType_name(init('type'));
			} else {
				if (!$eqLogic->hasRight('w')) {
					throw new Exception('Vous n\'etês pas autorisé à faire cette action');
				}
			}
			if (method_exists($eqLogic, 'preAjax')) {
				$eqLogic->preAjax();
			}
			utils::a2o($eqLogic, jeedom::fromHumanReadable($eqLogicSave));
			$dbList = $typeCmd::byEqLogicId($eqLogic->getId());
			$eqLogic->save();
			$enableList = array();

			if (isset($eqLogicSave['cmd'])) {
				$cmd_order = 0;
				foreach ($eqLogicSave['cmd'] as $cmd_info) {
					$cmd = null;
					if (isset($cmd_info['id'])) {
						$cmd = $typeCmd::byId($cmd_info['id']);
					}
					if (!is_object($cmd)) {
						$cmd = new $typeCmd();
					}
					$cmd->setEqLogic_id($eqLogic->getId());
					$cmd->setOrder($cmd_order);
					utils::a2o($cmd, jeedom::fromHumanReadable($cmd_info));
					$cmd->save();
					$cmd_order++;
					$enableList[$cmd->getId()] = true;
				}

				//suppression des entrées non innexistante.
				foreach ($dbList as $dbObject) {
					if (!isset($enableList[$dbObject->getId()]) && !$dbObject->dontRemoveCmd()) {
						$dbObject->remove();
					}
				}
			}
		}
		ajax::success(utils::o2a($eqLogic));
	}

	throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>
