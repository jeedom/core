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
require_once __DIR__ . '/../../core/php/core.inc.php';

class message {
	/*     * *************************Attributs****************************** */
	
	private $id;
	private $date;
	private $logicalId;
	private $plugin;
	private $message;
	private $action;
	private $_changed = false;
	private $occurrences;
	
	/*     * ***********************Methode static*************************** */
	/**
	*
	* @param type $_type
	* @param type $_message
	* @param type $_action
	* @param type $_logicalId
	* @param type $_writeMessage
	*/
	public static function add($_type, $_message, $_action = '', $_logicalId = '', $_writeMessage = true) {
		$message = (new message())
		->setPlugin(secureXSS($_type))
		->setMessage(secureXSS($_message))
		->setAction(secureXSS($_action))
		->setDate(date('Y-m-d H:i:s'))
		->setLogicalId(secureXSS($_logicalId));
		$message->save($_writeMessage);
	}
	
	public static function removeAll($_plugin = '', $_logicalId = '', $_search = false) {
		$values = array();
		$sql = 'DELETE FROM message';
		if ($_plugin != '') {
			$values['plugin'] = $_plugin;
			$sql .= ' WHERE plugin=:plugin';
			if ($_logicalId != '') {
				if ($_search) {
					$values['logicalId'] = '%' . $_logicalId . '%';
					$sql .= ' AND logicalId LIKE :logicalId';
				} else {
					$values['logicalId'] = $_logicalId;
					$sql .= ' AND logicalId=:logicalId';
				}
			}
		}
		DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
		event::add('message::refreshMessageNumber');
		return true;
	}
	
	public static function nbMessage() {
		$sql = 'SELECT count(*)
		FROM message';
		$count = DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		return $count['count(*)'];
	}
	
	public static function byId($_id) {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM message
		WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function byPluginLogicalId($_plugin, $_logicalId) {
		$values = array(
			'logicalId' => $_logicalId,
			'plugin' => $_plugin,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM message
		WHERE logicalId=:logicalId
		AND plugin=:plugin';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function removeByPluginLogicalId($_plugin, $_logicalId) {
		$values = array(
			'logicalId' => $_logicalId,
			'plugin' => $_plugin,
		);
		$sql = 'DELETE FROM message
		WHERE logicalId=:logicalId
		AND plugin=:plugin';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
	}
	
	public static function byPlugin($_plugin) {
		$values = array(
			'plugin' => $_plugin,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM message
		WHERE plugin=:plugin
		ORDER BY date DESC';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function listPlugin() {
		$sql = 'SELECT DISTINCT(plugin)
		FROM message';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
	}
	
	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM message
		ORDER BY date DESC
		LIMIT 500';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	
	/*     * *********************Methode d'instance************************* */
	
	public function save($_writeMessage = true) {
		if ($this->getMessage() == '') {
			return;
		}
		if ($this->getLogicalId() == '') {
			$this->setLogicalId($this->getPlugin() . '::' . config::genKey());
			$values = array(
				'message' => $this->getMessage(),
				'plugin' => $this->getPlugin(),
			);
			$sql = 'SELECT count(*)
			FROM message
			WHERE plugin=:plugin
			AND message=:message';
			$result = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
			if ($result['count(*)'] != 0) {
				$values = array(
					'message' => $this->getMessage(),
					'plugin' => $this->getPlugin(),
					'date' => $this->getDate(),
				);
				$sql = 'UPDATE message
				SET date=:date,occurrences=ifnull(occurrences, 1)+1
				WHERE plugin=:plugin
				AND message=:message
				LIMIT 1';
				$result = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
				return;
			}
		} else {
			$values = array(
				'logicalId' => $this->getLogicalId(),
				'plugin' => $this->getPlugin(),
			);
			$sql = 'SELECT count(*)
			FROM message
			WHERE plugin=:plugin
			AND logicalId=:logicalId';
			$result = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
			if ($result['count(*)'] != 0) {
				$values = array(
					'logicalId' => $this->getLogicalId(),
					'plugin' => $this->getPlugin(),
					'date' => $this->getDate(),
				);
				$sql = 'UPDATE message
				SET date=:date,occurrences=ifnull(occurrences, 1)+1
				WHERE plugin=:plugin
				AND logicalId=:logicalId
				LIMIT 1';
				$result = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
				return;
			}
		}
		if ($_writeMessage) {
			DB::save($this);
			$params = array(
				'#plugin#' => $this->getPlugin(),
				'#subject#' => $this->getMessage(),
				'#message#' => $this->getMessage(),
			);
			$actions = config::byKey('actionOnMessage');
			if (is_array($actions) && count($actions) > 0) {
				foreach ($actions as $action) {
					$options = array();
					if (isset($action['options'])) {
						$options = $action['options'];
					}
					foreach ($options as &$value) {
						$value = str_replace(array_keys($params), $params, $value);
					}
					scenarioExpression::createAndExec('action', $action['cmd'], $options);
				}
			}
			event::add('notify', array('title' => __('Message de ', __FILE__) . $this->getPlugin(), 'message' => $this->getMessage(), 'category' => 'message'));
			event::add('message::refreshMessageNumber');
		}
		return true;
	}
	
	public function remove() {
		DB::remove($this);
		event::add('message::refreshMessageNumber');
	}
	
	/*     * **********************Getteur Setteur*************************** */
	
	public function getId() {
		return $this->id;
	}
	
	public function getDate() {
		return $this->date;
	}
	
	public function getPlugin() {
		return $this->plugin;
	}
	
	public function getMessage() {
		return $this->message;
	}
	
	public function getAction() {
		return $this->action;
	}
	
	public function getOccurrences() {
		return $this->occurrences;
	}
	
	public function setId($_id) {
		$this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
		$this->id = $_id;
		return $this;
	}
	
	public function setDate($_date) {
		$this->_changed = utils::attrChanged($this->_changed,$this->date,$_date);
		$this->date = $_date;
		return $this;
	}
	
	public function setPlugin($_plugin) {
		$this->_changed = utils::attrChanged($this->_changed,$this->plugin,$_plugin);
		$this->plugin = $_plugin;
		return $this;
	}
	
	public function setMessage($_message) {
		$this->_changed = utils::attrChanged($this->_changed,$this->message,$_message);
		$this->message = $_message;
		return $this;
	}
	
	public function setAction($_action) {
		$this->_changed = utils::attrChanged($this->_changed,$this->action,$_action);
		$this->action = $_action;
		return $this;
	}
	
	public function getLogicalId() {
		return $this->logicalId;
	}
	
	public function setLogicalId($_logicalId) {
		$this->_changed = utils::attrChanged($this->_changed,$this->logicalId,$_logicalId);
		$this->logicalId = $_logicalId;
		return $this;
	}
	
	public function getChanged() {
		return $this->_changed;
	}
	
	public function setChanged($_changed) {
		$this->_changed = $_changed;
		return $this;
	}
	
}
