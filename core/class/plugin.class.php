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
	private $icon;
	private $index;
	private $display;
	private $mobile;
	private $allowRemote;
	private $eventjs;
	private $hasDependency;
	private $hasOwnDeamon;
	private $include = array();
	private static $_cache = array();

	/*     * ***********************Méthodes statiques*************************** */

	public static function byId($_id, $_translate = true) {
		if (is_string($_id) && isset(self::$_cache[$_id])) {
			return self::$_cache[$_id];
		}
		if (!file_exists($_id) || strpos($_id, '.xml') === false) {
			$_id = self::getPathById($_id);
			if (isset(self::$_cache[$_id])) {
				return self::$_cache[$_id];
			}
			if (!file_exists($_id)) {
				throw new Exception('Plugin introuvable : ' . $_id);
			}
		}
		libxml_use_internal_errors(true);
		$_id = str_replace('//', '/', $_id);
		$plugin_xml = @simplexml_load_file($_id);
		if (!is_object($plugin_xml)) {
			throw new Exception('Plugin introuvable (xml invalide) : ' . $_id . '. Description : ' . print_r(libxml_get_errors(), true));
		}
		$plugin = new plugin();
		$plugin->id = (string) $plugin_xml->id;
		$plugin->name = (string) $plugin_xml->name;
		$plugin->description = (string) $plugin_xml->description;
		$plugin->icon = (string) $plugin_xml->icon;
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

		if ($_translate) {
			$plugin->description = __($plugin->description, $_id);
			$plugin->installation = __($plugin->installation, $_id);
		}

		self::$_cache[$_id] = $plugin;
		self::$_cache[$plugin->id] = $plugin;
		return $plugin;
	}

	public static function getPathById($_id) {
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
					$pathInfoPlugin = $rootPluginPath . '/' . $dirPlugin . '/plugin_info/info.xml';
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
			$plugin->deamon_start(false, false, true);
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
			$plugin->deamon_start(false, false, true);
		}
	}

	/*     * *********************Méthodes d'instance************************* */

	public function isActive() {
		return config::byKey('active', $this->id);
	}

	public function callInstallFunction($_function) {
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

	public function dependancy_info() {
		$plugin_id = $this->getId();
		if ($this->getHasDependency() != 1 || !method_exists($plugin_id, 'dependancy_info')) {
			return array('state' => 'nok', 'log' => 'nok');
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
		$return['last_launch'] = config::byKey('lastDependancyInstallTime', $this->getId(), __('Inconnue', __FILE__));
		return $return;
	}

	public function dependancy_install() {
		$plugin_id = $this->getId();
		if ($this->getHasDependency() != 1 || !method_exists($plugin_id, 'dependancy_install')) {
			return;
		}
		message::add($plugin_id, __('Attention lancement de l\'installation des dépendances', __FILE__));
		$this->deamon_stop();
		config::save('lastDependancyInstallTime', date('Y-m-d H:i:s'), $plugin_id);
		$plugin_id::dependancy_install();
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
		$return['debug_mode'] = config::byKey('deamonDebugMode', $this->getId(), false);
		return $return;
	}

	public function deamon_start($_debug = false, $_forceRestart = false, $_auto = false) {
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
					config::save('lastDeamonLaunchTime', date('Y-m-d H:i:s'), $plugin_id);
					config::save('deamonDebugMode', $_debug, $plugin_id);
					$plugin_id::deamon_start($_debug);
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
			throw new Exception('Votre version de jeedom n\'est pas assez récente pour activer ce plugin');
		}
		$alreadyActive = config::byKey('active', $this->getId(), 0);
		if ($_state == 1) {
			if (config::byKey('jeeNetwork::mode') != 'master' && $this->getAllowRemote() != 1) {
				throw new Exception('Vous ne pouvez pas activer ce plugin sur un Jeedom configuré en esclave');
			}
			config::save('active', $_state, $this->getId());
		}
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
		}
		if ($alreadyActive == 0 && $_state == 1) {
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
				$dependancy_info = $this->dependancy_info();
				log::add($this->getId(), 'info', 'Info sur les dépendances : ' . print_r($dependancy_info, true));
				if ($dependancy_info['state'] == 'nok') {
					$this->dependancy_install();
				}
				if ($alreadyActive == 1) {
					$out = $this->callInstallFunction('update');
				} else {
					$out = $this->callInstallFunction('install');
				}
				$this->deamon_start(false, false, true);
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
		return true;
	}

	public function status() {
		return market::getInfo(array('logicalId' => $this->getId(), 'type' => 'plugin'));
	}

	public function launch($_function) {
		if ($_function == '') {
			throw new Exception('La fonction à lancer ne peut être vide');
		}
		if (!class_exists($this->getId()) || !method_exists($this->getId(), $_function)) {
			throw new Exception('Il n\'existe aucune méthode : ' . $this->getId() . '::' . $_function . '()');
		}
		$cmd = 'php ' . dirname(__FILE__) . '/../../core/php/jeePlugin.php ';
		$cmd .= ' plugin_id=' . $this->getId();
		$cmd .= ' function=' . $_function;
		if (jeedom::checkOngoingThread($cmd) > 0) {
			return true;
		}
		exec($cmd . ' >> /dev/null 2>&1 &');
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
		return 'plugins/' . $this->getId() . '/doc/images/' . $this->getId() . '_icon.png';
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

	public function getIcon() {
		return $this->icon;
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

}

?>
