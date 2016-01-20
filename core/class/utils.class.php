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
require_once dirname(__FILE__) . '/../php/core.inc.php';

class utils {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	public static function o2a($_object) {
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
		$reflections = array();
		$uuid = spl_object_hash($_object);
		if (!class_exists(get_class($_object))) {
			return array();
		}
		if (!isset($reflections[$uuid])) {
			$reflections[$uuid] = new ReflectionClass($_object);
		}
		$reflection = $reflections[$uuid];
		$properties = $reflection->getProperties();
		foreach ($properties as $property) {
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
				if (is_json($value)) {
					$array[$name] = json_decode($value, true);
				} else {
					$array[$name] = $value;
				}
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
					if (is_json($value)) {
						$value = json_decode($value, true);
					}
					if (is_array($value)) {
						if ($function->getNumberOfRequiredParameters() == 2) {
							foreach ($value as $arrayKey => $arrayValue) {
								if (is_array($arrayValue)) {
									if ($function->getNumberOfRequiredParameters() == 3) {
										foreach ($arrayValue as $arrayArraykey => $arrayArrayvalue) {
											$_object->$method($arrayKey, $arrayArraykey, $arrayArrayvalue);
										}
									} else {
										$_object->$method($arrayKey, $arrayValue);
									}
								} else {
									$_object->$method($arrayKey, $arrayValue);
								}
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
		//ajout/modif
		foreach ($_ajaxList as $ajaxObject) {
			$object = $_class::byId($ajaxObject['id']);
			if (!is_object($object)) {
				$object = new $_class();
			}
			self::a2o($object, $ajaxObject);
			$object->save();
			$enableList[$object->getId()] = true;
		}
		//suppression des entrées non modifiées.
		foreach ($_dbList as $dbObject) {
			if (!isset($enableList[$dbObject->getId()])) {
				$dbObject->remove();
			}
		}
	}

	public static function setJsonAttr($_attr, $_key, $_value) {
		if ($_value === null) {
			if ($_attr != '' && is_json($_attr)) {
				$attr = json_decode($_attr, true);
				unset($attr[$_key]);
				$_attr = json_encode($attr, JSON_UNESCAPED_UNICODE);
			}
		} else {
			if ($_attr == '' || !is_json($_attr)) {
				$_attr = json_encode(array($_key => $_value), JSON_UNESCAPED_UNICODE);
			} else {
				$attr = json_decode($_attr, true);
				$attr[$_key] = $_value;
				$_attr = json_encode($attr, JSON_UNESCAPED_UNICODE);
			}
		}
		return $_attr;
	}

	public static function getJsonAttr($_attr, $_key = '', $_default = '') {
		if ($_key == '') {
			if ($_attr == '' || !is_json($_attr)) {
				return $_attr;
			}
			return json_decode($_attr, true);
		}
		if ($_attr === '') {
			return $_default;
		}
		$attr = json_decode($_attr, true);
		return (isset($attr[$_key]) && $attr[$_key] !== '') ? $attr[$_key] : $_default;
	}

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */
}

?>
