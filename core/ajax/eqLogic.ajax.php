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
	$action = init('action');
	if (!method_exists('ajax_eqLogic', $action)) {
		throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
	}
	ajax::success(ajax_eqLogic::$action());
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}

class ajax_eqLogic {	
	public static function getEqLogicObject() {
		$eqLogic = self::_fromId($id);
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
		return $return;
	}

	public static function byId() {
		$eqLogic = self::_fromId($id);
		return utils::o2a($eqLogic);
	}

	public static function toHtml() {
		$eqLogic = self::_fromId($id);		
		return array(
			'id' => $eqLogic->getId(),
			'type' => $eqLogic->getEqType_name(),
			'object_id' => $eqLogic->getObject_id(),
			'html' => $eqLogic->toHtml(init('version')),
		);;
	}

	public static function listByType() {
		return utils::a2o(eqLogic::byType(init('type')));
	}

	public static function listByObjectAndCmdType() {
		$object_id = (init('object_id') != -1) ? init('object_id') : null;
		
		return eqLogic::listByObjectAndCmdType($object_id, init('typeCmd'), init('subTypeCmd'));
	}

	public static function listByObject() {
		$object_id = (init('object_id') != -1) ? init('object_id') : null;
		
		return utils::o2a(eqLogic::byObjectId($object_id, init('onlyEnable', true), init('onlyVisible', false), init('eqType_name', null), init('logicalId', null), init('orderByName', false)));
	}

	public static function listByTypeAndCmdType() {
		$results = eqLogic::listByTypeAndCmdType(init('type'), init('typeCmd'), init('subTypeCmd'));
		$return = array();
		foreach ($results as $result) {
			$eqLogic = self::_fromId($result['id']);
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
		
		return $return;
	}

	public static function setIsEnable() {
		self::_assertConnection('admin');
		$eqLogic = self::_fromId($id);
		$eqLogic->setIsEnable(init('isEnable'));
		$eqLogic->save();
		
		return '';
	}

	public static function setOrder() {
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
		
		return '';
	}

	public static function removes() {
		$eqLogics = json_decode(init('eqLogics'), true);
		foreach ($eqLogics as $id) {
			$eqLogic = self::_fromId($id);
			$eqLogic->remove();
		}
		
		return '';
	}

	public static function setIsVisibles() {
		$eqLogics = json_decode(init('eqLogics'), true);
		foreach ($eqLogics as $id) {
			$eqLogic = self::_fromId($id);
			$eqLogic->setIsVisible(init('isVisible'));
			$eqLogic->save();
		}
		
		return '';
	}

	public static function setIsEnables() {
		$eqLogics = json_decode(init('eqLogics'), true);
		foreach ($eqLogics as $id) {
			$eqLogic = self::_fromId($id);
			$eqLogic->setIsEnable(init('isEnable'));
			$eqLogic->save();
		}
		
		return '';
	}

	public static function simpleSave() {
		self::_assertConnection('admin');
		$eqLogicSave = json_decode(init('eqLogic'), true);
		$eqLogic = self::_fromId($eqLogicSave['id']);
		
		if (!$eqLogic->hasRight('w')) {
			throw new Exception('Vous n\'etês pas autorisé à faire cette action');
		}
		utils::a2o($eqLogic, $eqLogicSave);
		$eqLogic->save();
		
		return '';
	}

	public static function copy() {
		self::_assertConnection('admin');
		$eqLogic = self::_fromId();
		if (init('name') == '') {
			throw new Exception(__('Le nom de la copie de l\'équipement ne peut être vide', __FILE__));
		}
		
		return utils::o2a($eqLogic->copy(init('name')));
	}

	public static function remove() {
		self::_assertConnection('admin');
		$eqLogic = self::_fromId();
		if (!$eqLogic->hasRight('w')) {
			throw new Exception('Vous n\'etês pas autorisé à faire cette action');
		}
		$eqLogic->remove();
		
		return '';
	}

	public static function get() {
		$typeEqLogic = init('type');
		if ($typeEqLogic == '' || !class_exists($typeEqLogic)) {
			throw new Exception(__('Type incorrect (classe équipement inexistante) : ', __FILE__) . $typeEqLogic);
		}
		$eqLogic = self::_fromId();
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
		
		return jeedom::toHumanReadable($return);
	}

	public static function save() {
		self::_assertConnection('admin');

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
		}
		
		return utils::o2a($eqLogic);
	}
	
	protected static function _fromId($id = null)
	{
		$id = ($id === null) ? init('id') : $id;
		$eqLogic = eqLogic::byId($id);
		if (!is_object($eqLogic)) {
			throw new Exception(__('EqLogic inconnu verifiez l\'id', __FILE__));
		}
		
		return $eqLogic;
	}
	
	protected static function _assertConnection($_right = '')
	{
		if (!isConnect($role)) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
	}
}