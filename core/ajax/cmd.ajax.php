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
	
	if (init('action') == 'toHtml') {
		if (init('ids') != '') {
			$return = array();
			foreach (json_decode(init('ids'), true) as $id => $value) {
				$cmd = cmd::byId($id);
				if (!is_object($cmd)) {
					continue;
				}
				$return[$cmd->getId()] = array(
					'html' => $cmd->toHtml($value['version']),
					'id' => $cmd->getId(),
				);
			}
			ajax::success($return);
		} else {
			$cmd = cmd::byId(init('id'));
			if (!is_object($cmd)) {
				throw new Exception(__('Commande inconnue - Vérifiez l\'id', __FILE__));
			}
			$info_cmd = array();
			$info_cmd['id'] = $cmd->getId();
			$info_cmd['html'] = $cmd->toHtml(init('version'), init('option'), init('cmdColor', null));
			ajax::success($info_cmd);
		}
	}
	
	if (init('action') == 'setIsVisibles') {
		unautorizedInDemo();
		$cmds = json_decode(init('cmds'), true);
		foreach ($cmds as $id) {
			$cmd = cmd::byId($id);
			if (!is_object($cmd)) {
				throw new Exception(__('Cmd inconnu. Vérifiez l\'ID', __FILE__) . ' ' . $id);
			}
			$cmd->setIsVisible(init('isVisible'));
			$cmd->save(true);
		}
		ajax::success();
	}
	
	if (init('action') == 'execCmd') {
		$cmd = cmd::byId(init('id'));
		if (!is_object($cmd)) {
			throw new Exception(__('Commande ID inconnu : ', __FILE__) . init('id'));
		}
		$eqLogic = $cmd->getEqLogic();
		if ($cmd->getType() == 'action' && !$eqLogic->hasRight('x')) {
			throw new Exception(__('Vous n\'êtes pas autorisé à faire cette action', __FILE__));
		}
		if (!$cmd->checkAccessCode(init('codeAccess'))) {
			throw new Exception(__('Cette action nécessite un code d\'accès', __FILE__), -32005);
		}
		if ($cmd->getType() == 'action' && $cmd->getConfiguration('actionConfirm') == 1 && init('confirmAction') != 1) {
			throw new Exception(__('Cette action nécessite une confirmation', __FILE__), -32006);
		}
		$options = json_decode(init('value', '{}'), true);
			if (init('utid') != '') {
				$options['utid'] = init('utid');
			}
			ajax::success($cmd->execCmd($options));
		}
		
		if (init('action') == 'getByObjectNameEqNameCmdName') {
			$cmd = cmd::byObjectNameEqLogicNameCmdName(init('object_name'), init('eqLogic_name'), init('cmd_name'));
			if (!is_object($cmd)) {
				throw new Exception(__('Cmd inconnu : ', __FILE__) . init('object_name') . '/' . init('eqLogic_name') . '/' . init('cmd_name'));
			}
			ajax::success($cmd->getId());
		}
		
		if (init('action') == 'getByObjectNameCmdName') {
			$cmd = cmd::byObjectNameCmdName(init('object_name'), init('cmd_name'));
			if (!is_object($cmd)) {
				throw new Exception(__('Cmd inconnu : ', __FILE__) . init('object_name') . '/' . init('cmd_name'), 9999);
			}
			ajax::success(utils::o2a($cmd));
		}
		
		if (init('action') == 'byId') {
			$cmd = cmd::byId(init('id'));
			if (!is_object($cmd)) {
				throw new Exception(__('Commande inconnue : ', __FILE__) . init('id'), 9999);
			}
			ajax::success(jeedom::toHumanReadable(utils::o2a($cmd)));
		}
		
		if (init('action') == 'copyHistoryToCmd') {
			if (!isConnect('admin')) {
				throw new Exception(__('401 - Accès non autorisé', __FILE__));
			}
			unautorizedInDemo();
			ajax::success(history::copyHistoryToCmd(init('source_id'), init('target_id')));
		}
		
		if (init('action') == 'replaceCmd') {
			if (!isConnect('admin')) {
				throw new Exception(__('401 - Accès non autorisé', __FILE__));
			}
			unautorizedInDemo();
			ajax::success(jeedom::replaceTag(array('#' . str_replace('#', '', init('source_id')) . '#' => '#' . str_replace('#', '', init('target_id')) . '#')));
		}
		
		if (init('action') == 'byHumanName') {
			$cmd_id = cmd::humanReadableToCmd(init('humanName'));
			$cmd = cmd::byId(str_replace('#', '', $cmd_id));
			if (!is_object($cmd)) {
				throw new Exception(__('Commande inconnue : ', __FILE__) . init('humanName'), 9999);
			}
			ajax::success(utils::o2a($cmd));
		}
		
		if (init('action') == 'usedBy') {
			if (!isConnect('admin')) {
				throw new Exception(__('401 - Accès non autorisé', __FILE__));
			}
			$cmd = cmd::byId(init('id'));
			if (!is_object($cmd)) {
				throw new Exception(__('Commande inconnue : ', __FILE__) . init('id'), 9999);
			}
			$result = $cmd->getUsedBy();
			$return = array('cmd' => array(), 'eqLogic' => array(), 'scenario' => array());
			foreach ($result['cmd'] as $cmd) {
				$info = utils::o2a($cmd);
				$info['humanName'] = $cmd->getHumanName();
				$info['link'] = $cmd->getEqLogic()->getLinkToConfiguration();
				$return['cmd'][] = $info;
			}
			foreach ($result['eqLogic'] as $eqLogic) {
				$info = utils::o2a($eqLogic);
				$info['humanName'] = $eqLogic->getHumanName();
				$info['link'] = $eqLogic->getLinkToConfiguration();
				$return['eqLogic'][] = $info;
			}
			foreach ($result['scenario'] as $scenario) {
				$info = utils::o2a($cmd);
				$info['humanName'] = $scenario->getHumanName();
				$info['link'] = $scenario->getLinkToConfiguration();
				$return['scenario'][] = $info;
			}
			ajax::success($return);
		}
		
		if (init('action') == 'getHumanCmdName') {
			ajax::success(cmd::cmdToHumanReadable('#' . init('id') . '#'));
		}
		
		if (init('action') == 'byEqLogic') {
			ajax::success(utils::o2a(cmd::byEqLogicId(init('eqLogic_id'))));
		}
		
		if (init('action') == 'getCmd') {
			$cmd = cmd::byId(init('id'));
			if (!is_object($cmd)) {
				throw new Exception(__('Commande inconnue : ', __FILE__) . init('id'));
			}
			$return = jeedom::toHumanReadable(utils::o2a($cmd));
			$eqLogic = $cmd->getEqLogic();
			$return['eqLogic_name'] = $eqLogic->getName();
			$return['plugin'] = $eqLogic->getEqType_Name();
			if ($eqLogic->getObject_id() > 0) {
				$return['object_name'] = $eqLogic->getObject()->getName();
			}
			ajax::success($return);
		}
		
		if (init('action') == 'save') {
			if (!isConnect('admin')) {
				throw new Exception(__('401 - Accès non autorisé', __FILE__));
			}
			unautorizedInDemo();
			$cmd_ajax = jeedom::fromHumanReadable(json_decode(init('cmd'), true));
			$cmd = cmd::byId($cmd_ajax['id']);
			if (!is_object($cmd)) {
				$cmd = new cmd();
			}
			utils::a2o($cmd, $cmd_ajax);
			$cmd->save();
			ajax::success(utils::o2a($cmd));
		}
		
		if (init('action') == 'multiSave') {
			if (!isConnect('admin')) {
				throw new Exception(__('401 - Accès non autorisé', __FILE__));
			}
			unautorizedInDemo();
			$cmds = json_decode(init('cmd'), true);
			foreach ($cmds as $cmd_ajax) {
				$cmd = cmd::byId($cmd_ajax['id']);
				if (!is_object($cmd)) {
					continue;
				}
				utils::a2o($cmd, $cmd_ajax);
				$cmd->save(true);
			}
			ajax::success();
		}
		
		if (init('action') == 'changeHistoryPoint') {
			if (!isConnect('admin')) {
				throw new Exception(__('401 - Accès non autorisé', __FILE__));
			}
			unautorizedInDemo();
			$history = history::byCmdIdDatetime(init('cmd_id'), init('datetime'));
			if (!is_object($history)) {
				$history = history::byCmdIdDatetime(init('cmd_id'), init('datetime'), date('Y-m-d H:i:s', strtotime(init('datetime') . ' +1 hour')), init('oldValue'));
			}
			if (!is_object($history)) {
				$history = history::byCmdIdDatetime(init('cmd_id'), init('datetime'), date('Y-m-d H:i:s', strtotime(init('datetime') . ' +1 day')), init('oldValue'));
			}
			if (!is_object($history)) {
				$history = history::byCmdIdDatetime(init('cmd_id'), init('datetime'), date('Y-m-d H:i:s', strtotime(init('datetime') . ' +1 week')), init('oldValue'));
			}
			if (!is_object($history)) {
				$history = history::byCmdIdDatetime(init('cmd_id'), init('datetime'), date('Y-m-d H:i:s', strtotime(init('datetime') . ' +1 month')), init('oldValue'));
			}
			if (!is_object($history)) {
				throw new Exception(__('Aucun point ne correspond pour l\'historique : ', __FILE__) . init('cmd_id') . ' - ' . init('datetime'), init('oldValue'));
			}
			$value = init('value', null);
			if ($value === '') {
				$history->remove();
			} else {
				$history->setValue($value);
				$history->save(null, true);
			}
			ajax::success();
		}
		
		if (init('action') == 'getHistory') {
			global $JEEDOM_INTERNAL_CONFIG;
			$return = array();
			$data = array();
			$dateStart = null;
			$dateEnd = null;
			if (init('dateRange') != '' && init('dateRange') != 'all') {
				if (is_json(init('dateRange'))) {
					$dateRange = json_decode(init('dateRange'), true);
					if (isset($dateRange['start'])) {
						$dateStart = $dateRange['start'];
					}
					if (isset($dateRange['end'])) {
						$dateEnd = $dateRange['end'];
					}
				} else {
					$dateEnd = date('Y-m-d H:i:s');
					$dateStart = date('Y-m-d H:i:s', strtotime('- ' . init('dateRange') . ' ' . $dateEnd));
				}
			}
			
			if (init('dateStart') != '') {
				$dateStart = init('dateStart');
			}
			if (init('dateEnd') != '') {
				$dateEnd = init('dateEnd');
				if ($dateEnd == date('Y-m-d')) {
					$dateEnd = date('Y-m-d H:i:s');
				}
			}
			if (strtotime($dateEnd) > strtotime('now')) {
				$dateEnd = date('Y-m-d H:i:s');
			}
			
			if ($dateStart == '') {
				$dateStart =  init('startDate', date('Y-m-d', strtotime(config::byKey('history::defautShowPeriod') . ' ' . date('Y-m-d'))));
			}
			
			$return['maxValue'] = '';
			$return['minValue'] = '';
			if ($dateStart === null) {
				$return['dateStart'] = '';
			} else {
				$return['dateStart'] = $dateStart;
			}
			if ($dateEnd === null) {
				$return['dateEnd'] = '';
			} else {
				$return['dateEnd'] = $dateEnd;
			}
			
			if (is_numeric(init('id'))) {
				$cmd = cmd::byId(init('id'));
				if (!is_object($cmd)) {
					throw new Exception(__('Commande ID inconnu : ', __FILE__) . init('id'));
				}
				$eqLogic = $cmd->getEqLogic();
				if (!$eqLogic->hasRight('r')) {
					throw new Exception(__('Vous n\'êtes pas autorisé à faire cette action', __FILE__));
				}
				$groupingType=init('groupingType');
				if($groupingType == ''){
					$groupingType = $cmd->getDisplay('groupingType');
				}
				$derive = init('derive', $cmd->getDisplay('graphDerive'));
				if (trim($derive) == '') {
					$derive = $cmd->getDisplay('graphDerive');
				}
				if($derive){
					$groupingType = '';
				}
				$histories = $cmd->getHistory($dateStart, $dateEnd,$groupingType);
				$return['cmd_name'] = $cmd->getName();
				$return['history_name'] = $cmd->getHumanName();
				$return['unite'] = $cmd->getUnite();
				$return['cmd'] = utils::o2a($cmd);
				$return['eqLogic'] = utils::o2a($cmd->getEqLogic());
				$return['timelineOnly'] = $JEEDOM_INTERNAL_CONFIG['cmd']['type']['info']['subtype'][$cmd->getSubType()]['isHistorized']['timelineOnly'];
				$previousValue = null;
				$return['derive'] = $derive;
				foreach ($histories as $history) {
					$info_history = array();
					if($cmd->getDisplay('groupingType') != ''){
						$info_history[] = floatval(strtotime($history->getDatetime() . " UTC")) * 1000 - 1;
					}else{
						$info_history[] = floatval(strtotime($history->getDatetime() . " UTC")) * 1000;
					}
					if ($JEEDOM_INTERNAL_CONFIG['cmd']['type']['info']['subtype'][$cmd->getSubType()]['isHistorized']['timelineOnly']) {
						$value = $history->getValue();
					} else {
						$value = ($history->getValue() === null) ? null : floatval($history->getValue());
						if ($derive == 1 || $derive == '1') {
							if ($value !== null && $previousValue !== null) {
								$value = $value - $previousValue;
							} else {
								$value = null;
							}
							$previousValue = ($history->getValue() === null) ? null : floatval($history->getValue());
						}
					}
					$info_history[] = $value;
					if (!$JEEDOM_INTERNAL_CONFIG['cmd']['type']['info']['subtype'][$cmd->getSubType()]['isHistorized']['timelineOnly']) {
						if (($value !== null && $value > $return['maxValue']) || $return['maxValue'] == '') {
							$return['maxValue'] = round($value, 1);
						}
						if (($value !== null && $value < $return['minValue']) || $return['minValue'] == '') {
							$return['minValue'] = round($value, 1);
						}
					}
					$data[] = $info_history;
				}
			} else {
				$histories = history::getHistoryFromCalcul(jeedom::fromHumanReadable(init('id')), $dateStart, $dateEnd, init('allowZero', false),init('groupingType'));
				if (is_array($histories)) {
					foreach ($histories as $datetime => $value) {
						$info_history = array();
						$info_history[] = floatval($datetime) * 1000;
						$info_history[] = ($value === null) ? null : floatval($value);
						if ($value > $return['maxValue'] || $return['maxValue'] == '') {
							$return['maxValue'] = round($value, 1);
						}
						if ($value < $return['minValue'] || $return['minValue'] == '') {
							$return['minValue'] = round($value, 1);
						}
						$data[] = $info_history;
					}
				}
				$return['cmd_name'] = init('id');
				$return['history_name'] = init('id');
				$return['unite'] = init('unite');
			}
			if(init('lastPointToEnd') == 1){
				$last = end($data);
				if ($last[0] < (strtotime($dateEnd . " UTC") * 1000)) {
					$data[] = array((strtotime($dateEnd . " UTC") * 1000), $last[1]);
				}
			}
			$return['data'] = $data;
			ajax::success($return);
		}
		
		if (init('action') == 'emptyHistory') {
			if (!isConnect('admin')) {
				throw new Exception(__('401 - Accès non autorisé', __FILE__), -1234);
			}
			unautorizedInDemo();
			$cmd = cmd::byId(init('id'));
			if (!is_object($cmd)) {
				throw new Exception(__('Commande ID inconnu : ', __FILE__) . init('id'));
			}
			$cmd->emptyHistory(init('date'));
			ajax::success();
		}
		
		if (init('action') == 'setOrder') {
			unautorizedInDemo();
			$cmds = json_decode(init('cmds'), true);
			$eqLogics = array();
			foreach ($cmds as $cmd_json) {
				if (!isset($cmd_json['id']) || trim($cmd_json['id']) == '') {
					continue;
				}
				$cmd = cmd::byId($cmd_json['id']);
				if (!is_object($cmd)) {
					continue;
				}
				if($cmd->getOrder() != $cmd_json['order']){
					$cmd->setOrder($cmd_json['order']);
					$cmd->save(true);
				}
				if (isset($cmd_json['line']) && isset($cmd_json['column'])) {
					if(!isset($eqLogics[$cmd->getEqLogic_id()])){
						$eqLogics[$cmd->getEqLogic_id()] = array('eqLogic' => $cmd->getEqLogic(),'changed' => false);
					}
					if ($eqLogics[$cmd->getEqLogic_id()]['eqLogic']->getDisplay('layout::' . init('version', 'dashboard') . '::table::cmd::' . $cmd->getId() . '::line') != $cmd_json['line'] || $eqLogics[$cmd->getEqLogic_id()]['eqLogic']->getDisplay('layout::' . init('version', 'dashboard') . '::table::cmd::' . $cmd->getId() . '::column') != $cmd_json['column']) {
						$eqLogics[$cmd->getEqLogic_id()]['eqLogic']->setDisplay('layout::' . init('version', 'dashboard') . '::table::cmd::' . $cmd->getId() . '::line', $cmd_json['line']);
						$eqLogics[$cmd->getEqLogic_id()]['eqLogic']->setDisplay('layout::' . init('version', 'dashboard') . '::table::cmd::' . $cmd->getId() . '::column', $cmd_json['column']);
						$eqLogics[$cmd->getEqLogic_id()]['changed'] = true;
					}
				}
			}
			foreach($eqLogics as $eqLogic){
				if(!$eqLogic['changed']){
					continue;
				}
				$eqLogic['eqLogic']->save(true);
			}
			ajax::success();
		}
		
		if (init('action') == 'getDeadCmd') {
			$return = array(
				'core' => array('cmd' => jeedom::deadCmd(),'name' => __('Jeedom',__FILE__)),
				'cmd' => array('cmd' => cmd::deadCmd(),'name' => __('Commande',__FILE__)),
				'jeeObject' => array('cmd' => jeeObject::deadCmd(),'name' => __('Objet',__FILE__)),
				'scenario' => array('cmd' => scenario::consystencyCheck(true),'name' => __('Scénario',__FILE__)),
				'interactDef' => array('cmd' => interactDef::deadCmd(),'name' => __('Intéraction',__FILE__)),
				'user' => array('cmd' => user::deadCmd(),'name' => __('Utilisateur',__FILE__)),
			);
			foreach (plugin::listPlugin(true) as $plugin) {
				$plugin_id = $plugin->getId();
				$return[$plugin_id] =  array('cmd' => array(),'name' => 'Plugin '.$plugin->getName());
				if (method_exists($plugin_id, 'deadCmd')) {
					$return[$plugin_id]['cmd'] = $plugin_id::deadCmd();
				}else{
					$return[$plugin_id]['cmd'] = eqLogic::deadCmdGeneric($plugin_id);
				}
			}
			ajax::success($return);
		}
		
		throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
		/*     * *********Catch exeption*************** */
	} catch (Exception $e) {
		ajax::error(displayException($e), $e->getCode());
	}
	
