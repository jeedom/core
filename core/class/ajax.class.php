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

class ajax {
	/*     * *************************Attributs****************************** */

	/*     * *********************Methode static ************************* */

	public static function init($_checkToken = true) {
		if (!headers_sent()) {
			header('Content-Type: application/json');
		}
		if ($_checkToken && init('jeedom_token') != self::getToken()) {
			self::error(__('Token d\'accès invalide', __FILE__));
		}
	}

	public static function getToken() {
		if (session_status() == PHP_SESSION_NONE) {
			@session_start();
			@session_write_close();
		}
		if (!isset($_SESSION['jeedom_token'])) {
			@session_start();
			$_SESSION['jeedom_token'] = config::genKey();
			@session_write_close();
		}
		return $_SESSION['jeedom_token'];
	}

	public static function success($_data = '') {
		echo self::getResponse($_data);
		die();
	}

	public static function error($_data = '', $_errorCode = 0) {
		echo self::getResponse($_data, $_errorCode);
		die();
	}

	public static function getResponse($_data = '', $_errorCode = null) {
		$isError = !(null === $_errorCode);
		$return = array(
			'state' => $isError ? 'error' : 'ok',
			'result' => $_data,
		);
		if ($isError) {
			$return['code'] = $_errorCode;
		}
		return json_encode($return, JSON_UNESCAPED_UNICODE);
	}
	/*     * **********************Getteur Setteur*************************** */
}
