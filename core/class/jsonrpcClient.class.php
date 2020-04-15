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

class jsonrpcClient {
	/*     * ********Attributs******************* */

	private $errorCode = 9999;
	private $errorMessage = 'No error';
	private $error = 'No error';
	private $result;
	private $rawResult;
	private $apikey = '';
	private $options = array();
	private $apiAddr;
	private $cb_function = '';
	private $cb_class = '';
	private $certificate_path = '';
	private $noSslCheck = false;

	/*     * ********Static******************* */

	/**
	 *
	 * @param type $_apiAddr
	 * @param type $_apikey
	 * @param type $_options
	 */
	public function __construct($_apiAddr, $_apikey, $_options = array()) {
		$this->apiAddr = $_apiAddr;
		$this->apikey = $_apikey;
		$this->options = $_options;
	}
	/**
	 *
	 * @param type $_method
	 * @param array $_params
	 * @param int $_timeout
	 * @param type $_file
	 * @param int $_maxRetry
	 * @return boolean
	 */
	public function sendRequest($_method, $_params = null, $_timeout = 60, $_file = null, $_maxRetry = 2) {
		$_params['apikey'] = $this->apikey;
		$_params = array_merge($_params, $this->options);
		$request = array(
			'request' => json_encode(array(
				'jsonrpc' => '2.0',
				'id' => mt_rand(1, 9999),
				'method' => $_method,
				'params' => $_params,
			)));
		$this->rawResult = preg_replace('/[^[:print:]]/', '', trim($this->send($request, $_timeout, $_file, $_maxRetry)));

		if ($this->rawResult === false) {
			return false;
		}

		if (!((is_string($this->rawResult) && (is_object(json_decode($this->rawResult)) || is_array(json_decode($this->rawResult)))))) {
			if ($this->error == 'No error' || $this->error == '') {
				$this->error = '9999<br/>Reponse is not json : ' . $this->rawResult;
			}
			$this->errorMessage = $this->rawResult;
			return false;
		}
		$result = json_decode(trim($this->rawResult), true);
		if (isset($result['result'])) {
			$this->result = $result['result'];
			if ($this->getCb_class() != '') {
				$callback_class = $this->getCb_class();
				$callback_function = $this->getCb_function();
				if (method_exists($callback_class, $callback_function)) {
					$callback_class::$callback_function($result);
				}
			} elseif ($this->getCb_function() != '') {
				$callback_function = $this->getCb_function();
				if (function_exists($callback_function)) {
					$callback_function($result);
				}
			}
			return true;
		} else {
			if (isset($result['error']['code'])) {
				$this->error = 'Code : ' . $result['error']['code'];
				$this->errorCode = $result['error']['code'];
			}
			if (isset($result['error']['message'])) {
				$this->error .= '<br/>Message : ' . $result['error']['message'];
				$this->errorMessage = $result['error']['message'];
			}
			return false;
		}
	}

	private function send($_request, $_timeout = 15, $_file = null, $_maxRetry = 2) {
		if ($_file !== null) {
			if (version_compare(phpversion(), '5.5.0', '>=')) {
				foreach ($_file as $key => $value) {
					$_request[$key] = new CurlFile(str_replace('@', '', $value));
				}
			} else {
				$_request = array_merge($_request, $_file);
			}
			if ($_timeout < 1200) {
				$_timeout = 1200;
			}
		}
		$nbRetry = 0;
		while ($nbRetry < $_maxRetry) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->apiAddr);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_TIMEOUT, $_timeout);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $_request);
			curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
			if ($this->getCertificate_path() != '' && file_exists($this->getCertificate_path())) {
				curl_setopt($ch, CURLOPT_CAINFO, $this->getCertificate_path());
			}
			if ($this->getNoSslCheck()) {
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			}
			if(config::byKey('proxyEnabled')) {
				if(config::byKey('proxyAddress') == ''){ 
				// throw new Exception(__('renseigne l\'adresse', __FILE__));
				$this->error = 'Erreur address ';
			} else if (config::byKey('proxyPort') == ''){
			// throw new Exception(__('renseigne le port', __FILE__));
			} else {
				curl_setopt($ch, CURLOPT_PROXY, config::byKey('proxyAddress'));
				curl_setopt($ch, CURLOPT_PROXYPORT, config::byKey('proxyPort'));
				if(!empty(config::byKey('proxyLogin') || config::byKey('proxyPassword'))){
					curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'proxyLogin:proxyPassword');
				}
				log::add('Connection', 'debug', $ch);
			} 
		}
			$response = curl_exec($ch);
			$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$nbRetry++;
			if (curl_errno($ch) && $nbRetry < $_maxRetry) {
				curl_close($ch);
				usleep(500000);
			} else {
				$nbRetry = $_maxRetry + 1;
			}
		}
		if ($http_status == 301) {
			if (preg_match('/<a href="(.*)">/i', $response, $r)) {
				$this->apiAddr = trim($r[1]);
				return $this->send($_request, $_timeout, $_file, $_maxRetry);
			}
		}
		if ($http_status != 200) {
			$this->error = 'Erreur http : ' . $http_status . ' Details : ' . $response;
		}
		if (curl_errno($ch)) {
			$this->error = 'Erreur curl sur : ' . $this->apiAddr . '. Détail :' . curl_error($ch);
		}
		curl_close($ch);
		return $response;
	}

	/*     * ********Getteur Setteur******************* */

	public function getError() {
		return $this->error;
	}

	public function getResult() {
		return $this->result;
	}

	public function getRawResult() {
		return $this->rawResult;
	}

	public function getErrorCode() {
		return $this->errorCode;
	}

	public function getErrorMessage() {
		return $this->errorMessage;
	}

	public function getCb_function() {
		return $this->cb_function;
	}

	public function getCb_class() {
		return $this->cb_class;
	}

	public function setCb_function($cb_function) {
		$this->cb_function = $cb_function;
		return $this;
	}

	public function setCb_class($cb_class) {
		$this->cb_class = $cb_class;
		return $this;
	}

	public function setCertificate_path($certificate_path) {
		$this->certificate_path = $certificate_path;
		return $this;
	}

	public function getCertificate_path() {
		return $this->certificate_path;
	}

	public function setDisable_ssl_verifiy($noSslCheck) {
		$this->noSslCheck = $noSslCheck;
		return $this;
	}

	public function getNoSslCheck() {
		return $this->noSslCheck;
	}

	public function setNoSslCheck($noSslCHeck) {
		$this->noSslCheck = $noSslCHeck;
		return $this;
	}

}
