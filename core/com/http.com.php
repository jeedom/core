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

/* ------------------------------------------------------------ Inclusions */
require_once __DIR__ . '/../../core/php/core.inc.php';

class com_http {
	/*     * ***********************Attributs************************* */
	
	private $url;
	private $username = '';
	private $password = '';
	private $logError = false;
	private $ping = false;
	private $noSslCheck = true;
	private $sleepTime = 500000;
	private $post = '';
	private $put = '';
	private $delete = '';
	private $header = array('Connection: close');
	private $cookiesession = false;
	private $allowEmptyReponse = false;
	private $noReportError = false;
	private $userAgent = '';
	private $CURLOPT_HTTPAUTH = '';
	private $CURLOPT = array();
	
	/*     * ********************Fonctions statiques********************* */
	
	public function __construct($_url = '', $_username = '', $_password = '') {
		$this->url = $_url;
		$this->username = $_username;
		$this->password = $_password;
	}
	
	/*     * ************* Fonctions ************************************ */
	
	/**
	*
	* @param int $_timeout
	* @param int $_maxRetry
	* @return string
	* @throws Exception
	*/
	public function exec($_timeout = 2, $_maxRetry = 3) {
		$nbRetry = 0;
		while ($nbRetry < $_maxRetry) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			if ($this->getNoSslCheck()) {
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			}
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeader());
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			if ($_timeout < 1 && $_timeout > 0) {
				curl_setopt($ch, CURLOPT_TIMEOUT_MS, $_timeout * 1000);
			} else {
				curl_setopt($ch, CURLOPT_TIMEOUT, $_timeout);
			}
			if ($this->getCookiesession()) {
				curl_setopt($ch, CURLOPT_COOKIESESSION, true);
			} else {
				curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
				curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
			}
			if ($this->username != '') {
				curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
				if ($this->getCURLOPT_HTTPAUTH() != '') {
					curl_setopt($ch, CURLOPT_HTTPAUTH, $this->getCURLOPT_HTTPAUTH());
				}
			}
			if ($this->getPost() != '') {
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getPost());
			}
			if ($this->getPut() != '') {
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getPut());
			}
			if ($this->getDelete() != '') {
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getDelete());
			}
			if ($this->getUserAgent() != '') {
				curl_setopt($ch, CURLOPT_USERAGENT, $this->getUserAgent());
			}
			if(count($this->getCURLOPT()) > 0){
				foreach ($this->getCURLOPT() as $key => $value) {
					curl_setopt($ch, $key,$value);
				}
			}
			$response = curl_exec($ch);
			$nbRetry++;
			if (curl_errno($ch) && $nbRetry < $_maxRetry) {
				curl_close($ch);
				usleep($this->getSleepTime());
			} else {
				$nbRetry = $_maxRetry + 1;
			}
		}
		if (!isset($response)) {
			$response = '';
		}
		if (isset($ch) && is_resource($ch)) {
			if (curl_errno($ch)) {
				$curl_error = curl_error($ch);
				curl_close($ch);
				if ($this->getNoReportError() === false && $this->getAllowEmptyReponse() === true && strpos($curl_error, 'Empty reply from server') !== false) {
					return $response;
				}
				if ($this->getNoReportError() === false && $this->getLogError()) {
					log::add('http.com', 'error', __('Erreur cURL :', __FILE__) . ' ' . $curl_error . ' ' . __('sur la commande', __FILE__) . ' ' . $this->url . ' ' . __('après', __FILE__) . ' ' . $nbRetry . ' ' . __('relance(s)', __FILE__));
				}
				if ($this->getNoReportError() === false) {
					throw new Exception(__('Echec de la requête HTTP :', __FILE__) . ' ' . $this->url . ' cURL error : ' . $curl_error, 404);
				}
			} else {
				curl_close($ch);
			}
		}
		$ch = null;
		return $response;
	}
	
	public function getPing() {
		return $this->ping;
	}
	
	public function getNoSslCheck() {
		return $this->noSslCheck;
	}
	
	public function setPing($ping) {
		$this->ping = $ping;
		return $this;
	}
	
	public function setNoSslCheck($noSslCHeck) {
		$this->noSslCheck = $noSslCHeck;
		return $this;
	}
	
	public function getLogError() {
		return $this->logError;
	}
	
	public function setLogError($logError) {
		$this->logError = $logError;
		return $this;
	}
	
	public function getSleepTime() {
		return $this->sleepTime;
	}
	
	public function setSleepTime($sleepTime) {
		$this->sleepTime = $sleepTime * 1000000;
		return $this;
	}
	
	public function getPost() {
		return $this->post;
	}
	
	public function setPost($post = array()) {
		$this->post = $post;
		return $this;
	}
	
	public function getHeader() {
		return $this->header;
	}
	
	public function setHeader($header) {
		$this->header = $header;
		return $this;
	}
	
	public function getCookiesession() {
		return $this->cookiesession;
	}
	
	public function setCookiesession($cookiesession) {
		$this->cookiesession = $cookiesession;
		return $this;
	}
	
	public function getAllowEmptyReponse() {
		return $this->allowEmptyReponse;
	}
	
	public function setAllowEmptyReponse($allowEmptyReponse) {
		$this->allowEmptyReponse = $allowEmptyReponse;
		return $this;
	}
	
	public function getNoReportError() {
		return $this->noReportError;
	}
	
	public function setNoReportError($noReportError) {
		$this->noReportError = $noReportError;
		return $this;
	}
	
	public function getUrl() {
		return $this->url;
	}
	
	public function setUrl($url) {
		$this->url = $url;
		return $this;
	}
	
	public function getCURLOPT_HTTPAUTH() {
		return $this->CURLOPT_HTTPAUTH;
	}
	
	public function setCURLOPT_HTTPAUTH($CURLOPT_HTTPAUTH) {
		$this->CURLOPT_HTTPAUTH = $CURLOPT_HTTPAUTH;
		return $this;
	}
	
	public function getPut() {
		return $this->put;
	}
	
	public function setPut($put = array()) {
		$this->put = $put;
		return $this;
	}
	
	public function getUserAgent() {
		return $this->userAgent;
	}
	
	public function setUserAgent($userAgent) {
		$this->userAgent = $userAgent;
		return $this;
	}
	
	public function getDelete() {
		return $this->delete;
	}
	
	public function setDelete($delete = array()) {
		$this->delete = $delete;
		return $this;
	}
	
	public function getCURLOPT() {
		return $this->CURLOPT;
	}
	
	public function setCURLOPT($CURLOPT) {
		$this->CURLOPT = $CURLOPT;
		return $this;
	}
	
}
