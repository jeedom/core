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

class listener {
	/*     * *************************Attributs****************************** */

	private $id;
	private $event;
	private $class;
	private $function;
	private $option;

	/*     * ***********************Méthodes statiques*************************** */

	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM listener';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byId($_id) {
		$value = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM listener
                WHERE id=:id';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byClass($_class) {
		$value = array(
			'class' => $_class,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM listener
                WHERE class=:class';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byClassAndFunction($_class, $_function, $_option = '') {
		$value = array(
			'class' => $_class,
			'function' => $_function,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM listener
                WHERE class=:class
                    AND function=:function';
		if ($_option != '') {
			$_option = json_encode($_option, JSON_UNESCAPED_UNICODE);
			$value['option'] = $_option;
			$sql .= ' AND `option`=:option';
		}
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function searchEvent($_event) {
		if (strpos($_event, '#') !== false) {
			$value = array(
				'event' => '%' . $_event . '%',
			);
		} else {
			$value = array(
				'event' => '%#' . $_event . '#%',
			);
		}
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM listener
                WHERE `event` LIKE :event';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function check($_event, $_value) {
		$listeners = self::searchEvent($_event);
		if (count($listeners) > 0) {
			foreach ($listeners as $listener) {
				$listener->run(str_replace('#', '', $_event), $_value);
			}
		}
	}

	public static function backgroundCalculDependencyCmd($_event) {
		$cmd = dirname(__FILE__) . '/../php/jeeListener.php';
		$cmd .= ' event_id=' . $_event;
		php($cmd . ' >> /dev/null 2>&1 &');
	}

	public function getName() {
		if ($this->getClass() != '') {
			return $this->getClass() . '::' . $this->getFunction() . '()';
		}
		return $this->getFunction() . '()';
	}

	/*     * *********************Méthodes d'instance************************* */

	public function run($_event, $_value) {
		$cmd = dirname(__FILE__) . '/../php/jeeListener.php';
		$cmd .= ' listener_id=' . $this->getId() . ' event_id=' . $_event . ' value=' . $_value;
		php($cmd . ' >> /dev/null 2>&1 &');
	}

	public function preSave() {
		if ($this->getFunction() == '') {
			throw new Exception(__('La fonction ne peut pas être vide', __FILE__));
		}
	}

	public function save() {
		return DB::save($this);
	}

	public function remove() {
		return DB::remove($this);
	}

	public function emptyEvent() {
		$this->setEvent(array());
	}

	public function addEvent($_id, $_type = 'cmd') {
		$event = $this->getEvent();
		if ($_type == 'cmd') {
			if (strpos($_id, '#') !== false) {
				if (!in_array($_id, $event)) {
					$event[] = $_id;
				}
			} else {
				if (!in_array('#' . $_id . '#', $event)) {
					$event[] = '#' . $_id . '#';
				}
			}
		}
		$this->setEvent($event);
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function getEvent() {
		return json_decode($this->event, true);
	}

	public function getClass() {
		return $this->class;
	}

	public function getFunction() {
		return $this->function;
	}

	public function getOption() {
		return json_decode($this->option, true);
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setEvent($event) {
		$this->event = json_encode($event, JSON_UNESCAPED_UNICODE);
	}

	public function setClass($class) {
		$this->class = $class;
	}

	public function setFunction($function) {
		$this->function = $function;
	}

	public function setOption($option) {
		$this->option = json_encode($option, JSON_UNESCAPED_UNICODE);
	}

}

?>
