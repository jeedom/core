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

	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

	if (init('action') == 'all') {
		$results = utils::o2a(interactDef::all());
		foreach ($results as &$result) {
			$result['nbInteractQuery'] = count(interactQuery::byInteractDefId($result['id']));
			$result['nbEnableInteractQuery'] = count(interactQuery::byInteractDefId($result['id'], true));
			if ($result['link_type'] == 'cmd' && $result['link_id'] != '') {
				$link_id = '';
				foreach (explode('&&', $result['link_id']) as $cmd_id) {
					$cmd = cmd::byId($cmd_id);
					if (is_object($cmd)) {
						$link_id .= cmd::cmdToHumanReadable('#' . $cmd->getId() . '# && ');
					}

				}
				$result['link_id'] = trim(trim($link_id), '&&');
			}
		}
		ajax::success($results);
	}

	if (init('action') == 'byId') {
		$result = utils::o2a(interactDef::byId(init('id')));
		$result['nbInteractQuery'] = count(interactQuery::byInteractDefId($result['id']));
		$result['nbEnableInteractQuery'] = count(interactQuery::byInteractDefId($result['id'], true));
		if ($result['link_type'] == 'cmd' && $result['link_id'] != '') {
			$link_id = '';
			foreach (explode('&&', $result['link_id']) as $cmd_id) {
				$cmd = cmd::byId($cmd_id);
				if (is_object($cmd)) {
					$link_id .= cmd::cmdToHumanReadable('#' . $cmd->getId() . '# && ');
				}

			}
			$result['link_id'] = trim(trim($link_id), '&&');
		}
		ajax::success($result);
	}

	if (init('action') == 'save') {
		$interact_json = json_decode(init('interact'), true);
		if (isset($interact_json['id'])) {
			$interact = interactDef::byId($interact_json['id']);
		}
		if (!isset($interact) || !is_object($interact)) {
			$interact = new interactDef();
		}
		utils::a2o($interact, $interact_json);
		$interact->save();
		ajax::success(utils::o2a($interact));
	}

	if (init('action') == 'regenerateInteract') {
		interactDef::regenerateInteract();
		ajax::success();
	}

	if (init('action') == 'remove') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$interact = interactDef::byId(init('id'));
		if (!is_object($interact)) {
			throw new Exception(__('Interaction inconnu verifié l\'id', __FILE__));
		}
		$interact->remove();
		ajax::success();
	}

	if (init('action') == 'changeState') {
		$interactQuery = interactQuery::byId(init('id'));
		if (!is_object($interactQuery)) {
			throw new Exception(__('InteractQuery ID inconnu', __FILE__));
		}
		$interactQuery->setEnable(init('enable'));
		$interactQuery->save();
		ajax::success();
	}

	if (init('action') == 'changeAllState') {
		$interactQueries = interactQuery::byInteractDefId(init('id'));
		if (is_array($interactQueries)) {
			foreach ($interactQueries as $interactQuery) {
				$interactQuery->setEnable(init('enable'));
				$interactQuery->save();
			}
		}
		ajax::success();
	}

	if (init('action') == 'test') {
		$return = array();
		$interactQuery = interactQuery::recognize(init('query'));
		if ($interactQuery == null) {
			ajax::success(array('interactQuery' => null));
		}
		$interactDef = interactDef::byId($interactQuery->getInteractDef_id());
		$return['interactQuery'] = utils::o2a($interactQuery);
		if ($interactQuery->getLink_type() == 'cmd') {
			$return['cmd'] = '';
			foreach (explode('&&', $interactQuery->getLink_id()) as $cmd_id) {
				$cmd = cmd::byId($cmd_id);
				if (is_object($cmd)) {
					$return['cmd'] .= '#' . $cmd->getHumanName() . '# && ';
				}
			}
			$return['cmd'] = trim($return['cmd'], '&& ');
			$reply = $interactDef->selectReply();
			if (trim($reply) == '') {
				$reply = self::replyOk();
			}
			$return['reply'] = $reply;
		}
		if ($interactQuery->getLink_type() == 'whatDoYouKnow') {
			$object = object::byId($interactQuery->getLink_id());
			if (is_object($object)) {
				$reply = interactQuery::whatDoYouKnow($object);
				if (trim($reply) == '') {
					$return['reply'] = __('Je ne sais rien sur ', __FILE__) . $object->getName();
				}
				$return['reply'] = $reply;
			}
			$return['reply'] = interactQuery::whatDoYouKnow();
		}
		if ($interactQuery->getLink_type() == 'scenario') {
			$return['scenario'] = '';
			$scenario = scenario::byId($interactQuery->getLink_id());
			if (!is_object($scenario)) {
				$return['scenario'] = __('Impossible de trouver le scénario correspondant', __FILE__);
			}
			$return['scenario'] = '#' . $scenario->getHumanName() . '#';
			switch ($interactDef->getOptions('scenario_action')) {
				case 'start':
					$return['action'] = __('lancer', __FILE__);
					break;
				case 'stop':
					$return['action'] = __('arreter', __FILE__);
					break;
				case 'activate':
					$return['action'] = __('activer', __FILE__);
					break;
				case 'deactivate':
					$return['action'] = __('desactiver', __FILE__);
					break;
				default:
					$return['action'] = __('erreur', __FILE__);
					break;
			}
			$reply = $interactDef->selectReply();
			if (trim($reply) == '') {
				$reply = self::replyOk();
			}
			$return['reply'] = $reply;
		}

		ajax::success($return);
	}

	throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>
