<?php

/** @entrypoint */
/** @ajax */

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

require_once __DIR__ . '/ajax.handler.inc.php';

ajaxHandle(function ()
{
    ajax::checkAccess('admin');
	if (init('action') == 'all') {
		$results = utils::o2a(interactDef::all());
		foreach ($results as &$result) {
			$result['nbInteractQuery'] = count(interactQuery::byInteractDefId($result['id']));
			$result['nbEnableInteractQuery'] = count(interactQuery::byInteractDefId($result['id'], true));
			if (isset($result['link_type']) && $result['link_type'] == 'cmd' && $result['link_id'] != '') {
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
		return $results;
	}
	
	if (init('action') == 'byId') {
		$result = utils::o2a(interactDef::byId(init('id')));
		$result['nbInteractQuery'] = count(interactQuery::byInteractDefId($result['id']));
		$result['nbEnableInteractQuery'] = count(interactQuery::byInteractDefId($result['id'], true));
		return jeedom::toHumanReadable($result);
	}
	
	if (init('action') == 'save') {
		unautorizedInDemo();
		$interact_json = jeedom::fromHumanReadable(json_decode(init('interact'), true));
		if (isset($interact_json['id'])) {
			$interact = interactDef::byId($interact_json['id']);
		}
		if (!isset($interact) || !is_object($interact)) {
			$interact = new interactDef();
		}
		utils::a2o($interact, $interact_json);
		$interact->save();
		return utils::o2a($interact);
	}
	
	if (init('action') == 'regenerateInteract') {
		interactDef::regenerateInteract();
		return '';
	}
	
	if (init('action') == 'remove') {
		unautorizedInDemo();
		$interact = interactDef::byId(init('id'));
		if (!is_object($interact)) {
			throw new Exception(__('Interaction inconnue. Vérifiez l\'ID', __FILE__));
		}
		$interact->remove();
		return '';
	}
	
	if (init('action') == 'changeState') {
		unautorizedInDemo();
		$interactQuery = interactQuery::byId(init('id'));
		if (!is_object($interactQuery)) {
			throw new Exception(__('InteractQuery ID inconnu', __FILE__));
		}
		$interactQuery->setEnable(init('enable'));
		$interactQuery->save();
		return '';
	}
	
	if (init('action') == 'changeAllState') {
		unautorizedInDemo();
		$interactQueries = interactQuery::byInteractDefId(init('id'));
		if (is_array($interactQueries)) {
			foreach ($interactQueries as $interactQuery) {
				$interactQuery->setEnable(init('enable'));
				$interactQuery->save();
			}
		}
		return '';
	}
	
	if (init('action') == 'execute') {
		return interactQuery::tryToReply(init('query'));
	}
	
	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
});
