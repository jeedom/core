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

	private static $limit = 250;
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
		$find = array();
		$events = array_values($_events);
		$now = strtotime('now') + 300;
		foreach ($events as $key => $event) {
			if($event['datetime'] > $now){
				unset($events[$key]);
				continue;
			}
			if ($event['name'] == 'eqLogic::update') {
				$id = 'eqLogic::update::' . $event['option']['eqLogic_id'];
			} elseif ($event['name'] == 'cmd::update') {
				$id = 'cmd::update::' . $event['option']['cmd_id'];
			} elseif ($event['name'] == 'scenario::update') {
				$id = 'scenario::update::' . $event['option']['scenario_id'];
			} elseif ($event['name'] == 'jeeObject::summary::update') {
				$id = 'jeeObject::summary::update::' . $event['option']['object_id'];
				if(is_array($event['option']['keys']) && count($event['option']['keys']) > 0){
					foreach ($event['option']['keys'] as $key => $value) {
						$id .= $key;
					}
				}
			} else {
				continue;
			}
			if (isset($find[$id])) {
				if($find[$id]['datetime'] > $event['datetime']){
					unset($events[$key]);
					continue;
				}else{
					unset($events[$find[$id]['key']]);
				}
			}
			$find[$id] = array('datetime' => $event['datetime'],'key' => $key);
		}
		return $events;
	}

	public static function orderEvent($a, $b) {
		return ($a['datetime'] - $b['datetime']);
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
		$filters = ($_filter !== null) ? cache::byKey($_filter . '::event')->getValue(array()) : array();
		$return = array('datetime' => $_data['datetime'], 'result' => array());
		foreach ($_data['result'] as $value) {
			if ($_filter !== null && isset($_filter::$_listenEvents) && !in_array($value['name'], $_filter::$_listenEvents)) {
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
