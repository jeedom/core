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

class jsonrpc {
	/*     * ********Attributs******************* */
	
	private $jsonrpc;
	private $method;
	private $params;
	private $id = 99999;
	private $startTime;
	private $applicationName;
	private $additionnalParams = array();
	
	/*     * ********Static******************* */
	
	/**
	*
	* @param string $_jsonrpc
	*/
	public function __construct($_jsonrpc) {
		$this->startTime = getmicrotime();
		$this->applicationName = 'Unknown';
		$jsonrpc = json_decode($_jsonrpc, true);
		$this->jsonrpc = $jsonrpc['jsonrpc'];
		$this->method = ($jsonrpc['method'] != '') ? $jsonrpc['method'] : 'none';
		if (isset($jsonrpc['params'])) {
			if (is_array($jsonrpc['params'])) {
				$this->params = $jsonrpc['params'];
			} else {
				$this->params = json_decode($jsonrpc['params'], true);
			}
		}
		if(isset($jsonrpc['id'])){
			$this->id = $jsonrpc['id'];
		}
	}
	
	public function makeError($_code, $_message) {
		$return = array(
			'jsonrpc' => '2.0',
			'id' => $this->id,
			'error' => array(
				'code' => $_code,
				'message' => $_message,
			),
		);
		$return = array_merge($return, $this->getAdditionnalParams());
		if (init('callback') != '') {
			echo init('callback') . '(' . json_encode($return) . ')';
		} else {
			echo json_encode($return);
		}
		exit;
	}
	
	public function makeSuccess($_result = 'ok') {
		$return = array(
			'jsonrpc' => '2.0',
			'id' => $this->id,
			'result' => $_result,
		);
		$return = array_merge($return, $this->getAdditionnalParams());
		if (init('callback') != '') {
			echo init('callback') . '(' . json_encode($return) . ')';
		} else {
			echo json_encode($return);
		}
		exit;
	}
	
	/*     * ********Getteur Setteur******************* */
	
	public function getStartTime() {
		return $this->startTime;
	}
	
	public function getApplicationName() {
		return $this->applicationName;
	}
	
	public function getJsonrpc() {
		return $this->jsonrpc;
	}
	
	public function getMethod() {
		return $this->method;
	}
	
	public function getParams() {
		return $this->params;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setApplicationName($applicationName) {
		$this->applicationName = $applicationName;
		return $this;
	}
	
	public function getAdditionnalParams($_key = '', $_default = '') {
		return utils::getJsonAttr($this->additionnalParams, $_key, $_default);
	}
	
	public function setAdditionnalParams($_key, $_value) {
		if (in_array($_key, array('result', 'jsonrpc', 'id'))) {
			return;
		}
		$this->additionnalParams = utils::setJsonAttr($this->additionnalParams, $_key, $_value);
	}
	
}
