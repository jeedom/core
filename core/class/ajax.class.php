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

/**
 * Handles AJAX responses in Jeedom
 *
 * @example
 * ajax::init(['getInfos']); // returns void
 * ajax::success(['result' => 'ok']); // sends JSON response
 *
 * @see config::class For configuration management
 * @see log::class For logging management
 */
class ajax {
	/*     * *************************Attributs****************************** */

	/*     * *********************Methode static ************************* */

    /**
     * Initializes AJAX response with HTTP headers and GET action validation
     *
     * @param string[] $_allowGetAction List of allowed GET actions
     * @return void
     * @throws \Exception When requested GET action is not allowed
     */
	public static function init($_allowGetAction = array()) {
		if (!headers_sent()) {
			header('Content-Type: application/json');
		}
		if(isset($_GET['action']) && !in_array($_GET['action'], $_allowGetAction)){
			throw new \Exception(__('Méthode non autorisée en GET : ',__FILE__).$_GET['action']);
		}
	}

    /**
     * Returns authentication token
     *
     * @deprecated Since version 4.4, authentication is handled differently
     * @return string Empty token
     */
	public static function getToken(){
		return '';
	}

    /**
     * Sends a success response and ends execution
     *
     * @param mixed $_data Data to send in response
     * @return never
     */
	public static function success($_data = '') {
		echo self::getResponse($_data);
		die();
	}

    /**
     * Sends an error response and ends execution
     *
     * @param mixed $_data Error message or data to send
     * @param int $_errorCode Custom error code for client-side handling (default: 0)
     * @return never
     */
	public static function error($_data = '', $_errorCode = 0) {
		echo self::getResponse($_data, $_errorCode);
		die();
	}

    /**
     * Generates formatted JSON response
     *
     * @param mixed $_data Data to include in response
     * @param ?int $_errorCode Error code (null for success response)
     * @return string|false Encoded JSON response
     */
	public static function getResponse($_data = '', $_errorCode = null) {
		$isError = !(null === $_errorCode);
		$return = array(
			'state' => $isError ? 'error' : 'ok',
			'result' => $_data,
		);
		if ($isError) {
			$return['code'] = $_errorCode;
		}
		return json_encode($return, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
	}
	/*     * **********************Getteur Setteur*************************** */
}
