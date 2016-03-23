<?php

/**
 * Description of JSONRPC client
 *
 * @author Loïc Gevrey
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

	function __construct($_apiAddr, $_apikey, $_options = array()) {
		$this->apiAddr = $_apiAddr;
		$this->apikey = $_apikey;
		$this->options = $_options;
	}

	public function sendRequest($_method, $_params = null, $_timeout = 15, $_file = null, $_maxRetry = 2) {
		$_params['apikey'] = $this->apikey;
		$_params = array_merge($_params, $this->options);
		$request = array(
			'request' => json_encode(array(
				'jsonrpc' => '2.0',
				'id' => rand(1, 9999),
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
					$callback_class::$callback_function($this->result);
				}
			} elseif ($this->getCb_function() != '') {
				$callback_function = $this->getCb_function();
				if (function_exists($callback_function)) {
					$callback_function($this->result);
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
			$_timeout = 1200;
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
	}

	public function setCb_class($cb_class) {
		$this->cb_class = $cb_class;
	}

	public function setCertificate_path($certificate_path) {
		$this->certificate_path = $certificate_path;
	}

	public function getCertificate_path() {
		return $this->certificate_path;
	}

	public function setDisable_ssl_verifiy($noSslCheck) {
		$this->noSslCheck = $noSslCheck;
	}

	public function getNoSslCheck() {
		return $this->noSslCheck;
	}

	public function setNoSslCheck($noSslCHeck) {
		$this->noSslCheck = $noSslCHeck;
	}

}

?>
