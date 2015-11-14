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

class nodejs {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	public static function pushNotification($_title, $_text, $_category = '') {
		self::send(self::baseUrl() . 'type=notify&category=' . $_category . '&title=' . urlencode($_title) . '&text=' . urlencode($_text));
	}

	public static function pushUpdate($_event, $_option) {
		if (is_object($_option) || is_array($_option)) {
			$option = json_encode($_option, JSON_UNESCAPED_UNICODE);
		} else {
			$option = $_option;
		}
		$url = self::baseUrl() . 'type=' . urlencode($_event) . '&options=' . urlencode($option);
		self::send($url);
		if ($_event == 'eventCmd' && is_array($_option)) {
			$values = array();
			if (isset($_option['cmd_id'])) {
				if (isset($_option['value'])) {
					$values['cmd' . $_option['cmd_id']] = array('datetime' => strtotime('now'), 'type' => 'cmd', 'value' => $_option['value'], 'cmd_id' => $_option['cmd_id']);
				}
			} else {
				foreach ($_option as $option) {
					if (isset($option['value'])) {
						$values['cmd' . $option['cmd_id']] = array('datetime' => strtotime('now'), 'type' => 'cmd', 'value' => $option['value'], 'cmd_id' => $option['cmd_id']);
					}
				}
			}
			if (count($values) > 0) {
				$cache = cache::byKey('nodejs_event');
				$value = json_decode($cache->getValue('[]'), true);
				$value = array_slice($value, 0, 250);
				$value = array_merge($value, $values);
				uasort($value, 'nodejs::datetimeOrder');
				cache::set('nodejs_event', json_encode($value), 0);
			}
		}
	}

	public static function updateKey() {
		config::save('nodeJsKey', config::genKey());
	}

	private static function baseUrl() {
		if (config::byKey('nodeJsKey') == '') {
			self::updateKey();
		}
		return '127.0.0.1:' . config::byKey('nodeJsInternalPort') . '?key=' . config::byKey('nodeJsKey') . '&';
	}

	private static function send($_url) {
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $_url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_HEADER, false);
		curl_setopt($c, CURLOPT_TIMEOUT, 1);
		curl_exec($c);
		curl_close($c);
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
