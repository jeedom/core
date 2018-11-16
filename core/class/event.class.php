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

	private static $limit = 150;
	private static $_fd = null;

	/*     * ***********************Methode static*************************** */

	public static function getFileDescriptorLock() {
		if (self::$_fd === null) {
			self::$_fd = fopen(jeedom::getTmpFolder() . '/event_cache_lock', 'w');
			chmod(jeedom::getTmpFolder() . '/event_cache_lock', 0777);
		}
		return self::$_fd;
	}

	public static function add($_event, $_option = array()) {
		$waitIfLocked = true;
		$fd = self::getFileDescriptorLock();
		if (flock($fd, LOCK_EX, $waitIfLocked)) {
			$cache = cache::byKey('event');
			$value = json_decode($cache->getValue('[]'), true);
			if (!is_array($value)) {
				$value = array();
			}
			$value[] = array('datetime' => getmicrotime(), 'name' => $_event, 'option' => $_option);
			cache::set('event', json_encode(self::cleanEvent($value)));
			flock($fd, LOCK_UN);
		}
	}

	public static function adds($_event, $_values = array()) {
		$waitIfLocked = true;
		$fd = self::getFileDescriptorLock();
		if (flock($fd, LOCK_EX, $waitIfLocked)) {
			$cache = cache::byKey('event');
			$value_src = json_decode($cache->getValue('[]'), true);
			if (!is_array($value_src)) {
				$value_src = array();
			}
			$value = array();
			foreach ($_values as $option) {
				$value[] = array('datetime' => getmicrotime(), 'name' => $_event, 'option' => $option);
			}
			cache::set('event', json_encode(self::cleanEvent(array_merge($value_src, $value))));
			flock($fd, LOCK_UN);
		}
	}

	public static function cleanEvent($_events) {
		$_events = array_slice(array_values($_events), -self::$limit, self::$limit);
		$return = array();
		foreach ($_events as $event) {
			if (!in_array($event['name'], array('eqLogic::update', 'cmd::update', 'scenario::update', 'jeeObject::summary::update'))) {
				$return[] = $event;
				continue;
			}
			if ($event['name'] == 'eqLogic::update') {
				$return[$event['name'] . '::' . $event['option']['eqLogic_id']] = $event;
			} else if ($event['name'] == 'cmd::update') {
				$return[$event['name'] . '::' . $event['option']['cmd_id']] = $event;
			} else if ($event['name'] == 'scenario::update') {
				$return[$event['name'] . '::' . $event['option']['scenario_id']] = $event;
			} else if ($event['name'] == 'jeeObject::summary::update') {
				$return[$event['name'] . '::' . $event['option']['object_id']] = $event;
			}
		}
		usort($return, 'self::orderEvent');
		return $return;
	}

	public static function orderEvent($a, $b) {
		return ($a['datetime'] - $b['datetime']) ? -1 : 1;
	}

	public static function changes($_datetime, $_longPolling = null, $_filter = null) {
		$return = self::filterEvent(self::changesSince($_datetime), $_filter);
		if ($_longPolling === null || count($return['result']) > 0) {
			return $return;
		}
		$waitTime = config::byKey('event::waitPollingTime');
		$i = 0;
		$max_cycle = $_longPolling / $waitTime;
		while (count($return['result']) == 0 && $i < $max_cycle) {
			if ($waitTime < 1) {
				usleep(1000000 * $waitTime);
			} else {
				sleep(round($waitTime));
			}
			sleep(1);
			$return = self::filterEvent(self::changesSince($_datetime), $_filter);
			$i++;
		}
		return $return;
	}

	private static function filterEvent($_data = array(), $_filter = null) {
		if ($_filter == null) {
			return $_data;
		}
		$filters = cache::byKey($_filter . '::event')->getValue(array());
		$return = array('datetime' => $_data['datetime'], 'result' => array());
		foreach ($_data['result'] as $value) {
			if (isset($_filter::$_listenEvents) && !in_array($value['name'], $_filter::$_listenEvents)) {
				continue;
			}
			if (count($filters) != 0 && $value['name'] == 'cmd::update' && !in_array($value['option']['cmd_id'], $filters)) {
				continue;
			}
			$return['result'][] = $value;
		}
		return $return;
	}

	private static function changesSince($_datetime) {
		$return = array('datetime' => $_datetime, 'result' => array());
		$cache = cache::byKey('event');
		$events = json_decode($cache->getValue('[]'), true);
		if (!is_array($events)) {
			$events = array();
		}
		$values = array_reverse($events);
		if (count($values) > 0) {
			$return['datetime'] = $values[0]['datetime'];
			foreach ($values as $value) {
				if ($value['datetime'] <= $_datetime) {
					break;
				}
				$return['result'][] = $value;
			}
		}
		$return['result'] = array_reverse($return['result']);
		return $return;
	}

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */
}
