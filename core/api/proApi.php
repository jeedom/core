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

require_once __DIR__ . "/../php/core.inc.php";

if (isset($argv)) {
	foreach ($argv as $arg) {
		$argList = explode('=', $arg);
		if (isset($argList[0]) && isset($argList[1])) {
			$_REQUEST[$argList[0]] = $argList[1];
		}
	}
}

try {
	$IP = getClientIp();
	$request = init('request');
	if ($request == '') {
		$request = file_get_contents("php://input");
	}
	log::add('apipro', 'info', $request . ' - IP :' . $IP);

	$jsonrpc = new jsonrpc($request);

	if (!jeedom::apiModeResult(config::byKey('api::core::pro::mode', 'core', 'enable'))) {
		throw new Exception(__('Vous n\'êtes pas autorisé à effectuer cette action', __FILE__), -32001);
	}

	if ($jsonrpc->getJsonrpc() != '2.0') {
		throw new Exception(__('Requête invalide. Version JSON-RPC invalide : ' . $jsonrpc->getJsonrpc(), __FILE__), -32001);
	}

	$params = $jsonrpc->getParams();

	if (!isset($params['proapi'])) {
		throw new Exception(__('Vous n\'êtes pas autorisé à effectuer cette action', __FILE__), -32001);
	}

	if (isset($params['proapi']) && !jeedom::apiAccess($params['proapi'], 'apipro')) {
		throw new Exception(__('Vous n\'êtes pas autorisé à effectuer cette action', __FILE__), -32001);
	}

	log::add('api', 'info', __('connexion valide et verifiée : ' . $jsonrpc->getMethod(), __FILE__));

	/*             * ************************config*************************** */
	if ($jsonrpc->getMethod() == 'config::byKey') {
		$jsonrpc->makeSuccess(config::byKey($params['key'], $params['plugin'], $params['default']));
	}

	if ($jsonrpc->getMethod() == 'config::save') {
		$jsonrpc->makeSuccess(config::save($params['key'], $params['value'], $params['plugin']));
	}

	if (isset($params['plugin']) && $params['plugin'] != '') {
		log::add('api', 'info', 'Demande pour le plugin : ' . secureXSS($params['plugin']));
		include_file('core', $params['plugin'], 'api', $params['plugin']);
	} else {
		/*             * ***********************Ping********************************* */
		if ($jsonrpc->getMethod() == 'ping') {
			$jsonrpc->makeSuccess('pong');
		}

		/*             * ***********************Version********************************* */
		if ($jsonrpc->getMethod() == 'version') {
			$jsonrpc->makeSuccess(jeedom::version());
		}

		/*             * ***********************Health********************************* */
		if ($jsonrpc->getMethod() == 'health') {
			$health = array();

			$defaut = 0;
			$result = 'OK';
			$advice = '';
			$nbNeedUpdate = update::nbNeedUpdate();
			if ($nbNeedUpdate > 0) {
				$defaut = 1;
				$result = $nbNeedUpdate;
			}
			$health[] = array('plugin' => 'core', 'type' => 'Système à jour', 'defaut' => $defaut, 'result' => $result, 'advice' => $advice);

			$defaut = 0;
			$result = 'OK';
			$advice = '';
			if (config::byKey('enableCron', 'core', 1, true) == 0) {
				$defaut = 1;
				$result = 'NOK';
				$advice = __('Erreur cron : les crons sont désactivés. Allez dans Administration -> Moteur de tâches pour les réactiver', __FILE__);
			}
			$health[] = array('plugin' => 'core', 'type' => 'Cron actif', 'defaut' => $defaut, 'result' => $result, 'advice' => $advice);

			$defaut = 0;
			$result = 'OK';
			$advice = '';
			if (config::byKey('enableScenario') == 0 && count(scenario::all()) > 0) {
				$defaut = 1;
				$result = 'NOK';
				$advice = __('Erreur scénario : tous les scénarios sont désactivés. Allez dans Outils -> Scénarios pour les réactiver', __FILE__);
			}
			$health[] = array('plugin' => 'core', 'type' => 'Scénario actif', 'defaut' => $defaut, 'result' => $result, 'advice' => $advice);

			$defaut = 0;
			$result = 'OK';
			$advice = '';
			if (!jeedom::isStarted()) {
				$defaut = 1;
				$result = 'NOK';
			}
			$health[] = array('plugin' => 'core', 'type' => 'Démarré', 'defaut' => $defaut, 'result' => $result, 'advice' => $advice);

			$defaut = 0;
			$result = 'OK';
			$advice = '';
			if (!jeedom::isDateOk()) {
				$defaut = 1;
				$result = date('Y-m-d H:i:s');
			}
			$health[] = array('plugin' => 'core', 'type' => 'Date système', 'defaut' => $defaut, 'result' => $result, 'advice' => $advice);

			$defaut = 0;
			$result = 'OK';
			$advice = '';
			if (!jeedom::isCapable('sudo')) {
				$defaut = 1;
				$result = 'NOK';
				$advice = 'Donnez les droits root à Jeedom.';
			}
			$health[] = array('plugin' => 'core', 'type' => 'Droits sudo', 'defaut' => $defaut, 'result' => $result, 'advice' => $advice);

			$defaut = 0;
			$result = jeedom::version();
			$advice = '';
			$health[] = array('plugin' => 'core', 'type' => 'Version Jeedom', 'defaut' => $defaut, 'result' => $result, 'advice' => $advice);

			$defaut = 0;
			$result = phpversion();
			$advice = '';
			if (version_compare(phpversion(), '5.5', '<')) {
				$defaut = 1;
				$advice = __('Si vous êtes en version 5.4.x, on vous indiquera quand la version 5.5 sera obligatoire', __FILE__);
			}
			$health[] = array('plugin' => 'core', 'type' => 'Version PHP', 'defaut' => $defaut, 'result' => $result, 'advice' => $advice);

			$defaut = 0;
			$version = DB::Prepare('select version()', array(), DB::FETCH_TYPE_ROW);
			$result = $version['version()'];
			$advice = '';
			$health[] = array('plugin' => 'core', 'type' => 'Version database', 'defaut' => $defaut, 'result' => $result, 'advice' => $advice);

			$defaut = 0;
			$result = jeedom::checkSpaceLeft();
			$advice = '';
			if ($result < 10) {
				$defaut = 1;
			}
			$health[] = array('plugin' => 'core', 'type' => 'Espace disque libre', 'defaut' => $defaut, 'result' => $result . ' %', 'advice' => $advice);

			$defaut = 0;
			$result = 'OK';
			$advice = '';
			if (!network::test('internal')) {
				$defaut = 1;
				$result = 'NOK';
				$advice = __('Allez sur Administration -> Configuration -> Réseaux, puis configurez correctement la partie réseau', __FILE__);
			}
			$health[] = array('plugin' => 'core', 'type' => 'Configuration réseau interne', 'defaut' => $defaut, 'result' => $result, 'advice' => $advice);

			$defaut = 0;
			$result = 'OK';
			$advice = '';
			if (!network::test('external')) {
				$defaut = 1;
				$result = 'NOK';
				$advice = __('Allez sur Administration -> Configuration -> Réseaux, puis configurez correctement la partie réseau', __FILE__);
			}
			$health[] = array('plugin' => 'core', 'type' => 'Configuration réseau externe', 'defaut' => $defaut, 'result' => $result, 'advice' => $advice);

			$defaut = 0;
			$advice = '';
			if (cache::isPersistOk()) {
				if (config::byKey('cache::engine') != 'FilesystemCache' && config::byKey('cache::engine') != 'PhpFileCache') {
					$result = 'OK';
				} else {
					$filename = __DIR__ . '/../../cache.tar.gz';
					$result = 'OK (' . date('Y-m-d H:i:s', filemtime($filename)) . ')';
				}
			} else {
				$result = 'NOK';
				$defaut = 1;
				$advice = __('Votre cache n\'est pas sauvegardé. En cas de redémarrage, certaines informations peuvent être perdues. Essayez de lancer (à partir du moteur de tâches) la tâche cache::persist.', __FILE__);
			}
			$health[] = array('plugin' => 'core', 'type' => 'Persistance du cache', 'defaut' => $defaut, 'result' => $result, 'advice' => $advice);

			foreach (plugin::listPlugin(true) as $plugin) {
				$plugin_id = $plugin->getId();
				$result = 'OK';
				$defaut = 0;
				$advice = '';
				$hasSpecificHealth = 0;
				$hasSpecificHealthIcon = '';
				try {
					if ($plugin->getHasDependency() == 1) {
						$dependancy_info = $plugin->dependancy_info();
						switch ($dependancy_info['state']) {
							case 'ok':
								$result = 'OK';
								$defaut = 0;
								break;
							case 'nok':
								$result = 'NOK';
								$defaut = 1;
								break;
							case 'in_progress':
								$result = 'En cours';
								$defaut = 0;
								break;
							default:
								break;
						}
						$health[] = array('plugin' => $plugin_id, 'type' => 'dépendance', 'defaut' => $defaut, 'result' => $result, 'advice' => $advice);
					}
				} catch (Exception $e) {

				}
				try {
					if ($plugin->getHasOwnDeamon() == 1) {
						$deamon_info = $plugin->deamon_info();
						switch ($deamon_info['launchable']) {
							case 'ok':
								$result = 'OK';
								$defaut = 0;
								break;
							case 'nok':
								if ($deamon_info['auto'] != 1) {
									$result = 'Désactivé';
									$defaut = 0;
									$advice = $deamon_info['launchable_message'];
								} else {
									$result = 'NOK';
									$defaut = 1;
									$advice = $deamon_info['launchable_message'];
								}
								break;
						}
						$health[] = array('plugin' => $plugin_id, 'type' => 'Configuration démon', 'defaut' => $defaut, 'result' => $result, 'advice' => $advice);

						switch ($deamon_info['state']) {
							case 'ok':
								$result = 'OK';
								$defaut = 0;
								break;
							case 'nok':
								if ($deamon_info['auto'] != 1) {
									$result = 'Désactivé';
									$defaut = 0;
								} else {
									$result = 'NOK';
									$defaut = 1;
								}
								break;
						}
						$health[] = array('plugin' => $plugin_id, 'type' => 'Statut démon', 'defaut' => $defaut, 'result' => $result, 'advice' => $advice);
					}
				} catch (Exception $e) {

				}

				try {
					if (method_exists($plugin->getId(), 'health')) {

						foreach ($plugin_id::health() as $result) {
							if ($result['state']) {
								$defaut = 0;
							} else {
								$defaut = 1;
							}
							$health[] = array('plugin' => $plugin_id, 'type' => $result['test'], 'defaut' => $defaut, 'result' => $result['result'], 'advice' => $result['advice']);
						}
					}
				} catch (Exception $e) {

				}
			}

			$jsonrpc->makeSuccess($health);
		}

		/*             * ************************Plugin*************************** */
		if ($jsonrpc->getMethod() == 'plugin::listPlugin') {
			$activateOnly = (isset($params['activateOnly']) && $params['activateOnly'] == 1) ? true : false;
			$orderByCaterogy = (isset($params['orderByCaterogy']) && $params['orderByCaterogy'] == 1) ? true : false;
			$jsonrpc->makeSuccess(utils::o2a(plugin::listPlugin($activateOnly, $orderByCaterogy)));
		}

		/*             * ************************Object*************************** */
		if ($jsonrpc->getMethod() == 'jeeObject::all') {
			$jsonrpc->makeSuccess(utils::o2a(jeeObject::all()));
		}

		if ($jsonrpc->getMethod() == 'jeeObject::byId') {
			$object = jeeObject::byId($params['id']);
			if (!is_object($object)) {
				throw new Exception('Objet introuvable : ' . secureXSS($params['id']), -32601);
			}
			$jsonrpc->makeSuccess(utils::o2a($object));
		}

		if ($jsonrpc->getMethod() == 'jeeObject::full') {
			$jsonrpc->makeSuccess(jeeObject::fullData());
		}

		if ($jsonrpc->getMethod() == 'jeeObject::fullById') {
			$object = jeeObject::byId($params['id']);
			if (!is_object($object)) {
				throw new Exception('Objet introuvable : ' . secureXSS($params['id']), -32601);
			}
			$return = utils::o2a($object);
			$return['eqLogics'] = array();
			foreach ($object->getEqLogic() as $eqLogic) {
				$eqLogic_return = utils::o2a($eqLogic);
				$eqLogic_return['cmds'] = array();
				foreach ($eqLogic->getCmd() as $cmd) {
					$cmd_return = utils::o2a($cmd);
					if ($cmd->getType() == 'info') {
						$cmd_return['state'] = $cmd->execCmd();
					}
					$eqLogic_return['cmds'][] = $cmd_return;
				}
				$return['eqLogics'][] = $eqLogic_return;
			}
			$jsonrpc->makeSuccess($return);
		}

		/*             * ************************datastore*************************** */

		if ($jsonrpc->getMethod() == 'datastore::byTypeLinkIdKey') {
			$jsonrpc->makeSuccess(dataStore::byTypeLinkIdKey($params['type'], $params['linkId'], $params['key']));
		}

		if ($jsonrpc->getMethod() == 'datastore::save') {
			$dataStore = new dataStore();
			$dataStore->setType($params['type']);
			$dataStore->setKey($params['key']);
			$dataStore->setValue($params['value']);
			$dataStore->setLink_id($params['linkId']);
			$dataStore->save();
			$jsonrpc->makeSuccess('ok');
		}

		/*             * ************************Equipement*************************** */
		if ($jsonrpc->getMethod() == 'eqLogic::all') {
			$jsonrpc->makeSuccess(utils::o2a(eqLogic::all()));
		}

		if ($jsonrpc->getMethod() == 'eqLogic::byType') {
			$jsonrpc->makeSuccess(utils::o2a(eqLogic::byType($params['type'])));
		}

		if ($jsonrpc->getMethod() == 'eqLogic::byObjectId') {
			$jsonrpc->makeSuccess(utils::o2a(eqLogic::byObjectId($params['object_id'])));
		}

		if ($jsonrpc->getMethod() == 'eqLogic::byId') {
			$eqLogic = eqLogic::byId($params['id']);
			if (!is_object($eqLogic)) {
				throw new Exception('EqLogic introuvable : ' . secureXSS($params['id']), -32602);
			}
			$jsonrpc->makeSuccess(utils::o2a($eqLogic));
		}

		if ($jsonrpc->getMethod() == 'eqLogic::fullById') {
			$eqLogic = eqLogic::byId($params['id']);
			if (!is_object($eqLogic)) {
				throw new Exception('EqLogic introuvable : ' . secureXSS($params['id']), -32602);
			}
			$return = utils::o2a($eqLogic);
			$return['cmds'] = array();
			foreach ($eqLogic->getCmd() as $cmd) {
				$cmd_return = utils::o2a($cmd);
				if ($cmd->getType() == 'info') {
					$cmd_return['state'] = $cmd->execCmd();
				}
				$return['cmds'][] = $cmd_return;
			}
			$jsonrpc->makeSuccess($return);
		}

		if ($jsonrpc->getMethod() == 'eqLogic::save') {
			$typeEqLogic = $params['eqType_name'];
			$typeCmd = $typeEqLogic . 'Cmd';
			if ($typeEqLogic == '' || !class_exists($typeEqLogic) || !class_exists($typeCmd)) {
				throw new Exception(__('Type incorrect (classe commande inexistante)', __FILE__) . secureXSS($typeCmd));
			}
			$eqLogic = null;
			if (isset($params['id'])) {
				$eqLogic = $typeEqLogic::byId($params['id']);
			}
			if (!is_object($eqLogic)) {
				$eqLogic = new $typeEqLogic();
				$eqLogic->setEqType_name($params['eqType_name']);
			}
			utils::a2o($eqLogic, jeedom::fromHumanReadable($params));
			$eqLogic->save();
			$dbList = $typeCmd::byEqLogicId($eqLogic->getId());
			$eqLogic->save();
			$enableList = array();
			if (isset($params['cmd'])) {
				$cmd_order = 0;
				foreach ($params['cmd'] as $cmd_info) {
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

				//suppression des entrées inexistante.
				foreach ($dbList as $dbObject) {
					if (!isset($enableList[$dbObject->getId()]) && !$dbObject->dontRemoveCmd()) {
						$dbObject->remove();
					}
				}
			}
			$jsonrpc->makeSuccess(utils::o2a($eqLogic));
		}

		if ($jsonrpc->getMethod() == 'eqLogic::byTypeAndId') {
			$return = array();
			foreach ($params['eqType'] as $eqType) {
				$info_eqLogics = array();
				foreach (eqLogic::byType($eqType) as $eqLogic) {
					$info_cmds = array();
					foreach ($eqLogic->getCmd() as $cmd) {
						$info_cmd = utils::o2a($cmd);
						if ($cmd->getType() == 'info') {
							$info_cmd['value'] = $cmd->execCmd();
							$info_cmd['collectDate'] = $cmd->getCollectDate();
						}
						$info_cmds[] = $info_cmd;
					}
					$info_eqLogic = utils::o2a($eqLogic);
					$info_eqLogic['cmds'] = $info_cmds;
					$info_eqLogics[] = $info_eqLogic;
				}
				$return[$eqType] = $info_eqLogics;
			}

			foreach ($params['id'] as $id) {
				$eqLogic = eqLogic::byId($id);
				$info_cmds = array();
				foreach ($eqLogic->getCmd() as $cmd) {
					$info_cmd = utils::o2a($cmd);
					if ($cmd->getType() == 'info') {
						$info_cmd['value'] = $cmd->execCmd();
						$info_cmd['collectDate'] = $cmd->getCollectDate();
					}
					$info_cmds[] = $info_cmd;
				}

				$info_eqLogic = utils::o2a($eqLogic);
				$info_eqLogic['cmds'] = $info_cmds;
				$return[$id] = $info_eqLogic;
			}
			$jsonrpc->makeSuccess($return);

		}

		/*             * ************************Commande*************************** */
		if ($jsonrpc->getMethod() == 'cmd::all') {
			$jsonrpc->makeSuccess(utils::o2a(cmd::all()));
		}

		if ($jsonrpc->getMethod() == 'cmd::byEqLogicId') {
			$jsonrpc->makeSuccess(utils::o2a(cmd::byEqLogicId($params['eqLogic_id'])));
		}

		if ($jsonrpc->getMethod() == 'cmd::byId') {
			$cmd = cmd::byId($params['id']);
			if (!is_object($cmd)) {
				throw new Exception(__('Commande introuvable : ', __FILE__) . secureXSS($params['id']), -32701);
			}
			$jsonrpc->makeSuccess(utils::o2a($cmd));
		}

		if ($jsonrpc->getMethod() == 'cmd::execCmd') {
			if (is_array($params['id'])) {
				$return = array();
				foreach ($params['id'] as $id) {
					$cmd = cmd::byId($id);
					if (!is_object($cmd)) {
						throw new Exception(__('Commande introuvable : ', __FILE__) . secureXSS($id), -32702);
					}
					$return[$id] = array('value' => $cmd->execCmd($params['options']), 'collectDate' => $cmd->getCollectDate());
				}
			} else {
				$cmd = cmd::byId($params['id']);
				if (!is_object($cmd)) {
					throw new Exception(__('Commande introuvable : ', __FILE__) . secureXSS($params['id']), -32702);
				}
				$return = array('value' => $cmd->execCmd($params['options']), 'collectDate' => $cmd->getCollectDate());
			}
			$jsonrpc->makeSuccess($return);
		}

		if ($jsonrpc->getMethod() == 'cmd::getStatistique') {
			$cmd = cmd::byId($params['id']);
			if (!is_object($cmd)) {
				throw new Exception(__('Commande introuvable : ', __FILE__) . secureXSS($params['id']), -32702);
			}
			$jsonrpc->makeSuccess($cmd->getStatistique($params['startTime'], $params['endTime']));
		}

		if ($jsonrpc->getMethod() == 'cmd::getTendance') {
			$cmd = cmd::byId($params['id']);
			if (!is_object($cmd)) {
				throw new Exception(__('Commande introuvable : ', __FILE__) . secureXSS($params['id']), -32702);
			}
			$jsonrpc->makeSuccess($cmd->getTendance($params['startTime'], $params['endTime']));
		}

		if ($jsonrpc->getMethod() == 'cmd::getHistory') {
			$cmd = cmd::byId($params['id']);
			if (!is_object($cmd)) {
				throw new Exception('Commande introuvable : ' . secureXSS($params['id']), -32702);
			}
			$jsonrpc->makeSuccess(utils::o2a($cmd->getHistory($params['startTime'], $params['endTime'])));
		}

		/*             * ************************Scénario*************************** */
		if ($jsonrpc->getMethod() == 'scenario::all') {
			$jsonrpc->makeSuccess(utils::o2a(scenario::all()));
		}

		if ($jsonrpc->getMethod() == 'scenario::byId') {
			$scenario = scenario::byId($params['id']);
			if (!is_object($scenario)) {
				throw new Exception('Scénario introuvable : ' . secureXSS($params['id']), -32703);
			}
			$jsonrpc->makeSuccess(utils::o2a($scenario));
		}

		if ($jsonrpc->getMethod() == 'scenario::changeState') {
			$scenario = scenario::byId($params['id']);
			if (!is_object($scenario)) {
				throw new Exception('Scénario introuvable : ' . secureXSS($params['id']), -32702);
			}
			if ($params['state'] == 'stop') {
				$jsonrpc->makeSuccess($scenario->stop());
			}
			if ($params['state'] == 'run') {
				$jsonrpc->makeSuccess($scenario->launch(__('Scénario exécuté sur appel API', __FILE__)));
			}
			if ($params['state'] == 'enable') {
				$scenario->setIsActive(1);
				$jsonrpc->makeSuccess($scenario->save());
			}
			if ($params['state'] == 'disable') {
				$scenario->setIsActive(0);
				$jsonrpc->makeSuccess($scenario->save());
			}
			throw new Exception(__('Le paramètre "state" ne peut être vide et doit avoir pour valeur [run,stop,enable,disable]', __FILE__));
		}

		/*             * ************************JeeNetwork*************************** */
		if ($jsonrpc->getMethod() == 'jeeNetwork::handshake') {
			if (config::byKey('jeeNetwork::mode') != 'slave') {
				throw new Exception(__('Impossible d\'ajouter une box Jeedom non esclave à un réseau Jeedom', __FILE__));
			}
			$auiKey = config::byKey('auiKey');
			if ($auiKey == '') {
				$auiKey = config::genKey(255);
				config::save('auiKey', $auiKey);
			}
			$return = array(
				'mode' => config::byKey('jeeNetwork::mode'),
				'nbUpdate' => update::nbNeedUpdate(),
				'version' => jeedom::version(),
				'nbMessage' => message::nbMessage(),
				'auiKey' => $auiKey,
				'jeedom::url' => config::byKey('jeedom::url'),
				'ngrok::port' => config::byKey('ngrok::port'),
			);
			if (!filter_var(network::getNetworkAccess('external', 'ip'), FILTER_VALIDATE_IP) && network::getNetworkAccess('external', 'ip') != '') {
				$return['jeedom::url'] = network::getNetworkAccess('internal');
			}
			foreach (plugin::listPlugin(true) as $plugin) {
				if ($plugin->getAllowRemote() == 1) {
					$return['plugin'][] = $plugin->getId();
				}
			}
			$address = (isset($params['address']) && $params['address'] != '') ? $params['address'] : getClientIp();
			config::save('jeeNetwork::master::ip', $address);
			config::save('jeeNetwork::master::apikey', $params['apikey_master']);
			config::save('jeeNetwork::slave::id', $params['slave_id']);
			if (config::byKey('internalAddr') == '') {
				config::save('internalAddr', $params['slave_ip']);
			}
			jeeNetwork::testMaster();
			$jsonrpc->makeSuccess($return);
		}

		if ($jsonrpc->getMethod() == 'jeeNetwork::reload') {
			foreach (plugin::listPlugin(true) as $plugin) {
				try {
					$plugin->launch('slaveReload');
				} catch (Exception $ex) {

				}
			}
			$jsonrpc->makeSuccess('ok');
		}

		if ($jsonrpc->getMethod() == 'jeeNetwork::halt') {
			jeedom::haltSystem();
			$jsonrpc->makeSuccess('ok');
		}

		if ($jsonrpc->getMethod() == 'jeeNetwork::reboot') {
			jeedom::rebootSystem();
			$jsonrpc->makeSuccess('ok');
		}

		if ($jsonrpc->getMethod() == 'jeeNetwork::update') {
			jeedom::update('', 0);
			$jsonrpc->makeSuccess('ok');
		}

		if ($jsonrpc->getMethod() == 'jeeNetwork::checkUpdate') {
			update::checkAllUpdate();
			$jsonrpc->makeSuccess('ok');
		}

		if ($jsonrpc->getMethod() == 'jeeNetwork::receivedBackup') {
			if (config::byKey('jeeNetwork::mode') == 'slave') {
				throw new Exception(__('Seul un maître peut recevoir une sauvegarde', __FILE__));
			}
			$jeeNetwork = jeeNetwork::byId($params['slave_id']);
			if (!is_object($jeeNetwork)) {
				throw new Exception(__('Aucun esclave correspondant à l\'ID : ', __FILE__) . secureXSS($params['slave_id']));
			}
			if (substr(config::byKey('backup::path'), 0, 1) != '/') {
				$backup_dir = __DIR__ . '/../../' . config::byKey('backup::path');
			} else {
				$backup_dir = config::byKey('backup::path');
			}
			$uploaddir = $backup_dir . '/slave/';
			if (!file_exists($uploaddir)) {
				mkdir($uploaddir);
			}
			if (!file_exists($uploaddir)) {
				throw new Exception(__('Répertoire de téléversement non trouvé : ', __FILE__) . secureXSS($uploaddir));
			}
			$_file = $_FILES['file'];
			$extension = strtolower(strrchr($_file['name'], '.'));
			if (!in_array($extension, array('.tar.gz', '.gz', '.tar'))) {
				throw new Exception(__('Extension du fichier non valide (autorisé .tar.gz, .tar et .gz) : ', __FILE__) . secureXSS($extension));
			}
			if (filesize($_file['tmp_name']) > 50000000) {
				throw new Exception(__('La taille du fichier est trop importante (maximum 50Mo)', __FILE__));
			}
			$uploadfile = $uploaddir . $jeeNetwork->getId() . '-' . $jeeNetwork->getName() . '-' . $jeeNetwork->getConfiguration('version') . '-' . date('Y-m-d_H\hi') . '.tar' . $extension;
			if (!move_uploaded_file($_file['tmp_name'], $uploadfile)) {
				throw new Exception(__('Impossible de téléverser le fichier', __FILE__));
			}
			system('find ' . $uploaddir . $jeeNetwork->getId() . '*' . ' -mtime +' . config::byKey('backup::keepDays') . ' -print | xargs -r rm');
			$jsonrpc->makeSuccess('ok');
		}

		if ($jsonrpc->getMethod() == 'jeeNetwork::restoreBackup') {
			if (config::byKey('jeeNetwork::mode') != 'slave') {
				throw new Exception(__('Seul un esclave peut restaurer une sauvegarde', __FILE__));
			}
			if (substr(config::byKey('backup::path'), 0, 1) != '/') {
				$uploaddir = __DIR__ . '/../../' . config::byKey('backup::path');
			} else {
				$uploaddir = config::byKey('backup::path');
			}
			if (!file_exists($uploaddir)) {
				mkdir($uploaddir);
			}
			if (!file_exists($uploaddir)) {
				throw new Exception(__('Répertoire de téléversement non trouvé : ', __FILE__) . secureXSS($uploaddir));
			}
			$_file = $_FILES['file'];
			$extension = strtolower(strrchr($_file['name'], '.'));
			if (!in_array($extension, array('.tar.gz', '.gz', '.tar'))) {
				throw new Exception(__('Extension du fichier non valide (autorisé .tar.gz, .tar et .gz) : ', __FILE__) . secureXSS($extension));
			}
			if (filesize($_file['tmp_name']) > 50000000) {
				throw new Exception(__('La taille du fichier est trop importante (maximum 50Mo)', __FILE__));
			}
			$backup_name = 'backup-' . jeedom::version() . '-' . date("d-m-Y-H\hi") . '.tar.gz';
			$uploadfile = $uploaddir . '/' . $backup_name;
			if (!move_uploaded_file($_file['tmp_name'], $uploadfile)) {
				throw new Exception(__('Impossible de téléverser le fichier', __FILE__));
			}
			jeedom::restore($uploadfile, true);
			$jsonrpc->makeSuccess('ok');
		}

		if ($jsonrpc->getMethod() == 'jeeNetwork::backup') {
			jeedom::backup(true);
			$jsonrpc->makeSuccess('ok');
		}

		/*             * ************************Backup*************************** */

		if ($jsonrpc->getMethod() == 'backup::list') {
			$jsonrpc->makeSuccess(jeedom::listBackup());
		}

		if ($jsonrpc->getMethod() == 'backup::launch') {
			jeedom::backup(true);
			$jsonrpc->makeSuccess();
		}

		if ($jsonrpc->getMethod() == 'backup::remove') {
			jeedom::removeBackup($params['backup']);
			$jsonrpc->makeSuccess();
		}

		if ($jsonrpc->getMethod() == 'backup::restore') {
			jeedom::restore($params['backup'], true);
			$jsonrpc->makeSuccess();
		}

		if ($jsonrpc->getMethod() == 'backup::listMarket') {
			$jsonrpc->makeSuccess(repo_market::backup_list());
		}

		if ($jsonrpc->getMethod() == 'backup::restoreMarket') {
			repo_market::backup_restore($params['backup'], true);
			$jsonrpc->makeSuccess();
		}

		/*             * ************************Log*************************** */
		if ($jsonrpc->getMethod() == 'log::get') {
			$jsonrpc->makeSuccess(log::get($params['log'], $params['start'], $params['nbLine']));
		}

		if ($jsonrpc->getMethod() == 'log::list') {
			$jsonrpc->makeSuccess(log::liste());
		}

		if ($jsonrpc->getMethod() == 'log::empty') {
			$jsonrpc->makeSuccess(log::clear($params['log']));
		}

		if ($jsonrpc->getMethod() == 'log::remove') {
			$jsonrpc->makeSuccess(log::remove($params['log']));
		}

		/*             * ************************Messages*************************** */
		if ($jsonrpc->getMethod() == 'message::removeAll') {
			message::removeAll();
			$jsonrpc->makeSuccess('ok');
		}

		if ($jsonrpc->getMethod() == 'message::all') {
			log::add('api', 'info', 'recuperation messages ');
			$jsonrpc->makeSuccess(utils::o2a(message::all()));
		}

		/*             * ************************Interact*************************** */
		if ($jsonrpc->getMethod() == 'interact::tryToReply') {
			$jsonrpc->makeSuccess(interactQuery::tryToReply($params['query']));
		}

		/*             * ************************USB mapping*************************** */
		if ($jsonrpc->getMethod() == 'jeedom::getUsbMapping') {
			$name = (isset($params['name'])) ? $params['name'] : '';
			$gpio = (isset($params['gpio'])) ? $params['gpio'] : false;
			$jsonrpc->makeSuccess(jeedom::getUsbMapping($name, $gpio));
		}

		/*             * ************************Plugin*************************** */
		if ($jsonrpc->getMethod() == 'plugin::install') {
			try {
				$market = market::byId($params['plugin_id']);
			} catch (Exception $e) {
				$market = market::byLogicalId($params['plugin_id']);
			}
			if (!is_object($market)) {
				throw new Exception(__('Impossible de trouver l\'objet associé : ', __FILE__) . secureXSS($params['plugin_id']));
			}
			if (!isset($params['version'])) {
				$params['version'] = 'stable';
			}
			$market->install($params['version']);
			$jsonrpc->makeSuccess('ok');
		}

		if ($jsonrpc->getMethod() == 'plugin::remove') {
			$market = market::byId($params['plugin_id']);
			if (!is_object($market)) {
				throw new Exception(__('Impossible de trouver l\'objet associé : ', __FILE__) . secureXSS($params['plugin_id']));
			}
			if (!isset($params['version'])) {
				$params['version'] = 'stable';
			}
			$market->remove();
			$jsonrpc->makeSuccess('ok');
		}
		
		if ($jsonrpc->getMethod() == 'plugin::specificInfos') {
		    $infos = array();
		    foreach (plugin::listPlugin() as $plugin) {
			$pluginId = $plugin->getId();
			if(method_exists($pluginId, 'proApi')){
			    $infos[] = $pluginId::proApi();
			}
		    }
		    $jsonrpc->makeSuccess($infos);
		}

		/*             * ************************Update*************************** */
		if ($jsonrpc->getMethod() == 'update::all') {
			$jsonrpc->makeSuccess(utils::o2a(update::all()));
		}

		if ($jsonrpc->getMethod() == 'update::update') {
			jeedom::update('', 0);
			$jsonrpc->makeSuccess('ok');
		}

		if ($jsonrpc->getMethod() == 'update::checkUpdate') {
			update::checkAllUpdate();
			$jsonrpc->makeSuccess('ok');
		}

		if ($jsonrpc->getMethod() == 'update::nbNeedUpdate') {
			$return = array(
				'nbUpdate' => update::nbNeedUpdate(),
			);
			$jsonrpc->makeSuccess($return);
		}

		/*             * ************************Network*************************** */

		if ($jsonrpc->getMethod() == 'network::restartNgrok') {
			config::save('market::allowDNS', 1);
			if (network::dns_run()) {
				network::dns_stop();
			}
			network::dns_start();
			$jsonrpc->makeSuccess();
		}

		if ($jsonrpc->getMethod() == 'network::stopNgrok') {
			config::save('market::allowDNS', 0);
			network::dns_stop();
			$jsonrpc->makeSuccess();
		}

		if ($jsonrpc->getMethod() == 'network::ngrokRun') {
			if (!isset($params['proto'])) {
				$params['proto'] = 'https';
			}
			if (!isset($params['port'])) {
				$params['port'] = 80;
			}
			if (!isset($params['name'])) {
				$params['name'] = '';
			}
			$jsonrpc->makeSuccess(network::dns_run());
		}

		/*             * ************************************************************************ */
	}
	throw new Exception(__('Aucune méthode correspondante : ', __FILE__) . secureXSS($jsonrpc->getMethod()), -32500);
/*         * *********Catch exeption*************** */
} catch (Exception $e) {
	$message = $e->getMessage();
	$jsonrpc = new jsonrpc(init('request'));
	$errorCode = (is_numeric($e->getCode())) ? -32000 - $e->getCode() : -32599;
	$jsonrpc->makeError($errorCode, $message);
}
