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

class plan {
	/*     * *************************Attributs****************************** */

	private $id;
	private $planHeader_id;
	private $link_type;
	private $link_id;
	private $position;
	private $display;
	private $css;

	/*     * ***********************Methode static*************************** */

	public static function byId($_id) {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM plan
                WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byPlanHeaderId($_planHeader_id) {
		$values = array(
			'planHeader_id' => $_planHeader_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM plan
                WHERE planHeader_id=:planHeader_id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byLinkTypeLinkId($_link_type, $_link_id) {
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

	public static function byLinkTypeLinkIdPlanHedaerId($_link_type, $_link_id, $_planHeader_id) {
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

	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM plan';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	/*     * *********************Methode d'instance************************* */

	public function preSave() {
		if ($this->getCss('zoom') != '' && (!is_numeric($this->getCss('zoom')) || $this->getCss('zoom')) < 0.1) {
			$this->setCss('zoom', 1);
		}
		if ($this->getLink_id() == '') {
			$this->setLink_id(rand(0, 99999999) + 9999);
		}
	}

	public function save() {
		DB::save($this);
	}

	public function remove() {
		DB::remove($this);
	}

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
		}
		return null;
	}

	public function getHtml($_version = 'dplan') {
		if ($this->getLink_type() == 'eqLogic' || $this->getLink_type() == 'cmd' || $this->getLink_type() == 'scenario') {
			$link = $this->getLink();
			if (!is_object($link)) {
				return;
			}
			return array(
				'plan' => utils::o2a($this),
				'html' => $link->toHtml($_version),
			);
		} else if ($this->getLink_type() == 'plan') {
			$this_link = planHeader::byId($this->getLink_id());
			if (!is_object($this_link)) {
				return;
			}
			$link = 'index.php?v=d&p=plan&plan_id=' . $this_link->getId();
			$html = '<span class="cursor plan-link-widget label label-success" data-link_id="' . $this_link->getId() . '" data-offsetX="' . $this->getDisplay('offsetX') . '" data-offsetY="' . $this->getDisplay('offsetY') . '">';
			$html .= '<a style="color:' . $this->getCss('color', 'white') . ';text-decoration:none;font-size : 1.5em;">';
			if ($this->getDisplay('name') != '' || $this->getDisplay('icon') != '') {
				$html .= $this->getDisplay('icon') . ' ' . $this->getDisplay('name');
			} else {
				$html .= $this_link->getName();
			}
			$html .= '</a>';
			$html .= '</span>';
			return array(
				'plan' => utils::o2a($this),
				'html' => $html,
			);
		} else if ($this->getLink_type() == 'view') {
			$view = view::byId($this->getLink_id());
			if (!is_object($view)) {
				return;
			}
			$link = 'index.php?p=view&view_id=' . $view->getId();
			$html = '<span href="' . $link . '" class=" cursor view-link-widget label label-primary" data-link_id="' . $view->getId() . '" >';
			$html .= '<a href="' . $link . '" style="color:' . $this->getCss('color', 'white') . ';text-decoration:none;font-size : 1.5em;">';
			if ($this->getDisplay('name') != '' || $this->getDisplay('icon') != '') {
				$html .= $this->getDisplay('icon') . ' ' . $this->getDisplay('name');
			} else {
				$html .= $view->getName();
			}
			$html .= '</a>';
			$html .= '</span>';
			return array(
				'plan' => utils::o2a($this),
				'html' => $html,
			);
		} else if ($this->getLink_type() == 'graph') {
			$return[] = array(
				'plan' => utils::o2a($this),
				'html' => '',
			);
		} else if ($this->getLink_type() == 'text') {
			$html = '<div class="text-widget" data-text_id="' . $this->getLink_id() . '" style="color:' . $this->getCss('color', 'black') . ';">';
			if ($this->getDisplay('name') != '' || $this->getDisplay('icon') != '') {
				$html .= $this->getDisplay('icon') . ' ' . $this->getDisplay('text');
			} else {
				$html .= $this->getDisplay('text', 'Texte à insérer ici');
			}
			$html .= '</div>';
			return array(
				'plan' => utils::o2a($this),
				'html' => $html,
			);
		}
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function getLink_type() {
		return $this->link_type;
	}

	public function getLink_id() {
		return $this->link_id;
	}

	public function getPosition($_key = '', $_default = '') {
		return utils::getJsonAttr($this->position, $_key, $_default);
	}

	public function getDisplay($_key = '', $_default = '') {
		return utils::getJsonAttr($this->display, $_key, $_default);
	}

	public function getCss($_key = '', $_default = '') {
		return utils::getJsonAttr($this->css, $_key, $_default);
	}

	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	public function setLink_type($link_type) {
		$this->link_type = $link_type;
		return $this;
	}

	public function setLink_id($link_id) {
		$this->link_id = $link_id;
		return $this;
	}

	public function setPosition($_key, $_value) {
		$this->position = utils::setJsonAttr($this->position, $_key, $_value);
		return $this;
	}

	public function setDisplay($_key, $_value) {
		$this->display = utils::setJsonAttr($this->display, $_key, $_value);
		return $this;
	}

	public function setCss($_key, $_value) {
		$this->css = utils::setJsonAttr($this->css, $_key, $_value);
		return $this;
	}

	public function getPlanHeader_id() {
		return $this->planHeader_id;
	}

	public function setPlanHeader_id($planHeader_id) {
		$this->planHeader_id = $planHeader_id;
		return $this;
	}

}
