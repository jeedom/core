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
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';

class plugin {
	/*     * *************************Attributs****************************** */

	private $id;
	private $name;
	private $description;
	private $licence;
	private $installation;
	private $author;
	private $require;
	private $category;
	private $filepath;
	private $index;
	private $display;
	private $mobile;
	private $allowRemote;
	private $eventjs;
	private $hasDependency;
	private $maxDependancyInstallTime;
	private $hasOwnDeamon;
	private $issue = '';
	private $info = array();
	private $include = array();
	private static $_cache = array();
	private static $_enable = null;

	/*     * ***********************Méthodes statiques*************************** */

	public static function byId($_id, $_translate = true) {
		if (is_string($_id) && isset(self::$_cache[$_id])) {
			return self::$_cache[$_id];
		}
		if (!file_exists($_id) || (strpos($_id, '.xml') === false && strpos($_id, '.json') === false)) {
			$_id = self::getPathById($_id);
			if (isset(self::$_cache[$_id])) {
				return self::$_cache[$_id];
			}
			if (!file_exists($_id)) {
				throw new Exception('Plugin introuvable : ' . $_id);
			}
		}
		$_id = str_replace('//', '/', $_id);
		if (file_exists(str_replace('.xml', '.json', $_id))) {
			$_id = str_replace('.xml', '.json', $_id);
		}
		if (strpos($_id, '.json') !== false) {
			$data = json_decode(file_get_contents($_id), true);
			if (!is_array($data)) {
				throw new Exception('Plugin introuvable (json invalide) : ' . $_id);
			}
			$plugin = new plugin();
			$plugin->id = $data['id'];
			$plugin->name = $data['name'];
			$plugin->description = (isset($data['description'])) ? $data['description'] : '';
			$plugin->licence = (isset($data['licence'])) ? $data['licence'] : '';
			$plugin->author = (isset($data['author'])) ? $data['author'] : '';
			$plugin->installation = (isset($data['installation'])) ? $data['installation'] : '';
			$plugin->allowRemote = (isset($data['allowRemote'])) ? $data['allowRemote'] : 0;
			$plugin->hasDependency = (isset($data['hasDependency'])) ? $data['hasDependency'] : 0;
			$plugin->hasOwnDeamon = (isset($data['hasOwnDeamon'])) ? $data['hasOwnDeamon'] : 0;
			$plugin->maxDependancyInstallTime = (isset($data['maxDependancyInstallTime'])) ? $data['maxDependancyInstallTime'] : 30;
			$plugin->eventjs = (isset($data['eventjs'])) ? $data['eventjs'] : 0;
			$plugin->require = $data['require'];
			$plugin->category = $data['category'];
			$plugin->filepath = $_id;
			$plugin->index = (isset($data['index'])) ? (string) $data['index'] : $data['id'];
			$plugin->display = (isset($data['display'])) ? (string) $data['display'] : '';
			$plugin->issue = (isset($data['issue'])) ? (string) $data['issue'] : '';

			$plugin->mobile = '';
			if (file_exists(dirname(__FILE__) . '/../../plugins/' . $data['id'] . '/mobile/html')) {
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
		} else {
			libxml_use_internal_errors(true);
			$plugin_xml = @simplexml_load_file($_id);
			if (!is_object($plugin_xml)) {
				throw new Exception('Plugin introuvable (xml invalide) : ' . $_id . '. Description : ' . print_r(libxml_get_errors(), true));
			}
			$plugin = new plugin();
			$plugin->id = (string) $plugin_xml->id;
			$plugin->name = (string) $plugin_xml->name;
			$plugin->description = (string) $plugin_xml->description;
			$plugin->licence = (string) $plugin_xml->licence;
			$plugin->author = (string) $plugin_xml->author;
			$plugin->require = (string) $plugin_xml->require;
			$plugin->installation = (string) $plugin_xml->installation;
			$plugin->category = (string) $plugin_xml->category;
			$plugin->allowRemote = 0;
			if (isset($plugin_xml->allowRemote)) {
				$plugin->allowRemote = $plugin_xml->allowRemote;
			}
			$plugin->hasDependency = 0;
			if (isset($plugin_xml->hasDependency)) {
				$plugin->hasDependency = $plugin_xml->hasDependency;
			}
			$plugin->hasOwnDeamon = 0;
			if (isset($plugin_xml->hasOwnDeamon)) {
				$plugin->hasOwnDeamon = $plugin_xml->hasOwnDeamon;
			}
			if (isset($plugin_xml->maxDependancyInstallTime)) {
				$plugin->maxDependancyInstallTime = $plugin_xml->maxDependancyInstallTime;
			} else {
				$plugin->maxDependancyInstallTime = 10;
			}

			$plugin->eventjs = 0;
			if (isset($plugin_xml->eventjs)) {
				$plugin->eventjs = 1;
			}
			$plugin->filepath = $_id;
			$plugin->index = (isset($plugin_xml->index)) ? (string) $plugin_xml->index : $plugin_xml->id;
			$plugin->display = (isset($plugin_xml->display)) ? (string) $plugin_xml->display : '';

			$plugin->mobile = '';
			if (file_exists(dirname(__FILE__) . '/../../plugins/' . $plugin_xml->id . '/mobile/html')) {
				$plugin->mobile = (isset($plugin_xml->mobile)) ? (string) $plugin_xml->mobile : $plugin_xml->id;
			}
			if (isset($plugin_xml->include)) {
				$plugin->include = array(
					'file' => (string) $plugin_xml->include,
					'type' => (string) $plugin_xml->include['type'],
				);
			} else {
				$plugin->include = array(
					'file' => $plugin_xml->id,
					'type' => 'class',
				);
			}
		}

		if ($_translate) {
			$plugin->description = __($plugin->description, $_id);
			$plugin->installation = __($plugin->installation, $_id);
		}

		self::$_cache[$_id] = $plugin;
		self::$_cache[$plugin->id] = $plugin;
		return $plugin;
	}

	public static function getPathById($_id) {
		if (file_exists(dirname(__FILE__) . '/../../plugins/' . $_id . '/plugin_info/info.json')) {
			return dirname(__FILE__) . '/../../plugins/' . $_id . '/plugin_info/info.json';
		}
		return dirname(__FILE__) . '/../../plugins/' . $_id . '/plugin_info/info.xml';
	}

	public function getPathToConfigurationById() {
		if (file_exists(dirname(__FILE__) . '/../../plugins/' . $this->id . '/plugin_info/configuration.php')) {
			return 'plugins/' . $this->id . '/plugin_info/configuration.php';
		} else {
			return '';
		}
	}

	public static function listPlugin($_activateOnly = false, $_orderByCaterogy = false, $_translate = true, $_nameOnly = false) {
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
					$listPlugin[] = plugin::byId($result['plugin'], $_translate);
				} catch (Exception $e) {
					log::add('plugin', 'error', $e->getMessage(), 'pluginNotFound::' . $result['plugin']);
				} catch (Error $e) {
					log::add('plugin', 'error', $e->getMessage(), 'pluginNotFound::' . $result['plugin']);
				}
			}
		} else {
			$rootPluginPath = dirname(__FILE__) . '/../../plugins';
			foreach (ls($rootPluginPath, '*') as $dirPlugin) {
				if (is_dir($rootPluginPath . '/' . $dirPlugin)) {
					$pathInfoPlugin = $rootPluginPath . '/' . $dirPlugin . '/plugin_info/info.json';
					if (!file_exists($pathInfoPlugin)) {
						$pathInfoPlugin = $rootPluginPath . '/' . $dirPlugin . '/plugin_info/info.xml';
					}
					if (file_exists($pathInfoPlugin)) {
						try {
							$listPlugin[] = plugin::byId($pathInfoPlugin, $_translate);
						} catch (Exception $e) {
							log::add('plugin', 'error', $e->getMessage(), 'pluginNotFound::' . $pathInfoPlugin);
						} catch (Error $e) {
							log::add('plugin', 'error', $e->getMessage(), 'pluginNotFound::' . $pathInfoPlugin);
						}
					}
				}
			}
		}
		if ($_orderByCaterogy) {
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
			if (isset($listPlugin) && is_array($listPlugin) && count($listPlugin) > 0) {
				usort($listPlugin, 'plugin::orderPlugin');
				return $listPlugin;
			} else {
				return array();
			}
		}
	}

	public static function orderPlugin($a, $b) {
		$al = strtolower($a->name);
		$bl = strtolower($b->name);
		if ($al == $bl) {
			return 0;
		}
		return ($al > $bl) ? +1 : -1;
	}

	public static function cron() {
		foreach (self::listPlugin(true) as $plugin) {
			if (method_exists($plugin->getId(), 'cron')) {
				$plugin_id = $plugin->getId();
				try {
					$plugin_id::cron();
				} catch (Exception $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cron du plugin : ', __FILE__) . $e->getMessage());
				} catch (Error $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cron du plugin : ', __FILE__) . $e->getMessage());
				}
			}
		}
	}

	public static function cron5() {
		foreach (self::listPlugin(true) as $plugin) {
			if (method_exists($plugin->getId(), 'cron5')) {
				$plugin_id = $plugin->getId();
				try {
					$plugin_id::cron5();
				} catch (Exception $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cron15 du plugin : ', __FILE__) . $e->getMessage());
				} catch (Error $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cron15 du plugin : ', __FILE__) . $e->getMessage());
				}
			}
		}
	}

	public static function cron15() {
		foreach (self::listPlugin(true) as $plugin) {
			if (method_exists($plugin->getId(), 'cron15')) {
				$plugin_id = $plugin->getId();
				try {
					$plugin_id::cron15();
				} catch (Exception $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cron15 du plugin : ', __FILE__) . $e->getMessage());
				} catch (Error $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cron15 du plugin : ', __FILE__) . $e->getMessage());
				}
			}
		}
	}

	public static function cron30() {
		foreach (self::listPlugin(true) as $plugin) {
			if (method_exists($plugin->getId(), 'cron30')) {
				$plugin_id = $plugin->getId();
				try {
					$plugin_id::cron30();
				} catch (Exception $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cron30 du plugin : ', __FILE__) . $e->getMessage());
				} catch (Error $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cron30 du plugin : ', __FILE__) . $e->getMessage());
				}
			}
		}
	}

	public static function cronDaily() {
		foreach (self::listPlugin(true) as $plugin) {
			if (method_exists($plugin->getId(), 'cronDaily')) {
				$plugin_id = $plugin->getId();
				try {
					$plugin_id::cronDaily();
				} catch (Exception $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cronDaily du plugin : ', __FILE__) . $e->getMessage());
				} catch (Error $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cronDaily du plugin : ', __FILE__) . $e->getMessage());
				}
			}
		}
	}

	public static function cronHourly() {
		foreach (self::listPlugin(true) as $plugin) {
			if (method_exists($plugin->getId(), 'cronHourly')) {
				$plugin_id = $plugin->getId();
				try {
					$plugin_id::cronHourly();
				} catch (Exception $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cronHourly du plugin : ', __FILE__) . $e->getMessage());
				} catch (Error $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction cronHourly du plugin : ', __FILE__) . $e->getMessage());
				}
			}
		}
	}

	public static function start() {
		foreach (self::listPlugin(true) as $plugin) {
			$plugin->deamon_start(false, true);
			if (method_exists($plugin->getId(), 'start')) {
				$plugin_id = $plugin->getId();
				try {
					$plugin_id::start();
				} catch (Exception $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction start du plugin : ', __FILE__) . $e->getMessage());
				} catch (Error $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction start du plugin : ', __FILE__) . $e->getMessage());
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
				} catch (Exception $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction stop du plugin : ', __FILE__) . $e->getMessage());
				} catch (Error $e) {
					log::add($plugin_id, 'error', __('Erreur sur la fonction stop du plugin : ', __FILE__) . $e->getMessage());
				}
			}
		}
	}

	public static function checkDeamon() {
		foreach (self::listPlugin(true) as $plugin) {
			if (config::byKey('deamonAutoMode', $plugin->getId(), 1) != 1) {
				continue;
			}
			$dependancy_info = $plugin->dependancy_info();
			if ($dependancy_info['state'] == 'nok') {
				try {
					$plugin->dependancy_install();
				} catch (Exception $e) {

				}
			} else if ($dependancy_info['state'] == 'in_progress' && $dependancy_info['duration'] > $plugin->getMaxDependancyInstallTime()) {
				if (isset($return['progress_file']) && file_exists($return['progress_file'])) {
					shell_exec('sudo rm ' . $return['progress_file']);
				}
				config::save('deamonAutoMode', 0, $plugin->getId());
				log::add($plugin->getId(), 'error', __('Attention l\'installation des dépendances ont dépassées le temps maximum autorisé : ', __FILE__) . $plugin->getMaxDependancyInstallTime() . 'min');
			}
			try {
				$plugin->deamon_start(false, true);
			} catch (Exception $e) {

			}
		}
	}

	/*     * *********************Méthodes d'instance************************* */

	public function isActive() {
		if (self::$_enable == null) {
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
			log::add('plugin', 'debug', 'Recherche de ' . dirname(__FILE__) . '/../../plugins/' . $this->getId() . '/plugin_info/pre_install.php');
			if (file_exists(dirname(__FILE__) . '/../../plugins/' . $this->getId() . '/plugin_info/pre_install.php')) {
				log::add('plugin', 'debug', 'Fichier d\'installation trouvé pour  : ' . $this->getId());
				require_once dirname(__FILE__) . '/../../plugins/' . $this->getId() . '/plugin_info/pre_install.php';
				ob_start();
				$function = $this->getId() . '_' . $_function;
				if (function_exists($this->getId() . '_' . $_function)) {
					$function();
				}
				return ob_get_clean();
			}
		} else {
			log::add('plugin', 'debug', 'Recherche de ' . dirname(__FILE__) . '/../../plugins/' . $this->getId() . '/plugin_info/install.php');
			if (file_exists(dirname(__FILE__) . '/../../plugins/' . $this->getId() . '/plugin_info/install.php')) {
				log::add('plugin', 'debug', 'Fichier d\'installation trouvé pour  : ' . $this->getId());
				require_once dirname(__FILE__) . '/../../plugins/' . $this->getId() . '/plugin_info/install.php';
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
		if ($this->getHasDependency() != 1 || !method_exists($plugin_id, 'dependancy_info')) {
			return array('state' => 'nok', 'log' => 'nok');
		}
		$cache = cache::byKey('dependancy' . $this->getID());
		if ($_refresh) {
			$cache->remove();
		} else {
			$return = $cache->getValue();
			if (is_array($return) && $return['state'] == 'ok') {
				return $cache->getValue();
			}
		}
		$return = $plugin_id::dependancy_info();
		if (!isset($return['log'])) {
			$return['log'] = '';
		}
		if (isset($return['progress_file'])) {
			$return['progression'] = 0;
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
		if ($return['state'] == 'ok') {
			cache::set('dependancy' . $this->getID(), $return);
		}
		return $return;
	}

	public function dependancy_install($_force = true) {
		$plugin_id = $this->getId();
		if ($this->getHasDependency() != 1 || !method_exists($plugin_id, 'dependancy_install')) {
			return;
		}
		if ((strtotime('now') - 60) <= strtotime(config::byKey('lastDependancyInstallTime', $plugin_id))) {
			$cache = cache::byKey('dependancy' . $this->getID());
			$cache->remove();
			throw new Exception(__('Vous devez attendre au moins 60s entre 2 lancements d\'installation de dépendances', __FILE__));
		}
		$dependancy_info = $this->dependancy_info(true);
		if ($dependancy_info['state'] == 'in_progress') {
			throw new Exception(__('Les dépendances sont déja en cours d\'installation', __FILE__));
		}
		foreach (self::listPlugin(true) as $plugin) {
			if ($plugin->getId() == $this->getId()) {
				continue;
			}
			$dependancy_info = $plugin->dependancy_info();
			if ($dependancy_info['state'] == 'in_progress') {
				throw new Exception(__('Les dépendances d\'un autre plugin sont déjà en cours, veuillez attendre qu\'elles soient finies : ', __FILE__) . $plugin->getId());
			}
		}
		message::add($plugin_id, __('Attention, installation des dépendances lancée', __FILE__));
		$this->deamon_stop();
		config::save('lastDependancyInstallTime', date('Y-m-d H:i:s'), $plugin_id);
		$plugin_id::dependancy_install();
		sleep(1);
		$cache = cache::byKey('dependancy' . $this->getID());
		$cache->remove();
		return;
	}

	public function deamon_changeAutoMode($_mode) {
		config::save('deamonAutoMode', $_mode, $this->getId());
		$plugin_id = $this->getId();
		if (method_exists($plugin_id, 'deamon_changeAutoMode')) {
			$plugin_id::deamon_changeAutoMode($_mode);
		}
	}

	public function deamon_info() {
		$return = array();
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
		$plugin_id = $this->getId();
		if ($_forceRestart) {
			$this->deamon_stop();
		}
		try {
			if ($this->getHasOwnDeamon() == 1 && method_exists($plugin_id, 'deamon_info')) {
				$deamon_info = $this->deamon_info();
				if ($_auto && $deamon_info['auto'] == 0) {
					return;
				}
				if ($deamon_info['launchable'] == 'ok' && $deamon_info['state'] == 'nok' && method_exists($plugin_id, 'deamon_start')) {
					$inprogress = cache::bykey('deamonStart' . $this->getId() . 'inprogress');
					$info = $inprogress->getValue(array('state' => 0, 'datetime' => strtotime('now')));
					if ($info['state'] == 1 && (strtotime('now') - 45) <= $info['datetime']) {
						throw new Exception(__('Vous devez attendre au moins 45s entre 2 lancements du démon', __FILE__));
						return;
					}
					cache::set('deamonStart' . $this->getId() . 'inprogress', array('state' => 1, 'datetime' => strtotime('now')));
					config::save('lastDeamonLaunchTime', date('Y-m-d H:i:s'), $plugin_id);
					$plugin_id::deamon_start();
					cache::set('deamonStart' . $this->getId() . 'inprogress', array('state' => 0));
				}
			}
		} catch (Exception $e) {
			log::add($plugin_id, 'error', __('Erreur sur la fonction deamon_start du plugin : ', __FILE__) . $e->getMessage());
		} catch (Error $e) {
			log::add($plugin_id, 'error', __('Erreur sur la fonction deamon_start du plugin : ', __FILE__) . $e->getMessage());
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
		} catch (Exception $e) {
			log::add($plugin_id, 'error', __('Erreur sur la fonction deamon_stop du plugin : ', __FILE__) . $e->getMessage());
		} catch (Error $e) {
			log::add($plugin_id, 'error', __('Erreur sur la fonction deamon_stop du plugin : ', __FILE__) . $e->getMessage());
		}
	}

	public function setIsEnable($_state) {
		if (version_compare(jeedom::version(), $this->getRequire()) == -1 && $_state == 1) {
			throw new Exception(__('Votre version de jeedom n\'est pas assez récente pour activer ce plugin', __FILE__));
		}
		$alreadyActive = config::byKey('active', $this->getId(), 0);
		if ($_state == 1) {
			if (config::byKey('jeeNetwork::mode') != 'master' && $this->getAllowRemote() != 1) {
				throw new Exception(__('Vous ne pouvez pas activer ce plugin sur un Jeedom configuré en esclave', __FILE__));
			}
			config::save('active', $_state, $this->getId());
		}
		$deamonAutoState = config::byKey('deamonAutoMode', $this->getId(), 1);
		config::save('deamonAutoMode', 0, $this->getId());
		if ($_state == 0) {
			$eqLogics = eqLogic::byType($this->getId());
			if (is_array($eqLogics)) {
				foreach ($eqLogics as $eqLogic) {
					try {
						$eqLogic->setConfiguration('previousIsEnable', $eqLogic->getIsEnable());
						$eqLogic->setConfiguration('previousIsVisible', $eqLogic->getIsVisible());
						$eqLogic->setIsEnable(0);
						$eqLogic->setIsVisible(0);
						$eqLogic->save();
					} catch (Exception $e) {

					} catch (Error $e) {

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
			foreach (eqLogic::byType($this->getId()) as $eqLogic) {
				try {
					$eqLogic->setIsEnable($eqLogic->getConfiguration('previousIsEnable', 1));
					$eqLogic->setIsVisible($eqLogic->getConfiguration('previousIsVisible', 1));
					$eqLogic->save();
				} catch (Exception $e) {

				} catch (Error $e) {

				}
			}
		}
		try {
			if ($_state == 1) {
				log::add($this->getId(), 'info', 'Début d\'activation du plugin');
				$this->deamon_stop();

				$deamon_info = $this->deamon_info();
				sleep(1);
				log::add($this->getId(), 'info', 'Info sur le démon : ' . print_r($deamon_info, true));
				if ($deamon_info['state'] == 'ok') {
					$this->deamon_stop();
				}
				if ($alreadyActive == 1) {
					$out = $this->callInstallFunction('update');
				} else {
					$out = $this->callInstallFunction('install');
				}
				$this->dependancy_info(true);
			} else {
				$this->deamon_stop();
				if ($alreadyActive == 1) {
					$out = $this->callInstallFunction('remove');
				}
			}
			if (isset($out) && trim($out) != '') {
				log::add($this->getId(), 'info', "Installation/remove/update result : " . $out);
			}
		} catch (Exception $e) {
			config::save('active', $alreadyActive, $this->getId());
			log::add('plugin', 'error', $e->getMessage());
			throw $e;
		} catch (Error $e) {
			config::save('active', $alreadyActive, $this->getId());
			log::add('plugin', 'error', $e->getMessage());
			throw $e;
		}
		if ($_state == 0) {
			config::save('active', $_state, $this->getId());
		}
		if ($deamonAutoState) {
			config::save('deamonAutoMode', 1, $this->getId());
		}
		if ($alreadyActive == 0 && $_state == 1) {
			config::save('log::level::' . $this->getId(), '{"100":"0","200":"0","300":"0","400":"0","1000":"0","default":"1"}');
		}
		return true;
	}

	public function launch($_function, $_callInstallFunction = false) {
		if ($_function == '') {
			throw new Exception('La fonction à lancer ne peut être vide');
		}
		if (!$_callInstallFunction && (!class_exists($this->getId()) || !method_exists($this->getId(), $_function))) {
			throw new Exception('Il n\'existe aucune méthode : ' . $this->getId() . '::' . $_function . '()');
		}
		$cmd = dirname(__FILE__) . '/../../core/php/jeePlugin.php ';
		$cmd .= ' plugin_id=' . $this->getId();
		$cmd .= ' function=' . $_function;
		$cmd .= ' callInstallFunction=' . $_callInstallFunction;
		if (jeedom::checkOngoingThread($cmd) > 0) {
			return true;
		}
		log::add($this->getId(), 'debug', __('Lancement de : ', __FILE__) . $cmd);
		if ($_callInstallFunction) {
			return system::php($cmd . ' >> /dev/null 2>&1');
		} else {
			system::php($cmd . ' >> /dev/null 2>&1 &');
		}
		return true;
	}

	public function getTranslation($_language) {
		$dir = dirname(__FILE__) . '/../../plugins/' . $this->getId() . '/core/i18n';
		if (!file_exists($dir)) {
			@mkdir($dir, 0775, true);
		}
		if (!file_exists($dir)) {
			return array();
		}
		if (file_exists($dir . '/' . $_language . '.json')) {
			$return = file_get_contents($dir . '/' . $_language . '.json');

			if (is_json($return)) {
				return json_decode($return, true);
			} else {
				return array();
			}
		}
		return array();
	}

	public function saveTranslation($_language, $_translation) {
		$dir = dirname(__FILE__) . '/../../plugins/' . $this->getId() . '/core/i18n';
		if (!file_exists($dir)) {
			mkdir($dir, 0775, true);
		}
		file_put_contents($dir . '/' . $_language . '.json', json_encode($_translation, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
	}

	public function getUpdate() {
		return update::byTypeAndLogicalId('plugin', $this->getId());
	}

	public function getPathImgIcon() {
		if (file_exists(dirname(__FILE__) . '/../../plugins/' . $this->getId() . '/plugin_info/' . $this->getId() . '_icon.png')) {
			return 'plugins/' . $this->getId() . '/plugin_info/' . $this->getId() . '_icon.png';
		}
		if (file_exists(dirname(__FILE__) . '/../../plugins/' . $this->getId() . '/doc/images/' . $this->getId() . '_icon.png')) {
			return 'plugins/' . $this->getId() . '/doc/images/' . $this->getId() . '_icon.png';
		}
		if (file_exists(dirname(__FILE__) . '/../../plugins/' . $this->getId() . '/plugin_info/' . strtolower($this->getId()) . '_icon.png')) {
			return 'plugins/' . $this->getId() . '/plugin_info/' . strtolower($this->getId()) . '_icon.png';
		}
		if (file_exists(dirname(__FILE__) . '/../../plugins/' . $this->getId() . '/doc/images/' . strtolower($this->getId()) . '_icon.png')) {
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

	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getDescription() {
		return nl2br($this->description);
	}

	public function getInfo($_name = '', $_default = '') {
		if ($_name == 'doc' && file_exists(dirname(__FILE__) . '/../../plugins/' . $this->getId() . '/doc/' . config::byKey('language', 'core', 'fr_FR') . '/index.html')) {
			return 'plugins/' . $this->getId() . '/doc/' . config::byKey('language', 'core', 'fr_FR') . '/index.html';
		}
		if (count($this->info) == 0) {
			$update = update::byLogicalId($this->id);
			if (is_object($update)) {
				$this->info = $update->getInfo();
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

	public function getRequire() {
		return $this->require;
	}

	public function getCategory() {
		return $this->category;
	}

	public function getLicence() {
		return $this->licence;
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
	}

	public function getMobile() {
		return $this->mobile;
	}

	public function setMobile($mobile) {
		$this->mobile = $mobile;
	}

	public function getAllowRemote() {
		return $this->allowRemote;
	}

	public function setAllowRemote($allowRemote) {
		$this->allowRemote = $allowRemote;
	}

	public function getEventjs() {
		return $this->eventjs;
	}

	public function setEventjs($eventjs) {
		$this->eventjs = $eventjs;
	}

	public function getHasDependency() {
		return $this->hasDependency;
	}

	public function setHasDependency($hasDependency) {
		$this->hasDependency = $hasDependency;
	}

	public function getHasOwnDeamon() {
		return $this->hasOwnDeamon;
	}

	public function setHasOwnDeamony($hasOwnDeamon) {
		$this->hasOwnDeamon = $hasOwnDeamon;
	}

	public function getMaxDependancyInstallTime() {
		return $this->maxDependancyInstallTime;
	}

	public function setMaxDependancyInstallTime($maxDependancyInstallTime) {
		$this->maxDependancyInstallTime = $maxDependancyInstallTime;
	}
	
	public function getIssue() {
		return $this->issue;
	}

	public function setIssue($issue) {
		$this->issue = $issue;
		return $this;
	}

}

?>
