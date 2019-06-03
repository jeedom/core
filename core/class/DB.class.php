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
	private static $fields = array();

	/*     * **************  Fonctions statiques  ***************** */

	private function __construct() {
		global $CONFIG;
		if(isset($CONFIG['db']['unix_socket'])) {
			$this->connection = new PDO('mysql:unix_socket=' . $CONFIG['db']['unix_socket'] . ';dbname=' . $CONFIG['db']['dbname'], $CONFIG['db']['username'], $CONFIG['db']['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', PDO::ATTR_PERSISTENT => true));
		} else {
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
		} else if (self::$sharedInstance->lastConnection + 120 < strtotime('now')) {
			try {
				$result = @self::$sharedInstance->connection->query('select 1;');
				if (!$result) {
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
				if ($_fetch_opt === null) {
					$res = $stmt->fetch($_fetch_param);
				} else if ($_fetch_param == PDO::FETCH_CLASS) {
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
			throw new Exception('[MySQL] Error code : ' . $errorInfo[0] . ' (' . $errorInfo[1] . '). ' . $errorInfo[2] . '  : ' . $_query);
		}
		return $res;
	}

	public function __clone() {
		trigger_error('DB : Cloner cet objet n\'est pas permis', E_USER_ERROR);
	}

	public static function optimize() {
		$tables = self::Prepare("SELECT TABLE_NAME FROM information_schema.TABLES WHERE Data_Free > 0", array(), DB::FETCH_TYPE_ALL);
		foreach ($tables as $table) {
			$table = array_values($table);
			$table = $table[0];
			self::Prepare('OPTIMIZE TABLE `' . $table . '`', array(), DB::FETCH_TYPE_ROW);
		}
	}

	public static function beginTransaction() {
		self::getConnection()->beginTransaction();
	}

	public static function commit() {
		self::getConnection()->commit();
	}

	public static function rollBack() {
		self::getConnection()->rollBack();
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
			$fields = self::getFields($object);
			if (in_array('id', $fields)) {
				self::setField($object, 'id', null);
			}
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
				try {
					self::setField($object, 'id', self::getLastInsertId());
				} catch (Exception $exc) {
					trigger_error($exc->getMessage(), E_USER_NOTICE);
				} catch (InvalidArgumentException $ex) {
					trigger_error($ex->getMessage(), E_USER_NOTICE);
				}
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
			if(method_exists($object, 'getChanged')){
				$changed = $object->getChanged();
			}
			if($changed){
				list($sql, $parameters) = self::buildQuery($object);
				if (!$_direct && method_exists($object, 'getId')) {
					$parameters['id'] = $object->getId(); //override if necessary
				}
				$sql = 'UPDATE `' . self::getTableName($object) . '` SET ' . implode(', ', $sql) . ' WHERE id = :id';
				$res = self::Prepare($sql, $parameters, DB::FETCH_TYPE_ROW);
			}else{
				$res = true;
			}
			if(method_exists($object, 'setChanged')){
				$object->setChanged(false);
			}
			if (!$_direct && method_exists($object, 'postUpdate')) {
				$object->postUpdate();
			}
		}
		if (!$_direct && method_exists($object, 'postSave')) {
			$object->postSave();
		}
		return (null !== $res && false !== $res);
	}

	public static function refresh($object) {
		if (!self::getField($object, 'id')) {
			throw new Exception('DB ne peut rafraîchir l\'objet sans son ID');
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
	public static function getWithFilter(array $_filters, $_object) {
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
		if(isset($parameters['id'])){
			$sql .= '`id`=:id AND ';
			$parameters = array('id' => $parameters['id']);
		}else{
			foreach ($parameters as $field => $value) {
				if ($value != '') {
					$sql .= '`' . $field . '`=:' . $field . ' AND ';
				} else {
					unset($parameters[$field]);
				}
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
		$table = is_string($object) ? $object : self::getTableName($object);
		if (isset(self::$fields[$table])) {
			return self::$fields[$table];
		}
		$reflection = is_object($object) ? self::getReflectionClass($object) : new ReflectionClass($object);
		$properties = $reflection->getProperties();
		self::$fields[$table] = array();
		foreach ($properties as $property) {
			$name = $property->getName();
			if ('_' !== $name[0]) {
				self::$fields[$table][] = $name;
			}
		}
		if (empty(self::$fields[$table])) {
			throw new RuntimeException('No fields found for class ' . get_class($object));
		}
		return self::$fields[$table];
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
	
	/*************************DB ANALYZER***************************/
	
	function compareAndFix($_database,$_table='all',$_verbose = false,$_loop=0){
		$result = DB::compareDatabase($_database);
		$error = '';
		foreach ($result as $tname => $tinfo) {
			if($_table != 'all' && $tname != $_table){
				continue;
			}
			if( $tinfo['sql'] != ''){
				try {
					if($_verbose){
						echo "\nFix : ".$tinfo['sql'];
					}
					DB::prepare($tinfo['sql'], array());
				} catch (\Exception $e) {
					$error .= $e->getMessage()."\n";
				}
			}
			if(isset($tinfo['indexes']) && count($tinfo['indexes']) > 0){
				foreach ($tinfo['indexes'] as $iname => $iinfo) {
					if(!isset($iinfo['presql']) || trim($iinfo['presql']) == ''){
						continue;
					}
					try {
						if($_verbose){
							echo "\nFix : ".$iinfo['presql'];
						}
						DB::prepare($iinfo['presql'], array());
					} catch (\Exception $e) {
						$error .= $e->getMessage()."\n";
					}
				}
				
			}
			if(isset($tinfo['fields']) &&  count($tinfo['fields']) > 0){
				foreach ($tinfo['fields'] as $fname => $finfo) {
					if(!isset($finfo['sql']) || trim($finfo['sql']) == ''){
						continue;
					}
					try {
						if($_verbose){
							echo "\nFix : ".$finfo['sql'];
						}
						DB::prepare($finfo['sql'], array());
					} catch (\Exception $e) {
						$error .= $e->getMessage()."\n";
					}
				}
			}
			if(isset($tinfo['indexes']) && count($tinfo['indexes']) > 0){
				foreach ($tinfo['indexes'] as $iname => $iinfo) {
					if(!isset($iinfo['sql']) || trim($iinfo['sql']) == ''){
						continue;
					}
					try {
						if($_verbose){
							echo "\nFix : ".$iinfo['sql'];
						}
						DB::prepare($iinfo['sql'], array());
					} catch (\Exception $e) {
						$error .= $e->getMessage()."\n";
					}
				}
			}
		}
		if(trim($error) != ''){
			if($_loop < 1){
				return self::compareAndFix($_database,$_table,$_verbose,($_loop + 1));
			}
			throw new \Exception($error);
		}
		return true;
	}
	
	function compareDatabase($_database){
		$return = array();
		foreach ($_database['tables'] as $table) {
			$return = array_merge($return,self::compareTable($table));
		}
		return $return;
	}
	
	
	function compareTable($_table){
		try {
			$describes = DB::Prepare('describe `'.$_table['name'].'`',array(),DB::FETCH_TYPE_ALL);
		} catch (\Exception $e) {
			$describes = array();
		}
		$return =  array($_table['name'] => array('status' => 'ok','fields' => array(),'indexes' => array(),'sql' => ''));
		if(count($describes) == 0){
			$return = array($_table['name'] => array(
				'status' => 'nok',
				'message' => 'Not found',
				'sql' => 'CREATE TABLE IF NOT EXISTS '.'`'.$_table['name'].'` (',
			));
			foreach ($_table['fields'] as $field) {
				$return[$_table['name']]['sql'] .="\n". '`'.$field['name'].'`';
				$return[$_table['name']]['sql']	.= self::buildDefinitionField($field);
				$return[$_table['name']]['sql'] .= ',';
			}
			$return[$_table['name']]['sql'] .="\n".'primary key(';
			foreach ($_table['fields'] as $field) {
				if(isset($field['key']) && $field['key'] == 'PRI'){
					$return[$_table['name']]['sql'] .='`'.$field['name'].'`,';
				}
			}
			$return[$_table['name']]['sql'] = trim($return[$_table['name']]['sql'],',');
			$return[$_table['name']]['sql'] .=')';
			$return[$_table['name']]['sql'] .= ')'."\n";
			if(!isset($_table['engine'])){
				$_table['engine'] = 'InnoDB';
			}
			$return[$_table['name']]['sql'] .= ' ENGINE '.$_table['engine'].";\n";
			foreach ($_table['indexes'] as $index) {
				$return[$_table['name']]['sql'] .= "\n".self::buildDefinitionIndex($index,$_table['name']).';';
			}
			$return[$_table['name']]['sql'] = trim($return[$_table['name']]['sql'],';');
			return $return;
		}
		$forceRebuildIndex = false;
		foreach ($_table['fields'] as $field) {
			$found = false;
			foreach ($describes as $describe) {
				if($describe['Field'] != $field['name']){
					continue;
				}
				$return[$_table['name']]['fields'] = array_merge($return[$_table['name']]['fields'],self::compareField($field,$describe,$_table['name']));
				if(isset($return[$_table['name']]['fields'][$field['name']]) && $return[$_table['name']]['fields'][$field['name']]['status'] == 'nok'){
					$forceRebuildIndex = true;
				}
				$found = true;
			}
			if(!$found){
				$return[$_table['name']]['fields'][$field['name']] = array(
					'status' => 'nok',
					'message' => 'Not found',
					'sql' => 'ALTER TABLE `'.$_table['name'].'` ADD `'.$field['name'].'`'
				);
				$return[$_table['name']]['fields'][$field['name']]['sql']	.= self::buildDefinitionField($field);
			}
		}
		foreach ($describes as $describe) {
			$found = false;
			foreach ($_table['fields'] as $field) {
				if($describe['Field'] == $field['name']){
					$found = true;
					break;
				}
			}
			if(!$found){
				$return[$_table['name']]['fields'][$describe['Field']] = array(
					'status' => 'nok',
					'message' => 'Should not exist',
					'sql' => 'ALTER TABLE `'.$_table['name'].'` DROP `'.$describe['Field'].'`'
				);
			}
		}
		$showIndexes = self::prepareIndexCompare(DB::Prepare('show index from `'.$_table['name'].'`',array(),DB::FETCH_TYPE_ALL));
		foreach ($_table['indexes'] as $index) {
			$found = false;
			foreach ($showIndexes as $showIndex) {
				if($showIndex['Key_name'] != $index['Key_name']){
					continue;
				}
				
				$return[$_table['name']]['indexes'] = array_merge($return[$_table['name']]['indexes'],self::compareIndex($index,$showIndex,$_table['name'],$forceRebuildIndex));
				$found = true;
			}
			if(!$found){
				$return[$_table['name']]['indexes'][$index['Key_name']] = array(
					'status' => 'nok',
					'message' => 'Not found',
					'sql' => ''
				);
				$return[$_table['name']]['indexes'][$index['Key_name']]['sql']	.= self::buildDefinitionIndex($index,$_table['name']);
			}
		}
		foreach ($showIndexes as $showIndex) {
			$found = false;
			foreach ($_table['indexes'] as $index) {
				if($showIndex['Key_name'] == $index['Key_name']){
					$found = true;
					break;
				}
			}
			if(!$found){
				$return[$_table['name']]['indexes'][$showIndex['Key_name']] = array(
					'status' => 'nok',
					'message' => 'Should not exist',
					'sql' => 'ALTER TABLE `'.$_table['name'].'` DROP INDEX `'.$showIndex['Key_name'].'`;'
				);
			}
		}
		return $return;
	}
	
	function prepareIndexCompare($indexes){
		$return = array();
		foreach ($indexes as $index) {
			if($index['Key_name'] == 'PRIMARY'){
				continue;
			}
			if(!isset($return[$index['Key_name']])){
				$return[$index['Key_name']] = array(
					'Key_name' => $index['Key_name'],
					'Non_unique' => 0,
					'columns' => array(),
				);
			}
			$return[$index['Key_name']]['Non_unique'] = $index['Non_unique'];
			$return[$index['Key_name']]['columns'][$index['Seq_in_index']] = array('column'=> $index['Column_name'],'Sub_part' => $index['Sub_part']);
		}
		return $return;
	}
	
	function compareField($_ref_field,$_real_field,$_table_name){
		$return = array($_ref_field['name'] => array('status' => 'ok','sql' => ''));
		if($_ref_field['type'] != $_real_field['Type']){
			$return[$_ref_field['name']]['status'] = 'nok';
			$return[$_ref_field['name']]['message'] = 'Type nok';
		}
		if($_ref_field['null'] != $_real_field['Null']){
			$return[$_ref_field['name']]['status'] = 'nok';
			$return[$_ref_field['name']]['message'] = 'Null nok';
		}
		if($_ref_field['default'] != $_real_field['Default']){
			$return[$_ref_field['name']]['status'] = 'nok';
			$return[$_ref_field['name']]['message'] = 'Default nok';
		}
		if($_ref_field['extra'] != $_real_field['Extra']){
			$return[$_ref_field['name']]['status'] = 'nok';
			$return[$_ref_field['name']]['message'] = 'Extra nok';
		}
		if($return[$_ref_field['name']]['status'] == 'nok'){
			$return[$_ref_field['name']]['sql'] = 'ALTER TABLE `'.$_table_name.'` MODIFY COLUMN `'.$_ref_field['name'].'` ';
			$return[$_ref_field['name']]['sql'] .= self::buildDefinitionField($_ref_field);
		}
		return $return;
	}
	
	function compareIndex($_ref_index,$_real_index,$_table_name,$_forceRebuild = false){
		$return = array($_ref_index['Key_name'] => array('status' => 'ok','presql' => '','sql' => ''));
		if($_ref_index['Non_unique'] != $_real_index['Non_unique']){
			$return[$_ref_index['Key_name']]['status'] = 'nok';
			$return[$_ref_index['Key_name']]['message'] = 'Non_unique nok';
		}
		if($_ref_index['columns'] != $_real_index['columns']){
			$return[$_ref_index['Key_name']]['status'] = 'nok';
			$return[$_ref_index['Key_name']]['message'] = 'Columns nok';
		}
		if($_forceRebuild){
			$return[$_ref_index['Key_name']]['status'] = 'nok';
			$return[$_ref_index['Key_name']]['message'] = 'Force rebuild';
		}
		if($return[$_ref_index['Key_name']]['status'] == 'nok'){
			$return[$_ref_index['Key_name']]['presql'] =  'ALTER TABLE `'.$_table_name.'` DROP INDEX `'.$_ref_index['Key_name'].'`;';
			$return[$_ref_index['Key_name']]['sql'] = "\n".self::buildDefinitionIndex($_ref_index,$_table_name);
		}
		return $return;
	}
	
	function buildDefinitionField($_field){
		$return = ' '.$_field['type'];
		if($_field['null'] == 'NO'){
			$return .= ' NOT NULL';
		}else{
			$return .= ' NULL';
		}
		if($_field['default'] != ''){
			$return .= ' DEFAULT "'.$_field['default'].'"';
		}
		if($_field['extra'] == 'auto_increment'){
			$return .= ' AUTO_INCREMENT';
		}
		return $return;
	}
	
	function buildDefinitionIndex($_index,$_table_name){
		if($_index['Non_unique'] == 0){
			$return = 'CREATE UNIQUE INDEX `'.$_index['Key_name'].'` ON `'.$_table_name.'`'.' (';
		}else{
			$return = 'CREATE INDEX `'.$_index['Key_name'].'` ON `'.$_table_name.'`'.' (';
		}
		foreach ($_index['columns'] as $value) {
			$return .= '`'.$value['column'].'`';
			if($value['Sub_part'] != null){
				$return .= '('.$value['Sub_part'].')';
			}
			$return .= ' ASC,';
		}
		$return = trim($return,',');
		$return .= ')';
		return $return;
	}

}
