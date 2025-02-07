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

class cron {
	/*     * *************************Attributs****************************** */

    /** @var int Unique identifier */
	private $id;

    /** @var int Task enabled status */
	private $enable = 1;

    /** @var string Class name for class methods */
	private $class = '';

    /** @var string Method to execute */
	private $function;

    /** @var string Cron schedule expression */
	private $schedule = '';

    /** @var int Maximum execution time in seconds */
	private $timeout;

    /** @var int Daemon mode status */
	private $deamon = 0;

    /** @var int Sleep time between daemon execution cycles */
	private $deamonSleepTime;

    /** @var string|null JSON encoded options */
	private $option;

    /** @var int Single execution flag */
	private $once = 0;

    /** @var bool Change tracking flag */
	private $_changed = false;

	/*     * ***********************Méthodes statiques*************************** */

    /**
     * Retrieves all cron tasks
     *
     * @param bool $_order Order by daemon status
     * @return static[]
     */
	public static function all($_order = false) {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cron';
		if ($_order) {
			$sql .= ' ORDER BY deamon DESC';
		}
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * Finds a cron task by its ID
     *
     * @param int $_id Task identifier
     * @return static|null
     */
	public static function byId($_id) {
		$value = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cron
		WHERE id=:id';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * Finds a cron task by class and function
     *
     * @param string $_class Class name
     * @param string $_function Method name
     * @param string $_option JSON encoded options
     * @return static|null
     */
	public static function byClassAndFunction($_class, $_function, $_option = '') {
		$value = array(
			'class' => $_class,
			'function' => $_function,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cron
		WHERE class=:class
		AND `function`=:function';
		if ($_option != '') {
			$_option = json_encode($_option, JSON_UNESCAPED_UNICODE);
			$value['option'] = $_option;
			$sql .= ' AND `option`=:option';
		}
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * Searches cron tasks by class and function with pattern matching
     *
     * @param string $_class Class name
     * @param string $_function Method name
     * @param string|array<string, mixed> $_option Options to match
     * @return static[]
     */
	public static function searchClassAndFunction($_class, $_function, $_option = '') {
		$value = array(
			'class' => $_class,
			'function' => $_function,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cron
		WHERE class=:class
		AND `function`=:function';
		if ($_option != '') {
			if(is_array($_option)){
				$value['option'] = json_encode($_option);
				$sql .= ' AND JSON_CONTAINS(option,:option)';
			}else{
				$value['option'] = '%' . $_option . '%';
				$sql .= ' AND `option` LIKE :option';
			}
		}
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * Removes invalid cron schedules
     *
     * @return void
     */
	public static function clean() {
		$crons = self::all();
		foreach ($crons as $cron) {
			$c = new Cron\CronExpression(checkAndFixCron($cron->getSchedule()), new Cron\FieldFactory);
			try {
				if (!$c->isDue()) {
					$c->getNextRunDate();
				}
			} catch (Exception $ex) {
				$cron->remove();
			} catch (Error $ex) {
				$cron->remove();
			}
		}
	}

    /**
     * Counts running cron processes
     *
     * @return int Number of active cron processes
     */
	public static function nbCronRun() {
		return count(system::ps('jeeCron.php', array('grep', 'sudo', 'shell=/bin/bash - ', '/bin/bash -c ', posix_getppid(), getmypid())));
	}

    /**
     * Counts system processes
     *
     * @return int Total number of processes
     */
	public static function nbProcess() {
		return count(system::ps('.'));
	}

    /**
     * Gets system load averages
     *
     * @return array<int, float>|false Load averages for 1, 5 and 15 minutes
     */
	public static function loadAvg() {
		return sys_getloadavg();
	}

    /**
     * Stores current process ID
     *
     * @return void
     */
	public static function setPidFile() {
		$path = jeedom::getTmpFolder() . '/jeeCron.pid';
		$fp = fopen($path, 'w');
		fwrite($fp, getmypid());
		fclose($fp);
	}

    /**
     * Retrieves stored process ID
     *
     * @return string|false Process ID or empty string if not running
     */
	public static function getPidFile() {
		$path = jeedom::getTmpFolder() . '/jeeCron.pid';
		if (file_exists($path)) {
			return file_get_contents($path);
		}
		return '';
	}

    /**
     * Checks if cron master process is running
     *
     * @return false|int Session ID or false if not running
     */
	public static function jeeCronRun() {
		$pid = self::getPidFile();
		if ($pid == '' || !is_numeric($pid)) {
			return false;
		}
		return posix_getsid($pid);
	}

    /**
     * Converts timestamp to cron expression
     *
     * @param int $_date Timestamp
     * @return string Cron expression
     */
	public static function convertDateToCron($_date) {
		return date('i', $_date) . ' ' . date('H', $_date) . ' ' . date('d', $_date) . ' ' . date('m', $_date) . ' *';
	}

	/*     * *********************Méthodes d'instance************************* */

    /**
     * Validates cron task before saving
     *
     * @return void
     * @throws Exception On validation failure
     */
	public function preSave() {
		if ($this->getFunction() == '') {
			throw new Exception(__('La fonction ne peut pas être vide', __FILE__));
		}
		if ($this->getSchedule() == '') {
			throw new Exception(__('La programmation ne peut pas être vide :', __FILE__) . ' ' . print_r($this, true));
		}
		if ($this->getOption() == '' || count($this->getOption()) == 0) {
			$cron = cron::byClassAndFunction($this->getClass(), $this->getFunction());
			if (is_object($cron)) {
				$this->setId($cron->getId());
			}
		}
	}

    /**
     * Initializes a new cron task
     *
     * @return void
     */
	public function postInsert() {
		$this->setState('stop');
		$this->setPID();
	}

    /**
     * Saves current cron task
     *
     * @return bool Success status
     */
	public function save() {
		DB::save($this, false, true);
		return true;
	}

    /**
     * Deletes current cron task
     *
     * @param bool $halt_before Stop task before removal
     * @return bool Success status
     */
	public function remove($halt_before = true) {
		if ($halt_before && $this->running()) {
			$this->halt();
		}
		cache::delete('cronCacheAttr' . $this->getId());
		return DB::remove($this);
	}

    /**
     * Marks task for execution
     *
     * @return void
     */
	public function start() {
		if (!$this->running()) {
			$this->setState('starting');
		} else {
			$this->setState('run');
		}
	}

    /**
     * Executes the task (this method must be only call by jeecron master)
     *
     * @param bool $_noErrorReport Suppress error reporting
     * @return void
     * @throws Exception On execution failure
     */
	public function run($_noErrorReport = false) {
		$cmd = __DIR__ . '/../php/jeeCron.php';
		$cmd .= ' "cron_id=' . $this->getId() . '"';
		if (!$this->running()) {
			system::php($cmd . ' >> ' . log::getPathToLog('cron_execution') . ' 2>&1 &');
		} else {
			if (!$_noErrorReport) {
				$this->halt();
				if (!$this->running()) {
					system::php($cmd . ' >> ' . log::getPathToLog('cron_execution') . ' 2>&1 &');
				} else {
					throw new Exception(__('Impossible d\'exécuter la tâche car elle est déjà en cours d\'exécution (', __FILE__) . ' : ' . $cmd);
				}
			}
		}
	}

    /**
     * Checks if task is currently running
     *
     * @return bool Running status
     */
	public function running() {
		if (($this->getState() == 'run' || $this->getState() == 'stoping') && $this->getPID() > 0) {
			if (posix_getsid($this->getPID()) && (!file_exists('/proc/' . $this->getPID() . '/cmdline') || strpos(@file_get_contents('/proc/' . $this->getPID() . '/cmdline'), 'cron_id=' . $this->getId()) !== false)) {
				return true;
			}
		}
		if (count(system::ps('cron_id=' . $this->getId() . '$')) > 0) {
			return true;
		}
		return false;
	}

    /**
     * Updates task state
     *
     * @return bool Success status
     */
	public function refresh() {
		if (($this->getState() == 'run' || $this->getState() == 'stoping') && !$this->running()) {
			$this->setState('stop');
			$this->setPID();
		}
		return true;
	}

    /**
     * Signals task to stop
     *
     * @return void
     */
	public function stop() {
		if ($this->running()) {
			$this->setState('stoping');
		}
	}

    /**
     * Forces immediate task termination
     *
     * @return bool Success status
     * @throws Exception On termination failure
     */
	public function halt() {
		if (!$this->running()) {
			$this->setState('stop');
			$this->setPID();
		} else {
			log::add('cron', 'info', __('Arrêt de', __FILE__) . ' ' . $this->getClass() . '::' . $this->getFunction() . '(), PID : ' . $this->getPID());
			if ($this->getPID() > 0) {
				system::kill($this->getPID());
				$retry = 0;
				while ($this->running() && $retry < (config::byKey('deamonsSleepTime') + 5)) {
					sleep(1);
					system::kill($this->getPID());
					$retry++;
				}
				$retry = 0;
				while ($this->running() && $retry < (config::byKey('deamonsSleepTime') + 5)) {
					sleep(1);
					system::kill($this->getPID());
					$retry++;
				}
			}
			if ($this->running()) {
				system::kill("cron_id=" . $this->getId() . "$");
				sleep(1);
				if ($this->running()) {
					system::kill("cron_id=" . $this->getId() . "$");
					sleep(1);
				}
				if ($this->running()) {
					$this->setState('error');
					$this->setPID();
					throw new Exception($this->getClass() . '::' . $this->getFunction() . __('() : Impossible d\'arrêter la tâche', __FILE__));
				}
			} else {
				$this->setState('stop');
				$this->setPID();
			}
		}
		return true;
	}

    /**
     * Checks if task should be executed
     *
     * @param string|null $_datetime Optional check time
     * @return bool Execution status
     */
	public function isDue($_datetime = null) {
		//check if already sent on that minute
		$last = strtotime($this->getLastRun());
		$now = time();
		if (($now - $now % 60) == ($last - $last % 60)) {
			return false;
		}
		return cronIsDue($this->getSchedule(),$_datetime);
	}

    /**
     * Calculates next execution time
     *
     * @return false|string Next execution date or false on error
     */
	public function getNextRunDate() {
		try {
			$c = new Cron\CronExpression(checkAndFixCron($this->getSchedule()), new Cron\FieldFactory);
			return $c->getNextRunDate()->format('Y-m-d H:i:s');
		} catch (Exception $e) {

		} catch (Error $e) {

		}
		return false;
	}

    /**
     * Generates readable task name
     *
     * @return string Task name
     */
	public function getName() {
		if ($this->getClass() != '') {
			return $this->getClass() . '::' . $this->getFunction() . '()';
		}
		return $this->getFunction() . '()';
	}

    /**
     * Converts task to array format
     *
     * @return array<string, mixed> Task properties
     */
	public function toArray() {
		$return = utils::o2a($this, true);
		$return['state'] = $this->getState();
		$return['lastRun'] = $this->getLastRun();
		$return['pid'] = $this->getPID();
		$return['runtime'] = $this->getCache('runtime');
		return $return;
	}

	/*     * **********************Getteur Setteur*************************** */

    /**
     * @return int
     */
	public function getId() {
		return $this->id;
	}

    /**
     * @return string
     */
	public function getClass() {
		return $this->class;
	}

    /**
     * @return string
     */
	public function getFunction() {
		return $this->function;
	}

    /**
     * @return string
     */
	public function getLastRun() {
		return $this->getCache('lastRun');
	}

    /**
     * @return string
     */
	public function getState() {
		return $this->getCache('state', 'stop');
	}

    /**
     * @param int $_default Default value
     * @return int
     */
	public function getEnable($_default = 0) {
		if ($this->enable == '' || !is_numeric($this->enable)) {
			return $_default;
		}
		return $this->enable;
	}

    /**
     * @param int|null $_default Default value
     * @return int
     */
	public function getPID($_default = null) {
		return $this->getCache('pid', $_default);
	}

    /**
     * @param int $_id
     * @return static
     */
	public function setId($_id) {
		$this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
		$this->id = $_id;
		return $this;
	}

    /**
     * @param int $_enable
     * @return static
     */
	public function setEnable($_enable) {
		$this->_changed = utils::attrChanged($this->_changed,$this->enable,$_enable);
		$this->enable = $_enable;
		return $this;
	}

    /**
     * @param string $_class
     * @return static
     */
	public function setClass($_class) {
		$this->_changed = utils::attrChanged($this->_changed,$this->class,$_class);
		$this->class = $_class;
		return $this;
	}

    /**
     * @param string $_function
     * @return static
     */
	public function setFunction($_function) {
		$this->_changed = utils::attrChanged($this->_changed,$this->function,$_function);
		$this->function = $_function;
		return $this;
	}

    /**
     * @param string $lastRun
     * @return void
     */
	public function setLastRun($lastRun) {
		$this->setCache('lastRun', $lastRun);
	}

    /**
     * @param string $state One of 'stop', 'starting', 'run', 'stoping', 'error', 'Not found'
     * @return void
     */
	public function setState($state) {
		$this->setCache('state', $state);
	}

    /**
     * @param int $pid
     * @return void
     */
	public function setPID($pid = null) {
		$this->setCache('pid', $pid);
	}

    /**
     * @return string
     */
	public function getSchedule() {
		return $this->schedule;
	}

    /**
     * @param string $_schedule
     * @return static
     */
	public function setSchedule($_schedule) {
		$this->_changed = utils::attrChanged($this->_changed,$this->schedule,$_schedule);
		$this->schedule = $_schedule;
		return $this;
	}

    /**
     * @return int
     */
	public function getDeamon() {
		return $this->deamon;
	}

    /**
     * @param int $_deamons
     * @return static
     */
	public function setDeamon($_deamons) {
		$this->_changed = utils::attrChanged($this->_changed,$this->deamon,$_deamons);
		$this->deamon = $_deamons;
		return $this;
	}

    /**
     * @return int
     */
	public function getTimeout() {
		$timeout = $this->timeout;
		if ($timeout == 0) {
			$timeout = config::byKey('maxExecTimeCrontask');
		}
		return $timeout;
	}

    /**
     * @param int $_timeout
     * @return static
     */
	public function setTimeout($_timeout) {
		$this->_changed = utils::attrChanged($this->_changed,$this->timeout,$_timeout);
		$this->timeout = $_timeout;
		return $this;
	}

    /**
     * @return int
     */
	public function getDeamonSleepTime() {
		$deamonSleepTime = $this->deamonSleepTime;
		if ($deamonSleepTime == 0) {
			$deamonSleepTime = config::byKey('deamonsSleepTime');
		}
		return $deamonSleepTime;
	}

    /**
     * @param int $_deamonSleepTime
     * @return static
     */
	public function setDeamonSleepTime($_deamonSleepTime) {
		$this->_changed = utils::attrChanged($this->_changed,$this->deamonSleepTime,$_deamonSleepTime);
		$this->deamonSleepTime = $_deamonSleepTime;
		return $this;
	}

    /**
     * @return mixed
     */
	public function getOption() {
		return json_decode($this->option ?? '', true);
	}

    /**
     * @param int $_default Default value
     * @return int
     */
	public function getOnce($_default = 0) {
		if ($this->once == '' || !is_numeric($this->once)) {
			return $_default;
		}
		return $this->once;
	}

    /**
     * @param mixed $_option
     * @return static
     */
	public function setOption($_option) {
		$_option = json_encode($_option, JSON_UNESCAPED_UNICODE);
		$this->_changed = utils::attrChanged($this->_changed,$this->option,$_option);
		$this->option = $_option;
		return $this;
	}

    /**
     * @param int $_once
     * @return static
     */
	public function setOnce($_once) {
		$this->_changed = utils::attrChanged($this->_changed,$this->once,$_once);
		$this->once = $_once;
		return $this;
	}

    /**
     * @param string $_key Cache key
     * @param mixed $_default Default value
     * @return mixed
     */
	public function getCache($_key = '', $_default = '') {
		$cache = cache::byKey('cronCacheAttr' . $this->getId())->getValue();
		return utils::getJsonAttr($cache, $_key, $_default);
	}

    /**
     * @param string $_key Cache key
     * @param mixed|null $_value Value to store
     * @return void
     */
	public function setCache($_key, $_value = null) {
		cache::set('cronCacheAttr' . $this->getId(), utils::setJsonAttr(cache::byKey('cronCacheAttr' . $this->getId())->getValue(), $_key, $_value));
	}

    /**
     * @return bool
     */
	public function getChanged() {
		return $this->_changed;
	}

    /**
     * @param bool $_changed
     * @return static
     */
	public function setChanged($_changed) {
		$this->_changed = $_changed;
		return $this;
	}

}
