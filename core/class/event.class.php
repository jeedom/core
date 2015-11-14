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

	public static function add($_event, $_option) {
		$cache = cache::byKey('event');
		$value = json_decode($cache->getValue('[]'), true);
		$value[] = array('datetime' => getmicrotime(), 'name' => $_event, 'option' => $_option);
		uasort($value, 'event::datetimeOrder');
		cache::set('event', json_encode(array_slice($value, 0, self::$limit)), 0);
	}

	public static function changes($_datetime) {
		$return = array('datetime' => getmicrotime(), 'result' => array());
		$cache = cache::byKey('event');
		$values = json_decode($cache->getValue('[]'), true);
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

	private static function datetimeOrder($a, $b) {
		if ($a['datetime'] == $b['datetime']) {
			return 0;
		}
		return ($a['datetime'] > $b['datetime']) ? -1 : 1;
	}

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */
}

?>
