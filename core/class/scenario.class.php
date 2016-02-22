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

class scenario {
	/*     * *************************Attributs****************************** */

	private $id;
	private $name;
	private $isActive = 1;
	private $group = '';
	private $state = 'stop';
	private $lastLaunch = null;
	private $mode;
	private $schedule;
	private $pid;
	private $scenarioElement;
	private $trigger;
	private $_log;
	private $timeout = 0;
	private $object_id = null;
	private $isVisible = 1;
	private $display;
	private $description;
	private $configuration;
	private $type;
	private static $_templateArray;
	private $_elements = array();
	private $_changeState = false;
	private $_realTrigger = '';

	/*     * ***********************Méthodes statiques*************************** */

	/**
	 * Renvoie un objet scenario
	 * @param int  $_id id du scenario voulu
	 * @return scenario object scenario
	 */
	public static function byId($_id) {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM scenario
		WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	/**
	 * Renvoie tous les objets scenario
	 * @return [] scenario object scenario
	 */
	public static function all($_group = '', $_type = null) {
		$values = array();
		if ($_group === '') {
			$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
			FROM scenario s
			INNER JOIN object ob ON s.object_id=ob.id';
			if ($_type != null) {
				$values['type'] = $_type;
				$sql .= ' WHERE `type`=:type';
			}
			$sql .= ' ORDER BY ob.name,s.group, s.name';
			$result1 = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
			if (!is_array($result1)) {
				$result1 = array();
			}
			$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
			FROM scenario s
			WHERE s.object_id IS NULL';
			if ($_type != null) {
				$values['type'] = $_type;
				$sql .= ' AND `type`=:type';
			}
			$sql .= ' ORDER BY s.group, s.name';
			$result2 = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
			return array_merge($result1, $result2);
		} else if ($_group === null) {
			$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
			FROM scenario s
			INNER JOIN object ob ON s.object_id=ob.id
			WHERE (`group` IS NULL OR `group` = "")';
			if ($_type != null) {
				$values['type'] = $_type;
				$sql .= ' AND `type`=:type';
			}
			$sql .= ' ORDER BY s.group, s.name';
			$result1 = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
			if (!is_array($result1)) {
				$result1 = array();
			}
			$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
			FROM scenario s
			WHERE (`group` IS NULL OR `group` = "")
			AND s.object_id IS NULL';
			if ($_type != null) {
				$values['type'] = $_type;
				$sql .= ' AND `type`=:type';
			}
			$sql .= ' ORDER BY  s.name';
			$result2 = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
			return array_merge($result1, $result2);
		} else {
			$values = array(
				'group' => $_group,
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
			FROM scenario s
			INNER JOIN object ob ON s.object_id=ob.id
			WHERE `group`=:group';
			if ($_type != null) {
				$values['type'] = $_type;
				$sql .= ' AND `type`=:type';
			}
			$sql .= ' ORDER BY ob.name,s.group, s.name';
			$result1 = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
			$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
			FROM scenario s
			WHERE `group`=:group
			AND s.object_id IS NULL';
			if ($_type != null) {
				$values['type'] = $_type;
				$sql .= ' AND `type`=:type';
			}
			$sql .= ' ORDER BY s.group, s.name';
			$result2 = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
			return array_merge($result1, $result2);
		}
	}

	public static function schedule() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM scenario
		WHERE `mode` != "provoke"
		AND isActive=1
		AND state!="in progress"';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function listGroup($_group = null) {
		$values = array();
		$sql = 'SELECT DISTINCT(`group`)
		FROM scenario';
		if ($_group != null) {
			$values['group'] = '%' . $_group . '%';
			$sql .= ' WHERE `group` LIKE :group';
		}
		$sql .= ' ORDER BY `group`';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
	}

	public static function byTrigger($_cmd_id) {
		$values = array(
			'cmd_id' => '%#' . $_cmd_id . '#%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM scenario
		WHERE mode != "schedule"
		AND `trigger` LIKE :cmd_id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byElement($_element_id) {
		$values = array(
			'element_id' => '%' . $_element_id . '%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM scenario
		WHERE `scenarioElement` LIKE :element_id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byObjectId($_object_id, $_onlyEnable = true, $_onlyVisible = false) {
		$values = array();
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM scenario';
		if ($_object_id == null) {
			$sql .= ' WHERE object_id IS NULL';
		} else {
			$values['object_id'] = $_object_id;
			$sql .= ' WHERE object_id=:object_id';
		}
		if ($_onlyEnable) {
			$sql .= ' AND isActive = 1';
		}
		if ($_onlyVisible) {
			$sql .= ' AND isVisible = 1';
		}
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function check($_event = null) {
		$message = '';
		if ($_event != null) {
			if (is_object($_event)) {
				$scenarios = self::byTrigger($_event->getId());
				$trigger = '#' . $_event->getId() . '#';
				$message = __('Scénario exécuté automatiquement sur événement venant de : ', __FILE__) . $_event->getHumanName();
			} else {
				$scenarios = self::byTrigger($_event);
				$trigger = $_event;
				$message = __('Scénario exécuté sur événement : #', __FILE__) . $_event . '#';
			}
		} else {
			$message = __('Scénario exécuté automatiquement sur programmation', __FILE__);
			$scenarios = scenario::all();
			$trigger = '#schedule#';
			if (!jeedom::isDateOk()) {
				return;
			}
			foreach ($scenarios as $key => &$scenario) {
				if ($scenario->getIsActive() == 1 && $scenario->getState() != 'in progress' && ($scenario->getMode() == 'schedule' || $scenario->getMode() == 'all')) {
					if (!$scenario->isDue()) {
						unset($scenarios[$key]);
					}
				} else {
					unset($scenarios[$key]);
				}
			}
		}
		if (count($scenarios) == 0) {
			return true;
		}
		foreach ($scenarios as $scenario_) {
			$scenario_->launch(false, $trigger, $message);
		}
		return true;
	}

	public static function doIn($_options) {
		$scenario = self::byId($_options['scenario_id']);
		$scenarioElement = scenarioElement::byId($_options['scenarioElement_id']);
		$scenario->setLog(__('************Lancement sous tâche**************', __FILE__));
		if (!is_object($scenarioElement) || !is_object($scenario)) {
			return;
		}
		if (is_numeric($_options['second']) && $_options['second'] > 0) {
			sleep($_options['second']);
		}
		$scenarioElement->getSubElement('do')->execute($scenario);
		$scenario->setLog(__('************FIN sous tâche**************', __FILE__));
		$scenario->persistLog();
	}

	public static function cleanTable() {
		$ids = array(
			'element' => array(),
			'subelement' => array(),
			'expression' => array(),
		);
		foreach (scenario::all() as $scenario) {
			foreach ($scenario->getElement() as $element) {
				$result = $element->getAllId();
				$ids['element'] = array_merge($ids['element'], $result['element']);
				$ids['subelement'] = array_merge($ids['subelement'], $result['subelement']);
				$ids['expression'] = array_merge($ids['expression'], $result['expression']);
			}
		}

		$sql = 'DELETE FROM scenarioExpression WHERE id NOT IN (-1';
		foreach ($ids['expression'] as $expression_id) {
			$sql .= ',' . $expression_id;
		}
		$sql .= ')';
		DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);

		$sql = 'DELETE FROM scenarioSubElement WHERE id NOT IN (-1';
		foreach ($ids['subelement'] as $subelement_id) {
			$sql .= ',' . $subelement_id;
		}
		$sql .= ')';
		DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);

		$sql = 'DELETE FROM scenarioElement WHERE id NOT IN (-1';
		foreach ($ids['element'] as $element_id) {
			$sql .= ',' . $element_id;
		}
		$sql .= ')';
		DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
	}

	public static function consystencyCheck() {
		foreach (self::all() as $scenario) {
			if ($scenario->getIsActive() != 1) {
				continue;
			}
			if ($scenario->getMode() == 'provoke' || $scenario->getMode() == 'all') {
				$trigger_list = '';
				if (is_array($scenario->getTrigger())) {
					foreach ($scenario->getTrigger() as $trigger) {
						$trigger_list .= cmd::cmdToHumanReadable($trigger);
					}
				} else {
					$trigger_list = cmd::cmdToHumanReadable($scenario->getTrigger());
				}
				preg_match_all("/#([0-9]*)#/", $trigger_list, $matches);
				foreach ($matches[1] as $cmd_id) {
					if (is_numeric($cmd_id)) {
						log::add('scenario', 'error', __('Un déclencheur du scénario : ', __FILE__) . $scenario->getHumanName() . __(' est introuvable', __FILE__));
					}
				}
			}

			$expression_list = '';
			foreach ($scenario->getElement() as $element) {
				foreach ($element->getSubElement() as $subElement) {
					foreach ($subElement->getExpression() as $expression) {
						$expression_list .= cmd::cmdToHumanReadable($expression->getExpression()) . ' _ ';
						if (is_array($expression->getOptions())) {
							foreach ($expression->getOptions() as $key => $value) {
								$expression_list .= cmd::cmdToHumanReadable($value) . ' _ ';
							}
						}
					}
				}
			}
			preg_match_all("/#([0-9]*)#/", $expression_list, $matches);
			foreach ($matches[1] as $cmd_id) {
				if (is_numeric($cmd_id)) {
					log::add('scenario', 'error', __('Une commande du scénario : ', __FILE__) . $scenario->getHumanName() . __(' est introuvable', __FILE__));
				}
			}
		}
	}

	public static function byObjectNameGroupNameScenarioName($_object_name, $_group_name, $_scenario_name) {
		$values = array(
			'scenario_name' => html_entity_decode($_scenario_name),
		);

		if ($_object_name == __('Aucun', __FILE__)) {
			if ($_group_name == __('Aucun', __FILE__)) {
				$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
			FROM scenario s
			WHERE s.name=:scenario_name
			AND (`group` IS NULL OR `group`=""  OR `group`="Aucun" OR `group`="None")
			AND s.object_id IS NULL';
			} else {
				$values['group_name'] = $_group_name;
				$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
			FROM scenario s
			WHERE s.name=:scenario_name
			AND s.object_id IS NULL
			AND `group`=:group_name';
			}
		} else {
			$values['object_name'] = $_object_name;
			if ($_group_name == __('Aucun', __FILE__)) {
				$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
			FROM scenario s
			INNER JOIN object ob ON s.object_id=ob.id
			WHERE s.name=:scenario_name
			AND ob.name=:object_name
			AND (`group` IS NULL OR `group`=""  OR `group`="Aucun" OR `group`="None")';
			} else {
				$values['group_name'] = $_group_name;
				$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
			FROM scenario s
			INNER JOIN object ob ON s.object_id=ob.id
			WHERE s.name=:scenario_name
			AND ob.name=:object_name
			AND `group`=:group_name';
			}
		}
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function toHumanReadable($_input) {
		if (is_object($_input)) {
			$reflections = array();
			$uuid = spl_object_hash($_input);
			if (!isset($reflections[$uuid])) {
				$reflections[$uuid] = new ReflectionClass($_input);
			}
			$reflection = $reflections[$uuid];
			$properties = $reflection->getProperties();
			foreach ($properties as $property) {
				$property->setAccessible(true);
				$value = $property->getValue($_input);
				$property->setValue($_input, self::toHumanReadable($value));
				$property->setAccessible(false);
			}
			return $_input;
		}
		if (is_array($_input)) {
			foreach ($_input as $key => $value) {
				$_input[$key] = self::toHumanReadable($value);
			}
			return $_input;
		}
		$text = $_input;
		preg_match_all("/#scenario([0-9]*)#/", $text, $matches);
		foreach ($matches[1] as $scenario_id) {
			if (is_numeric($scenario_id)) {
				$scenario = self::byId($scenario_id);
				if (is_object($scenario)) {
					$text = str_replace('#scenario' . $scenario_id . '#', '#' . $scenario->getHumanName(true) . '#', $text);
				}
			}
		}
		return $text;
	}

	public static function fromHumanReadable($_input) {
		$isJson = false;
		if (is_json($_input)) {
			$isJson = true;
			$_input = json_decode($_input, true);
		}
		if (is_object($_input)) {
			$reflections = array();
			$uuid = spl_object_hash($_input);
			if (!isset($reflections[$uuid])) {
				$reflections[$uuid] = new ReflectionClass($_input);
			}
			$reflection = $reflections[$uuid];
			$properties = $reflection->getProperties();
			foreach ($properties as $property) {
				$property->setAccessible(true);
				$value = $property->getValue($_input);
				$property->setValue($_input, self::fromHumanReadable($value));
				$property->setAccessible(false);
			}
			return $_input;
		}
		if (is_array($_input)) {
			foreach ($_input as $key => $value) {
				$_input[$key] = self::fromHumanReadable($value);
			}
			if ($isJson) {
				return json_encode($_input, JSON_UNESCAPED_UNICODE);
			}
			return $_input;
		}
		$text = $_input;

		preg_match_all("/#\[(.*?)\]\[(.*?)\]\[(.*?)\]#/", $text, $matches);
		if (count($matches) == 4) {
			for ($i = 0; $i < count($matches[0]); $i++) {
				if (isset($matches[1][$i]) && isset($matches[2][$i]) && isset($matches[3][$i])) {
					$scenario = self::byObjectNameGroupNameScenarioName($matches[1][$i], $matches[2][$i], $matches[3][$i]);
					if (is_object($scenario)) {
						$text = str_replace($matches[0][$i], '#scenario' . $scenario->getId() . '#', $text);
					}
				}
			}
		}

		return $text;
	}

	public static function byUsedCommand($_cmd_id, $_variable = false) {
		$scenarios = null;
		if ($_variable) {
			$return = array();
			$expressions = array_merge(scenarioExpression::searchExpression('variable(' . $_cmd_id . ')'), scenarioExpression::searchExpression('variable', $_cmd_id, true));
		} else {
			$return = self::byTrigger($_cmd_id);
			$expressions = scenarioExpression::searchExpression('#' . $_cmd_id . '#', '#' . $_cmd_id . '#', false);
		}
		if (is_array($expressions)) {
			foreach ($expressions as $expression) {
				$scenarios[] = $expression->getSubElement()->getElement()->getScenario();
			}
		}
		if (is_array($scenarios)) {
			foreach ($scenarios as $scenario) {
				if (is_object($scenario)) {
					$find = false;
					foreach ($return as $existScenario) {
						if ($scenario->getId() == $existScenario->getId()) {
							$find = true;
							break;
						}
					}
					if (!$find) {
						$return[] = $scenario;
					}
				}
			}
		}
		return $return;
	}

	public static function getTemplate($_template = '') {
		$path = dirname(__FILE__) . '/../config/scenario';
		if (isset($_template) && $_template != '') {

		}
		return ls($path, '*.json', false, array('files', 'quiet'));
	}

/*     * *************************MARKET**************************************** */

	public static function shareOnMarket(&$market) {
		$moduleFile = dirname(__FILE__) . '/../config/scenario/' . $market->getLogicalId() . '.json';
		if (!file_exists($moduleFile)) {
			throw new Exception('Impossible de trouver le fichier de configuration ' . $moduleFile);
		}
		$tmp = dirname(__FILE__) . '/../../tmp/' . $market->getLogicalId() . '.zip';
		if (file_exists($tmp)) {
			if (!unlink($tmp)) {
				throw new Exception(__('Impossible de supprimer : ', __FILE__) . $tmp . __('. Vérifiez les droits', __FILE__));
			}
		}
		if (!create_zip($moduleFile, $tmp)) {
			throw new Exception(__('Echec de création du zip. Répertoire source : ', __FILE__) . $moduleFile . __(' / Répertoire cible : ', __FILE__) . $tmp);
		}
		return $tmp;
	}

	public static function getFromMarket(&$market, $_path) {
		$cibDir = dirname(__FILE__) . '/../config/scenario/';
		if (!file_exists($cibDir)) {
			mkdir($cibDir);
		}
		$zip = new ZipArchive;
		if ($zip->open($_path) === TRUE) {
			$zip->extractTo($cibDir . '/');
			$zip->close();
		} else {
			throw new Exception('Impossible de décompresser l\'archive zip : ' . $_path);
		}
	}

	public static function removeFromMarket(&$market) {

	}

	public static function listMarketObject() {
		return array();
	}

/*     * *********************Méthodes d'instance************************* */

	public function launch($_force = false, $_trigger = '', $_message = '') {
		if (config::byKey('enableScenario') != 1 || $this->getIsActive() != 1) {
			return false;
		}
		$cmd = 'php ' . dirname(__FILE__) . '/../../core/php/jeeScenario.php ';
		$cmd .= ' scenario_id=' . $this->getId();
		$cmd .= ' force=' . $_force;
		$cmd .= ' trigger=' . escapeshellarg($_trigger);
		$cmd .= ' message=' . escapeshellarg($_message);
		$cmd .= ' >> ' . log::getPathToLog('scenario_execution') . ' 2>&1 &';
		exec($cmd);
		return true;
	}

	public function execute($_trigger = '', $_message = '') {
		if ($this->getIsActive() != 1 && $this->getConfiguration('allowMultiInstance', 0) == 0) {
			$this->setLog(__('Impossible d\'exécuter le scénario : ', __FILE__) . $this->getHumanName() . __(' sur : ', __FILE__) . $_message . __(' car il est désactivé', __FILE__));
			$this->persistLog();
			return;
		}
		if ($this->getConfiguration('timeDependency', 0) == 1 && !jeedom::isDateOk()) {
			$this->setLog(__('Lancement du scénario : ', __FILE__) . $this->getHumanName() . __(' annulé car il utilise une condition de type temporelle et que la date système n\'est pas OK', __FILE__));
			$this->persistLog();
			return;
		}
		$cmd = cmd::byId(str_replace('#', '', $_trigger));
		if (is_object($cmd)) {
			log::add('event', 'info', __('Exécution du scénario ', __FILE__) . $this->getHumanName() . __(' déclenché par : ', __FILE__) . $cmd->getHumanName());
		} else {
			log::add('event', 'info', __('Exécution du scénario ', __FILE__) . $this->getHumanName() . __(' déclenché par : ', __FILE__) . $_trigger);
		}
		$this->setLog(__('Début d\'exécution du scénario : ', __FILE__) . $this->getHumanName() . '. ' . $_message);
		$this->setLastLaunch(date('Y-m-d H:i:s'));
		$this->setDisplay('icon', '');
		$this->setState('in progress');
		$this->setPID(getmypid());
		$this->save();
		$this->setRealTrigger($_trigger);
		foreach ($this->getElement() as $element) {
			$element->execute($this);
		}
		$this->setState('stop');
		$this->setPID('');
		$this->setLog(__('Fin correcte du scénario', __FILE__));
		$this->persistLog();
		$scenario = scenario::byId($this->getId());
		if ($scenario->getIsActive() != $this->getIsActive()) {
			$this->setIsActive($scenario->getIsActive());
		}
		$this->save();
		return true;
	}

	public function copy($_name) {
		$scenarioCopy = clone $this;
		$scenarioCopy->setName($_name);
		$scenarioCopy->setId('');
		$scenario_element_list = array();
		foreach ($this->getElement() as $element) {
			$scenario_element_list[] = $element->copy();
		}
		$scenarioCopy->setScenarioElement($scenario_element_list);
		$scenarioCopy->setLog('');
		$scenarioCopy->save();
		return $scenarioCopy;
	}

	public function toHtml($_version) {
		if (!$this->hasRight('r')) {
			return '';
		}
		$mc = cache::byKey('scenarioHtml' . $_version . $this->getId());
		if ($mc->getValue() != '') {
			return $mc->getValue();
		}

		$_version = jeedom::versionAlias($_version);
		$replace = array(
			'#id#' => $this->getId(),
			'#state#' => $this->getState(),
			'#isActive#' => $this->getIsActive(),
			'#name#' => ($this->getDisplay('name') != '') ? $this->getDisplay('name') : $this->getHumanName(),
			'#shortname#' => ($this->getDisplay('name') != '') ? $this->getDisplay('name') : $this->getName(),
			'#treename#' => $this->getHumanName(false, false, false, false, true),
			'#icon#' => $this->getIcon(),
			'#lastLaunch#' => $this->getLastLaunch(),
			'#scenarioLink#' => $this->getLinkToConfiguration(),
		);
		if (!isset(self::$_templateArray)) {
			self::$_templateArray = array();
		}
		if (!isset(self::$_templateArray[$_version])) {
			self::$_templateArray[$_version] = getTemplate('core', $_version, 'scenario');
		}
		$html = template_replace($replace, self::$_templateArray[$_version]);
		cache::set('scenarioHtml' . $_version . $this->getId(), $html, 0);
		return $html;
	}

	public function emptyCacheWidget() {
		$mc = cache::byKey('scenarioHtmldashboard' . $this->getId());
		$mc->remove();
		$mc = cache::byKey('scenarioHtmlmobile' . $this->getId());
		$mc->remove();
		$mc = cache::byKey('scenarioHtmlmview' . $this->getId());
		$mc->remove();
		$mc = cache::byKey('scenarioHtmldview' . $this->getId());
		$mc->remove();
	}

	public function getIcon($_only_class = false) {
		if ($_only_class) {
			if ($this->getIsActive() == 1) {
				switch ($this->getState()) {
					case 'in progress':
						return 'fa fa-spinner fa-spin';
					case 'error':
						return 'fa fa-exclamation-triangle';
					default:
						if (strpos($this->getDisplay('icon'), '<i') === 0) {
							return str_replace(array('<i', 'class=', '"', '/>'), '', $this->getDisplay('icon'));
						}
						return 'fa fa-check';
				}
			} else {
				return 'fa fa-times';
			}
		} else {
			if ($this->getIsActive() == 1) {
				switch ($this->getState()) {
					case 'in progress':
						return '<i class="fa fa-spinner fa-spin"></i>';
					case 'error':
						return '<i class="fa fa-exclamation-triangle"></i>';
					default:
						if (strpos($this->getDisplay('icon'), '<i') === 0) {
							return $this->getDisplay('icon');
						}
						return '<i class="fa fa-check"></i>';
				}
			} else {
				return '<i class="fa fa-times"></i>';
			}
		}
	}

	public function getLinkToConfiguration() {
		return 'index.php?v=d&p=scenario&id=' . $this->getId();
	}

	public function preSave() {
		if ($this->getTimeout() == '' || !is_numeric($this->getTimeout())) {
			$this->setTimeout(0);
		}
		if ($this->getName() == '') {
			throw new Exception('Le nom du scénario ne peut pas être vide.');
		}
		if (($this->getMode() == 'schedule' || $this->getMode() == 'all') && $this->getSchedule() == '') {
			throw new Exception(__('Le scénario est de type programmé mais la programmation est vide', __FILE__));
		}
	}

	public function save() {
		if ($this->getLastLaunch() == '' && ($this->getMode() == 'schedule' || $this->getMode() == 'all')) {
			$calculateScheduleDate = $this->calculateScheduleDate();
			$this->setLastLaunch($calculateScheduleDate['prevDate']);
		}
		if ($this->getId() != '') {
			$this->emptyCacheWidget();
		}
		DB::save($this);
		if ($this->_changeState) {
			event::add('scenario::update', $this->getId());
		}
	}

	public function refresh() {
		DB::refresh($this);
	}

	public function remove() {
		viewData::removeByTypeLinkId('scenario', $this->getId());
		dataStore::removeByTypeLinkId('scenario', $this->getId());
		foreach ($this->getElement() as $element) {
			$element->remove();
		}
		$this->emptyCacheWidget();
		return DB::remove($this);
	}

	public function removeData($_key, $_private = false) {
		if ($_private) {
			$dataStore = dataStore::byTypeLinkIdKey('scenario', $this->getId(), $_key);
		} else {
			$dataStore = dataStore::byTypeLinkIdKey('scenario', -1, $_key);
		}
		if (is_object($dataStore)) {
			return $dataStore->remove();
		}
		return true;
	}

	public function setData($_key, $_value, $_private = false) {
		$dataStore = new dataStore();
		$dataStore->setType('scenario');
		$dataStore->setKey($_key);
		$dataStore->setValue($_value);
		if ($_private) {
			$dataStore->setLink_id($this->getId());
		} else {
			$dataStore->setLink_id(-1);
		}
		$dataStore->save();
		return true;
	}

	public function getData($_key, $_private = false, $_default = '') {
		if ($_private) {
			$dataStore = dataStore::byTypeLinkIdKey('scenario', $this->getId(), $_key);
		} else {
			$dataStore = dataStore::byTypeLinkIdKey('scenario', -1, $_key);
		}
		if (is_object($dataStore)) {
			return $dataStore->getValue($_default);
		}
		return $_default;
	}

	public function calculateScheduleDate() {
		$calculatedDate = array('prevDate' => '', 'nextDate' => '');
		if (is_array($this->getSchedule())) {
			$calculatedDate_tmp = array('prevDate' => '', 'nextDate' => '');
			foreach ($this->getSchedule() as $schedule) {
				try {
					$c = new Cron\CronExpression($schedule, new Cron\FieldFactory);
					$calculatedDate_tmp['prevDate'] = $c->getPreviousRunDate()->format('Y-m-d H:i:s');
					$calculatedDate_tmp['nextDate'] = $c->getNextRunDate()->format('Y-m-d H:i:s');
				} catch (Exception $exc) {
					//echo $exc->getTraceAsString();
				} catch (Error $exc) {
					//echo $exc->getTraceAsString();
				}
				if ($calculatedDate['prevDate'] == '' || strtotime($calculatedDate['prevDate']) < strtotime($calculatedDate_tmp['prevDate'])) {
					$calculatedDate['prevDate'] = $calculatedDate_tmp['prevDate'];
				}
				if ($calculatedDate['nextDate'] == '' || strtotime($calculatedDate['nextDate']) > strtotime($calculatedDate_tmp['nextDate'])) {
					$calculatedDate['nextDate'] = $calculatedDate_tmp['nextDate'];
				}
			}
		} else {
			try {
				$c = new Cron\CronExpression($this->getSchedule(), new Cron\FieldFactory);
				$calculatedDate['prevDate'] = $c->getPreviousRunDate()->format('Y-m-d H:i:s');
				$calculatedDate['nextDate'] = $c->getNextRunDate()->format('Y-m-d H:i:s');
			} catch (Exception $exc) {
				//echo $exc->getTraceAsString();
			} catch (Error $exc) {
				//echo $exc->getTraceAsString();
			}
		}
		return $calculatedDate;
	}

	public function isDue() {
		$last = strtotime($this->getLastLaunch());
		$now = time();
		$now = ($now - $now % 60);
		$last = ($last - $last % 60);
		if ($now == $last) {
			return false;
		}
		if (is_array($this->getSchedule())) {
			foreach ($this->getSchedule() as $schedule) {
				try {
					$c = new Cron\CronExpression($schedule, new Cron\FieldFactory);
					try {
						if ($c->isDue()) {
							return true;
						}
					} catch (Exception $e) {

					} catch (Error $e) {

					}
					try {
						$prev = $c->getPreviousRunDate()->getTimestamp();
					} catch (Exception $e) {
						return false;
					} catch (Error $e) {
						return false;
					}
					$lastCheck = strtotime($this->getLastLaunch());
					$diff = abs((strtotime('now') - $prev) / 60);
					if ($lastCheck <= $prev && $diff <= config::byKey('maxCatchAllow') || config::byKey('maxCatchAllow') == -1) {
						return true;
					}
				} catch (Exception $e) {

				} catch (Error $e) {

				}
			}
		} else {
			try {
				$c = new Cron\CronExpression($this->getSchedule(), new Cron\FieldFactory);
				try {
					if ($c->isDue()) {
						return true;
					}
				} catch (Exception $e) {

				} catch (Error $e) {

				}
				try {
					$prev = $c->getPreviousRunDate()->getTimestamp();
				} catch (Exception $e) {
					return false;
				} catch (Error $e) {
					return false;
				}
				$lastCheck = strtotime($this->getLastLaunch());
				$diff = abs((strtotime('now') - $prev) / 60);
				if ($lastCheck <= $prev && $diff <= config::byKey('maxCatchAllow') || config::byKey('maxCatchAllow') == -1) {
					return true;
				}
			} catch (Exception $exc) {

			} catch (Error $exc) {

			}
		}
		return false;
	}

	public function running() {
		if ($this->getPID() > 0 && posix_getsid($this->getPID()) && (!file_exists('/proc/' . $this->getPID() . '/cmdline') || strpos(file_get_contents('/proc/' . $this->getPID() . '/cmdline'), 'scenario_id=' . $this->getId()) !== false)) {
			return true;
		}
		if (count(system::ps('scenario_id=' . $this->getId() . ' ', array(getmypid()))) > 0) {
			return true;
		}
		return false;
	}

	public function stop() {
		if ($this->running()) {
			if ($this->getPID() > 0) {
				system::kill($this->getPID());
				$retry = 0;
				while ($this->running() && $retry < 10) {
					sleep(1);
					system::kill($this->getPID());
					$retry++;
				}
			}

			if ($this->running()) {
				system::kill("scenario_id=" . $this->getId() . ' ');
				sleep(1);
				if ($this->running()) {
					system::kill("scenario_id=" . $this->getId() . ' ');
					sleep(1);
				}
			}
			if ($this->running()) {
				throw new Exception(__('Impossible d\'arrêter le scénario : ', __FILE__) . $this->getHumanName() . __('. PID : ', __FILE__) . $this->getPID());
			}
		}
		$this->setState('stop');
		$this->save();
		return true;
	}

	public function getElement() {
		if (count($this->_elements) > 0) {
			return $this->_elements;
		}
		$return = array();
		$elements = $this->getScenarioElement();
		if (is_array($elements)) {
			foreach ($this->getScenarioElement() as $element_id) {
				$element = scenarioElement::byId($element_id);
				if (is_object($element)) {
					$return[] = $element;
				}
			}
			$this->_elements = $return;
			return $return;
		}
		if ($elements != '') {
			$element = scenarioElement::byId($element_id);
			if (is_object($element)) {
				$return[] = $element;
				$this->_elements = $return;
				return $return;
			}
		}
		return array();
	}

	public function export($_mode = 'text') {
		if ($_mode == 'text') {
			$return = '';
			$return .= '- Nom du scénario : ' . $this->getName() . "\n";
			if (is_numeric($this->getObject_id())) {
				$return .= '- Objet parent : ' . $this->getObject()->getName() . "\n";
			}
			$return .= '- Mode du scénario : ' . $this->getMode() . "\n";
			$schedules = $this->getSchedule();
			if ($this->getMode() == 'schedule' || $this->getMode() == 'all') {
				if (is_array($schedules)) {
					foreach ($schedules as $schedule) {
						$return .= '    - Programmation : ' . $schedule . "\n";
					}
				} else {
					if ($schedules != '') {
						$return .= '    - Programmation : ' . $schedules . "\n";
					}
				}
			}
			if ($this->getMode() == 'provoke' || $this->getMode() == 'all') {
				$triggers = $this->getTrigger();
				if (is_array($triggers)) {
					foreach ($triggers as $trigger) {
						$return .= '    - Evènement : ' . jeedom::toHumanReadable($trigger) . "\n";
					}
				} else {
					if ($triggers != '') {
						$return .= '    - Evènement : ' . jeedom::toHumanReadable($triggers) . "\n";
					}
				}
			}
			$return .= "\n";
			$return .= $this->getDescription();
			$return .= "\n\n";
			foreach ($this->getElement() as $element) {
				$exports = explode("\n", $element->export());
				foreach ($exports as $export) {
					$return .= "    " . $export . "\n";
				}
			}
		}
		if ($_mode == 'array') {
			$return = utils::o2a($this);

			$return['trigger'] = jeedom::toHumanReadable($return['trigger']);
			$return['elements'] = array();
			foreach ($this->getElement() as $element) {
				$return['elements'][] = $element->getAjaxElement('array');
			}
			if (isset($return['id'])) {
				unset($return['id']);
			}
			if (isset($return['lastLaunch'])) {
				unset($return['lastLaunch']);
			}
			if (isset($return['log'])) {
				unset($return['log']);
			}
			if (isset($return['hlogs'])) {
				unset($return['hlogs']);
			}
			if (isset($return['object_id'])) {
				unset($return['object_id']);
			}
			if (isset($return['pid'])) {
				unset($return['pid']);
			}
			if (isset($return['scenarioElement'])) {
				unset($return['scenarioElement']);
			}
			if (isset($return['_templateArray'])) {
				unset($return['_templateArray']);
			}
			if (isset($return['_templateArray'])) {
				unset($return['_templateArray']);
			}
			if (isset($return['_changeState'])) {
				unset($return['_changeState']);
			}
			if (isset($return['_realTrigger'])) {
				unset($return['_realTrigger']);
			}
			if (isset($return['_templateArray'])) {
				unset($return['_templateArray']);
			}
			if (isset($return['_elements'])) {
				unset($return['_elements']);
			}
		}
		return $return;
	}

	public function getObject() {
		return object::byId($this->object_id);
	}

	public function getHumanName($_complete = false, $_noGroup = false, $_tag = false, $_prettify = false, $_withoutScenarioName = false) {
		$name = '';
		if (is_numeric($this->getObject_id())) {
			$object = $this->getObject();
			if ($_tag) {
				if ($object->getDisplay('tagColor') != '') {
					$name .= '<span class="label" style="text-shadow : none;background-color:' . $object->getDisplay('tagColor') . '">' . $object->getName() . '</span>';
				} else {
					$name .= '<span class="label label-primary" style="text-shadow : none;">' . $object->getName() . '</span>';
				}
			} else {
				$name .= '[' . $object->getName() . ']';
			}
		} else {
			if ($_complete) {
				if ($_tag) {
					$name .= '<span class="label label-default" style="text-shadow : none;">' . __('Aucun', __FILE__) . '</span>';
				} else {
					$name .= '[' . __('Aucun', __FILE__) . ']';
				}
			}
		}
		if (!$_noGroup) {
			if ($this->getGroup() != '') {
				$name .= '[' . $this->getGroup() . ']';
			} else {
				if ($_complete) {
					$name .= '[' . __('Aucun', __FILE__) . ']';
				}
			}
		}
		if ($_prettify) {
			$name .= '<br/><strong>';
		}
		if (!$_withoutScenarioName) {
			if ($_tag) {
				$name .= ' ' . $this->getName();
			} else {
				$name .= '[' . $this->getName() . ']';
			}
		}
		if ($_prettify) {
			$name .= '</strong>';
		}
		return $name;
	}

	public function hasRight($_right) {
		if (config::byKey('rights::enable') != 1) {
			return true;
		}
		if (session_status() != PHP_SESSION_NONE || !isset($_SESSION) || !isset($_SESSION['user'])) {
			return true;
		}
		$_user = $_SESSION['user'];
		if (!is_object($_user)) {
			return false;
		}
		if (!isConnect()) {
			return false;
		}
		if (isConnect('admin')) {
			return true;
		}
		$rights = null;
		if ($_right == 'x') {
			$rights = rights::byuserIdAndEntity($_user->getId(), 'scenario' . $this->getId() . 'action');
		} elseif ($_right == 'w') {
			$rights = rights::byuserIdAndEntity($_user->getId(), 'scenario' . $this->getId() . 'edit');
		} elseif ($_right == 'r') {
			$rights = rights::byuserIdAndEntity($_user->getId(), 'scenario' . $this->getId() . 'view');
		}
		if (!is_object($rights)) {
			return false;
		}
		return $rights->getRight();
	}

	public function persistLog() {
		if ($this->getConfiguration('noLog', 0) == 1) {
			return;
		}
		if (!file_exists(dirname(__FILE__) . '/../../log/scenarioLog')) {
			mkdir(dirname(__FILE__) . '/../../log/scenarioLog');
		}
		$path = dirname(__FILE__) . '/../../log/scenarioLog/scenario' . $this->getId() . '.log';
		$content = '';
		if (file_exists($path)) {
			$content = file_get_contents($path);
		}
		file_put_contents($path, $this->getLog() . "------------------------------------\n" . $content);
	}

/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getState() {
		if ($this->state == 'in progress' && !$this->running()) {
			return 'error';
		}
		return $this->state;
	}

	public function getIsActive() {
		return $this->isActive;
	}

	public function getGroup() {
		return $this->group;
	}

	public function getLastLaunch() {
		return $this->lastLaunch;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setIsActive($isActive) {
		if ($isActive != $this->getIsActive()) {
			$this->_changeState = true;
		}
		$this->isActive = $isActive;
	}

	public function setGroup($group) {
		$this->group = $group;
	}

	public function setState($state) {
		$this->_changeState = true;
		$this->state = $state;
	}

	public function setLastLaunch($lastLaunch) {
		$this->lastLaunch = $lastLaunch;
	}

	public function getType() {
		return $this->type;
	}

	function setType($type) {
		$this->type = $type;
	}

	public function getMode() {
		return $this->mode;
	}

	public function setMode($mode) {
		$this->mode = $mode;
	}

	public function getSchedule() {
		if (is_json($this->schedule)) {
			return json_decode($this->schedule, true);
		}
		return $this->schedule;
	}

	public function setSchedule($schedule) {
		if (is_array($schedule)) {
			$schedule = json_encode($schedule, JSON_UNESCAPED_UNICODE);
		}
		$this->schedule = $schedule;
	}

	public function getPID() {
		return $this->pid;
	}

	public function setPID($pid) {
		$this->pid = $pid;
	}

	public function getScenarioElement() {
		if (is_json($this->scenarioElement)) {
			return json_decode($this->scenarioElement, true);
		}
		return $this->scenarioElement;
	}

	public function setScenarioElement($scenarioElement) {
		if (is_array($scenarioElement)) {
			$scenarioElement = json_encode($scenarioElement, JSON_UNESCAPED_UNICODE);
		}
		$this->scenarioElement = $scenarioElement;
	}

	public function getTrigger() {
		if (is_json($this->trigger)) {
			return json_decode($this->trigger, true);
		}
		return $this->trigger;
	}

	public function setTrigger($trigger) {
		if (is_array($trigger)) {
			$trigger = json_encode($trigger, JSON_UNESCAPED_UNICODE);
		}
		$this->trigger = cmd::humanReadableToCmd($trigger);
	}

	public function getLog() {
		return $this->_log;
	}

	public function setLog($log) {
		$this->_log .= '[' . date('Y-m-d H:i:s') . '][SCENARIO] ' . $log . "\n";
	}

	public function getTimeout($_default = 0) {
		if ($this->timeout == '' || !is_numeric($this->timeout)) {
			return $_default;
		}
		return $this->timeout;
	}

	public function setTimeout($timeout) {
		$this->timeout = $timeout;
	}

	public function getObject_id() {
		return $this->object_id;
	}

	public function getIsVisible() {
		return $this->isVisible;
	}

	public function setObject_id($object_id = null) {
		$this->object_id = (!is_numeric($object_id)) ? null : $object_id;
	}

	public function setIsVisible($isVisible) {
		$this->isVisible = $isVisible;
	}

	public function getDisplay($_key = '', $_default = '') {
		return utils::getJsonAttr($this->display, $_key, $_default);
	}

	public function setDisplay($_key, $_value) {
		$this->display = utils::setJsonAttr($this->display, $_key, $_value);
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	public function getConfiguration($_key = '', $_default = '') {
		return utils::getJsonAttr($this->configuration, $_key, $_default);
	}

	public function setConfiguration($_key, $_value) {
		$this->configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
	}

	public function getRealTrigger() {
		return $this->_realTrigger;
	}

	public function setRealTrigger($_realTrigger) {
		$this->_realTrigger = $_realTrigger;
	}

}

?>
