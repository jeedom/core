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

class ajax {
	/*     * *************************Attributs****************************** */

	/*     * *********************Methode static ************************* */

    /**
     * @deprecated
     */
	public static function init($_checkToken = true) {
		if (!headers_sent()) {
			header('Content-Type: application/json');
		}
		if ($_checkToken) {
		    self::checkToken();
        }
	}

	public static function checkToken() {
        if (init('jeedom_token') != self::getToken()) {
            throw new Exception(__('Token d\'accès invalide', __FILE__));
        }
    }

    public static function checkAccess($right) {
        self::checkToken();
        checkAccess(__FILE__, $right);
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

    /** @deprecated Use ajax::getResponse instead */
	public static function success($_data = '') {
		echo self::getResponse($_data);
		die();
	}

    /** @deprecated Use ajax::getResponse instead */
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
