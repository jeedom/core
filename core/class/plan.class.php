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

class plan {
	/*     * *************************Attributs****************************** */

	private $id;
	private $planHeader_id;
	private $link_type;
	private $link_id;
	private $position;
	private $display;
	private $css;
	private $configuration;
	private $_changed = false;

	/*     * ***********************Methode static*************************** */

    /**
     * @param $_id
     * @return array|null
     * @throws ReflectionException
     */
    public static function byId($_id): ?array
    {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM plan
		WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_planHeader_id
     * @return array|null
     * @throws ReflectionException
     */
    public static function byPlanHeaderId($_planHeader_id): ?array
    {
		$values = array(
			'planHeader_id' => $_planHeader_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM plan
		WHERE planHeader_id=:planHeader_id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_link_type
     * @param $_link_id
     * @return array|null
     * @throws ReflectionException
     */
    public static function byLinkTypeLinkId($_link_type, $_link_id): ?array
    {
		$values = array(
			'link_type' => $_link_type,
			'link_id' => $_link_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM plan
		WHERE link_type=:link_type
		AND link_id=:link_id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_link_type
     * @param $_link_id
     * @param $_planHeader_id
     * @return array|null
     * @throws ReflectionException
     */
    public static function byLinkTypeLinkIdPlanHeaderId($_link_type, $_link_id, $_planHeader_id): ?array
    {
		$values = array(
			'link_type' => $_link_type,
			'link_id' => $_link_id,
			'planHeader_id' => $_planHeader_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM plan
		WHERE link_type=:link_type
		AND link_id=:link_id
		AND planHeader_id=:planHeader_id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_link_type
     * @param $_link_id
     * @param $_planHeader_id
     * @return array|null
     * @throws Exception
     */
    public static function removeByLinkTypeLinkIdPlanHeaderId($_link_type, $_link_id, $_planHeader_id): ?array
    {
		$values = array(
			'link_type' => $_link_type,
			'link_id' => $_link_id,
			'planHeader_id' => $_planHeader_id,
		);
		$sql = 'DELETE FROM plan
		WHERE link_type=:link_type
		AND link_id=:link_id
		AND planHeader_id=:planHeader_id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @return array|null
     * @throws ReflectionException
     */
    public static function all(): ?array
    {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM plan';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_search
     * @return array|null
     * @throws ReflectionException
     */
    public static function searchByDisplay($_search): ?array
    {
		$value = array(
			'search' => '%' . $_search . '%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM plan
		WHERE display LIKE :search';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_search
     * @param string $_not
     * @return array|null
     * @throws ReflectionException
     */
    public static function searchByConfiguration($_search, $_not = ''): ?array
    {
		$value = array(
			'search' => '%' . $_search . '%',
			'not' => $_not,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM plan
		WHERE configuration LIKE :search
		AND link_type !=:not';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	/*     * *********************Methode d'instance************************* */

    /**
     * @throws Exception
     */
    public function preInsert() {
		if ($this->getCss('z-index') == '') {
			$this->setCss('z-index', 1000);
		}
		if (in_array($this->getLink_type(), array('eqLogic', 'cmd', 'scenario'))) {
			self::removeByLinkTypeLinkIdPlanHeaderId($this->getLink_type(), $this->getLink_id(), $this->getPlanHeader_id());
		}
	}

	public function preSave() {
		if ($this->getCss('zoom') != '' && (!is_numeric($this->getCss('zoom')) || $this->getCss('zoom')) < 0.1) {
			$this->setCss('zoom', 1);
		}
		if ($this->getLink_id() == '') {
			$this->setLink_id(rand(0, 99999999) + 9999);
		}
	}

    /**
     * @throws Exception
     */
    public function save() {
		DB::save($this);
	}

    /**
     * @throws Exception
     */
    public function remove() {
		$link_type = $this->getLink_type();
		if ($this->getLink_type() == 'image') {
			$imgPath = $this->getDisplay('path', '');
			if ($imgPath != '') {
				@unlink(str_replace('data/plan/', __DIR__ . '/../../data/plan/', $imgPath));
				@rmdir(__DIR__ . '/../../data/plan/plan_'.$this->getId());
			}
		}
		DB::remove($this);
	}

    /**
     * @return plan
     * @throws Exception
     */
    public function copy(): plan
    {
		$planCopy = clone $this;
		$planCopy->setId('');
		$planCopy->setLink_id('');
		$planCopy->setPosition('top', '');
		$planCopy->setPosition('left', '');
		$planCopy->save();
		return $planCopy;
	}

    /**
     * @return array|mixed|scenario|void|null
     * @throws ReflectionException
     */
    public function getLink() {
		if ($this->getLink_type() == 'eqLogic') {
			$eqLogic = eqLogic::byId($this->getLink_id());
			return $eqLogic;
		} else if ($this->getLink_type() == 'scenario') {
			$scenario = scenario::byId($this->getLink_id());
			return $scenario;
		} else if ($this->getLink_type() == 'cmd') {
			$cmd = cmd::byId($this->getLink_id());
			return $cmd;
		} else if ($this->getLink_type() == 'summary') {
			$object = jeeObject::byId($this->getLink_id());
			return $object;
		}
		return null;
	}

	public function execute() {
		if ($this->getLink_type() != 'zone') {
			return;
		}
		if ($this->getConfiguration('zone_mode', 'simple') == 'simple') {
			$this->doAction('other');
		} else if ($this->getConfiguration('zone_mode', 'simple') == 'binary') {
			$result = jeedom::evaluateExpression($this->getConfiguration('binary_info', 0));
			if ($result) {
				$this->doAction('off');
			} else {
				$this->doAction('on');
			}
		}
	}

    /**
     * @param $_action
     * @throws Exception
     */
    public function doAction($_action) {
		foreach ($this->getConfiguration('action_' . $_action) as $action) {
			try {
				$cmd = cmd::byId(str_replace('#', '', $action['cmd']));
				if (is_object($cmd) && $this->getId() == $cmd->getEqLogic_id()) {
					continue;
				}
				$options = array();
				if (isset($action['options'])) {
					$options = $action['options'];
				}
				scenarioExpression::createAndExec('action', $action['cmd'], $options);
			} catch (Exception $e) {
				log::add('design', 'error', __('Erreur lors de l\'exécution de ', __FILE__) . $action['cmd'] . __('. Détails : ', __FILE__) . $e->getMessage());
			}
		}
	}

    /**
     * @param string $_version
     * @return array
     * @throws ReflectionException
     */
    public function getHtml($_version = 'dashboard'): array
    {
		$linkType = $this->getLink_type();
		if (in_array($linkType, array('eqLogic', 'cmd', 'scenario'))) {
			$link = $this->getLink();
			if (!is_object($link)) {
				return;
			}
			return array(
				'plan' => jeedom::toHumanReadable(utils::o2a($this)),
				'html' => $link->toHtml($_version),
			);
		} else if ($linkType == 'plan') {
			$html = '<span class="cursor plan-link-widget" data-id="'.$this->getId().'" data-link_id="' . $this->getLink_id() . '" data-offsetX="' . $this->getDisplay('offsetX') . '" data-offsetY="' . $this->getDisplay('offsetY') . '">';
			if ($this->getDisplay('color-defaut', 1) == 1) {
				$html .= '<a>';
			} else {
				$html .= '<a style="color:' . $this->getCss('color', '') . '!important;">';
			}
			$html .= $this->getDisplay('icon') . ' ' . $this->getDisplay('name');
			$html .= '</a>';
			$html .= '</span>';
			return array(
				'plan' => jeedom::toHumanReadable(utils::o2a($this)),
				'html' => $html,
			);
		} else if ($linkType == 'view') {
			$link = 'index.php?p=view&view_id=' . $this->getLink_id();
			$html = '<span href="' . $link . '" class="cursor view-link-widget" data-id="'.$this->getId().'" data-link_id="' . $this->getLink_id() . '" >';
			if ($this->getDisplay('color-defaut', 1) == 1) {
				$html .= '<a href="' . $link . '" class="noOnePageLoad">';
			} else {
				$html .= '<a href="' . $link . '" class="noOnePageLoad" style="color:' . $this->getCss('color', '') . '!important;">';
			}
			$html .= $this->getDisplay('icon') . ' ' . $this->getDisplay('name');
			$html .= '</a>';
			$html .= '</span>';
			return array(
				'plan' => jeedom::toHumanReadable(utils::o2a($this)),
				'html' => $html,
			);
		} else if ($linkType == 'graph') {
			$background_color = 'background-color: rgba(var(--panel-bg-color), var(--opacity)) !important;';
			if ($this->getDisplay('transparentBackground', false)) {
				$background_color = '';
			}
			$html = '<div class="graph-widget" data-graph_id="' . $this->getLink_id() . '" style="width:75px; height:75px;' . $background_color . '">';
			$html .= '<span class="graphOptions" style="display:none;">' . json_encode($this->getDisplay('graph', array())) . '</span>';
			$html .= '<div class="graph" id="graph' . $this->getLink_id() . '" style="width:100%; height:100%;"></div>';
			$html .= '</div>';
			return array(
				'plan' => jeedom::toHumanReadable(utils::o2a($this)),
				'html' => $html,
			);
		} else if ($linkType == 'text') {
			$html = '<div class="text-widget" data-text_id="' . $this->getLink_id() . '">';
			if ($this->getDisplay('name') != '' || $this->getDisplay('icon') != '') {
				$html .= $this->getDisplay('icon') . ' ' . $this->getDisplay('text');
			} else {
				$html .= $this->getDisplay('text');
			}
			$html .= '</div>';
			return array(
				'plan' => jeedom::toHumanReadable(utils::o2a($this)),
				'html' => $html,
			);
		} else if ($linkType == 'image') {
			$html = '<div class="image-widget" data-image_id="' . $this->getLink_id() . '" style="min-width:10px;min-height:10px;">';
			if ($this->getConfiguration('display_mode', 'image') == 'image') {
				$html .= '<img style="width:100%;height:100%" src="' . $this->getDisplay('path', 'core/img/no_image.gif') . '"/>';
			} else {
				$camera = eqLogic::byId(str_replace(array('#', 'eqLogic'), array('', ''), $this->getConfiguration('camera')));
				if (is_object($camera)) {
					$html .= $camera->toHtml($_version, true);
				}
			}
			$html .= '</div>';
			return array(
				'plan' => jeedom::toHumanReadable(utils::o2a($this)),
				'html' => $html,
			);
		} else if ($linkType == 'zone') {
			if ($this->getConfiguration('zone_mode', 'simple') == 'widget') {
				$class = '';
				if ($this->getConfiguration('showOnFly') == 1) {
					$class .= 'zoneEqLogicOnFly ';
				}
				if ($this->getConfiguration('showOnClic') == 1) {
					$class .= 'zoneEqLogicOnClic ';
				}
				$html = '<div class="zone-widget cursor zoneEqLogic ' . $class . '" data-position="' . $this->getConfiguration('position') . '" data-eqLogic_id="' . str_replace(array('#', 'eqLogic'), array('', ''), $this->getConfiguration('eqLogic')) . '" data-zone_id="' . $this->getLink_id() . '" style="min-width:20px;min-height:20px;"></div>';
			} else {
				$html = '<div class="zone-widget cursor" data-zone_id="' . $this->getLink_id() . '" style="min-width:20px;min-height:20px;"></div>';
			}
			return array(
				'plan' => jeedom::toHumanReadable(utils::o2a($this)),
				'html' => $html,
			);
		} else if ($linkType == 'summary') {
			$html = '<div class="summary-widget" data-summary_id="' . $this->getLink_id() . '" style="min-width:10px;min-height:10px;">';
			$summary = '';
			if ($this->getLink_id() == 0) {
				$summary = jeeObject::getGlobalHtmlSummary($_version);
			} else {
				$object = $this->getLink();
				if (is_object($object)) {
					$summary = $object->getHtmlSummary($_version);
				}
			}
			if ($summary == '') {
				$html .= __('Non configuré', __FILE__);
			} else {
				$html .= $summary;
			}
			$html .= '</div>';
			return array(
				'plan' => jeedom::toHumanReadable(utils::o2a($this)),
				'html' => $html,
			);
		}
	}

    /**
     * @return array|null
     */
    public function getPlanHeader(): ?array
    {
		return planHeader::byId($this->getPlanHeader_id());
	}

	/*     * **********************Getteur Setteur*************************** */

    /**
     * @return mixed
     */
    public function getId() {
		return $this->id;
	}

    /**
     * @return mixed
     */
    public function getLink_type() {
		return $this->link_type;
	}

    /**
     * @return mixed
     */
    public function getLink_id() {
		return $this->link_id;
	}

    /**
     * @param string $_key
     * @param string $_default
     * @return array|bool|mixed|string
     */
    public function getPosition($_key = '', $_default = '') {
		return utils::getJsonAttr($this->position, $_key, $_default);
	}

    /**
     * @param string $_key
     * @param string $_default
     * @return array|bool|mixed|string
     */
    public function getDisplay($_key = '', $_default = '') {
		return utils::getJsonAttr($this->display, $_key, $_default);
	}

    /**
     * @param string $_key
     * @param string $_default
     * @return array|bool|mixed|string
     */
    public function getCss($_key = '', $_default = '') {
		return utils::getJsonAttr($this->css, $_key, $_default);
	}

    /**
     * @param $_id
     * @return $this
     */
    public function setId($_id): plan
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
		$this->id = $_id;
		return $this;
	}

    /**
     * @param $_link_type
     * @return $this
     */
    public function setLink_type($_link_type): plan
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->link_type,$_link_type);
		$this->link_type = $_link_type;
		return $this;
	}

    /**
     * @param $_link_id
     * @return $this
     */
    public function setLink_id($_link_id): plan
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->link_id,$_link_id);
		$this->link_id = $_link_id;
		return $this;
	}

    /**
     * @param $_key
     * @param $_value
     * @return $this
     */
    public function setPosition($_key, $_value): plan
    {
		$position = utils::setJsonAttr($this->position, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed,$this->position,$position);
		$this->position = $position;
		return $this;
	}

    /**
     * @param $_key
     * @param $_value
     * @return $this
     */
    public function setDisplay($_key, $_value): plan
    {
		$display = utils::setJsonAttr($this->display, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed,$this->display,$display);
		$this->display = $display;
		return $this;
	}

    /**
     * @param $_key
     * @param $_value
     * @return $this
     */
    public function setCss($_key, $_value): plan
    {
		$css = utils::setJsonAttr($this->css, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed,$this->css,$css);
		$this->css = $css;
		return $this;
	}

    /**
     * @return mixed
     */
    public function getPlanHeader_id() {
		return $this->planHeader_id;
	}

    /**
     * @param $_planHeader_id
     * @return $this
     */
    public function setPlanHeader_id($_planHeader_id): plan
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->planHeader_id,$_planHeader_id);
		$this->planHeader_id = $_planHeader_id;
		return $this;
	}

    /**
     * @param string $_key
     * @param string $_default
     * @return array|bool|mixed|string
     */
    public function getConfiguration($_key = '', $_default = '') {
		return utils::getJsonAttr($this->configuration, $_key, $_default);
	}

    /**
     * @param $_key
     * @param $_value
     * @return $this
     */
    public function setConfiguration($_key, $_value): plan
    {
		$configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed,$this->configuration,$configuration);
		$this->configuration = $configuration;
		return $this;
	}

    /**
     * @return bool
     */
    public function getChanged(): bool
    {
		return $this->_changed;
	}

    /**
     * @param $_changed
     * @return $this
     */
    public function setChanged($_changed): plan
    {
		$this->_changed = $_changed;
		return $this;
	}
}
