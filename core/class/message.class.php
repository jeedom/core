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
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';

class message {
	/*     * *************************Attributs****************************** */

	private $id;
	private $date;
	private $logicalId;
	private $plugin;
	private $message;
	private $action;

	/*     * ***********************Methode static*************************** */

	public static function add($_type, $_message, $_action = '', $_logicalId = '') {
		if (config::byKey('message::disallow' . $_type, 'core', 0) == 1) {
			return;
		}
		$message = new message();
		$message->setPlugin(secureXSS($_type));
		$message->setMessage(secureXSS($_message));
		$message->setAction(secureXSS($_action));
		$message->setDate(date('Y-m-d H:i:m'));
		$message->setLogicalId(secureXSS($_logicalId));
		$message->save();
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

	public function save() {
		if ($this->getMessage() == '') {
			return;
		}
		if ($this->getLogicalId() == '') {
			$this->setLogicalId($this->getPlugin() . '::' . config::genKey());
		}
		$values = array(
			'message' => $this->getMessage(),
			'logicalId' => $this->getLogicalId(),
			'plugin' => $this->getPlugin(),
		);
		$sql = 'SELECT count(*)
				FROM message
				WHERE plugin=:plugin
					AND (logicalId=:logicalId
						OR message=:message)';
		$result = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
		if ($result['count(*)'] == 0) {
			DB::save($this);
			$cmds = explode(('&&'), config::byKey('emailAdmin'));
			if (count($cmds) > 0 && trim(config::byKey('emailAdmin')) != '') {
				$jeedomName = trim(config::byKey('name'));
				if ($jeedomName == '') {
					$jeedomName = 'JEEDOM';
				}
				foreach ($cmds as $id) {
					$cmd = cmd::byId(str_replace('#', '', $id));
					if (is_object($cmd)) {
						$cmd->execCmd(array(
							'title' => __('[' . $jeedomName . '] Message de ', __FILE__) . $this->getPlugin(),
							'message' => $jeedomName . ' : ' . $this->getMessage(),
						));
					}
				}
			}
			event::add('notify', array('title' => __('Message de ', __FILE__) . $this->getPlugin(), 'message' => $this->getMessage(), 'category' => 'message'));
			event::add('message::refreshMessageNumber');
		}

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

	public function setId($id) {
		$this->id = $id;
	}

	public function setDate($date) {
		$this->date = $date;
	}

	public function setPlugin($plugin) {
		$this->plugin = $plugin;
	}

	public function setMessage($message) {
		$this->message = $message;
	}

	public function setAction($action) {
		$this->action = $action;
	}

	public function getLogicalId() {
		return $this->logicalId;
	}

	public function setLogicalId($logicalId) {
		$this->logicalId = $logicalId;
	}

}

?>
