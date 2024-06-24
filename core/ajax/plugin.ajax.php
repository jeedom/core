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
		$plugin = plugin::byId(init('id'),init('full',0));
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
			throw new Exception(__('Plugin introuvable :', __FILE__) . ' ' . init('id'));
		}
		$plugin->setIsEnable(init('state'));
		ajax::success();
	}

	if (init('action') == 'all') {
		if (!isConnect()) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		ajax::success(utils::o2a(plugin::listPlugin(init('activateOnly', false))));
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
		ajax::success($plugin->dependancy_install(true));
	}

	if (init('action') == 'dependancyChangeAutoMode') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$plugin = plugin::byId(init('id'));
		if (!is_object($plugin)) {
			ajax::success();
		}
		ajax::success($plugin->dependancy_changeAutoMode(init('mode')));
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

	if (init('action') == 'createCommunityPost') {
		$header = __('Remplacez ce texte par votre demande en prenant soin de ne pas effacer les informations renseignées ci-dessous.', __FILE__) . '<br><br><br><br>';
		$header .= '<br>---<br>';
		$header .= '**' . __('Informations', __FILE__) . ' ' . config::byKey('product_name') . '**';
		$header .= '<br>```<br>';
		$footer = '<br>```<br>';

		$infoPost = plugin::getConfigForCommunity();

		/** @var plugin $plugin */
		$plugin = plugin::byId(init('type'));
		$plugin_id = $plugin->getId();
		$infoPost .= '<br>Plugin : ' . $plugin->getName() . '<br>';

		/** @var update $update */
		$update = $plugin->getUpdate();
		$isBeta = false;
		if (is_object($update)) {
			$version = $update->getConfiguration('version');
			$isBeta = ($version && $version != 'stable');
		}

		$infoPost .= __('Version', __FILE__) . ' : ' . $update->getLocalVersion() . ' (' . ($isBeta ? 'beta' : 'stable') . ')';

		if ($plugin->getHasOwnDeamon()) {
			$daemon_info = $plugin->deamon_info();
			$infoPost .= '<br>' . __('Statut Démon', __FILE__) . ' : ' . ($daemon_info['state'] == 'ok' ?  __('Démarré', __FILE__) :  __('Stoppé', __FILE__));
			$infoPost .= ' - (' . ($daemon_info['last_launch'] ?? __('Inconnue', __FILE__)) . ')';
		}

		$infoPlugin = '';
		if (method_exists($plugin_id, 'getConfigForCommunity')) {
			$infoPlugin .= '**' . __('Informations complémentaires', __FILE__) .  '**<br>';
			$infoPlugin .= $plugin_id::getConfigForCommunity();
		}

		// GENERATE URL with Query Param to create a new post
		$communitUrl = 'https://community.jeedom.com';
		$ressource = '/new-topic?';

		$finalBody = br2nl($header . $infoPost . $footer . $infoPlugin);

		$data = array(
			'category' => 'plugins/' . $plugin->getCategory(),
			'tags' => 'plugin-' . $plugin->getId(),
			'body' => $finalBody
		);

		$query = http_build_query($data);
		$url = $communitUrl . $ressource . $query;
		ajax::success(array('url' => $url, 'plugin' => $plugin->getName()));
	}

	throw new Exception(__('Aucune méthode correspondante à :', __FILE__) . ' ' . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}
