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

class event {
	/*     * *************************Attributs****************************** */

	static $limit = 250;

	/*     * ***********************Methode static*************************** */

	public static function add($_event, $_option = array()) {
		while (file_exists('/tmp/jeedom__event_cache_lock')) {
			usleep(rand(1, 10));
		}
		touch('/tmp/jeedom__event_cache_lock');
		chmod('/tmp/jeedom__event_cache_lock', 0777);
		$cache = cache::byKey('event');
		$value = json_decode($cache->getValue('[]'), true);
		$value[] = array('datetime' => getmicrotime(), 'name' => $_event, 'option' => $_option);
		cache::set('event', json_encode(array_slice($value, -self::$limit, self::$limit)), 0);
		unlink('/tmp/jeedom__event_cache_lock');
	}

	public static function adds($_event, $_values = array()) {
		$value = array();
		foreach ($_values as $option) {
			$value[] = array('datetime' => getmicrotime(), 'name' => $_event, 'option' => $option);
		}
		while (file_exists('/tmp/jeedom__event_cache_lock')) {
			usleep(rand(1, 10));
		}
		touch('/tmp/jeedom__event_cache_lock');
		chmod('/tmp/jeedom__event_cache_lock', 0777);
		$cache = cache::byKey('event');
		cache::set('event', json_encode(array_slice(array_merge(json_decode($cache->getValue('[]'), true), $value), -self::$limit, self::$limit)), 0);
		unlink('/tmp/jeedom__event_cache_lock');
	}

	public static function changes($_datetime, $_longPolling = null) {
		$return = self::changesSince($_datetime);
		if ($_longPolling == null || count($return['result']) > 0) {
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
			$return = self::changesSince($_datetime);
			$i++;
		}
		return $return;
	}

	private static function changesSince($_datetime) {
		$return = array('datetime' => getmicrotime(), 'result' => array());
		$cache = cache::byKey('event');
		$values = array_reverse(json_decode($cache->getValue('[]'), true));
		if (count($values) > 0) {
			foreach ($values as $value) {
				if ($value['datetime'] <= $_datetime) {
					break;
				}
				$return['result'][] = $value;
			}
		}
		return $return;
	}

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */
}

?>
