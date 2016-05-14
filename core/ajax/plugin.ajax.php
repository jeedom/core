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
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

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
		if (is_object($update)) {
			$class = 'repo_' . $update->getSource();
			if (method_exists($class, 'getInfo')) {
				$return['status'] = $class::getInfo(array('logicalId' => $plugin->getId(), 'type' => 'plugin'));
			}
		}
		$return['update'] = utils::o2a($update);
		$return['log'] = log::liste($plugin->getId());
		ajax::success($return);
	}

	if (init('action') == 'toggle') {
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
		$plugin = plugin::byId(init('id'));
		if (is_json(init('slave_id', 0)) && is_array(json_decode(init('slave_id', 0), true))) {
			$return = array();
			foreach (json_decode(init('slave_id', 0), true) as $key => $value) {
				if ($key == 0) {
					$plugin = plugin::byId(init('id'));
					if (is_object($plugin)) {
						$return[$key] = $plugin->dependancy_info();
					}
				} else {
					$jeeNetwork = jeeNetwork::byId([$key]);
					if (is_object($jeeNetwork)) {
						$return[$key] = $jeeNetwork->sendRawRequest('plugin::dependancyInfo', array('plugin_id' => $plugin_id));
					}
				}
			}
		} else {
			$return = array('state' => 'nok', 'log' => 'nok');
			if (init('slave_id', 0) == 0) {
				$plugin = plugin::byId(init('id'));
				if (is_object($plugin)) {
					$return = $plugin->dependancy_info();
				}
			} else {
				$jeeNetwork = jeeNetwork::byId(init('slave_id'));
				if (is_object($jeeNetwork)) {
					$return = $jeeNetwork->sendRawRequest('plugin::dependancyInfo', array('plugin_id' => init('id')));
				}
			}
		}
		ajax::success($return);
	}

	if (init('action') == 'dependancyInstall') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		if (init('slave_id', 0) == 0) {
			$plugin = plugin::byId(init('id'));
			if (!is_object($plugin)) {
				ajax::success();
			}
			ajax::success($plugin->dependancy_install());
		} else {
			$jeeNetwork = jeeNetwork::byId(init('slave_id'));
			if (is_object($jeeNetwork)) {
				ajax::success($jeeNetwork->sendRawRequest('plugin::dependancyInstall', array('plugin_id' => init('id'))));
			}
		}
		ajax::success();
	}

	if (init('action') == 'getDeamonInfo') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$plugin_id = init('id');
		if (is_json(init('slave_id', 0)) && is_array(json_decode(init('slave_id', 0), true))) {
			$return = array();
			foreach (json_decode(init('slave_id', 0), true) as $key => $value) {
				if ($key == 0) {
					$plugin = plugin::byId(init('id'));
					if (is_object($plugin)) {
						$return[$key] = $plugin->deamon_info();
					}
				} else {
					$jeeNetwork = jeeNetwork::byId([$key]);
					if (is_object($jeeNetwork)) {
						$return[$key] = $jeeNetwork->sendRawRequest('plugin::deamonInfo', array('plugin_id' => $plugin_id));
					}
				}
			}
		} else {
			$return = array('launchable_message' => '', 'launchable' => 'nok', 'state' => 'nok', 'log' => 'nok', 'auto' => 0);
			if (init('slave_id', 0) == 0) {
				$plugin = plugin::byId(init('id'));
				if (is_object($plugin)) {
					$return = $plugin->deamon_info();
				}
			} else {
				$jeeNetwork = jeeNetwork::byId(init('slave_id'));
				if (is_object($jeeNetwork)) {
					$return = $jeeNetwork->sendRawRequest('plugin::deamonInfo', array('plugin_id' => $plugin_id));
				}
			}
		}
		ajax::success($return);
	}

	if (init('action') == 'deamonStart') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$plugin_id = init('id');
		if (init('slave_id', 0) == 0) {
			$plugin = plugin::byId(init('id'));
			if (!is_object($plugin)) {
				ajax::success();
			}
			ajax::success($plugin->deamon_start(init('forceRestart', 0)));
		} else {
			$jeeNetwork = jeeNetwork::byId(init('slave_id'));
			if (!is_object($jeeNetwork)) {
				ajax::success();
			}
			ajax::success($jeeNetwork->sendRawRequest('plugin::deamonStart', array('plugin_id' => $plugin_id, 'debug' => init('debug', 0), 'forceRestart' => init('forceRestart', 0))));
		}
		ajax::success();
	}

	if (init('action') == 'deamonStop') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		if (init('slave_id', 0) == 0) {
			$plugin = plugin::byId(init('id'));
			if (!is_object($plugin)) {
				ajax::success();
			}
			ajax::success($plugin->deamon_stop());
		} else {
			$jeeNetwork = jeeNetwork::byId(init('slave_id'));
			if (!is_object($jeeNetwork)) {
				ajax::success();
			}
			ajax::success($jeeNetwork->sendRawRequest('plugin::deamonStop', array('plugin_id' => init('id'))));
		}
		ajax::success();
	}

	if (init('action') == 'deamonChangeAutoMode') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		if (init('slave_id', 0) == 0) {
			$plugin = plugin::byId(init('id'));
			if (!is_object($plugin)) {
				ajax::success();
			}
			ajax::success($plugin->deamon_changeAutoMode(init('mode')));
		} else {
			$jeeNetwork = jeeNetwork::byId(init('slave_id'));
			if (!is_object($jeeNetwork)) {
				ajax::success();
			}
			ajax::success($jeeNetwork->sendRawRequest('plugin::deamonChangeAutoMode', array('plugin_id' => init('id'), 'mode' => init('mode'))));
		}
		ajax::success();
	}

	throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>
