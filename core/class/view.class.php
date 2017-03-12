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

	/*     * *********************Méthodes d'instance************************* */

	public function report($_format = 'pdf', $_parameters = array()) {
		if (!isset($_parameters['user'])) {
			$users = user::searchByRight('admin');
			if (count($users) == 0) {
				throw new Exception(__('Aucun utilisateur admin trouvé pour la génération du rapport', __FILE__));
			}
			$user = $users[0];
		} else {
			$user = user::byId($_parameters['user']);
		}
		$out = dirname(__FILE__) . '/../../tmp/report_' . $this->getId() . '_' . date('Y_m_d_H_i_s') . '.' . $_format;
		$url = network::getNetworkAccess('internal') . '/index.php?v=d&p=view';
		$url .= '&view_id=' . $this->getId();
		$url .= '&report=1';
		$url .= '&auth=' . $user->getHash();
		$cmd = 'xvfb-run --server-args="-screen 0, 1280x1200x24" cutycapt --url="' . $url . '" --out="' . $out . '"';
		$cmd .= ' --delay=' . config::byKey('report::delay');
		$cmd .= ' --print-backgrounds=on';
		com_shell::execute($cmd);
		return $out;
	}

	public function presave() {
		if ($this->getName() == '') {
			throw new Exception('Le nom de la vue ne peut pas être vide');
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
					$viewDatas = $viewZone->getViewData();
					if (count($viewZone_info['viewData']) != 1) {
						continue;
					}
					$viewData = $viewZone_info['viewData'][0];
					for ($i = 0; $i < $viewZone->getConfiguration('nbline', 2); $i++) {
						$viewZone_info['html'] .= '<tr>';
						for ($j = 0; $j < $viewZone->getConfiguration('nbcol', 2); $j++) {
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

?>
