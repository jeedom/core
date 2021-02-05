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
	private float $startTime;
	private string $applicationName;
	private array $additionnalParams = array();

	/*     * ********Static******************* */

    /**
     *
     * @param string $_jsonrpc
     */
	public function __construct(string $_jsonrpc) {
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

    /**
     * @param $_code
     * @param $_message
     */
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

    /**
     * @param string $_result
     */
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

    /**
     * @return float
     */
    public function getStartTime(): float
    {
		return $this->startTime;
	}

    /**
     * @return string
     */
    public function getApplicationName(): string
    {
		return $this->applicationName;
	}

    /**
     * @return mixed
     */
    public function getJsonrpc() {
		return $this->jsonrpc;
	}

    /**
     * @return string
     */
    public function getMethod(): string
    {
		return $this->method;
	}

    /**
     * @return array
     */
    public function getParams(): array
    {
		return $this->params;
	}

    /**
     * @return int
     */
    public function getId(): int
    {
		return $this->id;
	}

    /**
     * @param $applicationName
     * @return $this
     */
    public function setApplicationName($applicationName): jsonrpc
    {
		$this->applicationName = $applicationName;
		return $this;
	}

    /**
     * @param string $_key
     * @param string $_default
     * @return array|bool|mixed|string
     */
    public function getAdditionnalParams($_key = '', $_default = '') {
		return utils::getJsonAttr($this->additionnalParams, $_key, $_default);
	}

    /**
     * @param $_key
     * @param $_value
     */
    public function setAdditionnalParams($_key, $_value) {
		if (in_array($_key, array('result', 'jsonrpc', 'id'))) {
			return;
		}
		$this->additionnalParams = utils::setJsonAttr($this->additionnalParams, $_key, $_value);
	}

}
