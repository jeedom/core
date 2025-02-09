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

/**
 * Database management class providing ORM-like functionality
 *
 * Handles database connections, queries, and object persistence with transaction support
 *
 * @see \PDO For underlying database connection
 */
class DB {
	/*     * **************  Constantes  ***************** */

    /** @var int Return a single row from database */
	public const FETCH_TYPE_ROW = 0;

    /** @var int Return all rows from database */
	public const FETCH_TYPE_ALL = 1;

	/*     * **************  Attributs  ***************** */

    /** @var \PDO|null Database connection instance */
	private static $connection = null;

    /** @var int Timestamp of last connection */
	private static $lastConnection;

    /** @var array<string, array<string>> Cache of table fields */
	private static $fields = array();

	/*     * **************  Fonctions statiques  ***************** */

    /**
     * Initialize database connection
     *
     * @return void
     * @throws \PDOException When connection fails
     */
	private static function initConnection() {
		global $CONFIG;
		$_options = [
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
			PDO::ATTR_PERSISTENT => true,
			// silent mode, default for php7: https://www.php.net/manual/fr/pdo.error-handling.php
			// exception mode for debug
			PDO::ATTR_ERRMODE => (DEBUG ? PDO::ERRMODE_EXCEPTION : PDO::ERRMODE_SILENT),
		];
		if (isset($CONFIG['db']['unix_socket'])) {
			static::$connection = new PDO('mysql:unix_socket=' . $CONFIG['db']['unix_socket'] . ';dbname=' . $CONFIG['db']['dbname'],
			 $CONFIG['db']['username'], $CONFIG['db']['password'], $_options);
		} else {
			static::$connection = new PDO('mysql:host=' . $CONFIG['db']['host'] . ';port=' . $CONFIG['db']['port'] . ';dbname=' . $CONFIG['db']['dbname'], 
			$CONFIG['db']['username'], $CONFIG['db']['password'], $_options);
		}
	}

    /**
     * Get ID of last inserted row
     *
     * @return false|string
     */
	public static function getLastInsertId() {
		return static::getConnection()->lastInsertId();
	}

    /**
     * Get database connection, initializing if needed
     *
     * @return \PDO
     * @throws \PDOException When connection fails
     */
	public static function getConnection() {
		if (static::$connection == null) {
			static::initConnection();
			static::$lastConnection = strtotime('now');
		} elseif (static::$lastConnection + 120 < strtotime('now')) {
			try {
				$result = @static::$connection->query('select 1;');
				if (!$result) {
					static::initConnection();
				}
			} catch (Exception $e) {
				static::initConnection();
			}
			static::$lastConnection = strtotime('now');
		}
		return static::$connection;
	}

    /**
     * Execute a stored procedure
     *
     * @param string $_procName Procedure name
     * @param array<string, int|string> $_params Parameters
     * @param int $_fetch_type FETCH_TYPE constant
     * @param string|null $_className Optional classname for object hydration
     * @param int|null $_fetch_opt Optional PDO fetch mode
     * @return null|object|object[]|array<string, mixed>|array<string, mixed>[]
     * @throws \Exception When stored procedure execution fails
     */
	public static function &CallStoredProc($_procName, $_params, $_fetch_type, $_className = NULL, $_fetch_opt = NULL) {
		$bind_params = '';
		foreach ($_params as $value) {
			$bind_params .= '?, ';
		}
		$bind_params = trim($bind_params, ', ');
		if ($_className != NULL && class_exists($_className)) {
			return static::Prepare("CALL $_procName($bind_params)", $_params, $_fetch_type, PDO::FETCH_CLASS, $_className);
		} elseif ($_fetch_opt != NULL) {
			return static::Prepare("CALL $_procName($bind_params)", $_params, $_fetch_type, $_fetch_opt, $_className);
		} else {
			return static::Prepare("CALL $_procName($bind_params)", $_params, $_fetch_type);
		}
	}

    /**
     * Execute a prepared statement
     *
     * @param string $_query SQL query
     * @param array<string, scalar|null> $_params Parameters
     * @param int $_fetchType FETCH_TYPE constant
     * @param int $_fetch_param PDO fetch mode
     * @param string|null $_fetch_opt Optional classname for object hydration
     * @return null|object|object[]|array<string, mixed>|array<string, mixed>[]
     * @throws \Exception When query execution fails
     */
	public static function &Prepare($_query, $_params, $_fetchType = self::FETCH_TYPE_ROW, $_fetch_param = PDO::FETCH_ASSOC, $_fetch_opt = NULL) {
		$stmt = static::getConnection()->prepare($_query);
		$res = NULL;
		if ($stmt != false && $stmt->execute($_params) != false) {
			if(preg_match('/^update|^replace|^delete|^create|^drop|^alter|^truncate/i', $_query)){
				$errorInfo = $stmt->errorInfo();
				if ($errorInfo[0] != 0000) {
					static::$lastConnection = 0;
					throw new Exception('[MySQL] Error code : ' . $errorInfo[0] . ' (' . $errorInfo[1] . '). ' . $errorInfo[2] . '  : ' . $_query);
				}
				static::$lastConnection = strtotime('now');
				return $res;
			}
			if ($_fetchType == static::FETCH_TYPE_ROW) {
				if ($_fetch_opt === null) {
					$res = $stmt->fetch($_fetch_param);
				} elseif ($_fetch_param == PDO::FETCH_CLASS) {
					$res = $stmt->fetchObject($_fetch_opt);
				}
			} else {
				if ($_fetch_opt === null) {
					$res = $stmt->fetchAll($_fetch_param);
				} else {
					$res = $stmt->fetchAll($_fetch_param, $_fetch_opt);
				}
			}
		}
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0000) {
			static::$lastConnection = 0;
			throw new Exception('[MySQL] Error code : ' . $errorInfo[0] . ' (' . $errorInfo[1] . '). ' . $errorInfo[2] . '  : ' . $_query);
		}
		static::$lastConnection = strtotime('now');
		if ($_fetch_param == PDO::FETCH_CLASS) {
			if (is_array($res) && count($res) > 0) {
				foreach ($res as &$obj) {
					if (is_object($obj) && method_exists($obj, 'decrypt')) {
						$obj->decrypt();
						if (method_exists($obj, 'setChanged')) {
							$obj->setChanged(false);
						}
					}
				}
			} else {
				if (is_object($res) && method_exists($res, 'decrypt')) {
					$res->decrypt();
					if (method_exists($res, 'setChanged')) {
						$res->setChanged(false);
					}
				}
			}
		}
		return $res;
	}

    /**
     * Prevent cloning of class
     *
     * @return void
     */
	public function __clone() {
		trigger_error('DB : Cloner cet objet n\'est pas permis', E_USER_ERROR);
	}

    /**
     * Optimize database tables
     *
     * @return void
     * @throws \Exception When optimization fails
     */
	public static function optimize() {
		$tables = static::Prepare("SELECT TABLE_NAME FROM information_schema.TABLES WHERE Data_Free > 0", array(), DB::FETCH_TYPE_ALL);
		foreach ($tables as $table) {
			$table = array_values($table);
			$table = $table[0];
			static::Prepare('OPTIMIZE TABLE `' . $table . '`', array(), DB::FETCH_TYPE_ROW);
		}
	}

    /**
     * Start a database transaction
     *
     * @return void
     */
	public static function beginTransaction() {
		static::getConnection()->beginTransaction();
	}

    /**
     * Commit the current transaction
     *
     * @return void
     */
	public static function commit() {
		static::getConnection()->commit();
	}

    /**
     * Rollback the current transaction
     *
     * @return void
     */
	public static function rollBack() {
		static::getConnection()->rollBack();
	}

    /**
     * Save an entity in the database
     *
     * @param object $object Entity to save
     * @param bool $_direct Skip pre/post hooks
     * @param bool $_replace Use REPLACE instead of INSERT/UPDATE
     * @return bool Success status
     */
	public static function save($object, $_direct = false, $_replace = false) {
		if (!$_direct && method_exists($object, 'preSave')) {
			$object->preSave();
		}
		if (!static::getField($object, 'id')) {
			//New object to save.
			$fields = static::getFields($object);
			if (in_array('id', $fields)) {
				static::setField($object, 'id', null);
			}
			if (!$_direct && method_exists($object, 'preInsert')) {
				$object->preInsert();
			}
			if (method_exists($object, 'encrypt')) {
				$object->encrypt();
			}
			list($sql, $parameters) = static::buildQuery($object);
			if ($_replace) {
				$sql = 'REPLACE INTO `' . static::getTableName($object) . '` SET ' . implode(', ', $sql);
			} else {
				$sql = 'INSERT INTO `' . static::getTableName($object) . '` SET ' . implode(', ', $sql);
			}
			$res = static::Prepare($sql, $parameters, DB::FETCH_TYPE_ROW);
			$reflection = static::getReflectionClass($object);
			if ($reflection->hasProperty('id')) {
				try {
					static::setField($object, 'id', static::getLastInsertId());
				} catch (Exception $exc) {
					trigger_error($exc->getMessage(), E_USER_NOTICE);
				} catch (InvalidArgumentException $ex) {
					trigger_error($ex->getMessage(), E_USER_NOTICE);
				}
			}
			if (method_exists($object, 'decrypt')) {
				$object->decrypt();
			}
			if (!$_direct && method_exists($object, 'postInsert')) {
				$object->postInsert();
			}
		} else {
			//Object to update.
			if (!$_direct && method_exists($object, 'preUpdate')) {
				$object->preUpdate();
			}
			$changed = true;
			if (method_exists($object, 'getChanged')) {
				$changed = $object->getChanged();
			}
			if ($changed) {
				if (method_exists($object, 'encrypt')) {
					$object->encrypt();
				}
				list($sql, $parameters) = static::buildQuery($object);
				if (!$_direct && method_exists($object, 'getId')) {
					$parameters['id'] = $object->getId(); //override if necessary
				}
				if ($_replace) {
					$sql = 'REPLACE INTO `' . static::getTableName($object) . '` SET ' . implode(', ', $sql);
				} else {
					$sql = 'UPDATE `' . static::getTableName($object) . '` SET ' . implode(', ', $sql) . ' WHERE id = :id';
				}
				$res = static::Prepare($sql, $parameters, DB::FETCH_TYPE_ROW);
			} else {
				$res = true;
			}
			if (method_exists($object, 'decrypt')) {
				$object->decrypt();
			}
			if (!$_direct && method_exists($object, 'postUpdate')) {
				$object->postUpdate();
			}
		}
		if (!$_direct && method_exists($object, 'postSave')) {
			$object->postSave();
		}
		if (method_exists($object, 'setChanged')) {
			$object->setChanged(false);
		}
		return (null !== $res && false !== $res);
	}

    /**
     * Refresh entity from database
     *
     * @param object $object Entity to refresh
     * @return bool Success status
     * @throws \Exception When ID is missing
     * @throws \ReflectionException When class reflection fails
     */
	public static function refresh($object) {
		if (!static::getField($object, 'id')) {
			throw new Exception('Can\'t refresh DB object without its ID');
		}
		$parameters = array('id' => static::getField($object, 'id'));
		$sql = 'SELECT ' . static::buildField(get_class($object)) .
			' FROM `' . static::getTableName($object) . '` ' .
			' WHERE ';
		foreach ($parameters as $field => $value) {
			if ($value != '') {
				$sql .= '`' . $field . '`=:' . $field . ' AND ';
			} else {
				unset($parameters[$field]);
			}
		}
		$sql .= '1';
		$newObject = static::Prepare($sql, $parameters, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, get_class($object));
		if (!is_object($newObject)) {
			return false;
		}
		foreach (static::getFields($object) as $field) {
			$reflection = static::getReflectionClass($object);
			$property = $reflection->getProperty($field);
			if (!$reflection->hasProperty($field)) {
				throw new InvalidArgumentException('Unknown field ' . get_class($object) . '::' . $field);
			}
			$property->setAccessible(true);
			$property->setValue($object, static::getField($newObject, $field));
			$property->setAccessible(false);
		}
		return true;
	}

    /**
     * Find entities matching filters
     *
     * @param array<string, string> $_filters Search criteria
     * @param object $_object Entity type to search
     * @return array<mixed>|null Found entities
     */
	public static function getWithFilter(array $_filters, $_object) {
		// operators have to remain in this order. If you put '<' before '<=', algorithm won't make the difference & will think a '<=' is a '<'
		$operators = array('!=', '<=', '>=', '<', '>', 'NOT LIKE', 'LIKE', '=');
		$fields = static::getFields($_object);
		$class = static::getReflectionClass($_object)->getName();
		// create query
		$query = 'SELECT ' . static::buildField($class) . ' FROM ' . $class . '';
		$values = array();
		$where = ' WHERE ';
		foreach ($fields as $property) {
			foreach ($_filters as $key => $value) {
				if ($property == $key && $value != '') {
					// treat value to get operator
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
			$where = substr($where, 0, strlen($where) - 5); // remove last ' AND '
			$query .= $where;
		}
		// if values contains id, one value only
		return static::Prepare($query . ';', $values, in_array('id', $values) ? static::FETCH_TYPE_ROW : static::FETCH_TYPE_ALL);
	}

    /**
     * Remove entity from database
     *
     * @param object $object Entity to remove
     * @return bool Success status
     */
	public static function remove($object) {
		if (method_exists($object, 'preRemove')) {
			if ($object->preRemove() === false) {
				return false;
			}
		}
		list($sql, $parameters) = static::buildQuery($object);
		$sql = 'DELETE FROM `' . static::getTableName($object) . '` WHERE ';
		if (isset($parameters['id'])) {
			$sql .= '`id`=:id AND ';
			$parameters = array('id' => $parameters['id']);
		} else {
			foreach ($parameters as $field => $value) {
				if ($value != '') {
					$sql .= '`' . $field . '`=:' . $field . ' AND ';
				} else {
					unset($parameters[$field]);
				}
			}
		}
		$sql .= '1';
		$res = static::Prepare($sql, $parameters, DB::FETCH_TYPE_ROW);
		$reflection = static::getReflectionClass($object);
		if ($reflection->hasProperty('id')) {
			static::setField($object, 'id', null);
		}
		if (method_exists($object, 'postRemove')) {
			$object->postRemove();
		}
		return null !== $res && false !== $res;
	}

    /**
     * Calculate table checksum
     *
     * @param string $_table Table name
     * @return string Checksum value
     * @throws \Exception When checksum fails
     */
	public static function checksum($_table) {
		$sql = 'CHECKSUM TABLE ' . $_table;
		$result = static::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		return $result['Checksum'];
	}

    /**
     * Lock entity for update
     *
     * @param object $object Entity to lock
     * @return bool Success status
     */
	public static function lock($object) {
		if (method_exists($object, 'preLock')) {
			if ($object->preLock() === false) {
				return false;
			}
		}
		list($sql, $parameters) = static::buildQuery($object);
		$sql = 'SELECT * FROM ' . static::getTableName($object) . ' WHERE ';
		foreach ($parameters as $field => $value) {
			if ($value != '') {
				$sql .= '`' . $field . '`=:' . $field . ' AND ';
			} else {
				unset($parameters[$field]);
			}
		}
		$sql .= '1 LOCK IN SHARE MODE';
		$res = static::Prepare($sql, $parameters, DB::FETCH_TYPE_ROW);
		if (method_exists($object, 'postLock')) {
			$object->postLock();
		}
		return null !== $res && false !== $res;
	}

    /**
     * Get table name for entity
     *
     * @param object $object Entity
     * @return string Table name
     */
	private static function getTableName($object) {
		if (method_exists($object, 'getTableName')) {
			return $object->getTableName();
		}
		return get_class($object);
	}

    /**
     * Get class fields using reflection
     *
     * @param string|object $object Entity or class name
     * @return array<string>
     * @throws \RuntimeException When no fields found
     */
	private static function getFields($object) {
		$table = is_string($object) ? $object : static::getTableName($object);
		if (isset(static::$fields[$table])) {
			return static::$fields[$table];
		}
		$reflection = is_object($object) ? static::getReflectionClass($object) : new ReflectionClass($object);
		$properties = $reflection->getProperties();
		static::$fields[$table] = array();
		foreach ($properties as $property) {
			$name = $property->getName();
			if ('_' !== $name[0]) {
				static::$fields[$table][] = $name;
			}
		}
		if (empty(static::$fields[$table])) {
			throw new RuntimeException('No fields found for class ' . get_class($object));
		}
		return static::$fields[$table];
	}

    /**
     * Set entity field value
     *
     * @param object $object Entity to modify
     * @param string $field Field name
     * @param mixed $value Field value
     * @return void
     */
	private static function setField($object, $field, $value) {
		$method = 'set' . ucfirst($field);
		if (method_exists($object, $method)) {
			$object->$method($value);
		} else {
			$reflection = static::getReflectionClass($object);
			if (!$reflection->hasProperty($field)) {
				throw new InvalidArgumentException('Unknown field ' . get_class($object) . '::' . $field);
			}
			$property = $reflection->getProperty($field);
			$property->setAccessible(true);
			$property->setValue($object, $value);
			$property->setAccessible(false);
		}
	}

    /**
     * Build SQL query parameters from entity
     *
     * @param object $object Source entity
     * @return array<array<scalar|null>>
     */
	private static function buildQuery($object) {
		$parameters = array();
		$sql = array();
		foreach (static::getFields($object) as $field) {
			$sql[] = '`' . $field . '` = :' . $field;
			$parameters[$field] = static::getField($object, $field);
		}
		return array($sql, $parameters);
	}

    /**
     * Get entity field value
     *
     * @param object $object Source entity
     * @param string $field Field name
     * @return scalar|null Field value
     * @throws \RuntimeException When getter not found
     */
	private static function getField($object, $field) {
		$retval = null;
		$method = 'get' . ucfirst($field);
		if (method_exists($object, $method)) {
			$retval = $object->$method();
		} else {
			$reflection = static::getReflectionClass($object);
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
     * Get reflected class for entity
     *
     * @param object $object Entity to reflect
     * @return \ReflectionClass
     */
	private static function getReflectionClass($object) {
		$reflections = array();
		$uuid = spl_object_hash($object);
		if (!isset($reflections[$uuid])) {
			$reflections[$uuid] = new ReflectionClass($object);
		}
		return $reflections[$uuid];
	}

    /**
     * Build SQL fields list
     *
     * @param string|object $_class Entity class
     * @param string $_prefix Optional table alias
     * @return string Fields list
     */
	public static function buildField($_class, $_prefix = '') {
		$fields = array();
		foreach (static::getFields($_class) as $field) {
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

	/*************************DB ANALYZER***************************/

    /**
     * Compare and fix database structure
     *
     * @param array<string, mixed> $_database Reference database structure
     * @param string $_table Specific table or 'all'
     * @param bool $_verbose Enable detailed output
     * @param int $_loop Current fix attempt
     * @return bool Success status
     * @throws \Exception When fixes fail
     */
	public static function compareAndFix($_database, $_table = 'all', $_verbose = false, $_loop = 0) {
		$result = DB::compareDatabase($_database);
		$error = '';
		foreach ($result as $tname => $tinfo) {
			if ($_table != 'all' && $tname != $_table) {
				continue;
			}
			if ($tinfo['sql'] != '') {
				try {
					if ($_verbose) {
						echo "\nFix : " . $tinfo['sql'];
					}
					DB::prepare($tinfo['sql'], array());
				} catch (\Exception $e) {
					$error .= $e->getMessage() . "\n";
				}
			}
			if (isset($tinfo['indexes']) && count($tinfo['indexes']) > 0) {
				foreach ($tinfo['indexes'] as $iname => $iinfo) {
					if (!isset($iinfo['presql']) || trim($iinfo['presql']) == '') {
						continue;
					}
					try {
						if ($_verbose) {
							echo "\nFix : " . $iinfo['presql'];
						}
						DB::prepare($iinfo['presql'], array());
					} catch (\Exception $e) {
						$error .= $e->getMessage() . "\n";
					}
				}
			}
			if (isset($tinfo['fields']) &&  count($tinfo['fields']) > 0) {
				foreach ($tinfo['fields'] as $fname => $finfo) {
					if (!isset($finfo['sql']) || trim($finfo['sql']) == '') {
						continue;
					}
					try {
						if ($_verbose) {
							echo "\nFix : " . $finfo['sql'];
						}
						DB::prepare($finfo['sql'], array());
					} catch (\Exception $e) {
						$error .= $e->getMessage() . "\n";
					}
				}
			}
			if (isset($tinfo['indexes']) && count($tinfo['indexes']) > 0) {
				foreach ($tinfo['indexes'] as $iname => $iinfo) {
					if (!isset($iinfo['sql']) || trim($iinfo['sql']) == '') {
						continue;
					}
					try {
						if ($_verbose) {
							echo "\nFix : " . $iinfo['sql'];
						}
						DB::prepare($iinfo['sql'], array());
					} catch (\Exception $e) {
						$error .= $e->getMessage() . "\n";
					}
				}
			}
		}
		if (trim($error) != '') {
			if ($_loop < 1) {
				return static::compareAndFix($_database, $_table, $_verbose, ($_loop + 1));
			}
			throw new \Exception($error);
		}
		return true;
	}

    /**
     * Compare database against reference structure
     *
     * @param array<string, mixed> $_database Reference database structure
     * @return array<string, array<string, array<string, mixed>>> Comparison results
     */
	public static function compareDatabase($_database) {
		$return = array();
		foreach ($_database['tables'] as $table) {
			$return = array_merge($return, static::compareTable($table));
		}
		return $return;
	}

    /**
     * Compare table against reference structure
     *
     * @param array<string, mixed> $_table Reference table structure
     * @return array<string, array<string, array<string, mixed>>> Comparison results
     * @throws \Exception When table analysis fails
     */
	public static function compareTable($_table) {
		try {
			$describes = DB::Prepare('describe `' . $_table['name'] . '`', array(), DB::FETCH_TYPE_ALL);
		} catch (\Exception $e) {
			$describes = array();
		}
		$return =  array($_table['name'] => array('status' => 'ok', 'fields' => array(), 'indexes' => array(), 'sql' => ''));
		if (count($describes) == 0) {
			$return = array($_table['name'] => array(
				'status' => 'nok',
				'message' => 'Not found',
				'sql' => 'CREATE TABLE IF NOT EXISTS ' . '`' . $_table['name'] . '` (',
			));
			foreach ($_table['fields'] as $field) {
				$return[$_table['name']]['sql'] .= "\n" . '`' . $field['name'] . '`';
				$return[$_table['name']]['sql']	.= static::buildDefinitionField($field);
				$return[$_table['name']]['sql'] .= ',';
			}
			$primary_key = '';
			foreach ($_table['fields'] as $field) {
				if (isset($field['key']) && $field['key'] == 'PRI') {
					$primary_key .= '`' . $field['name'] . '`,';
				}
			}
			if($primary_key != ''){
				$return[$_table['name']]['sql'] .= "\n" . 'primary key(';
				$return[$_table['name']]['sql'] .= trim($primary_key, ',');
				$return[$_table['name']]['sql'] .= ')';
			}
			$return[$_table['name']]['sql'] = trim($return[$_table['name']]['sql'],',');
			$return[$_table['name']]['sql'] .= ')' . "\n";
			if (!isset($_table['engine'])) {
				$_table['engine'] = 'InnoDB';
			}
			$return[$_table['name']]['sql'] .= ' ENGINE ' . $_table['engine'] . ";\n";
			foreach ($_table['indexes'] as $index) {
				$return[$_table['name']]['sql'] .= "\n" . static::buildDefinitionIndex($index, $_table['name']) . ';';
			}
			$return[$_table['name']]['sql'] = trim($return[$_table['name']]['sql'], ';');
			return $return;
		}
		$forceRebuildIndex = false;
		try {
			$status = DB::Prepare('show table status where name="' . $_table['name'] . '"', array(), DB::FETCH_TYPE_ROW);
		} catch (\Exception $e) {
			$status = array();
		}
	    if(!isset($_table['engine'])){
       		$_table['engine'] = 'InnoDB';
      	}
		if(isset($status['Engine']) && $status['Engine'] != $_table['engine']){ 
			$return[$_table['name']]['sql'] = 'ALTER TABLE `' . $_table['name'] . '` ENGINE = '.$_table['engine'];
		}
		foreach ($_table['fields'] as $field) {
			$found = false;
			foreach ($describes as $describe) {
				if ($describe['Field'] != $field['name']) {
					continue;
				}
				$return[$_table['name']]['fields'] = array_merge($return[$_table['name']]['fields'], static::compareField($field, $describe, $_table['name']));
				if (isset($return[$_table['name']]['fields'][$field['name']]) && $return[$_table['name']]['fields'][$field['name']]['status'] == 'nok') {
					$forceRebuildIndex = true;
				}
				$found = true;
			}
			if (!$found) {
				$return[$_table['name']]['fields'][$field['name']] = array(
					'status' => 'nok',
					'message' => 'Not found',
					'sql' => 'ALTER TABLE `' . $_table['name'] . '` ADD `' . $field['name'] . '`'
				);
				$return[$_table['name']]['fields'][$field['name']]['sql']	.= static::buildDefinitionField($field);
			}
		}
		foreach ($describes as $describe) {
			$found = false;
			foreach ($_table['fields'] as $field) {
				if ($describe['Field'] == $field['name']) {
					$found = true;
					break;
				}
			}
			if (!$found) {
				$return[$_table['name']]['fields'][$describe['Field']] = array(
					'status' => 'nok',
					'message' => 'Should not exist',
					'sql' => 'ALTER TABLE `' . $_table['name'] . '` DROP `' . $describe['Field'] . '`'
				);
			}
		}
		$showIndexes = static::prepareIndexCompare(DB::Prepare('show index from `' . $_table['name'] . '`', array(), DB::FETCH_TYPE_ALL));
		foreach ($_table['indexes'] as $index) {
			$found = false;
			foreach ($showIndexes as $showIndex) {
				if ($showIndex['Key_name'] != $index['Key_name']) {
					continue;
				}
				$return[$_table['name']]['indexes'] = array_merge($return[$_table['name']]['indexes'], static::compareIndex($index, $showIndex, $_table['name'], $forceRebuildIndex));
				$found = true;
			}
			if (!$found) {
				$return[$_table['name']]['indexes'][$index['Key_name']] = array(
					'status' => 'nok',
					'message' => 'Not found',
					'sql' => ''
				);
				$return[$_table['name']]['indexes'][$index['Key_name']]['sql']	.= static::buildDefinitionIndex($index, $_table['name']);
			}
		}
		foreach ($showIndexes as $showIndex) {
			$found = false;
			foreach ($_table['indexes'] as $index) {
				if ($showIndex['Key_name'] == $index['Key_name']) {
					$found = true;
					break;
				}
			}
			if (!$found) {
				$return[$_table['name']]['indexes'][$showIndex['Key_name']] = array(
					'status' => 'nok',
					'message' => 'Should not exist',
					'sql' => 'ALTER TABLE `' . $_table['name'] . '` DROP INDEX `' . $showIndex['Key_name'] . '`;'
				);
			}
		}
		return $return;
	}

    /**
     * Format index data for comparison
     *
     * @param array<string, array<string, mixed>> $indexes Raw index data
     * @return array<string, array<string, mixed>> Formatted index data
     */
	public static function prepareIndexCompare($indexes) {
		$return = array();
		foreach ($indexes as $index) {
			if ($index['Key_name'] == 'PRIMARY') {
				continue;
			}
			if (!isset($return[$index['Key_name']])) {
				$return[$index['Key_name']] = array(
					'Key_name' => $index['Key_name'],
					'Index_type' => $index['Index_type'],
					'Non_unique' => 0,
					'columns' => array(),
				);
			}
			$return[$index['Key_name']]['Non_unique'] = $index['Non_unique'];
			$return[$index['Key_name']]['columns'][$index['Seq_in_index']] = array('column' => $index['Column_name'], 'Sub_part' => $index['Sub_part']);
		}
		return $return;
	}

    /**
     * Compare field against reference structure
     *
     * @param array<string, mixed> $_ref_field Reference field structure
     * @param array<string, mixed> $_real_field Actual field structure
     * @param string $_table_name Table name
     * @return array<string, array<string, string>> Comparison results
     */
	public static function compareField($_ref_field, $_real_field, $_table_name) {
		$return = array($_ref_field['name'] => array('status' => 'ok', 'sql' => ''));
		if ($_ref_field['type'] != $_real_field['Type']) {
			$return[$_ref_field['name']]['status'] = 'nok';
			$return[$_ref_field['name']]['message'] = 'Type nok';
		}
		if ($_ref_field['null'] != $_real_field['Null']) {
			$return[$_ref_field['name']]['status'] = 'nok';
			$return[$_ref_field['name']]['message'] = 'Null nok';
		}
		if ($_ref_field['default'] != $_real_field['Default']) {
			$return[$_ref_field['name']]['status'] = 'nok';
			$return[$_ref_field['name']]['message'] = 'Default nok';
		}
		if ($_ref_field['extra'] != $_real_field['Extra']) {
			$return[$_ref_field['name']]['status'] = 'nok';
			$return[$_ref_field['name']]['message'] = 'Extra nok';
		}
		if ($return[$_ref_field['name']]['status'] == 'nok') {
			$return[$_ref_field['name']]['sql'] = 'ALTER TABLE `' . $_table_name . '` MODIFY COLUMN `' . $_ref_field['name'] . '` ';
			$return[$_ref_field['name']]['sql'] .= static::buildDefinitionField($_ref_field);
		}
		return $return;
	}

    /**
     * Compare index against reference structure
     *
     * @param array<string, mixed> $_ref_index Reference index structure
     * @param array<string, mixed> $_real_index Actual index structure
     * @param string $_table_name Table name
     * @param bool $_forceRebuild Force index rebuild
     * @return array<string, array<string, string>> Comparison results
     */
	public static function compareIndex($_ref_index, $_real_index, $_table_name, $_forceRebuild = false) {
		$return = array($_ref_index['Key_name'] => array('status' => 'ok', 'presql' => '', 'sql' => ''));
		if ($_ref_index['Non_unique'] != $_real_index['Non_unique']) {
			$return[$_ref_index['Key_name']]['status'] = 'nok';
			$return[$_ref_index['Key_name']]['message'] = 'Non_unique nok';
		}
		if ($_ref_index['columns'] != $_real_index['columns']) {
			$return[$_ref_index['Key_name']]['status'] = 'nok';
			$return[$_ref_index['Key_name']]['message'] = 'Columns nok';
		}
		if (isset($_ref_index['Index_type']) && $_ref_index['Index_type'] != $_real_index['Index_type']) {
			$return[$_ref_index['Key_name']]['status'] = 'nok';
			$return[$_ref_index['Key_name']]['message'] = 'Index type nok';
		}
		if ($_forceRebuild) {
			$return[$_ref_index['Key_name']]['status'] = 'nok';
			$return[$_ref_index['Key_name']]['message'] = 'Force rebuild';
		}
		if ($return[$_ref_index['Key_name']]['status'] == 'nok') {
			$return[$_ref_index['Key_name']]['presql'] =  'ALTER TABLE `' . $_table_name . '` DROP INDEX `' . $_ref_index['Key_name'] . '`;';
			$return[$_ref_index['Key_name']]['sql'] = "\n" . static::buildDefinitionIndex($_ref_index, $_table_name);
		}
		return $return;
	}

    /**
     * Build SQL field definition
     *
     * @param array<string, mixed> $_field Field structure
     * @return string SQL definition
     */
	public static function buildDefinitionField($_field) {
		$return = ' ' . $_field['type'];
		if ($_field['null'] == 'NO') {
			$return .= ' NOT NULL';
		} else {
			$return .= ' NULL';
		}
		if ($_field['default'] != '') {
			$return .= ' DEFAULT "' . $_field['default'] . '"';
		}
		if ($_field['extra'] == 'auto_increment') {
			$return .= ' AUTO_INCREMENT';
		}
		return $return;
	}

    /**
     * Build SQL index definition
     *
     * @param array<string, string> $_index Index structure
     * @param string $_table_name Table name
     * @return string SQL definition
     */
	public static function buildDefinitionIndex($_index, $_table_name) {
		if ($_index['Non_unique'] == 0) {
			$return = 'CREATE UNIQUE INDEX `' . $_index['Key_name'] . '` ON `' . $_table_name . '`' . ' (';
		} else if (isset($_index['Index_type']) && $_index['Index_type'] == 'FULLTEXT') {
			$return = 'CREATE FULLTEXT INDEX `' . $_index['Key_name'] . '` ON `' . $_table_name . '`' . ' (';
		} else {
			$return = 'CREATE INDEX `' . $_index['Key_name'] . '` ON `' . $_table_name . '`' . ' (';
		}
		foreach ($_index['columns'] as $value) {
			$return .= '`' . $value['column'] . '`';
			if ($value['Sub_part'] != null) {
				$return .= '(' . $value['Sub_part'] . ')';
			}
			$return .= ' ASC,';
		}
		$return = trim($return, ',');
		$return .= ')';
		return $return;
	}
}
