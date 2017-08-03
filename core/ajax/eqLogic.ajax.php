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

	ajax::init();

	if (init('action') == 'getEqLogicObject') {
		$object = object::byId(init('object_id'));

		if (!is_object($object)) {
			throw new Exception(__('Objet inconnu, verifiez l\'id', __FILE__));
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
			throw new Exception(__('EqLogic inconnu, vérifiez l\'id', __FILE__));
		}
		ajax::success(utils::o2a($eqLogic));
	}

	if (init('action') == 'toHtml') {
		if (init('ids') != '') {
			$return = array();
			foreach (json_decode(init('ids'), true) as $id => $value) {
				$eqLogic = eqLogic::byId($id);
				if (!is_object($eqLogic)) {
					continue;
				}
				$return[$eqLogic->getId()] = array(
					'html' => $eqLogic->toHtml($value['version']),
					'id' => $eqLogic->getId(),
					'type' => $eqLogic->getEqType_name(),
					'object_id' => $eqLogic->getObject_id(),
				);
			}
			ajax::success($return);
		} else {
			$eqLogic = eqLogic::byId(init('id'));
			if (!is_object($eqLogic)) {
				throw new Exception(__('Eqlogic inconnu, vérifiez l\'id', __FILE__));
			}
			$info_eqLogic = array();
			$info_eqLogic['id'] = $eqLogic->getId();
			$info_eqLogic['type'] = $eqLogic->getEqType_name();
			$info_eqLogic['object_id'] = $eqLogic->getObject_id();
			$info_eqLogic['html'] = $eqLogic->toHtml(init('version'));
			ajax::success($info_eqLogic);
		}
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
		ajax::success(utils::o2a(eqLogic::byObjectId($object_id, init('onlyEnable', true), init('onlyVisible', false), init('eqType_name', null), init('logicalId', null), init('orderByName', false))));
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
			throw new Exception(__('EqLogic inconnu, vérifiez l\'id', __FILE__));
		}
		if (!$eqLogic->hasRight('w')) {
			throw new Exception(__('Vous n\'êtes pas autorisé à faire cette action', __FILE__));
		}
		$eqLogic->setIsEnable(init('isEnable'));
		$eqLogic->save();
		ajax::success();
	}

	if (init('action') == 'setOrder') {
		$eqLogics = json_decode(init('eqLogics'), true);
		$sql = '';
		foreach ($eqLogics as $eqLogic_json) {
			if (!is_numeric($eqLogic_json['id']) || !is_numeric($eqLogic_json['order']) || (isset($eqLogic_json['object_id']) && !is_numeric($eqLogic_json['object_id']))) {
				throw new Exception("Erreur une des valeurs n'est pas un numérique");
			}
			if (isset($eqLogic_json['object_id'])) {
				if ($eqLogic_json['object_id'] == -1) {
					$eqLogic_json['object_id'] = "NULL";
				}
				$sql .= 'UPDATE eqLogic SET `order`= ' . $eqLogic_json['order'] . ', object_id=' . $eqLogic_json['object_id'] . '  WHERE id=' . $eqLogic_json['id'] . ' ;';
			} else {
				$sql .= 'UPDATE eqLogic SET `order`= ' . $eqLogic_json['order'] . '  WHERE id=' . $eqLogic_json['id'] . ' ;';
			}
		}
		DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		ajax::success();
	}

	if (init('action') == 'removes') {
		$eqLogics = json_decode(init('eqLogics'), true);
		foreach ($eqLogics as $id) {
			$eqLogic = eqLogic::byId($id);
			if (!is_object($eqLogic)) {
				throw new Exception(__('EqLogic inconnu, vérifiez l\'id :', __FILE__) . ' ' . $id);
			}
			if (!$eqLogic->hasRight('w')) {
				continue;
			}
			$eqLogic->remove();
		}
		ajax::success();
	}

	if (init('action') == 'setIsVisibles') {
		$eqLogics = json_decode(init('eqLogics'), true);
		foreach ($eqLogics as $id) {
			$eqLogic = eqLogic::byId($id);
			if (!is_object($eqLogic)) {
				throw new Exception(__('EqLogic inconnu, verifiez l\'id :', __FILE__) . ' ' . $id);
			}
			if (!$eqLogic->hasRight('w')) {
				continue;
			}
			$eqLogic->setIsVisible(init('isVisible'));
			$eqLogic->save();
		}
		ajax::success();
	}

	if (init('action') == 'setIsEnables') {
		$eqLogics = json_decode(init('eqLogics'), true);
		foreach ($eqLogics as $id) {
			$eqLogic = eqLogic::byId($id);
			if (!is_object($eqLogic)) {
				throw new Exception(__('EqLogic inconnu, vérifiez l\'id :', __FILE__) . ' ' . $id);
			}
			if (!$eqLogic->hasRight('w')) {
				continue;
			}
			$eqLogic->setIsEnable(init('isEnable'));
			$eqLogic->save();
		}
		ajax::success();
	}

	if (init('action') == 'simpleSave') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$eqLogicSave = json_decode(init('eqLogic'), true);
		$eqLogic = eqLogic::byId($eqLogicSave['id']);
		if (!is_object($eqLogic)) {
			throw new Exception(__('EqLogic inconnu vérifiez l\'id : ', __FILE__) . $eqLogicsSave['id']);
		}

		if (!$eqLogic->hasRight('w')) {
			throw new Exception(__('Vous n\'êtes pas autorisé à faire cette action', __FILE__));
		}
		utils::a2o($eqLogic, $eqLogicSave);
		$eqLogic->save();
		ajax::success();
	}

	if (init('action') == 'copy') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$eqLogic = eqLogic::byId(init('id'));
		if (!is_object($eqLogic)) {
			throw new Exception(__('EqLogic inconnu, vérifiez l\'id', __FILE__));
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
			throw new Exception(__('EqLogic inconnu, vérifiez l\'id : ', __FILE__) . init('id'));
		}
		if (!$eqLogic->hasRight('w')) {
			throw new Exception(__('Vous n\'êtes pas autorisé à faire cette action', __FILE__));
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
			throw new Exception(__('EqLogic inconnu, vérifiez l\'id : ', __FILE__) . init('id'));
		}
		$return = utils::o2a($eqLogic);
		$return['cmd'] = utils::o2a($eqLogic->getCmd());
		ajax::success(jeedom::toHumanReadable($return));
	}

	if (init('action') == 'save') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}

		$eqLogicsSave = json_decode(init('eqLogic'), true);

		foreach ($eqLogicsSave as $eqLogicSave) {
			try {
				if (!is_array($eqLogicSave)) {
					throw new Exception(__('Informations recues incorrecte', __FILE__));
				}
				$typeEqLogic = init('type');
				$typeCmd = $typeEqLogic . 'Cmd';
				if ($typeEqLogic == '' || !class_exists($typeEqLogic) || !class_exists($typeCmd)) {
					throw new Exception(__('Type incorrect, (classe commande inexistante)', __FILE__) . $typeCmd);
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
						throw new Exception(__('Vous n\'êtes pas autorisé à faire cette action', __FILE__));
					}
				}
				if (method_exists($eqLogic, 'preAjax')) {
					$eqLogic->preAjax();
				}
				$eqLogicSave = jeedom::fromHumanReadable($eqLogicSave);
				utils::a2o($eqLogic, $eqLogicSave);
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
						utils::a2o($cmd, $cmd_info);
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
				if (method_exists($eqLogic, 'postAjax')) {
					$eqLogic->postAjax();
				}
			} catch (Exception $e) {
				if (strpos($e->getMessage(), '[MySQL] Error code : 23000') !== false) {
					if ($e->getTrace()[2]['class'] == 'eqLogic') {
						throw new Exception(__('Un équipement portant ce nom (', __FILE__) . $e->getTrace()[0]['args'][1]['name'] . __(') existe déjà pour cet objet', __FILE__));
					} elseif ($e->getTrace()[2]['class'] == 'cmd') {
						throw new Exception(__('Une commande portant ce nom (', __FILE__) . $e->getTrace()[0]['args'][1]['name'] . __(') existe déjà pour cet équipement', __FILE__));
					}
				} else {
					throw new Exception($e->getMessage());
				}
			}
			ajax::success(utils::o2a($eqLogic));
		}
	}
	
	if (init('action') == 'getAlert') {
		$alerts = array();
		foreach (eqLogic::all() as $eqLogic) {
			if ($eqLogic->getAlert() == '') {
				continue;
			}
			$alerts[] = $eqLogic->toHtml(init('version'));
		}
		ajax::success($alerts);
	}

	throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
