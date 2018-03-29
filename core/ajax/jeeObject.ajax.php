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

	if (init('action') == 'remove') {
		unautorizedInDemo();
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$jeeObject = jeeObject::byId(init('id'));
		if (!is_object($jeeObject)) {
			throw new Exception(__('jeeObject inconnu. Vérifiez l\'ID', __FILE__));
		}
		$jeeObject->remove();
		ajax::success();
	}

	if (init('action') == 'byId') {
		$jeeObject = jeeObject::byId(init('id'));
		if (!is_object($jeeObject)) {
			throw new Exception(__('jeeObject inconnu. Vérifiez l\'ID ', __FILE__) . init('id'));
		}
		ajax::success(jeedom::toHumanReadable(utils::o2a($jeeObject)));
	}

	if (init('action') == 'createSummaryVirtual') {
		jeeObject::createSummaryToVirtual(init('key'));
		ajax::success();
	}

	if (init('action') == 'all') {
		ajax::success(utils::o2a(jeeObject::buildTree()));
	}

	if (init('action') == 'save') {
		unautorizedInDemo();
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$jeeObject_json = json_decode(init('jeeObject'), true);
		if (isset($jeeObject_json['id'])) {
			$jeeObject = jeeObject::byId($jeeObject_json['id']);
		}
		if (!isset($jeeObject) || !is_object($jeeObject)) {
			$jeeObject = new jeeObject();
		}
		utils::a2o($jeeObject, jeedom::fromHumanReadable($jeeObject_json));
		$jeeObject->save();
		ajax::success(utils::o2a($jeeObject));
	}

	if (init('action') == 'uploadImage') {
		unautorizedInDemo();
		$jeeObject = jeeObject::byId(init('id'));
		if (!is_object($jeeObject)) {
			throw new Exception(__('jeeObject inconnu. Vérifiez l\'ID', __FILE__));
		}
		if (!isset($_FILES['file'])) {
			throw new Exception(__('Aucun fichier trouvé. Vérifiez le paramètre PHP (post size limit)', __FILE__));
		}
		$extension = strtolower(strrchr($_FILES['file']['name'], '.'));
		if (!in_array($extension, array('.jpg', '.png'))) {
			throw new Exception('Extension du fichier non valide (autorisé .jpg .png) : ' . $extension);
		}
		if (filesize($_FILES['file']['tmp_name']) > 5000000) {
			throw new Exception(__('Le fichier est trop gros (maximum 5Mo)', __FILE__));
		}
		$jeeObject->setImage('type', str_replace('.', '', $extension));
		$jeeObject->setImage('size', getimagesize($_FILES['file']['tmp_name']));
		$jeeObject->setImage('data', base64_encode(file_get_contents($_FILES['file']['tmp_name'])));
		$jeeObject->save();
		ajax::success();
	}

	if (init('action') == 'getChild') {
		$jeeObject = jeeObject::byId(init('id'));
		if (!is_object($jeeObject)) {
			throw new Exception(__('jeeObject inconnu. Vérifiez l\'ID', __FILE__));
		}
		$return = utils::o2a($jeeObject->getChild());
		ajax::success($return);
	}

	if (init('action') == 'toHtml') {
		if (init('id') == '' || init('id') == 'all' || is_json(init('id'))) {
			if (is_json(init('id'))) {
				$jeeObjects = json_decode(init('id'), true);
			} else {
				$jeeObjects = array();
				foreach (jeeObject::all() as $jeeObject) {
					if ($jeeObject->getConfiguration('hideOnDashboard', 0) == 1) {
						continue;
					}
					$jeeObjects[] = $jeeObject->getId();
				}
			}
			$return = array();
			$i = 0;
			foreach ($jeeObjects as $id) {
				$html = '';
				if (init('summary') == '') {
					$eqLogics = eqLogic::byJeeObjectId($id, true, true);
				} else {
					$jeeObject = jeeObject::byId($id);
					$eqLogics = $jeeObject->getEqLogicBySummary(init('summary'), true, false);
				}
				foreach ($eqLogics as $eqLogic) {
					if (init('category', 'all') != 'all' && $eqLogic->getCategory(init('category')) != 1) {
						continue;
					}
					if (init('tag', 'all') != 'all' && strpos($eqLogic->getTags(), init('tag')) === false) {
						continue;
					}
					$html .= $eqLogic->toHtml(init('version'));
				}
				$return[$i . '::' . $id] = $html;
				$i++;
			}
			ajax::success($return);
		} else {
			$html = '';
			if (init('summary') == '') {
				$eqLogics = eqLogic::byJeeObjectId(init('id'), true, true);
			} else {
				$jeeObject = jeeObject::byId(init('id'));
				$eqLogics = $jeeObject->getEqLogicBySummary(init('summary'), true, false);
			}
			foreach ($eqLogics as $eqLogic) {
				if (init('category', 'all') != 'all' && $eqLogic->getCategory(init('category')) != 1) {
					continue;
				}
				if (init('tag', 'all') != 'all' && strpos($eqLogic->getTags(), init('tag')) === false) {
					continue;
				}
				$html .= $eqLogic->toHtml(init('version'));
			}
			ajax::success($html);
		}
	}

	if (init('action') == 'setOrder') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$position = 1;
		foreach (json_decode(init('jeeObjects'), true) as $id) {
			$jeeObject = jeeObject::byId($id);
			if (is_object($jeeObject)) {
				$jeeObject->setPosition($position);
				$jeeObject->save();
				$position++;
			}
		}
		ajax::success();
	}

	if (init('action') == 'getSummaryHtml') {
		if (init('ids') != '') {
			$return = array();
			foreach (json_decode(init('ids'), true) as $id => $value) {
				if ($id == 'global') {
					$return['global'] = array(
						'html' => jeeObject::getGlobalHtmlSummary($value['version']),
						'id' => 'global',
					);
					continue;
				}
				$jeeObject = jeeObject::byId($id);
				if (!is_object($jeeObject)) {
					continue;
				}
				$return[$jeeObject->getId()] = array(
					'html' => $jeeObject->getHtmlSummary($value['version']),
					'id' => $jeeObject->getId(),
				);
			}

			ajax::success($return);
		} else {
			$jeeObject = jeeObject::byId(init('id'));
			if (!is_object($jeeObject)) {
				throw new Exception(__('jeeObject inconnu. Vérifiez l\'ID', __FILE__));
			}
			$infojeeObject = array();
			$info_jeeObject['id'] = $jeeObject->getId();
			$info_jeeObject['html'] = $jeeObject->getHtmlSummary(init('version'));
			ajax::success($info_jeeObject);
		}
	}

	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}
