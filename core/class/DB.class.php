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

class DB {
	/*     * **************  Constantes  ***************** */

	const FETCH_TYPE_ROW = 0;
	const FETCH_TYPE_ALL = 1;

	/*     * **************  Attributs  ***************** */

	private $connection;
	private $lastConnection;
	private static $sharedInstance;

	/*     * **************  Fonctions statiques  ***************** */

	private function __construct() {
		global $CONFIG;
		try {
			$this->connection = new PDO('mysql:host=' . $CONFIG['db']['host'] . ';port=' . $CONFIG['db']['port'] . ';dbname=' . $CONFIG['db']['dbname'], $CONFIG['db']['username'], $CONFIG['db']['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', PDO::ATTR_PERSISTENT => true));
		} catch (Exception $e) {
			if ($CONFIG['db']['host'] != '127.0.0.1' && $CONFIG['db']['host'] != 'localhost') {
				throw $e;
			}
			if (strpos($e->getMessage(), 'Can\'t connect to local MySQL server through socket') !== false || strpos($e->getMessage(), 'SQLSTATE[HY000] [2002] No such file or directory') !== false) {
				shell_exec('sudo /etc/init.d/mysql restart  >> /dev/null 2>&1');
				log::add('mysql', 'error', 'There is an issue on database, I try to restart them');
			}
			$this->connection = new PDO('mysql:host=' . $CONFIG['db']['host'] . ';port=' . $CONFIG['db']['port'] . ';dbname=' . $CONFIG['db']['dbname'], $CONFIG['db']['username'], $CONFIG['db']['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', PDO::ATTR_PERSISTENT => true));
		}
	}

	public static function getLastInsertId() {
		if (!isset(self::$sharedInstance)) {
			throw new Exception('DB : Aucune connection active - impossible d\'avoir le dernier ID inséré');
		}
		return self::$sharedInstance->connection->lastInsertId();
	}

	public static function getConnection() {
		if (!isset(self::$sharedInstance)) {
			self::$sharedInstance = new self();
		} else if (self::$sharedInstance->lastConnection + 59 < strtotime('now')) {
			try {
				if (!self::$sharedInstance->connection->query('select 1;')) {
					self::$sharedInstance = new self();
				}
			} catch (Exception $e) {
				self::$sharedInstance = new self();
			}
		}
		self::$sharedInstance->lastConnection = strtotime('now');
		return self::$sharedInstance->connection;
	}

	public static function &CallStoredProc($_procName, $_params, $_fetch_type, $_className = NULL, $_fetch_opt = NULL) {
		$bind_params = '';
		foreach ($_params as $value) {
			$bind_params .= '?, ';
		}
		$bind_params = trim($bind_params, ', ');
		if ($_className != NULL && class_exists($_className)) {
			return self::Prepare("CALL $_procName($bind_params)", $_params, $_fetch_type, PDO::FETCH_CLASS, $_className);
		} else if ($_fetch_opt != NULL) {
			return self::Prepare("CALL $_procName($bind_params)", $_params, $_fetch_type, $_fetch_opt, $_className);
		} else {
			return self::Prepare("CALL $_procName($bind_params)", $_params, $_fetch_type);
		}

	}

	public static function &Prepare($_query, $_params, $_fetchType = self::FETCH_TYPE_ROW, $_fetch_param = PDO::FETCH_ASSOC, $_fetch_opt = NULL) {
		$stmt = self::getConnection()->prepare($_query);
		$res = NULL;

		if ($stmt != false && $stmt->execute($_params) != false) {
			if ($_fetchType == self::FETCH_TYPE_ROW) {
				if ($_fetch_opt == NULL) {
					$res = $stmt->fetch($_fetch_param);
				} else if ($_fetch_param == PDO::FETCH_CLASS) {
					$res = $stmt->fetchObject($_fetch_opt);
				}
			} else {
				if ($_fetch_opt == NULL) {
					$res = $stmt->fetchAll($_fetch_param);
				} else {
					$res = $stmt->fetchAll($_fetch_param, $_fetch_opt);
				}
			}
		}

		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0000) {
			if (strpos($errorInfo[2], 'Can\'t connect to local MySQL server through socket') !== false) {
				shell_exec('/bin/bash ' . dirname(__FILE__) . '/../../script/check_mysql.sh >> ' . dirname(__FILE__) . '/../../log/watchdog 2>&1');
			}
			throw new Exception('[MySQL] Error code : ' . $errorInfo[0] . ' (' . $errorInfo[1] . '). ' . $errorInfo[2]);
		}
		return $res;
	}

	public function __clone() {
		trigger_error('DB : Cloning this object is not permitted', E_USER_ERROR);
	}

	public function optimize() {
		$tables = self::Prepare("SELECT TABLE_NAME FROM information_schema.TABLES WHERE Data_Free > 0", array(), DB::FETCH_TYPE_ALL);
		foreach ($tables as $table) {
			$table = array_values($table);
			$table = $table[0];
			self::Prepare('OPTIMIZE TABLE `' . $table . '`', array(), DB::FETCH_TYPE_ROW);
		}
	}

	/**
	 * Saves an entity inside the repository. If the entity is new a new row
	 * will be created. If the entity is not new the row will be updated.
	 *
	 * @param object $object
	 * @return boolean
	 */
	public static function save($object, $_direct = false, $_replace = false) {
		if (!$_direct && method_exists($object, 'preSave')) {
			$object->preSave();
		}
		if (!self::getField($object, 'id')) {
			//New object to save.
			if (!$_direct && method_exists($object, 'preInsert')) {
				$object->preInsert();
			}
			list($sql, $parameters) = self::buildQuery($object);
			if ($_replace) {
				$sql = 'REPLACE INTO `' . self::getTableName($object) . '` SET ' . implode(', ', $sql);
			} else {
				$sql = 'INSERT INTO `' . self::getTableName($object) . '` SET ' . implode(', ', $sql);
			}
			$res = self::Prepare($sql, $parameters, DB::FETCH_TYPE_ROW);
			$reflection = self::getReflectionClass($object);
			if ($reflection->hasProperty('id')) {
				self::setField($object, 'id', self::getLastInsertId());
			}
			if (!$_direct && method_exists($object, 'postInsert')) {
				$object->postInsert();
			}
		} else {
			//Object to update.
			if (!$_direct && method_exists($object, 'preUpdate')) {
				$object->preUpdate();
			}
			list($sql, $parameters) = self::buildQuery($object);
			if (!$_direct && method_exists($object, 'getId')) {
				$parameters['id'] = $object->getId(); //override if necessary
			}
			$sql = 'UPDATE `' . self::getTableName($object) . '` SET ' . implode(', ', $sql) . ' WHERE id = :id';
			$res = self::Prepare($sql, $parameters, DB::FETCH_TYPE_ROW);
			if (!$_direct && method_exists($object, 'postUpdate')) {
				$object->postUpdate();
			}
		}
		if (!$_direct && method_exists($object, 'postSave')) {
			$object->postSave();
		}
		return null !== $res && false !== $res;
	}

	public static function refresh($object) {
		if (!self::getField($object, 'id')) {
			throw new Exception('DB cannot refresh object without id');
		}
		$parameters = array('id' => self::getField($object, 'id'));
		$sql = 'SELECT ' . self::buildField(get_class($object)) .
		' FROM `' . self::getTableName($object) . '` ' .
		' WHERE ';
		foreach ($parameters as $field => $value) {
			if ($value != '') {
				$sql .= '`' . $field . '`=:' . $field . ' AND ';
			} else {
				unset($parameters[$field]);
			}
		}
		$sql .= '1';
		$newObject = self::Prepare($sql, $parameters, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, get_class($object));
		if (!is_object($newObject)) {
			return false;
		}
		foreach (self::getFields($object) as $field) {
			$reflection = self::getReflectionClass($object);
			$property = $reflection->getProperty($field);
			if (!$reflection->hasProperty($field)) {
				throw new InvalidArgumentException('Unknown field ' . get_class($object) . '::' . $field);
			}
			$property->setAccessible(true);
			$property->setValue($object, self::getField($newObject, $field));
			$property->setAccessible(false);
		}
		return true;
	}

	/**
	 * Retourne une liste d'objets ou un objet en fonction de filtres
	 * @param $_filters Filtres à appliquer
	 * @param $_object Objet sur lequel appliquer les filtres
	 * @return Objet ou liste d'objets correspondant à la requête
	 */
	public static function getWithFilter($_filters, $_object) {
		// check filters
		if (!is_array($_filters)) {
			throw new Exception('Filter sent isn\'t an array.');
		}
		// operators have to remain in this order. If you put '<' before '<=', algorithm won't make the difference & will think a '<=' is a '<'
		$operators = array('!=', '<=', '>=', '<', '>', 'NOT LIKE', 'LIKE', '=');
		$fields = self::getFields($_object);
		$class = self::getReflectionClass($_object)->getName();
		// create query
		$query = 'SELECT ' . self::buildField($class) . ' FROM ' . $class . '';
		$values = array();
		$where = ' WHERE ';
		foreach ($fields as $property) {
			foreach ($_filters as $key => $value) {
				if ($property == $key && $value != '') {
					// traitement à faire sur value pour obtenir l'opérateur
					$thereIsOperator = false;
					$operatorInformation = array(
						'index' => -1,
						'value' => '=', // by default '='
						'length' => 0,
					);
					foreach ($operators as $operator) {
						if (($index = strpos($value, $operator)) !== false) {
							$thereIsOperator = true;
							$operatorInformation['index'] = $index;
							$operatorInformation['value'] = $operator;
							$operatorInformation['length'] = strlen($operator);
							break;
						}
					}
					if ($thereIsOperator) {
						// extract operator from value
						$value = substr($value, $operatorInformation['length'] + 1); // +1 because of space
						// add % % to LIKE operator
						if (in_array($operatorInformation['value'], array('LIKE', 'NOT LIKE'))) {
							$value = '%' . $value . '%';
						}
					}

					$where .= $property . ' ' . $operatorInformation['value'] . ' :' . $property . ' AND ';
					$values[$property] = $value;
					break;
				}
			}
		}
		if ($where != ' WHERE ') {
			$where = substr($where, 0, strlen($where) - 5); // on enlève le dernier ' AND '
			$query .= $where;
		}
		// si values contient id, on sait qu'il n'y aura au plus qu'une valeur
		return self::Prepare($query . ';', $values, in_array('id', $values) ? self::FETCH_TYPE_ROW : self::FETCH_TYPE_ALL);
	}

	/**
	 * Deletes an entity.
	 *
	 * @param object $object
	 * @return boolean
	 */
	public static function remove($object) {
		if (method_exists($object, 'preRemove')) {
			if ($object->preRemove() === false) {
				return false;
			}
		}
		list($sql, $parameters) = self::buildQuery($object);
		$sql = 'DELETE FROM `' . self::getTableName($object) . '` WHERE ';
		foreach ($parameters as $field => $value) {
			if ($value != '') {
				$sql .= '`' . $field . '`=:' . $field . ' AND ';
			} else {
				unset($parameters[$field]);
			}
		}
		$sql .= '1';
		$res = self::Prepare($sql, $parameters, DB::FETCH_TYPE_ROW);
		$reflection = self::getReflectionClass($object);
		if ($reflection->hasProperty('id')) {
			self::setField($object, 'id', null);
		}
		if (method_exists($object, 'postRemove')) {
			$object->postRemove();
		}
		return null !== $res && false !== $res;
	}

	public static function checksum($_table) {
		$sql = 'CHECKSUM TABLE ' . $_table;
		$result = self::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		return $result['Checksum'];
	}

	/**
	 * Lock an entity.
	 *
	 * @param object $object
	 * @return boolean
	 */
	public static function lock($object) {
		if (method_exists($object, 'preLock')) {
			if ($object->preLock() === false) {
				return false;
			}
		}
		list($sql, $parameters) = self::buildQuery($object);
		$sql = 'SELECT * FROM ' . self::getTableName($object) . ' WHERE ';
		foreach ($parameters as $field => $value) {
			if ($value != '') {
				$sql .= '`' . $field . '`=:' . $field . ' AND ';
			} else {
				unset($parameters[$field]);
			}
		}
		$sql .= '1 LOCK IN SHARE MODE';
		$res = self::Prepare($sql, $parameters, DB::FETCH_TYPE_ROW);
		if (method_exists($object, 'postLock')) {
			$object->postLock();
		}
		return null !== $res && false !== $res;
	}

	/**
	 * Returns the name of the table where to save entities.
	 *
	 * @return string
	 */
	private static function getTableName($object) {
		if (method_exists($object, 'getTableName')) {
			return $object->getTableName();
		}
		return get_class($object);
	}

	/**
	 *
	 *
	 * @param type $object
	 * @return type
	 * @throws RuntimeException
	 */
	private static function getFields($object) {
		if (is_object($object)) {
			$reflection = self::getReflectionClass($object);
		} else {
			$reflection = new ReflectionClass($object);
		}
		$properties = $reflection->getProperties();
		$fields = array();
		foreach ($properties as $property) {
			$name = $property->getName();
			if ('_' !== $name[0]) {
				$fields[] = $name;
			}
		}
		if (empty($fields)) {
			//Edge case that can be critical in some cases.
			throw new RuntimeException('No fields found for class ' . get_class($object));
		}
		return $fields;
	}

	/**
	 * Forces the value of a field of a given object, even if this field is
	 * not accessible.
	 *
	 * @param object $object The entity to alter
	 * @param string $field The name of the member to alter
	 * @param mixed $value The value to give to the member
	 */
	private static function setField($object, $field, $value) {
		$method = 'set' . ucfirst($field);
		if (method_exists($object, $method)) {
			$object->$method($value);
		} else {
			$reflection = self::getReflectionClass($object);
			if ($reflection->hasProperty($field)) {
				throw new InvalidArgumentException('Unknown field ' . get_class($object) . '::' . $field);
			}
			$property = $reflection->getProperty($field);
			$property->setAccessible(true);
			$property->setValue($object, $value);
			$property->setAccessible(false);
		}
	}

	/**
	 * Builds the elements for an SQL query. It will return two lists, the
	 * first being the list of parts "key=:key" to inject in the SQL, the
	 * second being the mapping of these parameters to the values.
	 *
	 * @param type $object
	 * @return type
	 */
	private static function buildQuery($object) {
		$parameters = array();
		$sql = array();
		foreach (self::getFields($object) as $field) {
			$sql[] = '`' . $field . '` = :' . $field;
			$parameters[$field] = self::getField($object, $field);
		}
		return array($sql, $parameters);
	}

	/**
	 * Returns the value of a field of a given object. It'll try to use a
	 * getter first if defined. If not defined, we'll use the reflection API.
	 *
	 * @param type $object
	 * @param type $field
	 * @return type
	 * @throws RuntimeException if the getter is not defined
	 */
	private static function getField($object, $field) {
		$retval = null;
		$method = 'get' . ucfirst($field);
		if (method_exists($object, $method)) {
			$retval = $object->$method();
		} else {
			$reflection = self::getReflectionClass($object);
			if ($reflection->hasProperty($field)) {
				$property = $reflection->getProperty($field);
				$property->setAccessible(true);
				$retval = $property->getValue($object);
				$property->setAccessible(false);
			}
		}
		if (is_array($retval) || is_object($retval)) {
			$retval = json_encode($retval, JSON_UNESCAPED_UNICODE);
		}
		return $retval;
	}

	/**
	 * Returns the reflection class for the given object.
	 *
	 * @param  object $object
	 * @return ReflectionClass
	 */
	private static function getReflectionClass($object) {
		$reflections = array();
		$uuid = spl_object_hash($object);
		if (!isset($reflections[$uuid])) {
			$reflections[$uuid] = new ReflectionClass($object);
		}
		return $reflections[$uuid];
	}

	public static function buildField($_class, $_prefix = '') {
		$fields = array();
		foreach (self::getFields($_class) as $field) {
			if ('_' !== $field[0]) {
				if ($_prefix != '') {
					$fields[] = '`' . $_prefix . '`.' . '`' . $field . '`';
				} else {
					$fields[] = '`' . $field . '`';
				}
			}
		}
		return implode(', ', $fields);
	}

}

?>
