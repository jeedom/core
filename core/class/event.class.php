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

class event {
	/*     * *************************Attributs****************************** */

	protected $datetime;
	protected $name;
	protected $options;

	/*     * ***********************Methode static*************************** */


	public static function add($_event, $_options = array(),$_clean = true) {
		if(is_array($_options)){
			$_options = json_encode($_options);
		}
		$value = array(
			'datetime' => getmicrotime(),
			'name' => $_event,
			'options' => $_options
		);
		$sql = 'INSERT INTO `event` SET `datetime`=:datetime, `name`=:name,`options`=:options';
		DB::Prepare($sql,$value, DB::FETCH_TYPE_ROW);
		if($_clean){
			self::cleanEvent();
		}
	}

	public static function adds($_event, $_values = array()) {
		foreach ($_values as $options) {
			self::add($_event,$options,false);
		}
		self::cleanEvent();
	}

	public static function cleanEvent() {
		$sql = 'SELECT count(*) as number FROM `event`';
		$result = DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		$delete_number = $result['number'] - 250;
		if($delete_number > 0){
			$sql = 'DELETE FROM event ORDER BY `datetime` ASC LIMIT '.$delete_number;
			DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		}
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM event
		ORDER BY `datetime`';
		$events = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
		$find = array();
		$now = strtotime('now') + 300;
		foreach ($events as $event) {
			if ($event->getDatetime() > $now) {
				$event->remove();
				continue;
			}
			if ($event->getName() == 'eqLogic::update') {
				$id = 'eqLogic::update::' . $event->getOptions('eqLogic_id');
			} elseif ($event->getName() == 'cmd::update') {
				$id = 'cmd::update::' . $event->getOptions('cmd_id');
			} elseif ($event->getName() == 'scenario::update') {
				$id = 'scenario::update::' . $event->getOptions('scenario_id');
			} elseif ($event->getName() == 'jeeObject::summary::update') {
				$id = 'jeeObject::summary::update::' . $event->getOptions('object_id');
				if (is_array($event->getOptions('keys')) && count($event->getOptions('keys')) > 0) {
					foreach ($event->getOptions('keys') as $key2 => $value) {
						$id .= $key2;
					}
				}
			} else {
				continue;
			}
			if (isset($find[$id])) {
				if ($find[$id]->getDatetime() > $event->getDatetime()) {
					$event->remove();
					continue;
				} else {
					$find[$id]->remove();
				}
			}
			$find[$id] = $event;
		}
	}

	public static function orderEvent($a, $b) {
		return ($a['datetime'] - $b['datetime']);
	}

	public static function changes($_datetime, $_longPolling = null, $_filter = null) {
		$return = self::filterEvent(self::changesSince($_datetime), $_filter);
		if ($_longPolling === null || count($return) > 0) {
			return array('datetime' => getmicrotime(), 'results'=> utils::o2a($return));
		}
		$waitTime = config::byKey('event::waitPollingTime');
		$i = 0;
		$max_cycle = $_longPolling / $waitTime;
		while (count($return) == 0 && $i < $max_cycle) {
			if ($waitTime < 1) {
				usleep(1000000 * $waitTime);
			} else {
				sleep(round($waitTime));
			}
			sleep(1);
			$return = self::filterEvent(self::changesSince($_datetime), $_filter);
			$i++;
		}
		return array('datetime' => getmicrotime(), 'results'=> utils::o2a($return));
	}

	private static function filterEvent($_events = array(), $_filter = null) {
		if ($_filter == null) {
			return $_events;
		}
		$filters = ($_filter !== null) ? cache::byKey($_filter . '::event')->getValue(array()) : array();
		$return = array();
		foreach (_events as $event) {
			if ($_filter !== null && isset($_filter::$_listenEvents) && !in_array($event->getName(), $_filter::$_listenEvents)) {
				continue;
			}
			if (count($filters) != 0 && $event->getName() == 'cmd::update' && !in_array($event->getOptions('cmd_id'), $filters)) {
				continue;
			}
			$return[] = $event;
		}
		return $return;
	}

	private static function changesSince($_datetime) {
		$now = getmicrotime();
		if ($_datetime > $now) {
			$_datetime = $now;
		}
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM event
		WHERE `datetime` >= :datetime
		ORDER BY `datetime`';
		return DB::Prepare($sql, array('datetime' => $_datetime), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	/*     * *********************Methode d'instance************************* */

	public function save($_direct = false){
		DB::save($this, $_direct);
	}

	public function remove() {
		return DB::remove($this);
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getDatetime() {
		return $this->datetime;
	}

	public function setDatetime($_datetime) {
		$this->datetime = $_datetime;
		return $this;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($_name) {
		$this->name = $_name;
		return $this;
	}

	public function getOptions($_key = '', $_default = '') {
		return utils::getJsonAttr($this->options, $_key, $_default);
	}

	public function setOptions($_key, $_value) {
		$this->options = utils::setJsonAttr($this->options, $_key, $_value);
		return $this;
	}
}
