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
require_once __DIR__ . '/../php/core.inc.php';

class utils {
	/*     * *************************Attributs****************************** */
	
	private static $properties = array();
	private static $jeedom_encryption = null;
	
	/*     * ***********************Methode static*************************** */
	
	public static function attrChanged($_changed,$_old,$_new){
		if($_changed){
			return true;
		}
		if(is_array($_old)){
			$_old = json_encode($_old);
		}
		if(is_array($_new)){
			$_new = json_encode($_new);
		}
		return ($_old != $_new);
	}
	
	public static function o2a($_object, $_noToArray = false) {
		if (is_array($_object)) {
			$return = array();
			foreach ($_object as $object) {
				$return[] = self::o2a($object);
			}
			return $return;
		}
		$array = array();
		if (!is_object($_object)) {
			return $array;
		}
		if (!$_noToArray && method_exists($_object, 'toArray')) {
			return $_object->toArray();
		}
		$class = get_class($_object);
		if (!isset(self::$properties[$class])) {
			self::$properties[$class] = (new ReflectionClass($class))->getProperties();
		}
		foreach (self::$properties[$class] as $property) {
			$name = $property->getName();
			if ('_' !== $name[0]) {
				$method = 'get' . ucfirst($name);
				if (method_exists($_object, $method)) {
					$value = $_object->$method();
				} else {
					$property->setAccessible(true);
					$value = $property->getValue($_object);
					$property->setAccessible(false);
				}
				$array[$name] = ($value === null) ? null : is_json($value, $value);
			}
		}
		return $array;
	}
	
	public static function a2o(&$_object, $_data) {
		if (is_array($_data)) {
			foreach ($_data as $key => $value) {
				$method = 'set' . ucfirst($key);
				if (method_exists($_object, $method)) {
					$function = new ReflectionMethod($_object, $method);
					$value = is_json($value, $value);
					if (is_array($value)) {
						if ($function->getNumberOfRequiredParameters() == 2) {
							foreach ($value as $arrayKey => $arrayValue) {
								if (is_array($arrayValue)) {
									if ($function->getNumberOfRequiredParameters() == 3) {
										foreach ($arrayValue as $arrayArraykey => $arrayArrayvalue) {
											$_object->$method($arrayKey, $arrayArraykey, $arrayArrayvalue);
										}
										continue;
									}
								}
								$_object->$method($arrayKey, $arrayValue);
							}
						} else {
							$_object->$method(json_encode($value, JSON_UNESCAPED_UNICODE));
						}
					} else {
						if ($function->getNumberOfRequiredParameters() < 2) {
							$_object->$method($value);
						}
					}
				}
			}
		}
	}
	
	public static function processJsonObject($_class, $_ajaxList, $_dbList = null) {
		if (!is_array($_ajaxList)) {
			if (is_json($_ajaxList)) {
				$_ajaxList = json_decode($_ajaxList, true);
			} else {
				throw new Exception('Invalid json : ' . print_r($_ajaxList, true));
			}
		}
		if (!is_array($_dbList)) {
			if (!class_exists($_class)) {
				throw new Exception('Invalid class : ' . $_class);
			}
			$_dbList = $_class::all();
		}
		
		$enableList = array();
		foreach ($_ajaxList as $ajaxObject) {
			$object = $_class::byId($ajaxObject['id']);
			if (!is_object($object)) {
				$object = new $_class();
			}
			self::a2o($object, $ajaxObject);
			$object->save();
			$enableList[$object->getId()] = true;
		}
		foreach ($_dbList as $dbObject) {
			if (!isset($enableList[$dbObject->getId()])) {
				$dbObject->remove();
			}
		}
	}
	
	public static function setJsonAttr($_attr, $_key, $_value = null) {
		if ($_value === null && !is_array($_key)) {
			if (!is_array($_attr)) {
				$_attr = is_json($_attr, array());
			}
			unset($_attr[$_key]);
		} else {
			if (!is_array($_attr)) {
				$_attr = is_json($_attr, array());
			}
			if (is_array($_key)) {
				$_attr = array_merge($_attr, $_key);
			} else {
				$_attr[$_key] = $_value;
			}
		}
		return $_attr;
	}
	
	public static function getJsonAttr(&$_attr, $_key = '', $_default = '') {
		if (is_array($_attr)) {
			if ($_key == '') {
				return $_attr;
			}
		} else {
			if ($_key == '') {
				return is_json($_attr, array());
			}
			if ($_attr === '') {
				if (is_array($_key)) {
					foreach ($_key as $key) {
						$return[$key] = $_default;
					}
					return $return;
				}
				return $_default;
			}
			$_attr = json_decode($_attr, true);
		}
		if (is_array($_key)) {
			$return = array();
			foreach ($_key as $key) {
				$return[$key] = (isset($_attr[$key]) && $_attr[$key] !== '') ? $_attr[$key] : $_default;
			}
			return $return;
		}
		return (isset($_attr[$_key]) && $_attr[$_key] !== '') ? $_attr[$_key] : $_default;
	}
	
	/*     * ******************Encrypt/decrypt*************************** */
	public static function getEncryptionPassword(){
		if(self::$jeedom_encryption == null){
			if(!file_exists(__DIR__.'/../../data/jeedom_encryption.key')){
				file_put_contents(__DIR__.'/../../data/jeedom_encryption.key',config::genKey());
			}
			self::$jeedom_encryption = file_get_contents(__DIR__.'/../../data/jeedom_encryption.key');
		}
		return self::$jeedom_encryption;
	}
	
	public static function encrypt($plaintext, $password = null) {
		if($plaintext == ''){
			return $plaintext;
		}
		if(strpos($plaintext,'crypt:') !== false){
			return $plaintext;
		}
		if($password == null){
			$password = self::getEncryptionPassword();
		}
		$iv = openssl_random_pseudo_bytes(16);
		$ciphertext = openssl_encrypt($plaintext, "AES-256-CBC", hash('sha256', $password, true), OPENSSL_RAW_DATA, $iv);
		$hmac = hash_hmac('sha256', $ciphertext.$iv, hash('sha256', $password, true), true);
		return 'crypt:'.base64_encode($iv.$hmac.$ciphertext);
	}
	
	public static function decrypt($ciphertext, $password = null) {
		if($password == null){
			$password = self::getEncryptionPassword();
		}
		if(strpos($ciphertext,'crypt:') === false){
			return $ciphertext;
		}
		$ciphertext = base64_decode(str_replace('crypt:','',$ciphertext));
		if (!hash_equals(hash_hmac('sha256', substr($ciphertext, 48).substr($ciphertext, 0, 16), hash('sha256', $password, true), true), substr($ciphertext, 16, 32))) return null;
		return openssl_decrypt(substr($ciphertext, 48), "AES-256-CBC", hash('sha256', $password, true), OPENSSL_RAW_DATA, substr($ciphertext, 0, 16));
	}
	
	/*     * *********************Methode d'instance************************* */
	
	/*     * **********************Getteur Setteur*************************** */
}
