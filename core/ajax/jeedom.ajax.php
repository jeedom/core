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

	ajax::init(array('backupupload', 'uploadImageIcon'));

	if (init('action') == 'getInfoApplication') {
		$return = jeedom::getThemeConfig();
		$return['serverDatetime'] = getmicrotime();
		$return['serverTZoffsetMin'] = getTZoffsetMin();
		if (!isConnect()) {
			$return['connected'] = false;
			ajax::success($return);
		}
		$return['user_id'] = $_SESSION['user']->getId();
		@session_start();
		$_SESSION['user']->refresh();
		@session_write_close();
		$return['user_login'] = $_SESSION['user']->getLogin();

		$return['langage'] = config::byKey('language', 'core', 'fr_FR');
		$return['userProfils'] = $_SESSION['user']->getOptions();
		$return['userProfils']['defaultMobileViewName'] = __('Vue', __FILE__);
		if ($_SESSION['user']->getOptions('defaultDesktopView') != '') {
			$view = view::byId($_SESSION['user']->getOptions('defaultDesktopView'));
			if (is_object($view)) {
				$return['userProfils']['defaultMobileViewName'] = $view->getName();
			}
		}
		$return['userProfils']['defaultMobileObjectName'] = __('Objet', __FILE__);
		if ($_SESSION['user']->getOptions('defaultDashboardObject') != '') {
			$object = jeeObject::byId($_SESSION['user']->getOptions('defaultDashboardObject'));
			if (is_object($object)) {
				$return['userProfils']['defaultMobileObjectName'] = $object->getName();
			}
		}

		$return['plugins'] = array();
		foreach (plugin::listPlugin(true) as $plugin) {
			if ($plugin->getMobile() != '' || $plugin->getEventJs() == 1) {
				$info_plugin = utils::o2a($plugin);
				$info_plugin['displayMobilePanel'] = config::byKey('displayMobilePanel', $plugin->getId(), 0);
				$return['plugins'][] = $info_plugin;
			}
		}
		$return['custom'] = array('js' => false, 'css' => false);
		if ($return['enableCustomCss'] == 1) {
			$return['custom']['js'] = file_exists(__DIR__ . '/../../mobile/custom/custom.js');
			$return['custom']['css'] = file_exists(__DIR__ . '/../../mobile/custom/custom.css');
		}
		ajax::success($return);
	}

	if (!isConnect()) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__), -1234);
	}

	if (init('action') == 'version') {
		ajax::success(jeedom::version());
	}

	if (init('action') == 'getDocumentationUrl') {
		$theme = 'light';
		if (init('theme') != '' || init('theme') == 'false') {
			if (strpos(init('theme'), 'Dark') !== false)
				$theme = 'dark';
		} elseif (strpos(config::byKey('default_bootstrap_theme'), 'Dark') !== false) {
			$theme = 'dark';
		}

		$plugin = null;
		if (init('plugin') != '' || init('plugin') == 'false') {
			try {
				$plugin = plugin::byId(init('plugin'));
			} catch (Exception $e) {
			}
		}
		if (isset($plugin) && is_object($plugin)) {
			if ($plugin->getDocumentation() != '') {
				ajax::success($plugin->getDocumentation() . '?theme=' . $theme);
			}
		} else {
			$page = init('page');
			if (init('page') == 'scenarioAssist') {
				$page = 'scenario';
			} else if (init('page') == 'view_edit') {
				$page = 'view';
			} else if (init('page') == 'plan') {
				$page = 'design';
			} else if (init('page') == 'plan3d') {
				$page = 'design3d';
			} else if (init('page') == 'editor' || init('page') == 'system' || init('page') == 'database') {
				$page = 'administration';
			}
			$version = substr(jeedom::version(), 0, 3);
			ajax::success(config::byKey('doc::base_url', 'core') . '/' . config::byKey('language', 'core', 'fr_FR') . '/core/' . $version . '/' . secureXSS($page) . '?theme=' . $theme);
		}
		throw new Exception(__('Aucune documentation trouvée', __FILE__), -1234);
	}

	if (init('action') == 'addWarnme') {
		$cmd = cmd::byId(init('cmd_id'));
		if (!is_object($cmd)) {
			throw new Exception(__('Commande non trouvée :', __FILE__) . ' ' . init('cmd_id'));
		}
		$options = array(
			'type' => 'cmd',
			'cmd_id' => $cmd->getId(),
			'name' => $cmd->getHumanName(),
			'test' => init('test'),
			'reply_cmd' => init('reply_cmd', $_SESSION['user']->getOptions('notification::cmd')),
		);

		if ($options['reply_cmd'] != '') {
			$listener = new listener();
			$listener->setClass('interactQuery');
			$listener->setFunction('warnMeExecute');
			$listener->addEvent($cmd->getId());
			$listener->setOption($options);
			$listener->save(true);
			ajax::success();
		} else {
			throw new Exception(__('Aucune Commande de Notification :', __FILE__) . ' ' . init('cmd_id'));
			ajax::error();
		}
	}

	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__), -1234);
	}

	if (init('action') == 'ssh') {
		unautorizedInDemo();
		$command = init('command');
		if (strpos($command, '2>&1') === false && strpos($command, '>') === false) {
			$command .= ' 2>&1';
		}
		$output = array();
		exec($command, $output);
		ajax::success(implode("\n", $output));
	}

	if (init('action') == 'db') {
		unautorizedInDemo();
		$microtime = getmicrotime();
		$result = array('sql' => DB::prepare(init('command'), array(), DB::FETCH_TYPE_ALL));
		$result['time'] = getmicrotime() - $microtime;
		ajax::success($result);
	}

	if (init('action') == 'getStringUsedBy') {
		$_search = init('search');
		$return = array('cmd' => array(), 'eqLogic' => array(), 'scenario' => array(), 'interactDef' => array(), 'note' => array());

		$result = scenarioExpression::searchExpression($_search);
		foreach ($result as $expr) {
			$expr = utils::o2a($expr);
			$subElement = scenarioSubElement::byId($expr['scenarioSubElement_id']);
			if (is_object($subElement)) {
				$scenario = $subElement->getElement()->getScenario();
				if (is_object($scenario)) {
					$info = utils::o2a($scenario);
					$info['humanNameTag'] = $scenario->getHumanName(true, false, true);
					$info['humanName'] = $scenario->getHumanName();
					$info['link'] = $scenario->getLinkToConfiguration();
					$info['linkId'] = $scenario->getId();
					$return['scenario'][] = $info;
				}
			}
		}
		$result = scenario::searchByTrigger($_search);
		foreach ($result as $scenario) {
			if (is_object($scenario)) {
				$info = utils::o2a($scenario);
				$info['humanNameTag'] = $scenario->getHumanName(true, false, true);
				$info['humanName'] = $scenario->getHumanName();
				$info['link'] = $scenario->getLinkToConfiguration();
				$info['linkId'] = $scenario->getId();
				$return['scenario'][] = $info;
			}
		}

		$result = interactQuery::searchQueries($_search);
		foreach ($result as $interactQuery) {
			$interact = $interactQuery->getInteractDef();
			$info = utils::o2a($interact);
			$info['humanName'] = $interact->getHumanName();
			$info['link'] = $interact->getLinkToConfiguration();
			$info['linkId'] = $interact->getId();
			$return['interactDef'][] = $info;
		}

		$result = eqLogic::searchByString($_search);
		foreach ($result as $eqLogic) {
			$info['humanName'] = $eqLogic->getHumanName();
			$info['link'] = $eqLogic->getLinkToConfiguration();
			$info['linkId'] = $eqLogic->getId();
			$return['eqLogic'][] = $info;
		}

		$result = cmd::searchByString($_search);
		foreach ($result as $cmd) {
			$info['humanName'] = $cmd->getHumanName();
			$info['link'] = $cmd->getEqLogic()->getLinkToConfiguration();
			$info['linkId'] = $cmd->getId();
			$return['cmd'][] = $info;
		}

		$result = note::searchByString($_search);
		foreach ($result as $note) {
			$info['humanName'] = $note->getName();
			$info['linkId'] = $note->getId();
			$return['note'][] = $info;
		}
		ajax::success($return);
	}

	if (init('action') == 'getIdUsedBy') {
		$_search = init('search');
		$return = array('cmd' => array(), 'eqLogic' => array(), 'scenario' => array(), 'interactDef' => array(), 'note' => array(), 'view' => array(), 'plan' => array());

		$plan = planHeader::byId($_search);
		if (is_object($plan)) {
			$info = utils::o2a($plan);
			$info['name'] = $plan->getName();
			$info['linkId'] = $plan->getId();
			$return['plan'][] = $info;
		}

		$view = view::byId($_search);
		if (is_object($view)) {
			$info = utils::o2a($view);
			$info['name'] = $view->getName();
			$info['linkId'] = $view->getId();
			$return['view'][] = $info;
		}

		$scenario = scenario::byId($_search);
		if (is_object($scenario)) {
			$info = utils::o2a($scenario);
			$info['humanNameTag'] = $scenario->getHumanName(true, false, true);
			$info['humanName'] = $scenario->getHumanName();
			$info['link'] = $scenario->getLinkToConfiguration();
			$info['linkId'] = $scenario->getId();
			$return['scenario'][] = $info;
		}

		$interactQuery = interactQuery::byId($_search);
		if (is_object($interactQuery)) {
			$interact = $interactQuery->getInteractDef();
			$info = utils::o2a($interact);
			$info['humanName'] = $interact->getHumanName();
			$info['link'] = $interact->getLinkToConfiguration();
			$info['linkId'] = $interact->getId();
			$return['interactDef'][] = $info;
		}

		$eqLogic = eqLogic::byId($_search);
		if (is_object($eqLogic)) {
			$info['humanName'] = $eqLogic->getHumanName();
			$info['link'] = $eqLogic->getLinkToConfiguration();
			$info['linkId'] = $eqLogic->getId();
			$return['eqLogic'][] = $info;
		}

		$cmd = cmd::byId($_search);
		if (is_object($cmd)) {
			$info['humanName'] = $cmd->getHumanName();
			$info['link'] = $cmd->getEqLogic()->getLinkToConfiguration();
			$info['linkId'] = $cmd->getId();
			$return['cmd'][] = $info;
		}

		$note = note::byId($_search);
		if (is_object($note)) {
			$info['humanName'] = $note->getName();
			$info['linkId'] = $note->getId();
			$return['note'][] = $info;
		}
		ajax::success($return);
	}

	if (init('action') == 'dbcorrectTable') {
		unautorizedInDemo();
		DB::compareAndFix(json_decode(file_get_contents(__DIR__ . '/../../install/database.json'), true), init('table'));
		ajax::success();
	}

	if (init('action') == 'systemCorrectPackage') {
		unautorizedInDemo();
		if (init('package') != 'all') {
			$cmd = "set -x\n";
			$cmd .= system::checkInstallationLog();
			$cmd .= system::getCmdSudo() . " apt update\n";
			$package = explode('::', init('package'));
			$cmd .= system::installPackage($package[0], $package[1]) . "\n";
			if (file_exists('/tmp/jeedom_fix_package')) {
				shell_exec(system::getCmdSudo() . ' rm /tmp/jeedom_fix_package');
			}
			file_put_contents('/tmp/jeedom_fix_package', $cmd);
			system::launchScriptPackage();
		} else {
			$packages = json_decode(file_get_contents(__DIR__ . '/../../install/packages.json'), true);
			system::checkAndInstall($packages, true);
		}
		ajax::success();
	}

	if (init('action') == 'health') {
		ajax::success(jeedom::health());
	}

	if (init('action') == 'update') {
		unautorizedInDemo();
		jeedom::update();
		ajax::success();
	}

	if (init('action') == 'clearDate') {
		$cache = cache::byKey('jeedom::lastDate');
		$cache->remove();
		ajax::success();
	}

	if (init('action') == 'backup') {
		unautorizedInDemo();
		jeedom::backup(true);
		ajax::success();
	}

	if (init('action') == 'restore') {
		unautorizedInDemo();
		jeedom::restore(init('backup'), true);
		ajax::success();
	}

	if (init('action') == 'removeBackup') {
		unautorizedInDemo();
		jeedom::removeBackup(init('backup'));
		ajax::success();
	}

	if (init('action') == 'listBackup') {
		ajax::success(jeedom::listBackup());
	}

	if (init('action') == 'getConfiguration') {
		ajax::success(jeedom::getConfiguration(init('key'), init('default')));
	}

	if (init('action') == 'resetHwKey') {
		unautorizedInDemo();
		config::save('jeedom::installKey', '');
		ajax::success();
	}

	if (init('action') == 'resetHour') {
		$cache = cache::delete('hour');
		ajax::success();
	}

	if (init('action') == 'backupupload') {
		unautorizedInDemo();
		$uploaddir = __DIR__ . '/../../backup';
		if (!file_exists($uploaddir)) {
			mkdir($uploaddir);
		}
		if (!file_exists($uploaddir)) {
			throw new Exception(__('Répertoire de téléversement non trouvé :', __FILE__) . ' ' . $uploaddir);
		}
		if (!isset($_FILES['file'])) {
			throw new Exception(__('Aucun fichier trouvé. Vérifiez le paramètre PHP (post size limit)', __FILE__));
		}
		$extension = strtolower(strrchr($_FILES['file']['name'], '.'));
		if (!in_array($extension, array('.gz'))) {
			throw new Exception(__('Extension du fichier non valide (autorisé .tar.gz) :', __FILE__) . ' ' . $extension);
		}
		if (filesize($_FILES['file']['tmp_name']) > 1000000000) {
			throw new Exception(__('Le fichier est trop gros (maximum 1Go)', __FILE__));
		}
		if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . '/' . $_FILES['file']['name'])) {
			throw new Exception(__('Impossible de déplacer le fichier temporaire', __FILE__));
		}
		if (!file_exists($uploaddir . '/' . $_FILES['file']['name'])) {
			throw new Exception(__('Impossible de téléverser le fichier (limite du serveur web ?)', __FILE__));
		}
		ajax::success();
	}

	if (init('action') == 'haltSystem') {
		unautorizedInDemo();
		ajax::success(jeedom::haltSystem());
	}

	if (init('action') == 'rebootSystem') {
		unautorizedInDemo();
		ajax::success(jeedom::rebootSystem());
	}

	if (init('action') == 'cleanDatabase') {
		unautorizedInDemo();
		ajax::success(jeedom::cleanDatabase());
	}

	if (init('action') == 'cleanFileSystemRight') {
		unautorizedInDemo();
		ajax::success(jeedom::cleanFileSystemRight());
	}

	if (init('action') == 'consistency') {
		unautorizedInDemo();
		ajax::success(jeedom::consistency());
	}

	if (init('action') == 'forceSyncHour') {
		unautorizedInDemo();
		ajax::success(jeedom::forceSyncHour());
	}

	if (init('action') == 'getGraphData') {
		$return = array('node' => array(), 'link' => array());
		$object = null;
		$type = init('filter_type');
		if ($type == 'object') {
			$type = 'jeeObject';
		}
		$object = $type::byId(init('filter_id'));
		if (!is_object($object)) {
			throw new Exception(__('Type :', __FILE__) . init('filter_type') . ' ' . __('avec id :', __FILE__) . ' ' . init('filter_id') . ' ' . __('inconnu', __FILE__));
		}
		ajax::success($object->getLinkData());
	}

	if (init('action') == 'getFileFolder') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$pathfile = calculPath(urldecode(init('path')));
		if ($pathfile === false) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$rootPath = realpath(__DIR__ . '/../../');
		if (strpos($pathfile, $rootPath) === false) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		ajax::success(ls($pathfile, '*', false, array(init('type'))));
	}

	if (init('action') == 'getFileContent') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$pathinfo = pathinfo(init('path'));
		if (!in_array($pathinfo['extension'], array('php', 'js', 'json', 'sql', 'ini', 'css', 'py', 'css', 'html', 'yaml', 'config', 'conf'))) {
			throw new Exception(__('Vous ne pouvez éditer ce type d\'extension :', __FILE__) . ' ' . $pathinfo['extension']);
		}
		$pathfile = calculPath(urldecode(init('path')));
		if ($pathfile === false) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$rootPath = realpath(__DIR__ . '/../../');
		if (strpos($pathfile, $rootPath) === false) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		ajax::success(file_get_contents($pathfile));
	}

	if (init('action') == 'setFileContent') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$pathinfo = pathinfo(init('path'));
		if (!in_array($pathinfo['extension'], array('php', 'js', 'json', 'sql', 'ini', 'css', 'py', 'css', 'html', 'yaml', 'config', 'conf'))) {
			throw new Exception(__('Vous ne pouvez éditer ce type d\'extension :', __FILE__) . ' ' . $pathinfo['extension']);
		}
		$pathfile = calculPath(urldecode(init('path')));
		if ($pathfile === false) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$rootPath = realpath(__DIR__ . '/../../');
		if (strpos($pathfile, $rootPath) === false) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		ajax::success(file_put_contents($pathfile, init('content')));
	}

	if (init('action') == 'deleteFile') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$pathinfo = pathinfo(init('path'));
		if (!in_array($pathinfo['extension'], array('php', 'js', 'json', 'sql', 'ini', 'css', 'py', 'css', 'html', 'yaml', 'config', 'conf'))) {
			throw new Exception(__('Vous ne pouvez éditer ce type d\'extension :', __FILE__) . ' ' . $pathinfo['extension']);
		}
		$pathfile = calculPath(urldecode(init('path')));
		if ($pathfile === false) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$rootPath = realpath(__DIR__ . '/../../');
		if (strpos($pathfile, $rootPath) === false) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		ajax::success(unlink($pathfile));
	}

	if (init('action') == 'createFile') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$pathinfo = pathinfo(init('name'));
		if (!in_array($pathinfo['extension'], array('php', 'js', 'json', 'sql', 'ini', 'css', 'py', 'css', 'html', 'yaml', 'config', 'conf'))) {
			throw new Exception(__('Vous ne pouvez éditer ce type d\'extension :', __FILE__) . ' ' . $pathinfo['extension']);
		}
		$pathfile = calculPath(urldecode(init('path')));
		if ($pathfile === false) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$rootPath = realpath(__DIR__ . '/../../');
		if (strpos($pathfile, $rootPath) === false) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		touch($pathfile . init('name'));
		if (!file_exists($pathfile . init('name'))) {
			throw new Exception(__('Impossible de créer le fichier, vérifiez les droits', __FILE__));
		}
		ajax::success();
	}

	if (init('action') == 'createFolder') {
		unautorizedInDemo();
		$pathfile = calculPath(urldecode(init('path')));
		if ($pathfile === false) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$rootPath = realpath(__DIR__ . '/../../');
		if (strpos($pathfile, $rootPath) === false) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		mkdir($pathfile . '/' . init('name'));
		ajax::success();
	}

	if (init('action') == 'emptyRemoveHistory') {
		unautorizedInDemo();
		unlink(__DIR__ . '/../../data/remove_history.json');
		ajax::success();
	}

	if (init('action') == 'uploadImageIcon') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		if (!isset($_FILES['file'])) {
			throw new Exception(__('Aucun fichier trouvé. Vérifiez le paramètre PHP (post size limit)', __FILE__));
		}
		$extension = strtolower(strrchr($_FILES['file']['name'], '.'));
		if (!in_array($extension, array('.jpg', '.png', '.gif'))) {
			throw new Exception(__('Extension du fichier non valide (autorisé .jpg .png .gif) :', __FILE__) . ' ' . $extension);
		}
		if (filesize($_FILES['file']['tmp_name']) > 5000000) {
			throw new Exception(__('Le fichier est trop gros (maximum 5Mo)', __FILE__));
		}
		$path = init('filepath');
		if (!file_exists(__DIR__ . '/../../' . $path)) {
			mkdir(__DIR__ . '/../../' . $path);
		}
		$filename = $_FILES['file']['name'];
		$filepath = __DIR__ . '/../../' . $path . $filename;
		file_put_contents($filepath, file_get_contents($_FILES['file']['tmp_name']));
		if (!file_exists($filepath)) {
			throw new \Exception(__('Impossible de sauvegarder l\'image', __FILE__));
		}
		ajax::success(array('filepath' => $filepath));
	}

	if (init('action') == 'removeImageIcon') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$filepath = __DIR__ . '/../../' . init('filepath');
		if (!file_exists($filepath)) {
			throw new Exception(__('Fichier introuvable, impossible de le supprimer', __FILE__));
		}
		unlink($filepath);
		if (file_exists($filepath)) {
			throw new Exception(__('Impossible de supprimer le fichier', __FILE__));
		}
		ajax::success();
	}

	if (init('action') == 'massEditSave') {
		unautorizedInDemo();
		$type = init('type');
		if (!class_exists($type)) {
			throw new Exception('{{Type non trouvé :}}' . ' ' . $type);
		}
		$datas = is_json(init('objects'), array());
		if (count($datas) > 0) {
			foreach ($datas as $data) {
				$object = $type::byId($data['id']);
				if (!is_object($object)) {
					continue;
				}
				utils::a2o($object, $data);
				try {
					$object->save(true);
				} catch (\Exception $e) {
					var_dump($e);
				}
			}
		}
		ajax::success();
	}

	throw new Exception(__('Aucune méthode correspondante à :', __FILE__) . ' ' . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}
