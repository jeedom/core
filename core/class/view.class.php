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

class view {
	/*     * *************************Attributs****************************** */

	private $id;
	private $name;
	private $display;
	private $order;

	/*     * ***********************Méthodes statiques*************************** */

	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM view
                ORDER BY `order`';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byId($_id) {
		$value = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM view
                WHERE id=:id';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function searchByUse($_type, $_id) {
		$return = array();
		$viewDatas = viewData::byTypeLinkId($_type, $_id);
		$search = '#' . str_replace('cmd', '', $_type . $_id) . '#';
		$viewDatas = array_merge($viewDatas, viewData::searchByConfiguration($search));
		foreach ($viewDatas as $viewData) {
			$viewZone = $viewData->getviewZone();
			$view = $viewZone->getView();
			$return[$view->getId()] = $view;
		}
		return $return;
	}

	/*     * *********************Méthodes d'instance************************* */

	public function report($_format = 'pdf', $_parameters = array()) {
		$url = network::getNetworkAccess('internal') . '/index.php?v=d&p=view';
		$url .= '&view_id=' . $this->getId();
		$url .= '&report=1';
		return report::generate($url, 'view', $this->getId(), $_format, $_parameters);
	}
	/**
	 *
	 * @throws Exception
	 */
	public function presave() {
		if (trim($this->getName()) == '') {
			throw new Exception(__('Le nom de la vue ne peut pas être vide', __FILE__));
		}
	}

	public function save() {
		return DB::save($this);
	}

	public function remove() {
		return DB::remove($this);
	}

	public function getviewZone() {
		return viewZone::byView($this->getId());
	}

	public function removeviewZone() {
		return viewZone::removeByViewId($this->getId());
	}

	public function toAjax($_version = 'dview') {
		$return = utils::o2a($this);
		$return['viewZone'] = array();
		foreach ($this->getViewZone() as $viewZone) {
			$viewZone_info = utils::o2a($viewZone);

			$viewZone_info['viewData'] = array();
			foreach ($viewZone->getViewData() as $viewData) {
				$viewData_info = utils::o2a($viewData);
				$viewData_info['name'] = '';
				switch ($viewData->getType()) {
					case 'cmd':
						$cmd = $viewData->getLinkObject();
						if (is_object($cmd)) {
							$viewData_info['type'] = 'cmd';
							$viewData_info['name'] = $cmd->getHumanName();
							$viewData_info['id'] = $cmd->getId();
							$viewData_info['html'] = $cmd->toHtml($_version);
						}
						break;
					case 'eqLogic':
						$eqLogic = $viewData->getLinkObject();
						if (is_object($eqLogic)) {
							$viewData_info['type'] = 'eqLogic';
							$viewData_info['name'] = $eqLogic->getHumanName();
							$viewData_info['id'] = $eqLogic->getId();
							$viewData_info['html'] = $eqLogic->toHtml($_version);
						}
						break;
					case 'scenario':
						$scenario = $viewData->getLinkObject();
						if (is_object($scenario)) {
							$viewData_info['type'] = 'scenario';
							$viewData_info['name'] = $scenario->getHumanName();
							$viewData_info['id'] = $scenario->getId();
							$viewData_info['html'] = $scenario->toHtml($_version);
						}
						break;
				}
				$viewZone_info['viewData'][] = $viewData_info;
				if ($viewZone->getType() == 'table') {
					$viewZone_info['html'] = '<table class="table table-condensed ui-responsive table-stroke" data-role="table" data-mode="columntoggle">';

					if (count($viewZone_info['viewData']) != 1) {
						continue;
					}
					$viewData = $viewZone_info['viewData'][0];
					$configurationViewZoneLine = $viewZone->getConfiguration('nbline', 2);
					for ($i = 0; $i < $configurationViewZoneLine; $i++) {
						$viewZone_info['html'] .= '<tr>';
						$configurationViewZoneColumn = $viewZone->getConfiguration('nbcol', 2);
						for ($j = 0; $j < $configurationViewZoneColumn; $j++) {
							$viewZone_info['html'] .= '<td><center>';
							if (isset($viewData['configuration'][$i][$j])) {
								$replace = array();
								preg_match_all("/#([0-9]*)#/", $viewData['configuration'][$i][$j], $matches);
								foreach ($matches[1] as $cmd_id) {
									$cmd = cmd::byId($cmd_id);
									if (!is_object($cmd)) {
										continue;
									}
									$replace['#' . $cmd_id . '#'] = $cmd->toHtml($_version);
								}
								$viewZone_info['html'] .= str_replace(array_keys($replace), $replace, $viewData['configuration'][$i][$j]);
							}
							$viewZone_info['html'] .= '</center></td>';
						}
						$viewZone_info['html'] .= '</tr>';
					}
					$viewZone_info['html'] .= '</table>';
				}
			}
			$return['viewZone'][] = $viewZone_info;
		}
		return jeedom::toHumanReadable($return);
	}

	public function getLinkData(&$_data = array('node' => array(), 'link' => array()), $_level = 0, $_drill = 3) {
		if (isset($_data['node']['view' . $this->getId()])) {
			return;
		}
		$_level++;
		if ($_level > $_drill) {
			return $_data;
		}
		$icon = findCodeIcon('fa-picture-o');
		$_data['node']['view' . $this->getId()] = array(
			'id' => 'interactDef' . $this->getId(),
			'name' => substr($this->getName(), 0, 20),
			'icon' => $icon['icon'],
			'fontfamily' => $icon['fontfamily'],
			'fontsize' => '1.5em',
			'fontweight' => ($_level == 1) ? 'bold' : 'normal',
			'texty' => -14,
			'textx' => 0,
			'title' => __('Vue :', __FILE__) . ' ' . $this->getName(),
			'url' => 'index.php?v=d&p=view&view_id=' . $this->getId(),
		);
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function getOrder($_default = null) {
		if ($this->order == '' || !is_numeric($this->order)) {
			return $_default;
		}
		return $this->order;
	}

	public function setOrder($order) {
		$this->order = $order;
		return $this;
	}

	public function getDisplay($_key = '', $_default = '') {
		return utils::getJsonAttr($this->display, $_key, $_default);
	}

	public function setDisplay($_key, $_value) {
		$this->display = utils::setJsonAttr($this->display, $_key, $_value);
		return $this;
	}

}
