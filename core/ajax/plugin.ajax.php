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

	if (init('action') == 'getConf') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$plugin = plugin::byId(init('id'));
		$update = update::byLogicalId(init('id'));
		$return = utils::o2a($plugin);
		$return['activate'] = $plugin->isActive();
		$return['configurationPath'] = $plugin->getPathToConfigurationById();
		$return['checkVersion'] = version_compare(jeedom::version(), $plugin->getRequire());
		$return['update'] = utils::o2a($update);
		$return['logs'] = array();
		$return['logs'][-1] = array('id' => -1, 'name' => 'local', 'log' => $plugin->getLogList());
		ajax::success($return);
	}

	if (init('action') == 'toggle') {
		unautorizedInDemo();
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$plugin = plugin::byId(init('id'));
		if (!is_object($plugin)) {
			throw new Exception(__('Plugin introuvable : ', __FILE__) . init('id'));
		}
		$plugin->setIsEnable(init('state'));
		ajax::success();
	}

	if (init('action') == 'all') {
		if (!isConnect()) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		ajax::success(utils::o2a(plugin::listPlugin()));
	}

	if (init('action') == 'getDependancyInfo') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}

		$return = array('state' => 'nok', 'log' => 'nok');
		$plugin = plugin::byId(init('id'));
		if (is_object($plugin)) {
			$return = $plugin->dependancy_info();
		}
		ajax::success($return);
	}

	if (init('action') == 'dependancyInstall') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$plugin = plugin::byId(init('id'));
		if (!is_object($plugin)) {
			ajax::success();
		}
		ajax::success($plugin->dependancy_install());
	}

	if (init('action') == 'getDeamonInfo') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$plugin_id = init('id');
		$return = array('launchable_message' => '', 'launchable' => 'nok', 'state' => 'nok', 'log' => 'nok', 'auto' => 0);
		$plugin = plugin::byId(init('id'));
		if (is_object($plugin)) {
			$return = $plugin->deamon_info();
		}
		$return['plugin'] = utils::o2a($plugin);
		ajax::success($return);
	}

	if (init('action') == 'deamonStart') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$plugin_id = init('id');
		$plugin = plugin::byId(init('id'));
		if (!is_object($plugin)) {
			ajax::success();
		}
		ajax::success($plugin->deamon_start(init('forceRestart', 0)));
	}

	if (init('action') == 'deamonStop') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$plugin = plugin::byId(init('id'));
		if (!is_object($plugin)) {
			ajax::success();
		}
		ajax::success($plugin->deamon_stop());
	}

	if (init('action') == 'deamonChangeAutoMode') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$plugin = plugin::byId(init('id'));
		if (!is_object($plugin)) {
			ajax::success();
		}
		ajax::success($plugin->deamon_changeAutoMode(init('mode')));
	}

	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}
?>
