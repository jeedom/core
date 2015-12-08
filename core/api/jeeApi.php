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

require_once dirname(__FILE__) . "/../php/core.inc.php";
if (isset($argv)) {
	foreach ($argv as $arg) {
		$argList = explode('=', $arg);
		if (isset($argList[0]) && isset($argList[1])) {
			$_REQUEST[$argList[0]] = $argList[1];
		}
	}
}

if (init('type') != '') {
	try {
		if (!jeedom::apiAccess(init('apikey', init('api')))) {
			connection::failed();
			throw new Exception('Clé API non valide (ou vide) . Demande venant de :' . getClientIp() . '. Clé API : ' . secureXSS(init('apikey') . init('api')));
		}
		connection::success('api');
		$type = init('type');
		if ($type == 'cmd') {
			if (is_json(init('id'))) {
				$ids = json_decode(init('id'), true);
				$result = array();
				foreach ($ids as $id) {
					$cmd = cmd::byId($id);
					if (!is_object($cmd)) {
						throw new Exception(__('Aucune commande correspondant à l\'id : ', __FILE__) . secureXSS($id));
					}
					$result[$id] = $cmd->execCmd($_REQUEST);
				}
				echo json_encode($result);
			} else {
				$cmd = cmd::byId(init('id'));
				if (!is_object($cmd)) {
					throw new Exception('Aucune commande correspondant à l\'id : ' . secureXSS(init('id')));
				}
				log::add('api', 'debug', 'Exécution de : ' . $cmd->getHumanName());
				echo $cmd->execCmd($_REQUEST);
			}
		} else if ($type == 'interact') {
			$query = init('query');
			if (init('utf8', 0) == 1) {
				$query = utf8_encode($query);
			}
			$param = array();
			if (init('emptyReply') != '') {
				$param['emptyReply'] = init('emptyReply');
			}
			if (init('profile') != '') {
				$param['profile'] = init('profile');
			}
			echo interactQuery::tryToReply($query, $param);
		} else if ($type == 'scenario') {
			log::add('api', 'debug', 'Demande api pour les scénarios');
			$scenario = scenario::byId(init('id'));
			if (!is_object($scenario)) {
				throw new Exception('Aucun scénario correspondant à l\'id : ' . secureXSS(init('id')));
			}
			switch (init('action')) {
				case 'start':
					log::add('api', 'debug', 'Démarrage scénario de : ' . $scenario->getHumanName());
					$tags = array();
					foreach ($_REQUEST as $key => $value) {
						$tags['#' . $key . '#'] = $value;
					}
					$scenario->setTags($tags);
					$scenario->launch(false, __('Exécution provoquée par un appel API ', __FILE__));
					break;
				case 'stop':
					log::add('api', 'debug', 'Arrêt scénario de : ' . $scenario->getHumanName());
					$scenario->stop();
					break;
				case 'deactivate':
					log::add('api', 'debug', 'Désactivation scénario de : ' . $scenario->getHumanName());
					$scenario->setIsActive(0);
					$scenario->save();
					break;
				case 'activate':
					log::add('api', 'debug', 'Activation scénario de : ' . $scenario->getHumanName());
					$scenario->setIsActive(1);
					$scenario->save();
					break;
				default:
					throw new Exception('Action non trouvée ou invalide [start,stop,deactivate,activate]');
			}
			echo 'ok';
		} else if ($type == 'message') {
			log::add('api', 'debug', 'Demande API pour ajouter un message');
			message::add(init('category'), init('message'));
		} else if ($type == 'object') {
			log::add('api', 'debug', 'Demande API pour les objets');
			echo json_encode(utils::o2a(object::all()));
		} else if ($type == 'eqLogic') {
			log::add('api', 'debug', 'Demande API pour les équipements');
			echo json_encode(utils::o2a(eqLogic::byObjectId(init('object_id'))));
		} else if ($type == 'command') {
			log::add('api', 'debug', 'Demande API pour les commandes');
			echo json_encode(utils::o2a(cmd::byEqLogicId(init('eqLogic_id'))));
		} else if ($type == 'fulData') {
			log::add('api', 'debug', 'Demande API pour les commandes');
			echo json_encode(object::fullData());
		} else {
			if (class_exists($type)) {
				if (method_exists($type, 'event')) {
					log::add('api', 'info', 'Appels de ' . secureXSS($type) . '::event()');
					$type::event();
				} else {
					throw new Exception('Aucune méthode correspondante : ' . secureXSS($type) . '::event()');
				}
			} else {
				throw new Exception('Aucun plugin correspondant : ' . secureXSS($type));
			}
		}
	} catch (Exception $e) {
		echo $e->getMessage();
		log::add('jeeEvent', 'error', $e->getMessage());
	}
	die();
} else {
	try {
		$IP = getClientIp();
		$request = init('request');
		if ($request == '') {
			$request = file_get_contents("php://input");
		}
		log::add('api', 'info', $request . ' - IP :' . $IP);

		$jsonrpc = new jsonrpc($request);

		if ($jsonrpc->getJsonrpc() != '2.0') {
			throw new Exception('Requête invalide. Version Jsonrpc invalide : ' . $jsonrpc->getJsonrpc(), -32001);
		}

		$params = $jsonrpc->getParams();

		if ($jsonrpc->getMethod() == 'user::getHash') {
			if (!isset($params['login']) || !isset($params['password']) || $params['login'] == '' || $params['password'] == '') {
				connection::failed();
				throw new Exception('Le login ou le password ne peuvent être vide', -32001);
			}
			$user = user::connect($params['login'], $params['password']);
			if (!is_object($user) || $user->getEnable() != 1) {
				connection::failed();
				throw new Exception('Echec de l\'authentification', -32001);
			}
			$jsonrpc->makeSuccess($user->getHash());
		}

		if ((isset($params['apikey']) && !jeedom::apiAccess($params['apikey'])) || (isset($params['api']) && !jeedom::apiAccess($params['api']))) {
			connection::failed();
			throw new Exception('Clé API invalide', -32001);
		}

		connection::success('api');

		/*             * ************************config*************************** */
		if ($jsonrpc->getMethod() == 'config::byKey') {
			$jsonrpc->makeSuccess(config::byKey($params['key'], $params['plugin'], $params['default']));
		}

		if ($jsonrpc->getMethod() == 'config::save') {
			$jsonrpc->makeSuccess(config::save($params['key'], $params['value'], $params['plugin']));
		}

		if (isset($params['plugin']) && $params['plugin'] != '' && $params['plugin'] != 'core') {
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

			/*             * ***********************Datetime********************************* */
			if ($jsonrpc->getMethod() == 'datetime') {
				$jsonrpc->makeSuccess(getmicrotime());
			}

			/*             * ***********************changes********************************* */
			if ($jsonrpc->getMethod() == 'event::changes') {
				$jsonrpc->makeSuccess(event::changes($params['datetime']));
			}

			/*             * ************************Plugin*************************** */
			if ($jsonrpc->getMethod() == 'plugin::listPlugin') {
				$activateOnly = (isset($params['activateOnly']) && $params['activateOnly'] == 1) ? true : false;
				$orderByCaterogy = (isset($params['orderByCaterogy']) && $params['orderByCaterogy'] == 1) ? true : false;
				$jsonrpc->makeSuccess(utils::o2a(plugin::listPlugin($activateOnly, $orderByCaterogy)));
			}

			/*             * ************************Object*************************** */
			if ($jsonrpc->getMethod() == 'object::all') {
				$jsonrpc->makeSuccess(utils::o2a(object::all()));
			}

			if ($jsonrpc->getMethod() == 'object::byId') {
				$object = object::byId($params['id']);
				if (!is_object($object)) {
					throw new Exception('Objet introuvable : ' . secureXSS($params['id']), -32601);
				}
				$jsonrpc->makeSuccess(utils::o2a($object));
			}

			if ($jsonrpc->getMethod() == 'object::full') {
				$cache = cache::byKey('api::object::full');
				$cron = cron::byClassAndFunction('object', 'fullData');
				if (!is_object($cron)) {
					$cron = new cron();
				}
				$cron->setClass('object');
				$cron->setFunction('fullData');
				$cron->setSchedule('* * * * * 2000');
				$cron->setTimeout(10);
				$cron->save();
				if (!$cron->running()) {
					$cron->run(true);
				}
				if ($cache->getValue() != '') {
					$jsonrpc->makeSuccess(json_decode($cache->getValue(), true));
				}
				$jsonrpc->makeSuccess(array());
			}

			if ($jsonrpc->getMethod() == 'object::fullById') {
				$object = object::byId($params['id']);
				if (!is_object($object)) {
					throw new Exception('Objet introuvable : ' . secureXSS($params['id']), -32601);
				}
				$return = utils::o2a($object);
				$return['eqLogics'] = array();
				foreach ($object->getEqLogic() as $eqLogic) {
					$eqLogic_return = utils::o2a($eqLogic);
					$eqLogic_return['cmds'] = array();
					foreach ($eqLogic->getCmd() as $cmd) {
						$return['cmds'][] = $cmd->exportApi();
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
					$return['cmds'][] = $cmd->exportApi();
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
						$info_eqLogic = utils::o2a($eqLogic);
						foreach ($eqLogic->getCmd() as $cmd) {
							$info_eqLogic['cmds'][] = $cmd->exportApi();
						}
						$info_eqLogics[] = $info_eqLogic;
					}
					$return[$eqType] = $info_eqLogics;
				}

				foreach ($params['id'] as $id) {
					$eqLogic = eqLogic::byId($id);
					$info_eqLogic = utils::o2a($eqLogic);
					foreach ($eqLogic->getCmd() as $cmd) {
						$info_eqLogic['cmds'][] = $cmd->exportApi();
					}
					$return[$id] = $info_eqLogic;
				}
				$jsonrpc->makeSuccess($return);

			}

			/*             * ************************Commande*************************** */
			if ($jsonrpc->getMethod() == 'cmd::all') {
				$return = array();
				foreach (cmd::all() as $cmd) {
					$return[] = $cmd->exportApi();
				}
				$jsonrpc->makeSuccess($return);
			}

			if ($jsonrpc->getMethod() == 'cmd::byEqLogicId') {
				$return = array();
				foreach (cmd::byEqLogicId($params['eqLogic_id']) as $cmd) {
					$return[] = $cmd->exportApi();
				}
				$jsonrpc->makeSuccess($return);
			}

			if ($jsonrpc->getMethod() == 'cmd::byId') {
				$cmd = cmd::byId($params['id']);
				if (!is_object($cmd)) {
					throw new Exception('Cmd introuvable : ' . secureXSS($params['id']), -32701);
				}
				$jsonrpc->makeSuccess($cmd->exportApi());
			}

			if ($jsonrpc->getMethod() == 'cmd::execCmd') {
				if (is_array($params['id'])) {
					$return = array();
					foreach ($params['id'] as $id) {
						$cmd = cmd::byId($id);
						if (!is_object($cmd)) {
							throw new Exception('Cmd introuvable : ' . secureXSS($id), -32702);
						}
						$return[$id] = array('value' => $cmd->execCmd($params['options']), 'collectDate' => $cmd->getCollectDate());
					}
				} else {
					$cmd = cmd::byId($params['id']);
					if (!is_object($cmd)) {
						throw new Exception('Cmd introuvable : ' . secureXSS($params['id']), -32702);
					}
					$return = array('value' => $cmd->execCmd($params['options']), 'collectDate' => $cmd->getCollectDate());
				}
				$jsonrpc->makeSuccess($return);
			}

			if ($jsonrpc->getMethod() == 'cmd::getStatistique') {
				$cmd = cmd::byId($params['id']);
				if (!is_object($cmd)) {
					throw new Exception('Cmd introuvable : ' . secureXSS($params['id']), -32702);
				}
				$jsonrpc->makeSuccess($cmd->getStatistique($params['startTime'], $params['endTime']));
			}

			if ($jsonrpc->getMethod() == 'cmd::getTendance') {
				$cmd = cmd::byId($params['id']);
				if (!is_object($cmd)) {
					throw new Exception('Cmd introuvable : ' . secureXSS($params['id']), -32702);
				}
				$jsonrpc->makeSuccess($cmd->getTendance($params['startTime'], $params['endTime']));
			}

			if ($jsonrpc->getMethod() == 'cmd::getHistory') {
				$cmd = cmd::byId($params['id']);
				if (!is_object($cmd)) {
					throw new Exception('Cmd introuvable : ' . secureXSS($params['id']), -32702);
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
					throw new Exception('Scenario introuvable : ' . secureXSS($params['id']), -32703);
				}
				$jsonrpc->makeSuccess(utils::o2a($scenario));
			}

			if ($jsonrpc->getMethod() == 'scenario::changeState') {
				$scenario = scenario::byId($params['id']);
				if (!is_object($scenario)) {
					throw new Exception('Scenario introuvable : ' . secureXSS($params['id']), -32702);
				}
				if ($params['state'] == 'stop') {
					$jsonrpc->makeSuccess($scenario->stop());
				}
				if ($params['state'] == 'run') {
					$jsonrpc->makeSuccess($scenario->launch(false, __('Scénario exécuté sur appel API', __FILE__)));
				}
				if ($params['state'] == 'enable') {
					$scenario->setIsActive(1);
					$jsonrpc->makeSuccess($scenario->save());
				}
				if ($params['state'] == 'disable') {
					$scenario->setIsActive(0);
					$jsonrpc->makeSuccess($scenario->save());
				}
				throw new Exception('La paramètre "state" ne peut être vide et doit avoir pour valeur [run,stop,enable;disable]');
			}

			/*             * ************************JeeNetwork*************************** */
			if ($jsonrpc->getMethod() == 'jeeNetwork::handshake') {
				if (config::byKey('jeeNetwork::mode') != 'slave') {
					throw new Exception('Impossible d\'ajouter une box jeedom non esclave à un réseau Jeedom');
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
					throw new Exception(__('Aucun esclave correspondant à l\'id : ', __FILE__) . secureXSS($params['slave_id']));
				}
				if (substr(config::byKey('backup::path'), 0, 1) != '/') {
					$backup_dir = dirname(__FILE__) . '/../../' . config::byKey('backup::path');
				} else {
					$backup_dir = config::byKey('backup::path');
				}
				$uploaddir = $backup_dir . '/slave/';
				if (!file_exists($uploaddir)) {
					mkdir($uploaddir);
				}
				if (!file_exists($uploaddir)) {
					throw new Exception('Répertoire de téléversement non trouvé : ' . secureXSS($uploaddir));
				}
				$_file = $_FILES['file'];
				$extension = strtolower(strrchr($_file['name'], '.'));
				if (!in_array($extension, array('.tar.gz', '.gz', '.tar'))) {
					throw new Exception('Extension du fichier non valide (autorisé .tar.gz, .tar et .gz) : ' . secureXSS($extension));
				}
				if (filesize($_file['tmp_name']) > 50000000) {
					throw new Exception('La taille du fichier est trop importante (maximum 50Mo)');
				}
				$uploadfile = $uploaddir . $jeeNetwork->getId() . '-' . $jeeNetwork->getName() . '-' . $jeeNetwork->getConfiguration('version') . '-' . date('Y-m-d_H\hi') . '.tar' . $extension;
				if (!move_uploaded_file($_file['tmp_name'], $uploadfile)) {
					throw new Exception('Impossible de téléverser le fichier');
				}
				system('find ' . $uploaddir . $jeeNetwork->getId() . '*' . ' -mtime +' . config::byKey('backup::keepDays') . ' -print | xargs -r rm');
				$jsonrpc->makeSuccess('ok');
			}

			if ($jsonrpc->getMethod() == 'jeeNetwork::restoreBackup') {
				if (config::byKey('jeeNetwork::mode') != 'slave') {
					throw new Exception(__('Seul un esclave peut restaurer une sauvegarde', __FILE__));
				}
				if (substr(config::byKey('backup::path'), 0, 1) != '/') {
					$uploaddir = dirname(__FILE__) . '/../../' . config::byKey('backup::path');
				} else {
					$uploaddir = config::byKey('backup::path');
				}
				if (!file_exists($uploaddir)) {
					mkdir($uploaddir);
				}
				if (!file_exists($uploaddir)) {
					throw new Exception('Repertoire de téléversement non trouvé : ' . secureXSS($uploaddir));
				}
				$_file = $_FILES['file'];
				$extension = strtolower(strrchr($_file['name'], '.'));
				if (!in_array($extension, array('.tar.gz', '.gz', '.tar'))) {
					throw new Exception('Extension du fichier non valide (autorisé .tar.gz, .tar et .gz) : ' . secureXSS($extension));
				}
				if (filesize($_file['tmp_name']) > 50000000) {
					throw new Exception('La taille du fichier est trop importante (maximum 50Mo)');
				}
				$bakcup_name = 'backup-' . jeedom::version() . '-' . date("d-m-Y-H\hi") . '.tar.gz';
				$uploadfile = $uploaddir . '/' . $bakcup_name;
				if (!move_uploaded_file($_file['tmp_name'], $uploadfile)) {
					throw new Exception('Impossible de téléverser le fichier');
				}
				jeedom::restore($uploadfile, true);
				$jsonrpc->makeSuccess('ok');
			}

			if ($jsonrpc->getMethod() == 'jeeNetwork::backup') {
				jeedom::backup(true);
				$jsonrpc->makeSuccess('ok');
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

			if ($jsonrpc->getMethod() == 'plugin::dependancyInfo') {
				$plugin_id = $params['plugin_id'];
				if (!method_exists($plugin_id, 'dependancy_info')) {
					$jsonrpc->makeSuccess(array());
				}
				$jsonrpc->makeSuccess($plugin_id::dependancy_info());
			}

			if ($jsonrpc->getMethod() == 'plugin::dependancyInstall') {
				$plugin_id = $params['plugin_id'];
				if (!method_exists($plugin_id, 'dependancy_info')) {
					$jsonrpc->makeSuccess(array());
				}
				$jsonrpc->makeSuccess($plugin_id::dependancy_install());
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

			/*             * ************************Network*************************** */

			if ($jsonrpc->getMethod() == 'network::restartDns') {
				config::save('market::allowDNS', 1);
				network::dns_start();
				$jsonrpc->makeSuccess();
			}

			if ($jsonrpc->getMethod() == 'network::stopDns') {
				config::save('market::allowDNS', 0);
				network::dns_stop();
				$jsonrpc->makeSuccess();
			}

			if ($jsonrpc->getMethod() == 'network::dnsRun') {
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
		throw new Exception('Aucune méthode correspondante : ' . secureXSS($jsonrpc->getMethod()), -32500);
/*         * *********Catch exeption*************** */
	} catch (Exception $e) {
		$message = $e->getMessage();
		$jsonrpc = new jsonrpc(init('request'));
		$errorCode = (is_numeric($e->getCode())) ? -32000 - $e->getCode() : -32599;
		$jsonrpc->makeError($errorCode, $message);
	}
}
?>
