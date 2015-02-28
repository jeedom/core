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
if (trim(config::byKey('api')) == '') {
	echo 'Vous n\'avez aucune clef API de configurer, veuillez d\'abord en générer une (Page Générale -> Administration -> Configuration';
	log::add('jeeEvent', 'error', 'Vous n\'avez aucune clef API de configurer, veuillez d\'abord en générer une (Page Générale -> Administration -> Configuration');
	die();
}
if ((init('apikey') != '' || init('api') != '') && init('type') != '') {
	try {
		if (config::byKey('api') != init('apikey') && config::byKey('api') != init('api')) {
			connection::failed();
			throw new Exception('Clef API non valide, vous n\'etes pas autorisé à effectuer cette action (jeeApi). Demande venant de :' . getClientIp() . 'Clef API : ' . init('apikey') . init('api'));
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
						throw new Exception(__('Aucune commande correspondant à l\'id : ', __FILE__) . $id);
					}
					$result[$id] = $cmd->execCmd($_REQUEST);
				}
				echo json_encode($result);
			} else {
				$cmd = cmd::byId(init('id'));
				if (!is_object($cmd)) {
					throw new Exception('Aucune commande correspondant à l\'id : ' . init('id'));
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
				throw new Exception('Aucun scénario correspondant à l\'id : ' . init('id'));
			}
			switch (init('action')) {
				case 'start':
					log::add('api', 'debug', 'Start scénario de : ' . $scenario->getHumanName());
					$scenario->launch(false, __('Lancement provoque par un appel api ', __FILE__));
					break;
				case 'stop':
					log::add('api', 'debug', 'Stop scénario de : ' . $scenario->getHumanName());
					$scenario->stop();
					break;
				case 'deactivate':
					log::add('api', 'debug', 'Activation scénario de : ' . $scenario->getHumanName());
					$scenario->setIsActive(0);
					$scenario->save();
					break;
				case 'activate':
					log::add('api', 'debug', 'Désactivation scénario de : ' . $scenario->getHumanName());
					$scenario->setIsActive(1);
					$scenario->save();
					break;
				default:
					throw new Exception('Action non trouvée ou invalide [start,stop,deactivate,activate]');
			}
			echo 'ok';
		} else {
			if (class_exists($type)) {
				if (method_exists($type, 'event')) {
					log::add('api', 'info', 'Appels de ' . $type . '::event()');
					$type::event();
				} else {
					throw new Exception('Aucune methode correspondante : ' . $type . '::event()');
				}
			} else {
				throw new Exception('Aucune plugin correspondant : ' . $type);
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
		log::add('api', 'info', init('request') . ' - IP :' . $IP);

		$jsonrpc = new jsonrpc(init('request'));

		if (!mySqlIsHere()) {
			throw new Exception('Mysql non lancé', -32001);
		}

		if ($jsonrpc->getJsonrpc() != '2.0') {
			throw new Exception('Requete invalide. Jsonrpc version invalide : ' . $jsonrpc->getJsonrpc(), -32001);
		}

		$params = $jsonrpc->getParams();

		if (isset($params['apikey']) || isset($params['api'])) {
			if (config::byKey('api') == '' || (config::byKey('api') != $params['apikey'] && config::byKey('api') != $params['api'])) {
				if (config::byKey('market::jeedom_apikey') == '' || config::byKey('market::jeedom_apikey') != $params['apikey'] || $_SERVER['REMOTE_ADDR'] != '94.23.188.164') {
					connection::failed();
					throw new Exception('Clef API invalide', -32001);
				}
			}
		} else if (isset($params['username']) && isset($params['password'])) {
			$user = user::connect($params['username'], $params['password']);
			if (!is_object($user) || $user->getRights('admin') != 1) {
				connection::failed();
				throw new Exception('Nom d\'utilisateur ou mot de passe invalide', -32001);
			}
		} else {
			connection::failed();
			throw new Exception('Aucune clef API ou nom d\'utilisateur', -32001);
		}

		connection::success('api');

		/*             * ************************config*************************** */
		if ($jsonrpc->getMethod() == 'config::byKey') {
			$jsonrpc->makeSuccess(config::byKey($params['key'], $params['plugin'], $params['default']));
		}

		if ($jsonrpc->getMethod() == 'config::save') {
			$jsonrpc->makeSuccess(config::save($params['key'], $params['value'], $params['plugin']));
		}

		if (isset($params['plugin']) && $params['plugin'] != '') {
			log::add('api', 'info', 'Demande pour le plugin : ' . $params['plugin']);
			include_file('core', $params['plugin'], 'api', $params['plugin']);
		} else {
			/*             * ***********************Ping********************************* */
			if ($jsonrpc->getMethod() == 'ping') {
				$jsonrpc->makeSuccess('pong');
			}

			/*             * ***********************Get API Key********************************* */
			if ($jsonrpc->getMethod() == 'getApiKey' && config::byKey('market::jeedom_apikey') == $params['apikey']) {
				market::validateTicket($params['ticket']);
				$jsonrpc->makeSuccess(config::byKey('api'));
			}

			/*             * ***********************Version********************************* */
			if ($jsonrpc->getMethod() == 'version') {
				$jsonrpc->makeSuccess(getVersion('jeedom'));
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
					throw new Exception('Objet introuvable : ' . $params['id'], -32601);
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
					throw new Exception('Objet introuvable : ' . $params['id'], -32601);
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
					throw new Exception('EqLogic introuvable : ' . $params['id'], -32602);
				}
				$jsonrpc->makeSuccess(utils::o2a($eqLogic));
			}

			if ($jsonrpc->getMethod() == 'eqLogic::fullById') {
				$eqLogic = eqLogic::byId($params['id']);
				if (!is_object($eqLogic)) {
					throw new Exception('EqLogic introuvable : ' . $params['id'], -32602);
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
					throw new Exception(__('Type incorrect (classe commande inexistante)', __FILE__) . $typeCmd);
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

					//suppression des entrées non innexistante.
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
					throw new Exception('Cmd introuvable : ' . $params['id'], -32701);
				}
				$jsonrpc->makeSuccess(utils::o2a($cmd));
			}

			if ($jsonrpc->getMethod() == 'cmd::execCmd') {
				if (is_array($params['id'])) {
					$return = array();
					foreach ($params['id'] as $id) {
						$cmd = cmd::byId($id);
						if (!is_object($cmd)) {
							throw new Exception('Cmd introuvable : ' . $id, -32702);
						}
						$return[$id] = array('value' => $cmd->execCmd($params['options']), 'collectDate' => $cmd->getCollectDate());
					}
				} else {
					$cmd = cmd::byId($params['id']);
					if (!is_object($cmd)) {
						throw new Exception('Cmd introuvable : ' . $params['id'], -32702);
					}
					$return = array('value' => $cmd->execCmd($params['options']), 'collectDate' => $cmd->getCollectDate());
				}
				$jsonrpc->makeSuccess($return);
			}

			if ($jsonrpc->getMethod() == 'cmd::getStatistique') {
				$cmd = cmd::byId($params['id']);
				if (!is_object($cmd)) {
					throw new Exception('Cmd introuvable : ' . $params['id'], -32702);
				}
				$jsonrpc->makeSuccess($cmd->getStatistique($params['startTime'], $params['endTime']));
			}

			if ($jsonrpc->getMethod() == 'cmd::getTendance') {
				$cmd = cmd::byId($params['id']);
				if (!is_object($cmd)) {
					throw new Exception('Cmd introuvable : ' . $params['id'], -32702);
				}
				$jsonrpc->makeSuccess($cmd->getTendance($params['startTime'], $params['endTime']));
			}

			if ($jsonrpc->getMethod() == 'cmd::getHistory') {
				$cmd = cmd::byId($params['id']);
				if (!is_object($cmd)) {
					throw new Exception('Cmd introuvable : ' . $params['id'], -32702);
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
					throw new Exception('Scenario introuvable : ' . $params['id'], -32703);
				}
				$jsonrpc->makeSuccess(utils::o2a($scenario));
			}

			if ($jsonrpc->getMethod() == 'scenario::changeState') {
				$scenario = scenario::byId($params['id']);
				if (!is_object($scenario)) {
					throw new Exception('Scenario introuvable : ' . $params['id'], -32702);
				}
				if ($params['state'] == 'stop') {
					$jsonrpc->makeSuccess($scenario->stop());
				}
				if ($params['state'] == 'run') {
					$jsonrpc->makeSuccess($scenario->launch(false, __('Scenario lance sur appels API', __FILE__)));
				}
				if ($params['state'] == 'enable') {
					$scenario->setIsActive(1);
					$jsonrpc->makeSuccess($scenario->save());
				}
				if ($params['state'] == 'disable') {
					$scenario->setIsActive(0);
					$jsonrpc->makeSuccess($scenario->save());
				}
				throw new Exception('La paramètre "state" ne peut être vide et doit avoir pour valuer [run,stop,enable;disable]');
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
					'version' => getVersion('jeedom'),
					'nbMessage' => message::nbMessage(),
					'auiKey' => $auiKey,
				);
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

			if ($jsonrpc->getMethod() == 'jeeNetwork::installPlugin') {
				$market = market::byId($params['plugin_id']);
				if (!is_object($market)) {
					throw new Exception(__('Impossible de trouver l\'objet associé : ', __FILE__) . $params['plugin_id']);
				}
				if (!isset($params['version'])) {
					$params['version'] = 'stable';
				}
				$market->install($params['version']);
				$jsonrpc->makeSuccess('ok');
			}

			if ($jsonrpc->getMethod() == 'jeeNetwork::receivedBackup') {
				if (config::byKey('jeeNetwork::mode') == 'slave') {
					throw new Exception(__('Seul un maitre peut recevoir un backup', __FILE__));
				}
				$jeeNetwork = jeeNetwork::byId($params['slave_id']);
				if (!is_object($jeeNetwork)) {
					throw new Exception(__('Aucun esclave correspondant à l\'id : ', __FILE__) . $params['slave_id']);
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
					throw new Exception('Répertoire d\'upload non trouvé : ' . $uploaddir);
				}
				$_file = $_FILES['file'];
				$extension = strtolower(strrchr($_file['name'], '.'));
				if (!in_array($extension, array('.tar.gz', '.gz', '.tar'))) {
					throw new Exception('Extension du fichier non valide (autorisé .tar.gz, .tar et .gz) : ' . $extension);
				}
				if (filesize($_file['tmp_name']) > 50000000) {
					throw new Exception('Le fichier est trop gros (miximum 50mo)');
				}
				$uploadfile = $uploaddir . $jeeNetwork->getId() . '-' . $jeeNetwork->getName() . '-' . $jeeNetwork->getConfiguration('version') . '-' . date('Y-m-d_H\hi') . '.tar' . $extension;
				if (!move_uploaded_file($_file['tmp_name'], $uploadfile)) {
					throw new Exception('Impossible d\'uploader le fichier');
				}
				system('find ' . $uploaddir . $jeeNetwork->getId() . '*' . ' -mtime +' . config::byKey('backup::keepDays') . ' -print | xargs -r rm');
				$jsonrpc->makeSuccess('ok');
			}

			if ($jsonrpc->getMethod() == 'jeeNetwork::restoreBackup') {
				if (config::byKey('jeeNetwork::mode') != 'slave') {
					throw new Exception(__('Seul un esclave peut restorer un backup', __FILE__));
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
					throw new Exception('Repertoire d\'upload non trouve : ' . $uploaddir);
				}
				$_file = $_FILES['file'];
				$extension = strtolower(strrchr($_file['name'], '.'));
				if (!in_array($extension, array('.tar.gz', '.gz', '.tar'))) {
					throw new Exception('Extension du fichier non valide (autorisé .tar.gz, .tar et .gz) : ' . $extension);
				}
				if (filesize($_file['tmp_name']) > 50000000) {
					throw new Exception('Le fichier est trop gros (miximum 50mo)');
				}
				$bakcup_name = 'backup-' . getVersion('jeedom') . '-' . date("d-m-Y-H\hi") . '.tar.gz';
				$uploadfile = $uploaddir . '/' . $bakcup_name;
				if (!move_uploaded_file($_file['tmp_name'], $uploadfile)) {
					throw new Exception('Impossible d\'uploader le fichier');
				}
				jeedom::restore($uploadfile, true);
				$jsonrpc->makeSuccess('ok');
			}

			if ($jsonrpc->getMethod() == 'jeeNetwork::backup') {
				jeedom::backup();
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
				$jsonrpc->makeSuccess(jeedom::getUsbMapping());
			}
			/*             * ************************************************************************ */
		}
		throw new Exception('Aucune méthode correspondante : ' . $jsonrpc->getMethod(), -32500);
/*         * *********Catch exeption*************** */
	} catch (Exception $e) {
		$message = $e->getMessage();
		$jsonrpc = new jsonrpc(init('request'));
		$errorCode = (is_numeric($e->getCode())) ? -32000 - $e->getCode() : -32599;
		$jsonrpc->makeError($errorCode, $message);
	}
}
?>
