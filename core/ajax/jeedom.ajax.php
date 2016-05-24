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

	if (!isConnect()) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__), -1234);
	}

	ajax::init(false);

	if (init('action') == 'getInfoApplication') {
		$_SESSION['user']->refresh();
		$return = array();
		$return['jeedom_token'] = ajax::getToken();
		$return['user_id'] = $_SESSION['user']->getId();
		$return['serverDatetime'] = strtotime('now') * 1000;
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
			$object = object::byId($_SESSION['user']->getOptions('defaultDashboardObject'));
			if (is_object($object)) {
				$return['userProfils']['defaultMobileObjectName'] = $object->getName();
			}
		}

		$return['plugins'] = array();
		foreach (plugin::listPlugin(true) as $plugin) {
			if ($plugin->getMobile() != '' || $plugin->getEventJs() == 1) {
				$info_plugin = utils::o2a($plugin);
				$info_plugin['displayMobilePanel'] = config::bykey('displayMobilePanel', $plugin->getId(), 0);
				$return['plugins'][] = $info_plugin;
			}
		}
		$return['custom'] = array('js' => false, 'css' => false);
		if (config::byKey('enableCustomCss', 'core', 1) == 1) {
			$return['custom']['js'] = file_exists(dirname(__FILE__) . '/../../mobile/custom/custom.js');
			$return['custom']['css'] = file_exists(dirname(__FILE__) . '/../../mobile/custom/custom.css');
		}
		ajax::success($return);
	}

	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__), -1234);
	}

	ajax::init(true);

	if (init('action') == 'ssh') {
		$command = init('command');
		if (strpos($command, '2>&1') === false) {
			$command .= ' 2>&1';
		}
		ajax::success(shell_exec($command));
	}

	if (init('action') == 'update') {
		jeedom::update();
		ajax::success();
	}

	if (init('action') == 'clearDate') {
		$cache = cache::byKey('jeedom::lastDate');
		$cache->remove();
		ajax::success();
	}

	if (init('action') == 'backup') {
		jeedom::backup(true);
		ajax::success();
	}

	if (init('action') == 'restore') {
		jeedom::restore(init('backup'), true);
		ajax::success();
	}

	if (init('action') == 'removeBackup') {
		jeedom::removeBackup(init('backup'));
		ajax::success();
	}

	if (init('action') == 'listBackup') {
		ajax::success(jeedom::listBackup());
	}

	if (init('action') == 'getConfiguration') {
		ajax::success(jeedom::getConfiguration(init('key'), init('default')));
	}

	if (init('action') == 'flushcache') {
		cache::flush();
		ajax::success();
	}

	if (init('action') == 'resetHwKey') {
		config::save('jeedom::installKey', '');
		ajax::success();
	}

	if (init('action') == 'backupupload') {
		$uploaddir = dirname(__FILE__) . '/../../backup';
		if (!file_exists($uploaddir)) {
			mkdir($uploaddir);
		}
		if (!file_exists($uploaddir)) {
			throw new Exception(__('Répertoire d\'upload non trouvé : ', __FILE__) . $uploaddir);
		}
		if (!isset($_FILES['file'])) {
			throw new Exception(__('Aucun fichier trouvé. Vérifié parametre PHP (post size limit)', __FILE__));
		}
		$extension = strtolower(strrchr($_FILES['file']['name'], '.'));
		if (!in_array($extension, array('.gz'))) {
			throw new Exception('Extension du fichier non valide (autorisé .tar.gz) : ' . $extension);
		}
		if (filesize($_FILES['file']['tmp_name']) > 100000000) {
			throw new Exception(__('Le fichier est trop gros (maximum 100mo)', __FILE__));
		}
		if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . '/' . $_FILES['file']['name'])) {
			throw new Exception(__('Impossible de déplacer le fichier temporaire', __FILE__));
		}
		if (!file_exists($uploaddir . '/' . $_FILES['file']['name'])) {
			throw new Exception(__('Impossible d\'uploader le fichier (limite du serveur web ?)', __FILE__));
		}
		ajax::success();
	}

	if (init('action') == 'haltSystem') {
		ajax::success(jeedom::haltSystem());
	}

	if (init('action') == 'rebootSystem') {
		ajax::success(jeedom::rebootSystem());
	}

	if (init('action') == 'forceSyncHour') {
		ajax::success(jeedom::forceSyncHour());
	}

	if (init('action') == 'saveCustom') {
		$path = dirname(__FILE__) . '/../../';
		if (init('version') != 'desktop' && init('version') != 'mobile') {
			throw new Exception(__('La version ne peut etre que desktop ou mobile', __FILE__));
		}
		if (init('type') != 'js' && init('type') != 'css') {
			throw new Exception(__('La version ne peut etre que js ou css', __FILE__));
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

	throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>
