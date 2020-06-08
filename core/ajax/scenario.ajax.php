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
	
	ajax::init(array('templateupload'));
	
	if (init('action') == 'changeState') {
		$scenario = scenario::byId(init('id'));
		if (!is_object($scenario)) {
			throw new Exception(__('Scénario ID inconnu : ', __FILE__) . init('id'));
		}
		if (!$scenario->hasRight('x')) {
			throw new Exception(__('Vous n\'êtes pas autorisé à faire cette action', __FILE__));
		}
		switch (init('state')) {
			case 'start':
			if (!$scenario->getIsActive()) {
				throw new Exception(__('Impossible de lancer le scénario car il est désactivé. Veuillez l\'activer', __FILE__));
			}
			$scenario->launch('user', __('Scénario lancé manuellement', __FILE__), 0);
			break;
			case 'stop':
			$scenario->stop();
			break;
			case 'deactivate':
			$scenario->setIsActive(0);
			$scenario->save();
			break;
			case 'activate':
			$scenario->setIsActive(1);
			$scenario->save();
			break;
		}
		ajax::success();
	}
	
	if (init('action') == 'listScenarioHtml') {
		$return = array();
		foreach (scenario::all() as $scenario) {
			if ($scenario->getIsVisible() == 1) {
				$return[] = $scenario->toHtml(init('version'));
			}
		}
		ajax::success($return);
	}
	
	if (init('action') == 'setOrder') {
		unautorizedInDemo();
		$scenarios = json_decode(init('scenarios'), true);
		foreach ($scenarios as $scenario_json) {
			if (!isset($scenario_json['id']) || trim($scenario_json['id']) == '') {
				continue;
			}
			$scenario = scenario::byId($scenario_json['id']);
			if (!is_object($scenario)) {
				continue;
			}
			utils::a2o($scenario, $scenario_json);
			$scenario->save(true);
		}
		ajax::success();
	}
	
	if (init('action') == 'testExpression') {
		$return = array();
		$scenario = null;
		$return['evaluate'] = scenarioExpression::setTags(jeedom::fromHumanReadable(init('expression')), $scenario,true);
		$return['result'] = evaluate($return['evaluate']);
		$return['correct'] = 'ok';
		if (trim($return['result']) == trim($return['evaluate'])) {
			$return['correct'] = 'nok';
		}
		ajax::success($return);
	}
	
	if (init('action') == 'getTemplate') {
		ajax::success(scenario::getTemplate());
	}
	
	if (init('action') == 'convertToTemplate') {
		$scenario = scenario::byId(init('id'));
		if (!is_object($scenario)) {
			throw new Exception(__('Scénario ID inconnu : ', __FILE__) . init('id'));
		}
		$path = __DIR__ . '/../../data/scenario';
		if (!file_exists($path)) {
			mkdir($path);
		}
		if (trim(init('template')) == '' || trim(init('template')) == '.json') {
			throw new Exception(__('Le nom du template ne peut être vide ', __FILE__));
		}
		$name = init('template');
		file_put_contents($path . '/' . $name, json_encode($scenario->export('array'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
		if (!file_exists($path . '/' . $name)) {
			throw new Exception(__('Impossible de créer le template, vérifiez les droits : ', __FILE__) . $path . '/' . $name);
		}
		ajax::success();
	}
	
	if (init('action') == 'removeTemplate') {
		unautorizedInDemo();
		$path = __DIR__ . '/../../data/scenario';
		if (file_exists($path . '/' . init('template'))) {
			unlink($path . '/' . init('template'));
		}
		ajax::success();
	}
	
	if (init('action') == 'loadTemplateDiff') {
		$path = __DIR__ . '/../../data/scenario';
		if (!file_exists($path . '/' . init('template'))) {
			throw new Exception(__('Fichier non trouvé : ', __FILE__) . $path . '/' . init('template'));
		}
		$return = array();
		$fileContent = file_get_contents($path . '/' . init('template'));
		$fileLines = preg_split("/((\r?\n)|(\r\n?))/", $fileContent);
		foreach ($fileLines as $line) {
			preg_match_all("/#\[(.*?)\]\[(.*?)\]\[(.*?)\]#/", $line, $matches, PREG_SET_ORDER);
			if (count($matches) > 0) {
				foreach ($matches as $match) {
					$return[$match[0]] = '';
					$cmd = null;
					try {
						$cmd = cmd::byString($match[0]);
						if(is_object($cmd)){
							$return[$match[0]] = '#' . $cmd->getHumanName() . '#';
						}
					} catch (Exception $e) {
						
					}
				}
			} else {
				preg_match_all("/#\[(.*?)\]\[(.*?)\]#/", $line, $matches, PREG_SET_ORDER);
				if (count($matches) > 0) {
					foreach ($matches as $match) {
						$return[$match[0]] = '';
						try {
							$eqLogic = eqLogic::byString($match[0]);
							if(is_object($cmd)){
								$return[$match[0]] = '#' . $eqLogic->getHumanName() . '#';
							}
						} catch (Exception $e) {
							
						}
					}
				}
			}
			
			preg_match_all("/variable\((.*?)\)/", $line, $matches, PREG_SET_ORDER);
			if (count($matches) > 0) {
				foreach ($matches as $match) {
					$return[$match[1]] = $match[1];
				}
			}
		}
		ajax::success($return);
	}
	
	if (init('action') == 'applyTemplate') {
		unautorizedInDemo();
		$path = __DIR__ . '/../../data/scenario';
		if (!file_exists($path . '/' . init('template'))) {
			throw new Exception(__('Fichier non trouvé : ', __FILE__) . $path . '/' . init('template'));
		}
		foreach (json_decode(init('convert'), true) as $value) {
			if (trim($value['end']) == '') {
				throw new Exception(__('La conversion suivante ne peut être vide : ', __FILE__) . $value['begin']);
			}
			$converts[$value['begin']] = $value['end'];
		}
		$content = str_replace(array_keys($converts), $converts, file_get_contents($path . '/' . init('template')));
		$scenario_ajax = json_decode($content, true);
		$scenario_ajax['order'] = 9999;
		if (isset($scenario_ajax['name'])) {
			unset($scenario_ajax['name']);
		}
		if (isset($scenario_ajax['group'])) {
			unset($scenario_ajax['group']);
		}
		$scenario_db = scenario::byId(init('id'));
		if (!is_object($scenario_db)) {
			throw new Exception(__('Scénario ID inconnu : ', __FILE__) . init('id'));
		}
		if (!$scenario_db->hasRight('w')) {
			throw new Exception(__('Vous n\'êtes pas autorisé à faire cette action', __FILE__));
		}
		$scenario_db->setTrigger(array());
		$scenario_db->setSchedule(array());
		utils::a2o($scenario_db, $scenario_ajax);
		$scenario_db->save();
		$scenario_element_list = array();
		if (isset($scenario_ajax['elements'])) {
			foreach ($scenario_ajax['elements'] as $element_ajax) {
				$scenario_element_list[] = scenarioElement::saveAjaxElement($element_ajax);
			}
			$scenario_db->setScenarioElement($scenario_element_list);
		}
		$scenario_db->save();
		ajax::success();
	}
	
	if (init('action') == 'all') {
		$scenarios = scenario::all();
		$return = array();
		foreach ($scenarios as $scenario) {
			$info_scenario = utils::o2a($scenario);
			$info_scenario['humanName'] = $scenario->getHumanName();
			$return[] = $info_scenario;
		}
		ajax::success($return);
	}
	
	if (init('action') == 'saveAll') {
		unautorizedInDemo();
		$scenarios = json_decode(init('scenarios'), true);
		if (is_array($scenarios)) {
			foreach ($scenarios as $scenario_ajax) {
				$scenario = scenario::byId($scenario_ajax['id']);
				if (!is_object($scenario)) {
					continue;
				}
				utils::a2o($scenario, $scenario_ajax);
				$scenario->save();
			}
		}
		ajax::success();
	}
	
	if (init('action') == 'autoCompleteGroup') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$return = array();
		foreach (scenario::listGroup(init('term')) as $group) {
			$return[] = $group['group'];
		}
		ajax::success($return);
	}
	
	if (init('action') == 'toHtml') {
		if (init('id') == 'all' || is_json(init('id'))) {
			if (is_json(init('id'))) {
				$scenario_ajax = json_decode(init('id'), true);
				$scenarios = array();
				foreach ($scenario_ajax as $id) {
					$scenarios[] = scenario::byId($id);
				}
			} else {
				$scenarios = scenario::all();
			}
			$return = array();
			foreach ($scenarios as $scenario) {
				$return[] = $scenario->toHtml(init('version'));
			}
			ajax::success($return);
		} else {
			$scenario = scenario::byId(init('id'));
			if (is_object($scenario)) {
				ajax::success($scenario->toHtml(init('version')));
			}
		}
		ajax::success();
	}
	
	if (init('action') == 'remove') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$scenario = scenario::byId(init('id'));
		if (!is_object($scenario)) {
			throw new Exception(__('Scénario ID inconnu', __FILE__));
		}
		if (!$scenario->hasRight('w')) {
			throw new Exception(__('Vous n\'êtes pas autorisé à faire cette action', __FILE__));
		}
		$scenario->remove();
		ajax::success();
	}
	
	if (init('action') == 'emptyLog') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$scenario = scenario::byId(init('id'));
		if (!is_object($scenario)) {
			throw new Exception(__('Scénario ID inconnu', __FILE__));
		}
		if (!$scenario->hasRight('w')) {
			throw new Exception(__('Vous n\'êtes pas autorisé à faire cette action', __FILE__));
		}
		if (file_exists(__DIR__ . '/../../log/scenarioLog/scenario' . $scenario->getId() . '.log')) {
			unlink(__DIR__ . '/../../log/scenarioLog/scenario' . $scenario->getId() . '.log');
		}
		ajax::success();
	}
	
	if (init('action') == 'copy') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$scenario = scenario::byId(init('id'));
		if (!is_object($scenario)) {
			throw new Exception(__('Scénario ID inconnu', __FILE__));
		}
		ajax::success(utils::o2a($scenario->copy(init('name'))));
	}
	
	if (init('action') == 'get') {
		$scenario = scenario::byId(init('id'));
		if (!is_object($scenario)) {
			throw new Exception(__('Scénario ID inconnu', __FILE__));
		}
		$return = utils::o2a($scenario);
		$return['trigger'] = jeedom::toHumanReadable($return['trigger']);
		$return['forecast'] = $scenario->calculateScheduleDate();
		$return['elements'] = array();
		foreach ($scenario->getElement() as $element) {
			$return['elements'][] = $element->getAjaxElement();
		}
		$return['scenario_link'] = array('scenario' => array());
		$usedBy = $scenario->getUsedBy();
		foreach ($usedBy['scenario'] as $scenarioLink) {
			if($scenarioLink->getId() == $scenario->getId()){
				continue;
			}
			$return['scenario_link']['scenario'][$scenarioLink->getId()] = array('name' => $scenarioLink->getHumanName(),'isActive' => $scenarioLink->getIsActive());
		}
		$use = $scenario->getUse();
		foreach ($use['scenario'] as $scenarioLink) {
			if($scenarioLink->getId() == $scenario->getId()){
				continue;
			}
			$return['scenario_link']['scenario'][$scenarioLink->getId()] = array('name' => $scenarioLink->getHumanName(),'isActive' => $scenarioLink->getIsActive());
		}
		ajax::success($return);
	}
	
	if (init('action') == 'save') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		if (!is_json(init('scenario'))) {
			throw new Exception(__('Champs json invalide', __FILE__));
		}
		unautorizedInDemo();
		$time_dependance = 0;
		foreach (array('#time#', '#seconde#', '#heure#', '#minute#', '#jour#', '#mois#', '#annee#', '#timestamp#', '#date#', '#semaine#', '#sjour#', '#njour#', '#smois#') as $keyword) {
			if (strpos(init('scenario'), $keyword) !== false) {
				$time_dependance = 1;
				break;
			}
		}
		
		$has_return = 0;
		foreach (array('scenario_return') as $keyword) {
			if (strpos(init('scenario'), $keyword) !== false) {
				$has_return = 1;
				break;
			}
		}
		
		$scenario_ajax = json_decode(init('scenario'), true);
		if (isset($scenario_ajax['id'])) {
			$scenario_db = scenario::byId($scenario_ajax['id']);
		}
		if (!isset($scenario_db) || !is_object($scenario_db)) {
			$scenario_db = new scenario();
		} elseif (!$scenario_db->hasRight('w')) {
			throw new Exception(__('Vous n\'êtes pas autorisé à faire cette action', __FILE__));
		}
		if (isset($scenario_ajax['trigger'])) {
			$scenario_db->setTrigger(array());
		}
		if (isset($scenario_ajax['schedule'])) {
			$scenario_db->setSchedule(array());
		}
		utils::a2o($scenario_db, $scenario_ajax);
		$scenario_db->setConfiguration('timeDependency', $time_dependance);
		$scenario_db->setConfiguration('has_return', $has_return);
		$scenario_element_list = array();
		if (isset($scenario_ajax['elements'])) {
			foreach ($scenario_ajax['elements'] as $element_ajax) {
				$scenario_element_list[] = scenarioElement::saveAjaxElement($element_ajax);
			}
			$scenario_db->setScenarioElement($scenario_element_list);
		}
		$scenario_db->save();
		ajax::success(utils::o2a($scenario_db));
	}
	
	if (init('action') == 'actionToHtml') {
		if (init('params') != '' && is_json(init('params'))) {
			$return = array();
			$params = json_decode(init('params'), true);
			foreach ($params as $param) {
				if (!isset($param['options'])) {
					$param['options'] = array();
				}
				$html = scenarioExpression::getExpressionOptions($param['expression'], $param['options']);
				if (!isset($html['html']) || $html['html'] == '') {
					continue;
				}
				$return[] = array(
					'html' => $html,
					'id' => $param['id'],
				);
			}
			ajax::success($return);
		}
		ajax::success(scenarioExpression::getExpressionOptions(init('expression'), init('option')));
	}
	
	if (init('action') == 'templateupload') {
		unautorizedInDemo();
		$uploaddir = __DIR__ . '/../../data/scenario';
		if (!file_exists($uploaddir)) {
			mkdir($uploaddir);
		}
		if (!file_exists($uploaddir)) {
			throw new Exception(__('Répertoire de téléversement non trouvé : ', __FILE__) . $uploaddir);
		}
		if (!isset($_FILES['file'])) {
			throw new Exception(__('Aucun fichier trouvé. Vérifiez le paramètre PHP (post size limit)', __FILE__));
		}
		$extension = strtolower(strrchr($_FILES['file']['name'], '.'));
		if (!in_array($extension, array('.json'))) {
			throw new Exception(__('Extension du fichier non valide (autorisé .json) : ', __FILE__) . $extension);
		}
		if (filesize($_FILES['file']['tmp_name']) > 10000000) {
			throw new Exception(__('Le fichier est trop gros (maximum 10Mo)', __FILE__));
		}
		if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . '/' . $_FILES['file']['name'])) {
			throw new Exception(__('Impossible de déplacer le fichier temporaire', __FILE__));
		}
		if (!file_exists($uploaddir . '/' . $_FILES['file']['name'])) {
			throw new Exception(__('Impossible de téléverser le fichier (limite du serveur web ?)', __FILE__));
		}
		ajax::success();
	}
	
	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}
