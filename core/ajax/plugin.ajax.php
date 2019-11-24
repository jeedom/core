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
    ajax::checkAccess('');
	if (init('action') == 'getConf') {
        ajax::checkAccess('admin');
		$plugin = plugin::byId(init('id'));
		$update = update::byLogicalId(init('id'));
		$return = utils::o2a($plugin);
		$return['activate'] = $plugin->isActive();
		$return['configurationPath'] = $plugin->getPathToConfigurationById();
		$return['checkVersion'] = version_compare(jeedom::version(), $plugin->getRequire());
		if (is_object($update)) {
			$class = 'repo_' . $update->getSource();
			if (method_exists($class, 'getInfo')) {
				$return['status'] = $class::getInfo(array('logicalId' => $plugin->getId(), 'type' => 'plugin'));
			}
			if (!isset($return['status'])) {
				$return['status'] = array();
			}
			if (!isset($return['status']['owner'])) {
				$return['status']['owner'] = array();
			}
			foreach (update::listRepo() as $key => $repo) {
				if (!isset($repo['scope']['sendPlugin']) || !$repo['scope']['sendPlugin']) {
					continue;
				}
				if ($update->getSource() != $key) {
					$return['status']['owner'][$key] = 0;
					$class = 'repo_' . $key;
					if (config::byKey($key . '::enable')) {
						$info = $class::getInfo(array('logicalId' => $plugin->getId(), 'type' => 'plugin'));
						if (isset($info['owner']) && isset($info['owner'][$key])) {
							$return['status']['owner'][$key] = $info['owner'][$key];
						}
					}
				}
			}
		}

		$return['update'] = utils::o2a($update);
		$return['logs'] = array();
		$return['logs'][-1] = array('id' => -1, 'name' => 'local', 'log' => $plugin->getLogList());
		return $return;
	}

	if (init('action') == 'toggle') {
		unautorizedInDemo();
		ajax::checkAccess('admin');
		$plugin = plugin::byId(init('id'));
		if (!is_object($plugin)) {
			throw new Exception(__('Plugin introuvable : ', __FILE__) . init('id'));
		}
		$plugin->setIsEnable(init('state'));
		return '';
	}

	if (init('action') == 'all') {
        ajax::checkAccess(''); // Double appel
		return utils::o2a(plugin::listPlugin(init('activateOnly',false)));
	}

	if (init('action') == 'getDependancyInfo') {
		ajax::checkAccess('admin');

		$return = array('state' => 'nok', 'log' => 'nok');
		$plugin = plugin::byId(init('id'));
		if (is_object($plugin)) {
			$return = $plugin->dependancy_info();
		}
		return $return;
	}

	if (init('action') == 'dependancyInstall') {
		ajax::checkAccess('admin');
		unautorizedInDemo();
		$plugin = plugin::byId(init('id'));
		if (!is_object($plugin)) {
			return '';
		}
		return $plugin->dependancy_install();
	}

	if (init('action') == 'getDeamonInfo') {
		ajax::checkAccess('admin');
		$plugin_id = init('id');
		$return = array('launchable_message' => '', 'launchable' => 'nok', 'state' => 'nok', 'log' => 'nok', 'auto' => 0);
		$plugin = plugin::byId(init('id'));
		if (is_object($plugin)) {
			$return = $plugin->deamon_info();
		}
		$return['plugin'] = utils::o2a($plugin);
		return $return;
	}

	if (init('action') == 'deamonStart') {
		ajax::checkAccess('admin');
		unautorizedInDemo();
		$plugin_id = init('id');
		$plugin = plugin::byId(init('id'));
		if (!is_object($plugin)) {
			return '';
		}
		return $plugin->deamon_start(init('forceRestart', 0));
	}

	if (init('action') == 'deamonStop') {
		ajax::checkAccess('admin');
		unautorizedInDemo();
		$plugin = plugin::byId(init('id'));
		if (!is_object($plugin)) {
			return '';
		}
		return $plugin->deamon_stop();
	}

	if (init('action') == 'deamonChangeAutoMode') {
		ajax::checkAccess('admin');
		unautorizedInDemo();
		$plugin = plugin::byId(init('id'));
		if (!is_object($plugin)) {
			return '';
		}
		return $plugin->deamon_changeAutoMode(init('mode'));
	}

	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
});
