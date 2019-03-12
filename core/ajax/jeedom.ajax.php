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
	
	ajax::init(false);
	
	if (init('action') == 'getInfoApplication') {
		$return = array();
		$return['product_name'] = config::byKey('product_name');
		$return['product_icon'] = config::byKey('product_icon');
		$return['product_image'] = config::byKey('product_image');
		$return['widget_margin'] = config::byKey('widget::margin');
		$return['serverDatetime'] = getmicrotime();
		if (!isConnect()) {
			$return['connected'] = false;
			ajax::success($return);
		}
		
		$return['user_id'] = $_SESSION['user']->getId();
		$return['jeedom_token'] = ajax::getToken();
		@session_start();
		$_SESSION['user']->refresh();
		@session_write_close();
		
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
		if (config::byKey('enableCustomCss', 'core', 1) == 1) {
			$return['custom']['js'] = file_exists(__DIR__ . '/../../mobile/custom/custom.js');
			$return['custom']['css'] = file_exists(__DIR__ . '/../../mobile/custom/custom.css');
		}
		ajax::success($return);
	}
	
	if (!isConnect()) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__), -1234);
	}
	
	ajax::init(true);
	
	if (init('action') == 'getDocumentationUrl') {
		$plugin = null;
		if (init('plugin') != '' || init('plugin') == 'false') {
			try {
				$plugin = plugin::byId(init('plugin'));
			} catch (Exception $e) {
				
			}
		}
		if (isset($plugin) && is_object($plugin)) {
			if ($plugin->getDocumentation() != '') {
				ajax::success($plugin->getDocumentation());
			}
		} else {
			$page = init('page');
			if (init('page') == 'scenarioAssist') {
				$page = 'scenario';
			} else if (init('page') == 'view_edit') {
				$page = 'view';
			} else if (init('page') == 'plan') {
				$page = 'design';
			}else if (init('page') == 'plan3d') {
				$page = 'design3d';
			}
			ajax::success('https://jeedom.github.io/core/' . config::byKey('language', 'core', 'fr_FR') . '/' . secureXSS($page));
		}
		throw new Exception(__('Aucune documentation trouvée', __FILE__), -1234);
	}
	
	if (init('action') == 'addWarnme') {
		$cmd = cmd::byId(init('cmd_id'));
		if (!is_object($cmd)) {
			throw new Exception(__('Commande non trouvée : ', __FILE__) . init('cmd_id'));
		}
		$listener = new listener();
		$listener->setClass('interactQuery');
		$listener->setFunction('warnMeExecute');
		$listener->addEvent($cmd->getId());
		$options = array(
			'type' => 'cmd',
			'cmd_id' => $cmd->getId(),
			'name' => $cmd->getHumanName(),
			'test' => init('test'),
			'reply_cmd' => init('reply_cmd', $_SESSION['user']->getOptions('notification::cmd')),
		);
		$listener->setOption($options);
		$listener->save(true);
		ajax::success();
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
		ajax::success(DB::prepare(init('command'), array(), DB::FETCH_TYPE_ALL));
	}
	
	if (init('action') == 'dbcorrectTable') {
		unautorizedInDemo();
		DB::compareAndFix(json_decode(file_get_contents(__DIR__.'/../../install/database.json'),true),init('table'));
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
			throw new Exception(__('Répertoire de téléversement non trouvé : ', __FILE__) . $uploaddir);
		}
		if (!isset($_FILES['file'])) {
			throw new Exception(__('Aucun fichier trouvé. Vérifiez le paramètre PHP (post size limit)', __FILE__));
		}
		$extension = strtolower(strrchr($_FILES['file']['name'], '.'));
		if (!in_array($extension, array('.gz'))) {
			throw new Exception('Extension du fichier non valide (autorisé .tar.gz) : ' . $extension);
		}
		if (filesize($_FILES['file']['tmp_name']) > 300000000) {
			throw new Exception(__('Le fichier est trop gros (maximum 300Mo)', __FILE__));
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
	
	if (init('action') == 'forceSyncHour') {
		unautorizedInDemo();
		ajax::success(jeedom::forceSyncHour());
	}
	
	if (init('action') == 'saveCustom') {
		unautorizedInDemo();
		$path = __DIR__ . '/../../';
		if (init('version') != 'desktop' && init('version') != 'mobile') {
			throw new Exception(__('La version ne peut être que desktop ou mobile', __FILE__));
		}
		if (init('type') != 'js' && init('type') != 'css') {
			throw new Exception(__('La version ne peut être que js ou css', __FILE__));
		}
		$path .= init('version') . '/custom/';
		if (!file_exists($path)) {
			mkdir($path);
		}
		$path .= 'custom.' . init('type');
		if (file_exists($path)) {
			unlink($path);
		}
		file_put_contents($path, init('content'));
		ajax::success();
	}
	
	if (init('action') == 'getGraphData') {
		$return = array('node' => array(), 'link' => array());
		$object = null;
		$type = init('filter_type');
		$object = $type::byId(init('filter_id'));
		if (!is_object($object)) {
			throw new Exception(__('Type :', __FILE__) . init('filter_type') . __(' avec id : ', __FILE__) . init('filter_id') . __(' inconnu', __FILE__));
		}
		ajax::success($object->getLinkData());
	}
	
	if (init('action') == 'getTimelineEvents') {
		$return = array();
		$events = jeedom::getTimelineEvent();
		foreach ($events as $event) {
			$info = null;
			switch ($event['type']) {
				case 'cmd':
				$info = cmd::timelineDisplay($event);
				break;
				case 'scenario':
				$info = scenario::timelineDisplay($event);
				break;
			}
			if ($info != null) {
				$return[] = $info;
			}
		}
		ajax::success($return);
	}
	
	if (init('action') == 'removeTimelineEvents') {
		unautorizedInDemo();
		ajax::success(jeedom::removeTimelineEvent());
	}
	
	if (init('action') == 'getFileFolder') {
		unautorizedInDemo();
		ajax::success(ls(init('path'), '*', false, array(init('type'))));
	}
	
	if (init('action') == 'getFileContent') {
		unautorizedInDemo();
		$pathinfo = pathinfo(init('path'));
		if (!in_array($pathinfo['extension'], array('php', 'js', 'json', 'sql', 'ini','html','py','css'))) {
			throw new Exception(__('Vous ne pouvez éditer ce type d\'extension : ' . $pathinfo['extension'], __FILE__));
		}
		ajax::success(file_get_contents(init('path')));
	}
	
	if (init('action') == 'setFileContent') {
		unautorizedInDemo();
		$pathinfo = pathinfo(init('path'));
		if (!in_array($pathinfo['extension'], array('php', 'js', 'json', 'sql', 'ini','html','py','css'))) {
			throw new Exception(__('Vous ne pouvez éditer ce type d\'extension : ' . $pathinfo['extension'], __FILE__));
		}
		ajax::success(file_put_contents(init('path'), init('content')));
	}
	
	if (init('action') == 'deleteFile') {
		unautorizedInDemo();
		$pathinfo = pathinfo(init('path'));
		if (!in_array($pathinfo['extension'], array('php', 'js', 'json', 'sql', 'ini','css'))) {
			throw new Exception(__('Vous ne pouvez éditer ce type d\'extension : ' . $pathinfo['extension'], __FILE__));
		}
		ajax::success(unlink(init('path')));
	}
	
	if (init('action') == 'createFile') {
		unautorizedInDemo();
		$pathinfo = pathinfo(init('name'));
		if (!in_array($pathinfo['extension'], array('php', 'js', 'json', 'sql', 'ini','css'))) {
			throw new Exception(__('Vous ne pouvez éditer ce type d\'extension : ' . $pathinfo['extension'], __FILE__));
		}
		touch(init('path') . init('name'));
		if (!file_exists(init('path') . init('name'))) {
			throw new Exception(__('Impossible de créer le fichier, vérifiez les droits', __FILE__));
		}
		ajax::success();
	}
	
	if (init('action') == 'emptyRemoveHistory') {
		unautorizedInDemo();
		unlink(__DIR__ . '/../../data/remove_history.json');
		ajax::success();
	}
	
	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}
