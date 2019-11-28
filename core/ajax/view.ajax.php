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
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$view = view::byId(init('id'));
		if (!is_object($view)) {
			throw new Exception(__('Vue non trouvée. Vérifiez l\'iD', __FILE__));
		}
		$view->remove();
		ajax::success();
	}

	if (init('action') == 'all') {
		ajax::success(utils::o2a(view::all()));
	}

	if (init('action') == 'get') {
		if (init('id') == 'all' || is_json(init('id'))) {
			if (is_json(init('id'))) {
				$view_ajax = json_decode(init('id'), true);
				$views = array();
				foreach ($view_ajax as $id) {
					$views[] = view::byId($id);
				}
			} else {
				$views = view::all();
			}
			$return = array();
			foreach (view::all() as $view) {
				$return[$view->getId()] = $view->toAjax(init('version', 'dashboard'), init('html'));
			}
			ajax::success($return);
		} else {
			$view = view::byId(init('id'));
			if (!is_object($view)) {
				throw new Exception(__('Vue non trouvée. Vérifiez l\'ID', __FILE__));
			}
			ajax::success($view->toAjax(init('version', 'dashboard'), init('html')));
		}
	}

	if (init('action') == 'save') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$view = view::byId(init('view_id'));
		if (!is_object($view)) {
			$view = new view();
		}
		$view_ajax = json_decode(init('view'), true);
		utils::a2o($view, $view_ajax);
		$view->save();
		if (isset($view_ajax['zones']) && count($view_ajax['zones']) > 0) {
			$view->removeviewZone();
			foreach ($view_ajax['zones'] as $viewZone_info) {
				$viewZone = new viewZone();
				$viewZone->setView_id($view->getId());
				utils::a2o($viewZone, $viewZone_info);
				$viewZone->save();
				if (isset($viewZone_info['viewData'])) {
					$order = 0;
					foreach ($viewZone_info['viewData'] as $viewData_info) {
						$viewData = new viewData();
						$viewData->setviewZone_id($viewZone->getId());
						$viewData->setOrder($order);
						utils::a2o($viewData, jeedom::fromHumanReadable($viewData_info));
						$viewData->save();
						$order++;
					}
				}
			}
		}
		ajax::success(utils::o2a($view));
	}

	if (init('action') == 'getEqLogicviewZone') {
		$viewZone = viewZone::byId(init('viewZone_id'));
		if (!is_object($viewZone)) {
			throw new Exception(__('Vue non trouvée. Vérifiez l\'ID', __FILE__));
		}
		$return = utils::o2a($viewZone);
		$return['eqLogic'] = array();
		foreach ($viewZone->getviewData() as $viewData) {
			$infoViewDatat = utils::o2a($viewData->getLinkObject());
			$infoViewDatat['html'] = $viewData->getLinkObject()->toHtml(init('version'));
			$return['viewData'][] = $infoViewDatat;
		}
		ajax::success($return);
	}

	if (init('action') == 'setComponentOrder') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$components = json_decode(init('components'), true);
		$sql = '';
		foreach ($components as $component) {
			if (!isset($component['viewZone_id']) || !is_numeric($component['viewZone_id']) || !is_numeric($component['id']) || !is_numeric($component['order']) || (isset($component['object_id']) && !is_numeric($component['object_id']))) {
				continue;
			}
			$sql .= 'UPDATE viewData SET `order`= ' . $component['order'] . '  WHERE link_id=' . $component['id'] . ' AND type="' . $component['type'] . '" AND  viewZone_id=' . $component['viewZone_id'] . ';';
			if($component['type'] == 'eqLogic'){
				$eqLogic = eqLogic::byId($component['id']);
				if (!is_object($eqLogic)) {
					continue;
				}
				utils::a2o($eqLogic, $component);
				$eqLogic->save(true);
			}elseif($component['type'] == 'scenario'){
				$scenario = scenario::byId($component['id']);
				if (!is_object($scenario)) {
					continue;
				}
				utils::a2o($scenario, $component);
				$scenario->save(true);
			}
		}
		if ($sql != '') {
			DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		}
		ajax::success();
	}

	if (init('action') == 'setOrder') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$order = 1;
		foreach (json_decode(init('views'), true) as $id) {
			$view = view::byId($id);
			if (is_object($view)) {
				$view->setOrder($order);
				$view->save();
				$order++;
			}
		}
		ajax::success();
	}

	if (init('action') == 'removeImage') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$view = view::byId(init('id'));
		if (!is_object($view)) {
			throw new Exception(__('Vue inconnu. Vérifiez l\'ID ', __FILE__) . init('id'));
		}
		$view->setImage('sha512', '');
		$view->save();
		@rrmdir(__DIR__ . '/../../core/img/view');
		ajax::success();
	}

	if (init('action') == 'uploadImage') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$view = view::byId(init('id'));
		if (!is_object($view)) {
			throw new Exception(__('Objet inconnu. Vérifiez l\'ID', __FILE__));
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
		$files = ls(__DIR__ . '/../../data/view/','view'.$view->getId().'*');
		if(count($files)  > 0){
			foreach ($files as $file) {
				unlink(__DIR__ . '/../../data/view/'.$file);
			}
		}
		$view->setImage('type', str_replace('.', '', $extension));
		$view->setImage('sha512', sha512(file_get_contents($_FILES['file']['tmp_name'])));
		$filename = 'view'.$view->getId().'-'.$view->getImage('sha512') . '.' . $view->getImage('type');
		$filepath = __DIR__ . '/../../data/view/' . $filename;
		file_put_contents($filepath,file_get_contents($_FILES['file']['tmp_name']));
		if(!file_exists($filepath)){
			throw new \Exception(__('Impossible de sauvegarder l\'image',__FILE__));
		}
		$view->save();
		ajax::success();
	}

	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}
