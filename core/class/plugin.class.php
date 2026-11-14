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

/* * ***************************Includes********************************* */
require_once __DIR__ . '/../../core/php/core.inc.php';

class plugin {
	/*     * *************************Attributs****************************** */

	private $id;
	private $name;
	private $description;
	private $license;
	private $installation;
	private $author;
	private $require;
	private $requireOsVersion;
	private $category;
	private $filepath;
	private $index;
	private $display;
	private $mobile;
	private $eventjs;
	private $hasDependency;
	private $hasTtsEngine;
	private $maxDependancyInstallTime;
	private $hasOwnDeamon;
	private $issue = '';
	private $changelog = '';
	private $documentation = '';
	private $changelog_beta = '';
	private $documentation_beta = '';
	private $source = '';
	private $whiteListFolders = array();
	private $specialAttributes = array('object' => array(), 'user' => array(), 'eqLogic' => array());
	private $info = array();
	private $include = array();
	private $functionality = array();
	private $usedSpace = 0;
	private static $_cache = array();
	private static $_enable = null;

	/*     * ***********************Méthodes statiques*************************** */

	public static function byId(string $_id, bool $_full = false): plugin {
		global $JEEDOM_INTERNAL_CONFIG;
		if (($cached = self::getFromCache($_id, $_full)) !== null) {
			return $cached;
		}
		$path = self::resolvePath($_id);
		if (!file_exists($path)) {
			self::forceDisablePlugin($_id);
			throw new Exception('Plugin introuvable : ' . $_id);
		}
		$data = json_decode(file_get_contents($path), true);
		if (!is_array($data)) {
			self::forceDisablePlugin($_id);
			throw new Exception('Plugin introuvable (json invalide) : ' . $_id . ' => ' . print_r($data, true));
		}
		$plugin = new plugin();
		$plugin->id = $data['id'];
		$plugin->name = $data['name'];
		$plugin->description = (isset($data['description'])) ? $data['description'] : '';
		if (is_array($plugin->description)) {
			if (isset($plugin->description[translate::getLanguage()])) {
				$plugin->description = $plugin->description[translate::getLanguage()];
			} else {
				$plugin->description = $plugin->description['fr_FR'];
			}
		}
		$plugin->license = (isset($data['licence'])) ? $data['licence'] : '';
		$plugin->license = (isset($data['license'])) ? $data['license'] : $plugin->license;
		$plugin->author = (isset($data['author'])) ? $data['author'] : '';
		$plugin->installation = (isset($data['installation'])) ? $data['installation'] : '';
		$plugin->hasDependency = (isset($data['hasDependency'])) ? $data['hasDependency'] : 0;
		$plugin->hasOwnDeamon = (isset($data['hasOwnDeamon'])) ? $data['hasOwnDeamon'] : 0;
		$plugin->hasTtsEngine = (isset($data['hasTtsEngine'])) ? $data['hasTtsEngine'] : 0;
		$plugin->maxDependancyInstallTime = (isset($data['maxDependancyInstallTime'])) ? $data['maxDependancyInstallTime'] : 30;
		$plugin->eventjs = (isset($data['eventjs'])) ? $data['eventjs'] : 0;
		$plugin->require = (isset($data['require'])) ? $data['require'] : '';
		$plugin->requireOsVersion = (isset($data['requireOsVersion'])) ? $data['requireOsVersion'] : '';
		$plugin->category = (isset($data['category'])) ? $data['category'] : '';
		$plugin->filepath = $path;
		$plugin->index = (isset($data['index'])) ? $data['index'] : $data['id'];
		$plugin->display = (isset($data['display'])) ? $data['display'] : '';
		$plugin->issue = (isset($data['issue'])) ? $data['issue'] : '';
		$plugin->whiteListFolders = (isset($data['whiteListFolders'])) ? $data['whiteListFolders'] : array();
		$plugin->changelog = (isset($data['changelog'])) ? str_replace('#language#', config::byKey('language', 'core', 'fr_FR'), $data['changelog']) : '';
		$plugin->documentation = (isset($data['documentation'])) ? str_replace('#language#', config::byKey('language', 'core', 'fr_FR'), $data['documentation']) : '';
		$plugin->changelog_beta = (isset($data['changelog_beta'])) ? str_replace('#language#', config::byKey('language', 'core', 'fr_FR'), $data['changelog_beta']) : '';
		$plugin->documentation_beta = (isset($data['documentation_beta'])) ? str_replace('#language#', config::byKey('language', 'core', 'fr_FR'), $data['documentation_beta']) : '';
		if (isset($data['specialAttributes'])) {
			if (isset($data['specialAttributes']['object'])) {
				$plugin->specialAttributes['object'] = $data['specialAttributes']['object'];
			}
			if (isset($data['specialAttributes']['user'])) {
				$plugin->specialAttributes['user'] = $data['specialAttributes']['user'];
			}
			if (isset($data['specialAttributes']['eqLogic'])) {
				$plugin->specialAttributes['eqLogic'] = $data['specialAttributes']['eqLogic'];
			}
		}
		$plugin->mobile = '';
		if (file_exists(__DIR__ . '/../../plugins/' . $data['id'] . '/mobile/html')) {
			$plugin->mobile = (isset($data['mobile'])) ? $data['mobile'] : $data['id'];
		}
		if (isset($data['include'])) {
			$plugin->include = array(
				'file' => $data['include']['file'],
				'type' => $data['include']['type'],
			);
		} else {
			$plugin->include = array(
				'file' => $data['id'],
				'type' => 'class',
			);
		}


		$plugin->functionality['interact'] = array('exists' => method_exists($plugin->getId(), 'interact'), 'controlable' => 1);
		$plugin->functionality['cron'] = array('exists' => method_exists($plugin->getId(), 'cron'), 'controlable' => 1);
		$plugin->functionality['cron5'] = array('exists' => method_exists($plugin->getId(), 'cron5'), 'controlable' => 1);
		$plugin->functionality['cron10'] = array('exists' => method_exists($plugin->getId(), 'cron10'), 'controlable' => 1);
		$plugin->functionality['cron15'] = array('exists' => method_exists($plugin->getId(), 'cron15'), 'controlable' => 1);
		$plugin->functionality['cron30'] = array('exists' => method_exists($plugin->getId(), 'cron30'), 'controlable' => 1);
		$plugin->functionality['cronHourly'] = array('exists' => method_exists($plugin->getId(), 'cronHourly'), 'controlable' => 1);
		$plugin->functionality['cronDaily'] = array('exists' => method_exists($plugin->getId(), 'cronDaily'), 'controlable' => 1);
		$plugin->functionality['deadcmd'] = array('exists' => method_exists($plugin->getId(), 'deadCmd'), 'controlable' => 0);
		$plugin->functionality['health'] = array('exists' => method_exists($plugin->getId(), 'health'), 'controlable' => 0);

		$plugin->functionality['interact'] = array('exists' => method_exists($plugin->getId(), 'interact'), 'controlable' => 1);
		$plugin->functionality['cron'] = array('exists' => method_exists($plugin->getId(), 'cron'), 'controlable' => 1);
		$plugin->functionality['cron5'] = array('exists' => method_exists($plugin->getId(), 'cron5'), 'controlable' => 1);
		$plugin->functionality['cron10'] = array('exists' => method_exists($plugin->getId(), 'cron10'), 'controlable' => 1);
		$plugin->functionality['cron15'] = array('exists' => method_exists($plugin->getId(), 'cron15'), 'controlable' => 1);
		$plugin->functionality['cron30'] = array('exists' => method_exists($plugin->getId(), 'cron30'), 'controlable' => 1);
		$plugin->functionality['cronHourly'] = array('exists' => method_exists($plugin->getId(), 'cronHourly'), 'controlable' => 1);
		$plugin->functionality['cronDaily'] = array('exists' => method_exists($plugin->getId(), 'cronDaily'), 'controlable' => 1);
		$plugin->functionality['deadcmd'] = array('exists' => method_exists($plugin->getId(), 'deadCmd'), 'controlable' => 0);
		$plugin->functionality['health'] = array('exists' => method_exists($plugin->getId(), 'health'), 'controlable' => 0);
		if ($_full) {
			if ($plugin->getCache('usedSpace', -1) == -1) {
				$plugin->setCache('usedSpace', getDirectorySize(__DIR__ . '/../../plugins/' . $data['id']), 86400);
			}
			$plugin->usedSpace = $plugin->getCache('usedSpace', -1);
		}
		if (!isset($JEEDOM_INTERNAL_CONFIG['plugin']['category'][$plugin->category])) {
			foreach ($JEEDOM_INTERNAL_CONFIG['plugin']['category'] as $key => $value) {
				if (!isset($value['alias'])) {
					continue;
				}
				if (in_array($plugin->category, $value['alias'])) {
					$plugin->category = $key;
					break;
				}
			}
		}
		self::addToCache($plugin->id, $_full, $plugin);
		return $plugin;
	}

	public static function forceDisablePlugin($_id) {
		if (config::byKey('active', $_id, 0) == 1) {
			config::save('active', 0, $_id);
		}
		$values = array(
			'eqType_name' => $_id,
		);
		$sql = 'UPDATE eqLogic
		SET isEnable=0
		WHERE eqType_name=:eqType_name';
		DB::Prepare($sql, $values);
	}

	public static function getPluginPath($_pluginId) {
		return __DIR__ . '/../../plugins/' . $_pluginId;
	}

	public static function getPathById($_id) {
		return __DIR__ . '/../../plugins/' . $_id . '/plugin_info/info.json';
	}

	private static function resolvePath(string $_id): string {
		if (!file_exists($_id) || strpos($_id, '/') === false) {
			return self::getPathById($_id);
		}
		return $_id;
	}

	private static function getFromCache(string $_id, bool $_full): ?plugin {
		$key = $_id . '::' . ($_full ? '1' : '0');
		return self::$_cache[$key] ?? null;
	}

	private static function addToCache(string $_id, bool $_full, plugin $_plugin): void {
		$key = $_id . '::' . ($_full ? '1' : '0');
		self::$_cache[$key] = $_plugin;
	}

	public function getPathToConfigurationById() {
		if (file_exists(__DIR__ . '/../../plugins/' . $this->id . '/plugin_info/configuration.php')) {
			return 'plugins/' . $this->id . '/plugin_info/configuration.php';
		} else {
			return '';
		}
	}

	public static function listPlugin($_activateOnly = false, $_orderByCategory = false, $_translate = true, $_nameOnly = false) {
		$listPlugin = array();
		if ($_activateOnly) {
			$sql = "SELECT plugin
			FROM config
			WHERE `key`='active'
			AND `value`='1'";
			$results = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
			if ($_nameOnly) {
				foreach ($results as $result) {
					$listPlugin[] = $result['plugin'];
				}
				return $listPlugin;
			}
			foreach ($results as $result) {
				try {
					$listPlugin[] = plugin::byId($result['plugin']);
				} catch (\Throwable $e) {
					log::add('plugin', 'error', log::exception($e), 'pluginNotFound::' . $result['plugin']);
				}
			}
		} else {
			$rootPluginPath = __DIR__ . '/../../plugins';
			foreach (ls($rootPluginPath, '*') as $dirPlugin) {
				if (is_dir($rootPluginPath . '/' . $dirPlugin)) {
					$pathInfoPlugin = $rootPluginPath . '/' . $dirPlugin . 'plugin_info/info.json';
					if (!file_exists($pathInfoPlugin)) {
						continue;
					}
					if ($_nameOnly) {
						$listPlugin[] = str_replace('/', '', $dirPlugin);
					} else {
						try {
							$listPlugin[] = plugin::byId($pathInfoPlugin);
						} catch (\Throwable $e) {
							log::add('plugin', 'error', log::exception($e), 'pluginNotFound::' . $pathInfoPlugin);
						}
					}
				}
			}
			if ($_nameOnly) {
				return $listPlugin;
			}
		}
		if ($_orderByCategory) {
			$return = array();
			if (count($listPlugin) > 0) {
				foreach ($listPlugin as $plugin) {
					$category = $plugin->getCategory();
					if ($category == '') {
						$category = __('Autre', __FILE__);
					}
					if (!isset($return[$category])) {
						$return[$category] = array();
					}
					$return[$category][] = $plugin;
				}
				foreach ($return as &$category) {
					usort($category, 'plugin::orderPlugin');
				}
				ksort($return);
			}
			return $return;
		} else {
			if (is_array($listPlugin) && count($listPlugin) > 0) {
				usort($listPlugin, 'plugin::orderPlugin');
				return $listPlugin;
			} else {
				return array();
			}
		}
	}

	public static function getTranslation($_plugin, $_language) {
		if (in_array(trim($_plugin), array('', 'core', 'fr_FR', '.', '..'))) {
			return array();
		}
		$dir = __DIR__ . '/../../plugins/' . $_plugin . '/core/i18n';
		if (!file_exists($dir)) {
			@mkdir($dir, 0775, true);
		}
		if (!file_exists($dir)) {
			return array();
		}
		if (file_exists($dir . '/' . $_language . '.json')) {
			$return = file_get_contents($dir . '/' . $_language . '.json');
			return is_json($return, array());
		}
		return array();
	}

	public static function orderPlugin($a, $b) {
		$al = strtolower($a->name);
		$bl = strtolower($b->name);
		if ($al == $bl) {
			return 0;
		}
		return ($al > $bl) ? +1 : -1;
	}

	public static function heartbeat() {
		foreach (self::listPlugin(true) as $plugin) {
			try {
				if ($plugin->isGuidedActivationPending()) {
					continue;
				}
				$heartbeat = config::byKey('heartbeat::delay::' . $plugin->getId(), 'core', 0);
				if ($heartbeat == 0 || !is_numeric($heartbeat)) {
					continue;
				}
				$eqLogics = eqLogic::byType($plugin->getId());
				$ok = false;
				$enable = 0;
				foreach ($eqLogics as $eqLogic) {
					if (strtotime($eqLogic->getStatus('lastCommunication', date('Y-m-d H:i:s'))) > strtotime('-' . $heartbeat . ' minutes ' . date('Y-m-d H:i:s'))) {
						$ok = true;
						break;
					}
					if ($eqLogic->getIsEnable() == 1) {
						$enable++;
					}
				}
				if ($enable == 0) {
					continue;
				}
				if (!$ok) {
					$message = __('Attention le plugin', __FILE__) . ' ' . $plugin->getName();
					$message .= ' ' . __('n\'a pas recu de message depuis', __FILE__) . ' ' . $heartbeat . ' ' . __('min', __FILE__);
					$action = '<a href="/' . $plugin->getLinkToConfiguration() . '">' . __('Configuration', __FILE__) . '</a>';
					$logicalId = 'heartbeat' . $plugin->getId();
					message::add($plugin->getId(), $message, $action, $logicalId);
					if ($plugin->getHasOwnDeamon() && config::byKey('heartbeat::restartDeamon::' . $plugin->getId(), 'core', 0) == 1) {
						$plugin->deamon_start(true);
					}
				}
			} catch (Exception $e) {
			}
		}
	}

	public static function cron() {
		$cache = cache::byKey('plugin::cron::inprogress');
		if (is_array($cache->getValue(0))) {
			cache::set('plugin::cron::inprogress', -1);
			$cache = cache::byKey('plugin::cron::inprogress');
		}
		if ($cache->getValue(0) > 3) {
			$message = __('La tâche plugin::cron n\'arrive pas à finir à cause du plugin :', __FILE__) . ' ' . cache::byKey('plugin::cron::last')->getValue() . ' ' . __('nous vous conseillons de désactiver le plugin et de contacter l\'auteur', __FILE__);
			$action = '<a href="/index.php?v=d&p=plugin&id=' . cache::byKey('plugin::cron::last')->getValue() . '">' . __('Configuration', __FILE__) . '</a>';
			message::add('core', $message, $action);
		}
		cache::set('plugin::cron::inprogress', $cache->getValue(0) + 1);
		foreach (self::listPlugin(true) as $plugin) {
			if (method_exists($plugin->getId(), 'cron')) {
				if (config::byKey('functionality::cron::enable', $plugin->getId(), 1) == 0) {
					continue;
				}
				$plugin_id = $plugin->getId();
				cache::set('plugin::cron::last', $plugin_id);
				try {
					$plugin_id::cron();
				} catch (\Throwable $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cron du plugin :', __FILE__) . ' ' . log::exception($e));
				}
			}
		}
		cache::set('plugin::cron::inprogress', 0);
	}

	public static function cron5() {
		$cache = cache::byKey('plugin::cron5::inprogress');
		if (is_array($cache->getValue(0))) {
			cache::set('plugin::cron5::inprogress', -1);
			$cache = cache::byKey('plugin::cron5::inprogress');
		}
		if ($cache->getValue(0) > 3) {
			$message = __('La tâche plugin::cron5 n\'arrive pas à finir à cause du plugin :', __FILE__) . ' ' . cache::byKey('plugin::cron5::last')->getValue() . ' ' . __('nous vous conseillons de désactiver le plugin et de contacter l\'auteur', __FILE__);
			$action = '<a href="/index.php?v=d&p=plugin&id=' . cache::byKey('plugin::cron5::last')->getValue() . '">' . __('Configuration', __FILE__) . '</a>';
			message::add('core', $message, $action);
		}
		cache::set('plugin::cron5::inprogress', $cache->getValue(0) + 1);
		foreach (self::listPlugin(true) as $plugin) {
			if (method_exists($plugin->getId(), 'cron5')) {
				if (config::byKey('functionality::cron5::enable', $plugin->getId(), 1) == 0) {
					continue;
				}
				$plugin_id = $plugin->getId();
				cache::set('plugin::cron5::last', $plugin_id);
				try {
					$plugin_id::cron5();
				} catch (\Throwable $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cron5 du plugin :', __FILE__) . ' ' . log::exception($e));
				}
			}
		}
		cache::set('plugin::cron5::inprogress', 0);
	}

	public static function cron10() {
		$cache = cache::byKey('plugin::cron10::inprogress');
		if (is_array($cache->getValue(0))) {
			cache::set('plugin::cron10::inprogress', -1);
			$cache = cache::byKey('plugin::cron10::inprogress');
		}
		if ($cache->getValue(0) > 3) {
			$message = __('La tâche plugin::cron10 n\'arrive pas à finir à cause du plugin :', __FILE__) . ' ' . cache::byKey('plugin::cron10::last')->getValue() . ' ' . __('nous vous conseillons de désactiver le plugin et de contacter l\'auteur', __FILE__);
			$action = '<a href="/index.php?v=d&p=plugin&id=' . cache::byKey('plugin::cron10::last')->getValue() . '">' . __('Configuration', __FILE__) . '</a>';
			message::add('core', $message, $action);
		}
		cache::set('plugin::cron10::inprogress', $cache->getValue(0) + 1);
		foreach (self::listPlugin(true) as $plugin) {
			if (method_exists($plugin->getId(), 'cron10')) {
				if (config::byKey('functionality::cron10::enable', $plugin->getId(), 1) == 0) {
					continue;
				}
				$plugin_id = $plugin->getId();
				cache::set('plugin::cron10::last', $plugin_id);
				try {
					$plugin_id::cron10();
				} catch (\Throwable $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cron10 du plugin :', __FILE__) . ' ' . log::exception($e));
				}
			}
		}
		cache::set('plugin::cron10::inprogress', 0);
	}

	public static function cron15() {
		$cache = cache::byKey('plugin::cron15::inprogress');
		if (is_array($cache->getValue(0))) {
			cache::set('plugin::cron15::inprogress', -1);
			$cache = cache::byKey('plugin::cron15::inprogress');
		}
		if ($cache->getValue(0) > 3) {
			$message = __('La tâche plugin::cron15 n\'arrive pas à finir à cause du plugin :', __FILE__) . ' ' . cache::byKey('plugin::cron15::last')->getValue() . ' ' . __('nous vous conseillons de désactiver le plugin et de contacter l\'auteur', __FILE__);
			$action = '<a href="/index.php?v=d&p=plugin&id=' . cache::byKey('plugin::cron15::last')->getValue() . '">' . __('Configuration', __FILE__) . '</a>';
			message::add('core', $message, $action);
		}
		cache::set('plugin::cron15::inprogress', $cache->getValue(0) + 1);
		foreach (self::listPlugin(true) as $plugin) {
			if (method_exists($plugin->getId(), 'cron15')) {
				if (config::byKey('functionality::cron15::enable', $plugin->getId(), 1) == 0) {
					continue;
				}
				$plugin_id = $plugin->getId();
				cache::set('plugin::cron15::last', $plugin_id);
				try {
					$plugin_id::cron15();
				} catch (\Throwable $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cron15 du plugin :', __FILE__) . ' ' . log::exception($e));
				}
			}
		}
		cache::set('plugin::cron15::inprogress', 0);
	}

	public static function cron30() {
		$cache = cache::byKey('plugin::cron30::inprogress');
		if (is_array($cache->getValue(0))) {
			cache::set('plugin::cron30::inprogress', -1);
			$cache = cache::byKey('plugin::cron30::inprogress');
		}
		if ($cache->getValue(0) > 3) {
			$message = __('La tâche plugin::cron30 n\'arrive pas à finir à cause du plugin :', __FILE__) . ' ' . cache::byKey('plugin::cron30::last')->getValue() . ' ' . __('nous vous conseillons de désactiver le plugin et de contacter l\'auteur', __FILE__);
			$action = '<a href="/index.php?v=d&p=plugin&id=' . cache::byKey('plugin::cron30::last')->getValue() . '">' . __('Configuration', __FILE__) . '</a>';
			message::add('core', $message, $action);
		}
		cache::set('plugin::cron30::inprogress', $cache->getValue(0) + 1);
		foreach (self::listPlugin(true) as $plugin) {
			if (method_exists($plugin->getId(), 'cron30')) {
				if (config::byKey('functionality::cron30::enable', $plugin->getId(), 1) == 0) {
					continue;
				}
				$plugin_id = $plugin->getId();
				cache::set('plugin::cron30::last', $plugin_id);
				try {
					$plugin_id::cron30();
				} catch (\Throwable $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cron30 du plugin :', __FILE__) . ' ' . log::exception($e));
				}
			}
		}
		cache::set('plugin::cron30::inprogress', 0);
	}

	public static function cronDaily() {
		$cache = cache::byKey('plugin::cronDaily::inprogress');
		if (is_array($cache->getValue(0))) {
			cache::set('plugin::cronDaily::inprogress', -1);
			$cache = cache::byKey('plugin::cronDaily::inprogress');
		}
		if ($cache->getValue(0) > 3) {
			$message = __('La tâche plugin::cronDaily n\'arrive pas à finir à cause du plugin :', __FILE__) . ' ' . cache::byKey('plugin::cronDaily::last')->getValue() . ' ' . __('nous vous conseillons de désactiver le plugin et de contacter l\'auteur', __FILE__);
			$action = '<a href="/index.php?v=d&p=plugin&id=' . cache::byKey('plugin::cronDaily::last')->getValue() . '">' . __('Configuration', __FILE__) . '</a>';
			message::add('core', $message, $action);
		}
		cache::set('plugin::cronDaily::inprogress', $cache->getValue(0) + 1);
		foreach (self::listPlugin(true) as $plugin) {
			if (method_exists($plugin->getId(), 'cronDaily')) {
				if (config::byKey('functionality::cronDaily::enable', $plugin->getId(), 1) == 0) {
					continue;
				}
				$plugin_id = $plugin->getId();
				cache::set('plugin::cronDaily::last', $plugin_id);
				try {
					$plugin_id::cronDaily();
				} catch (\Throwable $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cronDaily du plugin :', __FILE__) . ' ' . log::exception($e));
				}
			}
		}
		cache::set('plugin::cronDaily::inprogress', 0);
	}

	public static function cronHourly() {
		$cache = cache::byKey('plugin::cronHourly::inprogress');
		if (is_array($cache->getValue(0))) {
			cache::set('plugin::cronHourly::inprogress', -1);
			$cache = cache::byKey('plugin::cronHourly::inprogress');
		}
		if ($cache->getValue(0) > 3) {
			$message = __('La tâche plugin::cronHourly n\'arrive pas à finir à cause du plugin :', __FILE__) . ' ' . cache::byKey('plugin::cronHourly::last')->getValue() . ' ' . __('nous vous conseillons de désactiver le plugin et de contacter l\'auteur', __FILE__);
			$action = '<a href="/index.php?v=d&p=plugin&id=' . cache::byKey('plugin::cronHourly::last')->getValue() . '">' . __('Configuration', __FILE__) . '</a>';
			message::add('core', $message, $action);
		}
		cache::set('plugin::cronHourly::inprogress', $cache->getValue(0) + 1);
		foreach (self::listPlugin(true) as $plugin) {
			if (method_exists($plugin->getId(), 'cronHourly')) {
				if (config::byKey('functionality::cronHourly::enable', $plugin->getId(), 1) == 0) {
					continue;
				}
				$plugin_id = $plugin->getId();
				cache::set('plugin::cronHourly::last', $plugin_id);
				try {
					$plugin_id::cronHourly();
				} catch (\Throwable $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cronHourly du plugin :', __FILE__) . ' ' . log::exception($e));
				}
			}
		}
		cache::set('plugin::cronHourly::inprogress', 0);
	}

	public static function start() {
		foreach (self::listPlugin(true) as $plugin) {
			$plugin->deamon_start(false, true);
			if (method_exists($plugin->getId(), 'start')) {
				$plugin_id = $plugin->getId();
				try {
					$plugin_id::start();
				} catch (\Throwable $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction start du plugin :', __FILE__) . ' ' . log::exception($e));
				}
			}
		}
	}

	public static function stop() {
		foreach (self::listPlugin(true) as $plugin) {
			$plugin->deamon_stop();
			if (method_exists($plugin->getId(), 'stop')) {
				$plugin_id = $plugin->getId();
				try {
					$plugin_id::stop();
				} catch (\Throwable $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction stop du plugin :', __FILE__) . ' ' . log::exception($e));
				}
			}
		}
	}

	public static function checkDeamon() {
		foreach (self::listPlugin(true) as $plugin) {
			if ($plugin->isGuidedActivationPending() || config::byKey('deamonAutoMode', $plugin->getId(), 1) != 1) {
				continue;
			}
			$dependancy_info = $plugin->dependancy_info();
			if ($dependancy_info['state'] == 'nok' && config::byKey('dependancyAutoMode', $plugin->getId(), 1) == 1) {
				try {
					$plugin->dependancy_install();
				} catch (Exception $e) {
				}
			} else if ($dependancy_info['state'] == 'in_progress' && $dependancy_info['duration'] > $plugin->getMaxDependancyInstallTime()) {
				if (isset($dependancy_info['progress_file']) && file_exists($dependancy_info['progress_file'])) {
					shell_exec('rm ' . $dependancy_info['progress_file']);
				}
				log::add($plugin->getId(), 'error', __('Attention : l\'installation des dépendances a dépassé le temps maximum autorisé :', __FILE__) . ' ' . $plugin->getMaxDependancyInstallTime() . 'min');
			}
			try {
				$plugin->deamon_start(false, true);
			} catch (Exception $e) {
			}
		}
	}

	public static function isInstalled(string $_pluginId): bool {
		if (self::getFromCache($_pluginId, false) !== null) {
			return true;
		}
		$path = self::resolvePath($_pluginId);
		if (!file_exists($path)) {
			return false;
		}
		return is_array(json_decode(file_get_contents($path), true));
	}

	/* Installations de plugins */

	private const INSTALLATION_QUEUE_ID = 'rest-plugin-installations';

	public static function prepareMarketInstallation(int $marketId, string $version): array {
		if ($marketId < 1 || $marketId > 2147483647 || !in_array($version, array('stable', 'beta'), true)) {
			throw new InvalidArgumentException('Identifiant Market ou branche invalide.', 422);
		}
		if (!repo_market::isConnected()) {
			throw new RuntimeException('Connectez ce Jeedom au Market.', 409);
		}
		try {
			$market = repo_market::byId($marketId);
		} catch (Throwable $e) {
			throw new RuntimeException(update::operationErrorMessage($e), in_array($e->getCode(), array(401, 403, 404), true) ? (int) $e->getCode() : 502);
		}
		if (!is_object($market) || $market->getType() !== 'plugin' || (int) $market->getId() !== $marketId) {
			throw new RuntimeException('Plugin Market introuvable.', 404);
		}
		$id = (string) $market->getLogicalId();
		self::validatePluginId($id);
		if (!$market->getPurchase() || !in_array($version, (array) $market->getAllowVersion(), true)) {
			throw new RuntimeException('Le compte Market configuré sur cette box ne permet pas d’installer cette branche du plugin.', 403);
		}
		if ((int) $market->getStatus($version) !== 1) {
			throw new RuntimeException('Cette branche du plugin n’est pas publiée.', 409);
		}
		$minimum = (string) $market->getMinJeedomVersion();
		if ($minimum !== '' && version_compare(jeedom::version(), $minimum, '<')) {
			throw new RuntimeException('Version de Jeedom incompatible : minimum ' . $minimum . '.', 409);
		}
		$os = (string) system::getOsVersion();
		foreach (array('min' => '<', 'max' => '>') as $bound => $operator) {
			$required = $market->getParameters('os::' . $version . '::' . $bound);
			if ($required !== '' && $required !== null && version_compare($os, (string) $required, $operator)) {
				throw new RuntimeException('Version du système incompatible : ' . $bound . ' ' . $required . '.', 409);
			}
		}
		$hardware = (array) $market->getHardwareCompatibility();
		$platform = jeedom::getHardwareName();
		if (array_key_exists($platform, $hardware) && (int) $hardware[$platform] !== 1) {
			throw new RuntimeException('Ce plugin n’est pas compatible avec cette plateforme.', 409);
		}
		$update = update::byTypeAndLogicalId('plugin', $id);
		if (is_object($update) && ($update->getSource() !== 'market' || $update->getConfiguration('doNotUpdate', 0) == 1)) {
			throw new RuntimeException('Le plugin est géré par une autre source ou ses mises à jour sont désactivées.', 409);
		}
		$installed = is_file(self::getPathById($id));
		return array('marketId' => $marketId, 'pluginId' => $id, 'name' => (string) $market->getName(), 'version' => $version,
			'publishedAt' => $market->getDatetime($version) ?: null, 'minJeedomVersion' => $minimum !== '' ? $minimum : null,
			'installed' => $installed, 'isEnable' => $installed && config::byKey('active', $id, 0) == 1,
			'installedVersion' => is_object($update) ? $update->getConfiguration('version', 'stable') : null,
			'box' => array('name' => (string) config::byKey('name'), 'version' => jeedom::version()));
	}

	public static function startMarketInstallation(int $marketId, string $version, int $userId, string $requestKey): array {
		if (!preg_match('/\A[A-Za-z0-9_-]{16,128}\z/', $requestKey)) {
			throw new InvalidArgumentException('Clé de requête invalide.', 422);
		}
		$preview = self::prepareMarketInstallation($marketId, $version);
		$last = self::latestInstallation($preview['pluginId']);
		if ($last !== null && ($last['requestKey'] ?? null) === $requestKey) {
			if ($last['marketId'] !== $marketId || $last['version'] !== $version) {
				throw new RuntimeException('Cette clé de requête a déjà été utilisée pour une autre installation.', 409);
			}
			$existing = self::installationStatus($last['id']);
			if ($existing !== null) return $existing;
		}
		return self::withInstallationLock($preview['pluginId'], function () use ($preview, $userId, $requestKey) {
			$last = self::latestInstallation($preview['pluginId']);
			if ($last !== null && ($last['requestKey'] ?? null) === $requestKey) {
				if ($last['marketId'] !== $preview['marketId'] || $last['version'] !== $preview['version']) {
					throw new RuntimeException('Cette clé de requête a déjà été utilisée pour une autre installation.', 409);
				}
				return self::installationStatus($last['id']);
			}
			self::assertInstallationIdle($preview['pluginId']);
			if (config::byKey('enableQueue', 'core', 1, true) == 0) {
				throw new RuntimeException('Les tâches en queue sont désactivées.', 409);
			}
			$operation = array('id' => $preview['pluginId'] . '.' . bin2hex(random_bytes(16)), 'pluginId' => $preview['pluginId'],
				'marketId' => $preview['marketId'], 'version' => $preview['version'], 'state' => 'queued', 'userId' => $userId, 'requestKey' => $requestKey,
				'createdAt' => date('c'), 'startedAt' => null, 'finishedAt' => null, 'queueId' => null, 'error' => null);
			return self::enqueueInstallation($operation);
		});
	}

	public static function startRepositoryInstallation(string $id, string $source, array $configuration, int $userId, string $requestKey): array {
		if (!preg_match('/\A[A-Za-z0-9_-]{16,128}\z/', $requestKey)) throw new InvalidArgumentException('Clé de requête invalide.', 422);
		$configuration = pluginRepository::validate($id, $source, $configuration, false);
		$fingerprint = hash('sha256', json_encode(array($source, $configuration), JSON_THROW_ON_ERROR));
		return self::withInstallationLock($id, function () use ($id, $source, $configuration, $userId, $requestKey, $fingerprint) {
			$last = self::latestInstallation($id);
			if ($last !== null && ($last['requestKey'] ?? null) === $requestKey) {
				if (($last['fingerprint'] ?? null) !== $fingerprint) throw new RuntimeException('Cette clé a déjà été utilisée pour une autre installation.', 409);
				return self::installationStatus($last['id']);
			}
			self::assertInstallationIdle($id);
			pluginRepository::validate($id, $source, $configuration);
			if (config::byKey('enableQueue', 'core', 1, true) == 0) throw new RuntimeException('Les tâches en queue sont désactivées.', 409);
			return self::enqueueInstallation(array('id' => $id . '.' . bin2hex(random_bytes(16)), 'pluginId' => $id, 'marketId' => null,
				'source' => $source, 'version' => $configuration['version'] ?? null, 'state' => 'queued', 'userId' => $userId,
				'requestKey' => $requestKey, 'fingerprint' => $fingerprint,
				'parameters' => utils::encrypt(json_encode($configuration, JSON_THROW_ON_ERROR)),
				'createdAt' => date('c'), 'startedAt' => null, 'finishedAt' => null, 'queueId' => null, 'error' => null));
		});
	}

	private static function enqueueInstallation(array $operation): array {
		self::storeInstallation($operation);
		try {
			$queue = new queue();
			$queue->setClass(self::class);
			$queue->setFunction('executeInstallation');
			$queue->setArguments(array($operation['id']));
			$queue->setQueueId(self::INSTALLATION_QUEUE_ID);
			$queue->setTimeout(1800);
			$queue->save();
			$operation['queueId'] = (int) $queue->getId();
			self::storeInstallation($operation);
			$queue->start();
		} catch (Throwable $e) {
			self::failInstallation($operation, $e);
			throw new RuntimeException('Impossible de planifier l’installation.', 503);
		}
		try {
			queue::runNext(self::INSTALLATION_QUEUE_ID);
		} catch (Throwable $e) {
			// The scheduled queue runner can still pick up the saved job.
			log::add('queue', 'warning', 'Démarrage immédiat de l’installation impossible : ' . update::operationErrorMessage($e));
		}
		return $operation;
	}

	/** Last operation per plugin; old operation IDs cannot select a replacement job. */
	public static function installationStatus(string $operationId): ?array {
		if (!preg_match('/\A([A-Za-z0-9_][A-Za-z0-9_-]{0,126})\.[a-f0-9]{32}\z/', $operationId, $match)) {
			return null;
		}
		$operation = self::latestInstallation($match[1]);
		if (!is_array($operation) || $operation['id'] !== $operationId) {
			return null;
		}
		if (in_array($operation['state'], array('queued', 'running'), true) && $operation['queueId'] !== null) {
			$queue = queue::byId($operation['queueId']);
			$lost = !is_object($queue) || in_array($queue->getState(), array('error', 'Not found'), true)
				|| ($operation['state'] === 'running' && !$queue->running());
			if ($lost) {
				// A worker may have just completed between the first read and queue removal.
				$fresh = self::latestInstallation($match[1]);
				if ($fresh === null || $fresh['id'] !== $operationId) return null;
				if ($fresh['state'] !== $operation['state']) {
					return $fresh;
				}
				$operation['state'] = 'failed';
				$operation['error'] = array('code' => 'installation_interrupted', 'message' => 'La tâche d’installation a été interrompue.');
			}
		}
		return $operation;
	}

	public static function latestInstallation(string $pluginId): ?array {
		self::validatePluginId($pluginId);
		$value = config::byKey('rest::pluginInstallation::' . hash('sha256', $pluginId), 'core', null, true);
		return is_array($value) && isset($value['id'], $value['state']) ? $value : null;
	}

	public static function assertInstallationIdle(string $pluginId): void {
		$last = self::latestInstallation($pluginId);
		$operation = $last !== null ? self::installationStatus($last['id']) : null;
		if ($operation !== null && in_array($operation['state'], array('queued', 'running'), true)) {
			throw new RuntimeException('Une installation est déjà en cours : ' . $operation['id'], 409);
		}
	}

	public static function withInstallationLock(string $pluginId, callable $action, bool $wait = false) {
		return update::withPluginMutation(static function () use ($pluginId, $action, $wait) {
			self::validatePluginId($pluginId);
			$lock = fopen(jeedom::getTmpFolder('plugin-installations') . '/' . $pluginId . '.lock', 'c');
			if ($lock === false) {
				throw new RuntimeException('Impossible de verrouiller le plugin.', 503);
			}
			try {
				if (!flock($lock, $wait ? LOCK_EX : LOCK_EX | LOCK_NB)) {
					throw new RuntimeException('Une opération sur ce plugin est déjà en cours.', 409);
				}
				return $action();
			} finally {
				flock($lock, LOCK_UN);
				fclose($lock);
			}
		}, $wait);
	}

	/** Native queue entry point. Never rethrow: failed installs must not be retried automatically. */
	public static function executeInstallation(string $operationId): void {
		$operation = self::installationStatus($operationId);
		if ($operation === null || $operation['state'] !== 'queued') {
			return;
		}
		try {
			self::withInstallationLock($operation['pluginId'], function () use ($operation) {
				$fresh = self::latestInstallation($operation['pluginId']);
				if ($fresh['id'] !== $operation['id'] || $fresh['state'] !== 'queued') {
					return;
				}
				$old = null;
				$update = null;
				$active = null;
				$operation['state'] = 'running';
				$operation['startedAt'] = date('c');
				self::storeInstallation($operation);
				try {
					$actor = user::byId($operation['userId']);
					if (!is_object($actor) || !$actor->isValidAndEnabled() || $actor->getProfils() !== 'admin') {
						throw new RuntimeException('L’administrateur ayant demandé l’installation n’est plus autorisé.', 403);
					}
					$configuration = null;
					if (($operation['source'] ?? 'market') !== 'market') {
						$configuration = json_decode(utils::decrypt($operation['parameters']), true, 512, JSON_THROW_ON_ERROR);
						$configuration = pluginRepository::validate($operation['pluginId'], $operation['source'], $configuration);
						if ($operation['source'] === 'file') $configuration['path'] = pluginRepository::archivePath($configuration['path'], $operation['pluginId']);
						$preview = array('pluginId' => $operation['pluginId'], 'name' => $operation['pluginId'],
							'isEnable' => is_file(self::getPathById($operation['pluginId'])) && config::byKey('active', $operation['pluginId'], 0) == 1);
					} else {
						$preview = self::prepareMarketInstallation($operation['marketId'], $operation['version']);
					}
					if ($preview['pluginId'] !== $operation['pluginId']) {
						throw new RuntimeException('La référence du plugin a changé sur le Market.', 409);
					}
					$update = update::byTypeAndLogicalId('plugin', $operation['pluginId']);
					$old = is_object($update) ? clone $update : null;
					$active = $preview['isEnable'] ? 1 : 0;
					if (!is_object($update)) {
						$update = new update();
						$update->setType('plugin');
						$update->setLogicalId($operation['pluginId']);
						$update->setName($preview['name']);
						$update->setSource('market');
					}
					if ($configuration !== null) {
						$update->setSource($operation['source']);
						foreach ($configuration as $key => $value) $update->setConfiguration($key, $value);
					} else {
						$update->setConfiguration('version', $operation['version']);
					}
					$update->save();
					$update->doUpdate();
					$path = self::getPathById($operation['pluginId']);
					$manifest = is_file($path) ? json_decode(file_get_contents($path), true) : null;
					if (!is_array($manifest) || ($manifest['id'] ?? null) !== $operation['pluginId']) {
						throw new RuntimeException('Le plugin installé est absent ou son manifeste est invalide.');
					}
					config::save('active', $active, $operation['pluginId']);
					$active = null;
					$operation['state'] = 'succeeded';
					$operation['finishedAt'] = date('c');
					self::clearInstallationParameters($operation);
					self::storeInstallation($operation);
				} catch (Throwable $e) {
					try {
						if (is_object($old)) {
							$old->save();
						} elseif (is_object($update) && $update->getId()) {
							$update->remove();
						}
						if ($active !== null) config::save('active', $active, $operation['pluginId']);
					} catch (Throwable $restoreError) {
						$e = new RuntimeException(update::operationErrorMessage($e) . ' Restauration de l’état : ' . update::operationErrorMessage($restoreError));
					}
					self::failInstallation($operation, $e);
				}
			}, wait: true);
		} catch (Throwable $e) {
			if ($e->getCode() !== 409) self::failInstallation($operation, $e);
		}
	}

	private static function storeInstallation(array $operation): void {
		config::save('rest::pluginInstallation::' . hash('sha256', $operation['pluginId']), $operation);
	}

	private static function failInstallation(array $operation, Throwable $error): void {
		self::clearInstallationParameters($operation);
		$operation['state'] = 'failed';
		$operation['finishedAt'] = date('c');
		$operation['error'] = array('code' => 'installation_failed', 'message' => update::operationErrorMessage($error));
		self::storeInstallation($operation);
	}

	private static function clearInstallationParameters(array &$operation): void {
		if (!isset($operation['parameters'])) return;
		try {
			$configuration = json_decode(utils::decrypt($operation['parameters']), true, 512, JSON_THROW_ON_ERROR);
			if (($operation['source'] ?? null) === 'file') pluginRepository::removeArchive($configuration['path']);
		} catch (Throwable $ignored) { /* A missing or corrupt payload must not hide the installation result. */ }
		unset($operation['parameters']);
	}

	public static function validatePluginId(string $id): void {
		if (!preg_match('/\A[A-Za-z0-9_][A-Za-z0-9_-]{0,126}\z/', $id)) {
			throw new InvalidArgumentException('Identifiant de plugin invalide.', 422);
		}
	}

	/*     * *********************Méthodes d'instance************************* */

	public function report($_format = 'pdf', $_parameters = array()) {
		if ($this->getDisplay() == '') {
			throw new Exception(__('Vous ne pouvez pas faire de rapport sur un plugin sans panneau', __FILE__));
		}
		$url = network::getNetworkAccess('internal') . '/index.php?v=d&p=' . $this->getDisplay();
		$url .= '&m=' . $this->getId();
		$url .= '&report=1';
		if (isset($_parameters['theme']) && $_parameters['theme'] != '') {
			$url .= '&theme=' . $_parameters['theme'];
		}
		if (isset($_parameters['arg']) && trim($_parameters['arg']) != '') {
			$url .= '&' . $_parameters['arg'];
		}
		return report::generate($url, 'plugin', $this->getId(), $_format, $_parameters);
	}

	public function isActive() {
		if (self::$_enable === null) {
			self::$_enable = config::getPluginEnable();
		}
		if (isset(self::$_enable[$this->id])) {
			return self::$_enable[$this->id];
		}
		return 0;
	}

	public function callInstallFunction($_function, $_direct = false) {
		if (!$_direct) {
			return $this->launch($_function, true);
		}
		if (strpos($_function, 'pre_') !== false) {
			log::add('plugin', 'debug', 'Recherche de ' . __DIR__ . '/../../plugins/' . $this->getId() . '/plugin_info/pre_install.php');
			if (file_exists(__DIR__ . '/../../plugins/' . $this->getId() . '/plugin_info/pre_install.php')) {
				log::add('plugin', 'debug', __('Fichier d\'installation trouvé pour  :', __FILE__) . ' ' . $this->getId());
				require_once __DIR__ . '/../../plugins/' . $this->getId() . '/plugin_info/pre_install.php';
				ob_start();
				$function = $this->getId() . '_' . $_function;
				if (function_exists($this->getId() . '_' . $_function)) {
					$function();
				}
				return ob_get_clean();
			}
		} else {
			log::add('plugin', 'debug', 'Recherche de ' . __DIR__ . '/../../plugins/' . $this->getId() . '/plugin_info/install.php');
			if (file_exists(__DIR__ . '/../../plugins/' . $this->getId() . '/plugin_info/install.php')) {
				log::add('plugin', 'debug', __('Fichier d\'installation trouvé pour  :', __FILE__) . ' ' . $this->getId());
				require_once __DIR__ . '/../../plugins/' . $this->getId() . '/plugin_info/install.php';
				ob_start();
				$function = $this->getId() . '_' . $_function;
				if (function_exists($this->getId() . '_' . $_function)) {
					$function();
				}
				return ob_get_clean();
			}
		}
	}

	public function dependancy_info($_refresh = false) {
		$plugin_id = $this->getId();
		$cache = cache::byKey('dependancy' . $this->getId());
		if ($_refresh) {
			$cache->remove();
		} else {
			$return = $cache->getValue();
			if (is_array($return) && $return['state'] == 'ok') {
				return $cache->getValue();
			}
		}
		if (file_exists(__DIR__ . '/../../plugins/' . $plugin_id . '/plugin_info/packages.json')) {
			$return = array('log' => $plugin_id . '_packages');
			$packages = system::checkAndInstall(json_decode(file_get_contents(__DIR__ . '/../../plugins/' . $plugin_id . '/plugin_info/packages.json'), true), false, false, $plugin_id);
			$has_dep_to_install = false;
			foreach ($packages as $package => $info) {
				if ($info['status'] != 0 || $info['optional']) {
					continue;
				}
				$has_dep_to_install = true;
			}
			$return['state'] = ($has_dep_to_install) ? 'nok' : 'ok';
			$return['progress_file'] = '/tmp/jeedom_install_in_progress_' . $plugin_id;
			if (file_exists($return['progress_file'])) {
				$progression = trim(file_get_contents($return['progress_file']));
				if ($progression != '') {
					$return['progression'] = $progression;
				}
				$return['state'] = 'in_progress';
				if (config::byKey('lastDependancyInstallTime', $plugin_id) == '') {
					config::save('lastDependancyInstallTime', date('Y-m-d H:i:s'), $plugin_id);
				}
				$return['duration'] = round((strtotime('now') - strtotime(config::byKey('lastDependancyInstallTime', $plugin_id))) / 60);
			} else {
				$return['duration'] = -1;
			}
			$return['last_launch'] = config::byKey('lastDependancyInstallTime', $this->getId(), __('Inconnue', __FILE__));
			$return['auto'] = config::byKey('dependancyAutoMode', $this->getId(), 1);

			if ($return['state'] === 'ok' && method_exists($plugin_id, 'additionnalDependancyCheck')) {
				$additionnal = $plugin_id::additionnalDependancyCheck();
				if (isset($additionnal['state'])) {
					$return['state'] = $additionnal['state'];
				}
			}

			if ($return['state'] === 'ok') {
				cache::set('dependancy' . $this->getId(), $return);
			}
			return $return;
		}
		if ($this->getHasDependency() != 1 || !method_exists($plugin_id, 'dependancy_info')) {
			return array('state' => 'nok', 'log' => 'nok');
		}
		$return = $plugin_id::dependancy_info();
		if (!isset($return['log'])) {
			$return['log'] = '';
		}
		if (isset($return['progress_file'])) {
			if (@file_exists($return['progress_file'])) {
				$return['state'] = 'in_progress';
				$progression = trim(file_get_contents($return['progress_file']));
				if ($progression != '') {
					$return['progression'] = $progression;
				}
			}
		}
		if ($return['state'] == 'in_progress') {
			if (config::byKey('lastDependancyInstallTime', $plugin_id) == '') {
				config::save('lastDependancyInstallTime', date('Y-m-d H:i:s'), $plugin_id);
			}
			$return['duration'] = round((strtotime('now') - strtotime(config::byKey('lastDependancyInstallTime', $plugin_id))) / 60);
		} else {
			$return['duration'] = -1;
		}
		$return['last_launch'] = config::byKey('lastDependancyInstallTime', $this->getId(), __('Inconnue', __FILE__));
		$return['auto'] = config::byKey('dependancyAutoMode', $this->getId(), 1);
		if ($return['state'] == 'ok') {
			cache::set('dependancy' . $this->getId(), $return);
		}
		return $return;
	}
	/**
	 *
	 * @return null
	 * @throws Exception
	 */
	public function dependancy_install($_force = false, $_foreground  = false) {
		$plugin_id = $this->getId();
		if (!$_force && config::byKey('dontProtectTooFastLaunchDependancy', 'core', 0) == 0 && abs(strtotime('now') - strtotime(config::byKey('lastDependancyInstallTime', $plugin_id))) <= 60) {
			$cache = cache::byKey('dependancy' . $this->getId());
			$cache->remove();
			throw new Exception(__('Vous devez attendre au moins 60 secondes entre deux lancements d\'installation de dépendances', __FILE__));
		}
		$dependancy_info = $this->dependancy_info(true);
		if ($dependancy_info['state'] == 'in_progress') {
			throw new Exception(__('Les dépendances sont déjà en cours d\'installation', __FILE__));
		}
		if (file_exists(__DIR__ . '/../../plugins/' . $plugin_id . '/plugin_info/packages.json')) {
			$this->deamon_stop();
			config::save('lastDependancyInstallTime', date('Y-m-d H:i:s'), $plugin_id);
			system::checkAndInstall(json_decode(file_get_contents(__DIR__ . '/../../plugins/' . $plugin_id . '/plugin_info/packages.json'), true), true, $_foreground, $plugin_id, $_force);
			$cache = cache::byKey('dependancy' . $this->getId());
			$cache->remove();
			return;
		}
		if ($this->getHasDependency() != 1 || !method_exists($plugin_id, 'dependancy_install')) {
			return;
		}
		foreach (self::listPlugin(true) as $plugin) {
			if ($plugin->getId() == $this->getId()) {
				continue;
			}
			$dependancy_info = $plugin->dependancy_info();
			if ($dependancy_info['state'] == 'in_progress') {
				throw new Exception(__('Les dépendances d\'un autre plugin sont déjà en cours, veuillez attendre qu\'elles soient finies :', __FILE__) . ' ' . $plugin->getId());
			}
		}
		$cmd = $plugin_id::dependancy_install();
		if (is_array($cmd) && count($cmd) == 2) {
			$script = str_replace('#stype#', system::get('type'), $cmd['script']);
			$script_array = explode(' ', $script);
			if (file_exists($script_array[0])) {
				if (jeedom::isCapable('sudo')) {
					$this->deamon_stop();
					$message = __('Attention : installation des dépendances lancée', __FILE__);
					$action = '<a href="/index.php?v=d&p=plugin&id=' . $plugin_id . '">' . __('Configuration', __FILE__) . '</a>';
					message::add($plugin_id, $message, $action);
					config::save('lastDependancyInstallTime', date('Y-m-d H:i:s'), $plugin_id);
					if (exec('which at | wc -l') == 0) {
						exec(system::getCmdSudo() . '/bin/bash ' . $script . ' >> ' . $cmd['log'] . ' 2>&1 &');
					} else {
						if (!file_exists($cmd['log'])) {
							touch($cmd['log']);
						}
						exec('echo "/bin/bash ' . $script . ' >> ' . $cmd['log'] . ' 2>&1" | ' . system::getCmdSudo() . ' at now');
					}
					sleep(1);
				} else {
					log::add($plugin_id, 'error', __('Veuillez exécuter le script :', __FILE__) . ' /bin/bash ' . $script);
				}
			} else {
				log::add($plugin_id, 'error', __('Aucun script ne correspond à votre type de Linux :', __FILE__) . ' ' . $cmd['script'] . ' ' . __('avec #stype# :', __FILE__) . ' ' . system::get('type'));
			}
		}
		$cache = cache::byKey('dependancy' . $this->getId());
		$cache->remove();
		return;
	}

	public function dependancy_changeAutoMode($_mode) {
		config::save('dependancyAutoMode', $_mode, $this->getId());
		$plugin_id = $this->getId();
		if (method_exists($plugin_id, 'dependancy_changeAutoMode')) {
			$plugin_id::dependancy_changeAutoMode($_mode);
		}
		$this->dependancy_info(true);
	}

	public function deamon_changeAutoMode($_mode) {
		config::save('deamonAutoMode', $_mode, $this->getId());
		$plugin_id = $this->getId();
		if (method_exists($plugin_id, 'deamon_changeAutoMode')) {
			$plugin_id::deamon_changeAutoMode($_mode);
		}
	}
	/**
	 *
	 * @return array
	 */
	public function deamon_info() {
		$plugin_id = $this->getId();
		if ($this->getHasOwnDeamon() != 1 || !method_exists($plugin_id, 'deamon_info')) {
			return array('launchable_message' => '', 'launchable' => 'nok', 'state' => 'nok', 'log' => 'nok', 'auto' => 0);
		}
		$return = $plugin_id::deamon_info();
		if ($this->getHasDependency() == 1 && method_exists($plugin_id, 'dependancy_info') && $return['launchable'] == 'ok') {
			$dependancy_info = $this->dependancy_info();
			if ($dependancy_info['state'] != 'ok') {
				$return['launchable'] = 'nok';
				if ($dependancy_info['state'] == 'in_progress') {
					$return['launchable_message'] = __('Dépendances en cours d\'installation', __FILE__);
				} else {
					$return['launchable_message'] = __('Dépendances non installées', __FILE__);
				}
			}
		}
		if (!isset($return['launchable_message'])) {
			$return['launchable_message'] = '';
		}
		if (!isset($return['log'])) {
			$return['log'] = '';
		}
		$return['auto'] = config::byKey('deamonAutoMode', $this->getId(), 1);
		if ($return['auto'] == 0) {
			$return['launchable_message'] = __('Gestion automatique désactivée', __FILE__);
		}
		if (config::byKey('enableCron', 'core', 1, true) == 0) {
			$return['launchable'] = 'nok';
			$return['launchable_message'] = __('Les crons et démons sont désactivés', __FILE__);
		}
		if (!jeedom::isStarted()) {
			$return['launchable'] = 'nok';
			$return['launchable_message'] = __('Jeedom n\'est pas encore démarré', __FILE__);
		}
		$return['last_launch'] = config::byKey('lastDeamonLaunchTime', $this->getId(), __('Inconnue', __FILE__));
		return $return;
	}

	public function deamon_start($_forceRestart = false, $_auto = false) {
		if ($_auto && $this->isGuidedActivationPending()) {
			return;
		}
		$plugin_id = $this->getId();
		if ($_forceRestart) {
			$this->deamon_stop();
		}
		try {
			if ($this->getHasOwnDeamon() == 1 && method_exists($plugin_id, 'deamon_info')) {
				$deamon_info = $this->deamon_info();
				if ($deamon_info['state'] == 'ok' && config::byKey('deamonRestartNumber', $plugin_id, 0) != 0) {
					config::save('deamonRestartNumber', 0, $plugin_id);
				}
				if ($_auto && $deamon_info['auto'] == 0) {
					return;
				}
				if ($deamon_info['launchable'] == 'ok' && $deamon_info['state'] == 'nok' && method_exists($plugin_id, 'deamon_start')) {
					$inprogress = cache::byKey('deamonStart' . $this->getId() . 'inprogress');
					$info = $inprogress->getValue(array('datetime' => strtotime('now') - 60));
					$info['datetime'] = (isset($info['datetime'])) ? $info['datetime'] : strtotime('now') - 60;
					if (abs(strtotime('now') - $info['datetime']) < 45) {
						if ($_auto) {
							return;
						}
						if (config::byKey('dontProtectTooFastLaunchDeamony', 'core', 0) == 0) {
							throw new Exception(__('Vous devez attendre au moins 45 secondes entre deux lancements du démon. Dernier lancement :', __FILE__) . ' ' . date("Y-m-d H:i:s", $info['datetime']));
						}
					}
					if (config::byKey('deamonRestartNumber', $plugin_id, 0) > 3) {
						log::add($plugin_id, 'error', __('Attention je pense qu\'il y a un soucis avec le démon que j\'ai relancé plus de 3 fois consécutivement', __FILE__));
					}
					if (!$_forceRestart) {
						config::save('deamonRestartNumber', config::byKey('deamonRestartNumber', $plugin_id, 0) + 1, $plugin_id);
					}
					cache::set('deamonStart' . $this->getId() . 'inprogress', array('datetime' => strtotime('now')));
					config::save('lastDeamonLaunchTime', date('Y-m-d H:i:s'), $plugin_id);
					$fct = new ReflectionMethod($plugin_id, 'deamon_start');
					if ($fct->getNumberOfRequiredParameters() > 0) {
						$plugin_id::deamon_start($_auto);
					} else {
						$plugin_id::deamon_start();
					}
				}
			}
		} catch (\Throwable $e) {
			log::add($plugin_id, 'error', __('Erreur sur la fonction deamon_start du plugin :', __FILE__) . ' ' . log::exception($e));
		}
	}

	public function deamon_stop() {
		$plugin_id = $this->getId();
		try {
			if ($this->getHasOwnDeamon() == 1 && method_exists($plugin_id, 'deamon_info')) {
				$deamon_info = $this->deamon_info();
				if ($deamon_info['state'] == 'ok' && method_exists($plugin_id, 'deamon_stop')) {
					$plugin_id::deamon_stop();
				}
			}
		} catch (\Throwable $e) {
			log::add($plugin_id, 'error', __('Erreur sur la fonction deamon_stop du plugin :', __FILE__) . ' ' . log::exception($e));
		}
	}

	public function isGuidedActivationPending(): bool {
		return (int) config::byKey('guidedActivationPending', $this->getId(), 0, true) > time();
	}

	public function finishGuidedActivation(): void {
		config::remove('guidedActivationPending', $this->getId());
	}

	public function setIsEnable($_state, $_force = false, $_foreground = false, bool $_guided = false) {
		if (version_compare(jeedom::version(), $this->getRequire()) == -1 && $_state == 1) {
			throw new Exception(__('Votre version de Jeedom n\'est pas assez récente pour activer ce plugin', __FILE__));
		}
		$osVersion = $this->getRequireOsVersion();
		$distrib = system::getDistrib();
		if (isset($osVersion)) {
			if ($distrib == 'debian' && version_compare(system::getOsVersion(), $osVersion) == -1 && $_state == 1) {
				throw new Exception(sprintf(__('Votre version Debian n\'est pas assez récente pour activer cette version du plugin, %s minimum demandé', __FILE__), $osVersion));
			}
		}
		$alreadyActive = config::byKey('active', $this->getId(), 0);
		if ($_guided && $_state == 1 && $alreadyActive == 0) {
			// An abandoned browser must not leave automatic management suspended indefinitely.
			config::save('guidedActivationPending', time() + 86400, $this->getId());
		}
		if ($_state == 1) {
			config::save('active', $_state, $this->getId());
		}
		$deamonAutoState = config::byKey('deamonAutoMode', $this->getId(), 1);
		config::save('deamonAutoMode', 0, $this->getId());
		if ($_state == 0) {
			$eqLogics = eqLogic::byType($this->getId());
			if (is_array($eqLogics)) {
				foreach ($eqLogics as $eqLogic) {
					try {
						if ($alreadyActive == 1) {
							$eqLogic->setConfiguration('previousIsEnable', $eqLogic->getIsEnable());
							$eqLogic->setConfiguration('previousIsVisible', $eqLogic->getIsVisible());
						}
						$eqLogic->setIsEnable(0);
						$eqLogic->setIsVisible(0);
						$eqLogic->save();
					} catch (\Throwable $e) {
					}
				}
			}
			$listeners = listener::byClass($this->getId());
			if (is_array($listeners)) {
				foreach ($listeners as $listener) {
					$listener->remove();
				}
			}
		} else if ($alreadyActive == 0 && $_state == 1) {
			try {
				include_file('core', $this->getId(), 'class', $this->getId());
			} catch (\Throwable $e) {
			}
			foreach (eqLogic::byType($this->getId()) as $eqLogic) {
				try {
					$eqLogic->setIsEnable($eqLogic->getConfiguration('previousIsEnable', 1));
					$eqLogic->setIsVisible($eqLogic->getConfiguration('previousIsVisible', 1));
					$eqLogic->save();
				} catch (\Throwable $e) {
				}
			}
		}
		try {
			if ($_state == 1) {
				log::add($this->getId(), 'info', 'Début d\'activation du plugin');
				$this->deamon_stop();

				$deamon_info = $this->deamon_info();
				sleep(1);
				log::add($this->getId(), 'info', 'Info sur le démon : ' . json_encode($deamon_info));
				if ($deamon_info['state'] == 'ok') {
					$this->deamon_stop();
				}
				if ($alreadyActive == 1) {
					$out = $this->callInstallFunction('update');
				} else {
					$out = $this->callInstallFunction('install');
				}
				if (!$this->isGuidedActivationPending()) {
					$dependancy_info = $this->dependancy_info(true);
					if ($dependancy_info['state'] == 'nok' && config::byKey('dependancyAutoMode', $this->getId(), 1) == 1) {
						try {
							$this->dependancy_install($_force, $_foreground);
						} catch (Exception $e) {
						}
					}
				}
			} else {
				$this->deamon_stop();
				if ($alreadyActive == 1) {
					$out = $this->callInstallFunction('remove');
				}
				rrmdir(jeedom::getTmpFolder($this->getId()));
			}
			if (isset($out) && trim($out) != '') {
				log::add($this->getId(), 'info', "Installation/remove/update result : " . $out);
			}
		} catch (\Throwable $e) {
			config::save('active', $alreadyActive, $this->getId());
			if ($_guided && $alreadyActive == 0) {
				$this->finishGuidedActivation();
			}
			log::add('plugin', 'error', log::exception($e));
			throw $e;
		} finally {
			if ($deamonAutoState) {
				config::save('deamonAutoMode', 1, $this->getId());
			}
		}
		if ($_state == 0) {
			config::save('active', $_state, $this->getId());
			$this->finishGuidedActivation();
		}
		if ($alreadyActive == 0 && $_state == 1) {
			config::save('log::level::' . $this->getId(), '{"100":"0","200":"0","300":"0","400":"0","1000":"0","default":"1"}');
		}
		self::$_enable = null;
		return true;
	}

	public function launch($_function, $_callInstallFunction = false) {
		if ($_function == '') {
			throw new Exception('La fonction à lancer ne peut être vide');
		}
		if (!$_callInstallFunction && (!class_exists($this->getId()) || !method_exists($this->getId(), $_function))) {
			throw new Exception('Il n\'existe aucune méthode : ' . $this->getId() . '::' . $_function . '()');
		}
		$cmd = __DIR__ . '/../../core/php/jeePlugin.php ';
		$cmd .= ' plugin_id=' . $this->getId();
		$cmd .= ' function=' . $_function;
		$cmd .= ' callInstallFunction=' . $_callInstallFunction;
		if (jeedom::checkOngoingThread($cmd) > 0) {
			return true;
		}
		log::add($this->getId(), 'debug', __('Lancement de :', __FILE__) . ' ' . $cmd);
		if ($_callInstallFunction) {
			return system::php($cmd . ' >> /dev/null 2>&1');
		} else {
			system::php($cmd . ' >> /dev/null 2>&1 &');
		}
		return true;
	}

	public function getUpdate() {
		return update::byTypeAndLogicalId('plugin', $this->getId());
	}

	public function getPathImgIcon() {
		if (file_exists(__DIR__ . '/../../plugins/' . $this->getId() . '/plugin_info/' . $this->getId() . '_icon.png')) {
			return 'plugins/' . $this->getId() . '/plugin_info/' . $this->getId() . '_icon.png';
		}
		if (file_exists(__DIR__ . '/../../plugins/' . $this->getId() . '/doc/images/' . $this->getId() . '_icon.png')) {
			return 'plugins/' . $this->getId() . '/doc/images/' . $this->getId() . '_icon.png';
		}
		if (file_exists(__DIR__ . '/../../plugins/' . $this->getId() . '/plugin_info/' . strtolower($this->getId()) . '_icon.png')) {
			return 'plugins/' . $this->getId() . '/plugin_info/' . strtolower($this->getId()) . '_icon.png';
		}
		if (file_exists(__DIR__ . '/../../plugins/' . $this->getId() . '/doc/images/' . strtolower($this->getId()) . '_icon.png')) {
			return 'plugins/' . $this->getId() . '/doc/images/' . strtolower($this->getId()) . '_icon.png';
		}
		return 'core/img/no-image-plugin.png';
	}

	public function getLogList() {
		$return = array();
		foreach (ls(log::getPathToLog(''), '*') as $log) {
			if ($log == $this->getId()) {
				$return[] = $log;
				continue;
			}
			if (strpos($log, $this->getId()) === 0) {
				$return[] = $log;
				continue;
			}
		}
		return $return;
	}

	public function getLinkToConfiguration() {
		return 'index.php?v=d&p=plugin&id=' . $this->getId();
	}

	public static function getConfigForCommunity($_separator = '<br>') {
		// retrieve core version and branch
		$infoCore = 'Core : ' . config::byKey('version', 'core', '#NA#') . ' (' . config::byKey('core::branch') . ')' . $_separator;

		// check if connexion used jeedom DNS
		$url =  network::getNetworkAccess('external');
		$hasDns  = ((strpos($url, 'jeedom.com') !== false || strpos($url, 'eu.jeedom.link')) !== false);
		$infoCore .= 'DNS ' . config::byKey('product_name') . ' : ' . ($hasDns ? __('oui', __FILE__) : __('non', __FILE__));
		$infoCore .= $_separator;

		return $infoCore;
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getDescription() {
		if (is_array($this->description)) {
			foreach ($this->description as $key => &$value) {
				$value = nl2br($value);
			}
			return $this->description;
		}
		return nl2br($this->description);
	}

	public function getSpecialAttributes() {
		return $this->specialAttributes;
	}

	public function getInfo($_name = '', $_default = '') {
		if (count($this->info) == 0) {
			$update = update::byLogicalId($this->id);
			if (is_object($update)) {
				$this->info = $update->getInfo();
				if (is_object($update)) {
					$this->source = $update->getSource();
				}
			}
		}
		if ($_name !== '') {
			if (isset($this->info[$_name])) {
				return $this->info[$_name];
			}
			return $_default;
		}
		return $this->info;
	}

	public function getAuthor() {
		return $this->author;
	}

	public function getFunctionality(): array {
		return $this->functionality;
	}

	public function getUsedSpace() {
		return $this->usedSpace;
	}

	/** Prepare a Community draft URL; never submit a post on behalf of the user. */
	public function getCommunityPost(): array {
		$update = $this->getUpdate();
		$version = is_object($update) ? $update->getConfiguration('version', 'stable') : 'stable';
		$body = 'Remplacez ce texte par votre demande en conservant les informations ci-dessous.' . "\n\n---\n";
		$body .= '**Informations ' . config::byKey('product_name') . "**\n```\n";
		$body .= br2nl(self::getConfigForCommunity()) . "\nPlugin : " . $this->getName();
		$body .= "\nVersion : " . (is_object($update) ? $update->getLocalVersion() : '') . ' (' . $version . ')';
		if ($this->getHasOwnDeamon()) {
			$daemon = $this->deamon_info();
			$body .= "\nStatut Démon : " . ($daemon['state'] ?? 'nok') . ' - ' . ($daemon['last_launch'] ?? 'Inconnue');
		}
		$body .= "\n```\n[details=\"Santé\"]\n```\n";
		foreach (jeedom::health() as $health) {
			$body .= $health['name'] . ' : ' . str_replace(array("\r", "\n"), ' ', (string) $health['result']) . "\n";
		}
		$body .= "```\n[/details]\n";
		$class = $this->getId();
		if (method_exists($class, 'getConfigForCommunity')) {
			$body .= "\n**Informations complémentaires**\n" . br2nl($class::getConfigForCommunity());
		}
		return array('url' => 'https://community.jeedom.com/new-topic?' . http_build_query(array(
			'category' => 'plugins/' . $this->getCategory(), 'tags' => 'plugin-' . $this->getId(), 'body' => $body)),
			'plugin' => $this->getName());
	}

	public function getRequire() {
		return $this->require;
	}

	public function getRequireOsVersion() {
		return $this->requireOsVersion;
	}

	public function getCategory() {
		return $this->category;
	}

	public function getLicense() {
		return $this->license;
	}

	public function getFilepath() {
		return $this->filepath;
	}

	public function getInstallation() {
		return nl2br($this->installation);
	}

	public function getIndex() {
		return $this->index;
	}

	public function getInclude() {
		return $this->include;
	}

	public function getDisplay() {
		return $this->display;
	}

	public function setDisplay($display) {
		$this->display = $display;
		return $this;
	}

	public function getMobile() {
		return $this->mobile;
	}

	public function setMobile($mobile) {
		$this->mobile = $mobile;
		return $this;
	}

	public function getEventjs() {
		return $this->eventjs;
	}

	public function setEventjs($eventjs) {
		$this->eventjs = $eventjs;
		return $this;
	}

	public function getHasDependency() {
		return $this->hasDependency;
	}

	public function setHasDependency($hasDependency) {
		$this->hasDependency = $hasDependency;
		return $this;
	}

	public function getHasOwnDeamon() {
		return $this->hasOwnDeamon;
	}

	public function setHasOwnDeamony($hasOwnDeamon) {
		$this->hasOwnDeamon = $hasOwnDeamon;
		return $this;
	}

	public function getHasTtsEngine() {
		return $this->hasTtsEngine;
	}

	public function setHasTtsEngine($hasTtsEngine) {
		$this->hasTtsEngine = $hasTtsEngine;
		return $this;
	}

	public function getMaxDependancyInstallTime() {
		return $this->maxDependancyInstallTime;
	}

	public function setMaxDependancyInstallTime($maxDependancyInstallTime) {
		$this->maxDependancyInstallTime = $maxDependancyInstallTime;
		return $this;
	}

	public function getIssue() {
		return $this->issue;
	}

	public function setIssue($issue) {
		$this->issue = $issue;
		return $this;
	}

	public function getChangelog() {
		if ($this->changelog == '') {
			return $this->getInfo('changelog');
		}
		return $this->changelog;
	}

	public function setChangelog($changelog) {
		$this->changelog = $changelog;
		return $this;
	}

	public function getDocumentation() {
		if ($this->documentation == '') {
			return $this->getInfo('doc');
		}
		return $this->documentation;
	}

	public function setDocumentation($documentation) {
		$this->documentation = $documentation;
		return $this;
	}

	public function getChangelog_beta() {
		if ($this->changelog_beta == '') {
			return $this->getInfo('changelog_beta');
		}
		return $this->changelog_beta;
	}

	public function setChangelog_beta($changelog_beta) {
		$this->changelog_beta = $changelog_beta;
		return $this;
	}

	public function getDocumentation_beta() {
		if ($this->documentation_beta == '') {
			return $this->getInfo('doc_beta');
		}
		return $this->documentation_beta;
	}

	public function setDocumentation_beta($documentation_beta) {
		$this->documentation_beta = $documentation_beta;
		return $this;
	}

	public function getSource() {
		if ($this->source == '') {
			$update = update::byLogicalId($this->id);
			if (is_object($update)) {
				$this->source = $update->getSource();
			}
		}
		return $this->source;
	}

	public function getWhiteListFolders() {
		return $this->whiteListFolders;
	}

	public function setWhiteListFolders($paths) {
		$this->whiteListFolders = (array) $paths;
		return $this;
	}

	public function getCache($_key = '', $_default = '') {
		$cache = cache::byKey('pluginCacheAttr' . $this->getId())->getValue();
		return utils::getJsonAttr($cache, $_key, $_default);
	}

	public function setCache($_key, $_value = null, $_lifetime = 0) {
		cache::set('pluginCacheAttr' . $this->getId(), utils::setJsonAttr(cache::byKey('pluginCacheAttr' . $this->getId())->getValue(), $_key, $_value), $_lifetime);
	}
}
