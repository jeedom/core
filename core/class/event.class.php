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
	protected $option;

	/*     * ***********************Methode static*************************** */


	public static function add($_event, $_option = array(),$_clean = true) {
		if(is_array($_option)){
			$_option = json_encode($_option, JSON_UNESCAPED_UNICODE);
		}
		$value = array(
			'datetime' => getmicrotime(),
			'name' => $_event,
			'option' => $_option
		);
		$sql = 'INSERT INTO `event` SET `datetime`=:datetime, `name`=:name,`option`=:option';
		DB::Prepare($sql,$value, DB::FETCH_TYPE_ROW);
		if($_clean){
			self::cleanEvent();
		}
	}

	public static function adds($_event, $_values = array()) {
		foreach ($_values as $option) {
			self::add($_event,$option,false);
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
		FROM event';
		$events = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
		$find = array();
		$now = strtotime('now') + 300;
		foreach ($events as $event) {
			if ($event->getDatetime() > $now) {
				$event->remove();
				continue;
			}
			if ($event->getName() == 'eqLogic::update') {
				$id = 'eqLogic::update::' . $event->getOption('eqLogic_id');
			} elseif ($event->getName() == 'cmd::update') {
				$id = 'cmd::update::' . $event->getOption('cmd_id');
			} elseif ($event->getName() == 'scenario::update') {
				$id = 'scenario::update::' . $event->getOption('scenario_id');
			} elseif ($event->getName() == 'jeeObject::summary::update') {
				$id = 'jeeObject::summary::update::' . $event->getOption('object_id');
				if (is_array($event->getOption('keys')) && count($event->getOption('keys')) > 0) {
					foreach ($event->getOption('keys') as $key2 => $value) {
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
					unset($find[$id]);
				}
			}
			$find[$id] = $event;
		}
	}

	public static function changes($_datetime, $_longPolling = null, $_filter = null) {
		$return = self::filterEvent(self::changesSince($_datetime), $_filter);
		if ($_longPolling === null || count($return) > 0) {
			return array('datetime' => getmicrotime(), 'result'=> utils::o2a($return));
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
		return array('datetime' => getmicrotime(), 'result'=> utils::o2a($return));
	}

	private static function filterEvent($_events = array(), $_filter = null) {
		if ($_filter == null) {
			return $_events;
		}
		$filters = ($_filter !== null) ? cache::byKey($_filter . '::event')->getValue(array()) : array();
		$return = array();
		foreach ($_events as $event) {
			if ($_filter !== null && isset($_filter::$_listenEvents) && !in_array($event->getName(), $_filter::$_listenEvents)) {
				continue;
			}
			if (count($filters) != 0 && $event->getName() == 'cmd::update' && !in_array($event->getOption('cmd_id'), $filters)) {
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

	public function getOption($_key = '', $_default = '') {
		if(!is_json($this->option)){
			return $this->option;
		}
		return utils::getJsonAttr($this->option, $_key, $_default);
	}

	public function setOption($_key, $_value) {
		$this->option = utils::setJsonAttr($this->option, $_key, $_value);
		return $this;
	}
}
