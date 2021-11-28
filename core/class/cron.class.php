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

	private $id;
	private $enable = 1;
	private $class = '';
	private $function;
	private $schedule = '';
	private $timeout;
	private $deamon = 0;
	private $deamonSleepTime;
	private $option;
	private $once = 0;
	private $_changed = false;

	/*     * ***********************Méthodes statiques*************************** */

	/**
	* Return an array of all cron object
	* @return array
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
	* Get cron object associate to id
	* @param int $_id
	* @return object
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
	* Return cron object corresponding to parameters
	* @param string $_class
	* @param string $_function
	* @param string $_option
	* @return object
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
	*
	* @param type $_class
	* @param type $_function
	* @param type $_option
	* @return type
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
	* Return number of cron running
	* @return int
	*/
	public static function nbCronRun() {
		return count(system::ps('jeeCron.php', array('grep', 'sudo', 'shell=/bin/bash - ', '/bin/bash -c ', posix_getppid(), getmypid())));
	}

	/**
	* Return number of process on system
	* @return int
	*/
	public static function nbProcess() {
		return count(system::ps('.'));
	}

	/**
	* Return array of load average
	* @return array
	*/
	public static function loadAvg() {
		return sys_getloadavg();
	}

	/**
	* Set jeecron pid of current process
	*/
	public static function setPidFile() {
		$path = jeedom::getTmpFolder() . '/jeeCron.pid';
		$fp = fopen($path, 'w');
		fwrite($fp, getmypid());
		fclose($fp);
	}

	/**
	* Return the current pid of jeecron or empty if not running
	* @return int
	*/
	public static function getPidFile() {
		$path = jeedom::getTmpFolder() . '/jeeCron.pid';
		if (file_exists($path)) {
			return file_get_contents($path);
		}
		return '';
	}

	/**
	* Return state of jeecron master
	* @return boolean
	*/
	public static function jeeCronRun() {
		$pid = self::getPidFile();
		if ($pid == '' || !is_numeric($pid)) {
			return false;
		}
		return posix_getsid($pid);
	}

	public static function convertDateToCron($_date) {
		return date('i', $_date) . ' ' . date('H', $_date) . ' ' . date('d', $_date) . ' ' . date('m', $_date) . ' *';
	}

	/*     * *********************Méthodes d'instance************************* */

	/**
	* Check if cron object is valid before save
	* @throws Exception
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

	public function postInsert() {
		$this->setState('stop');
		$this->setPID();
	}

	/**
	* Save cron object
	* @return boolean
	*/
	public function save() {
		DB::save($this, false, true);
		return true;
	}

	/**
	* Remove cron object
	* @return boolean
	*/
	public function remove($halt_before = true) {
		if ($halt_before && $this->running()) {
			$this->halt();
		}
		cache::delete('cronCacheAttr' . $this->getId());
		return DB::remove($this);
	}

	/**
	* Set cron to be start
	*/
	public function start() {
		if (!$this->running()) {
			$this->setState('starting');
		} else {
			$this->setState('run');
		}
	}

	/**
	* Launch cron (this method must be only call by jeecron master)
	* @throws Exception
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
	* Check if this cron is currently running
	* @return boolean
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
	* Refresh DB state of this cron
	* @return boolean
	*/
	public function refresh() {
		if (($this->getState() == 'run' || $this->getState() == 'stoping') && !$this->running()) {
			$this->setState('stop');
			$this->setPID();
		}
		return true;
	}

	/*
	* Set this cron to stop
	*/

	public function stop() {
		if ($this->running()) {
			$this->setState('stoping');
		}
	}

	/*
	* Stop immediatly cron (this method must be only call by jeecron master)
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
	* Check if it's time to launch cron
	* @return boolean
	*/
	public function isDue() {
		if(((new DateTime('today midnight +1 day'))->format('I') - (new DateTime('today midnight'))->format('I')) == -1 && date('Gi') > 155 && date('Gi') < 305){
			return false;
		}
		//check if already sent on that minute
		$last = strtotime($this->getLastRun());
		$now = time();
		$now = ($now - $now % 60);
		$last = ($last - $last % 60);
		if ($now == $last) {
			return false;
		}
		try {
			$c = new Cron\CronExpression(checkAndFixCron($this->getSchedule()), new Cron\FieldFactory);
			try {
				if ($c->isDue()) {
					return true;
				}
			} catch (Exception $e) {

			} catch (Error $e) {

			}
			try {
				$prev = $c->getPreviousRunDate()->getTimestamp();
			} catch (Exception $e) {
				return false;
			} catch (Error $e) {
				return false;
			}
			$diff = abs((strtotime('now') - $prev) / 60);
			if (strtotime($this->getLastRun()) < $prev && ($diff <= config::byKey('maxCatchAllow') || config::byKey('maxCatchAllow') == -1)) {
				return true;
			}
		} catch (Exception $e) {
			log::add('cron', 'debug', 'Error on isDue : ' . $e->getMessage() . ', cron : ' . $this->getSchedule());
		} catch (Error $e) {
			log::add('cron', 'debug', 'Error on isDue : ' . $e->getMessage() . ', cron : ' . $this->getSchedule());
		}
		return false;
	}

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
	* Get human name of cron
	* @return string
	*/
	public function getName() {
		if ($this->getClass() != '') {
			return $this->getClass() . '::' . $this->getFunction() . '()';
		}
		return $this->getFunction() . '()';
	}

	public function toArray() {
		$return = utils::o2a($this, true);
		$return['state'] = $this->getState();
		$return['lastRun'] = $this->getLastRun();
		$return['pid'] = $this->getPID();
		$return['runtime'] = $this->getCache('runtime');
		return $return;
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function getClass() {
		return $this->class;
	}

	public function getFunction() {
		return $this->function;
	}

	public function getLastRun() {
		return $this->getCache('lastRun');
	}

	public function getState() {
		return $this->getCache('state', 'stop');
	}

	public function getEnable($_default = 0) {
		if ($this->enable == '' || !is_numeric($this->enable)) {
			return $_default;
		}
		return $this->enable;
	}

	public function getPID($_default = null) {
		return $this->getCache('pid', $_default);
	}

	public function setId($_id) {
		$this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
		$this->id = $_id;
		return $this;
	}

	public function setEnable($_enable) {
		$this->_changed = utils::attrChanged($this->_changed,$this->enable,$_enable);
		$this->enable = $_enable;
		return $this;
	}

	public function setClass($_class) {
		$this->_changed = utils::attrChanged($this->_changed,$this->class,$_class);
		$this->class = $_class;
		return $this;
	}

	public function setFunction($_function) {
		$this->_changed = utils::attrChanged($this->_changed,$this->function,$_function);
		$this->function = $_function;
		return $this;
	}

	public function setLastRun($lastRun) {
		$this->setCache('lastRun', $lastRun);
	}

	public function setState($state) {
		$this->setCache('state', $state);
	}

	public function setPID($pid = null) {
		$this->setCache('pid', $pid);
	}

	public function getSchedule() {
		return $this->schedule;
	}

	public function setSchedule($_schedule) {
		$this->_changed = utils::attrChanged($this->_changed,$this->schedule,$_schedule);
		$this->schedule = $_schedule;
		return $this;
	}

	public function getDeamon() {
		return $this->deamon;
	}

	public function setDeamon($_deamons) {
		$this->_changed = utils::attrChanged($this->_changed,$this->deamon,$_deamons);
		$this->deamon = $_deamons;
		return $this;
	}

	public function getTimeout() {
		$timeout = $this->timeout;
		if ($timeout == 0) {
			$timeout = config::byKey('maxExecTimeCrontask');
		}
		return $timeout;
	}

	public function setTimeout($_timeout) {
		$this->_changed = utils::attrChanged($this->_changed,$this->timeout,$_timeout);
		$this->timeout = $_timeout;
		return $this;
	}

	public function getDeamonSleepTime() {
		$deamonSleepTime = $this->deamonSleepTime;
		if ($deamonSleepTime == 0) {
			$deamonSleepTime = config::byKey('deamonsSleepTime');
		}
		return $deamonSleepTime;
	}

	public function setDeamonSleepTime($_deamonSleepTime) {
		$this->_changed = utils::attrChanged($this->_changed,$this->deamonSleepTime,$_deamonSleepTime);
		$this->deamonSleepTime = $_deamonSleepTime;
		return $this;
	}

	public function getOption() {
		return json_decode($this->option, true);
	}

	public function getOnce($_default = 0) {
		if ($this->once == '' || !is_numeric($this->once)) {
			return $_default;
		}
		return $this->once;
	}

	public function setOption($_option) {
		$_option = json_encode($_option, JSON_UNESCAPED_UNICODE);
		$this->_changed = utils::attrChanged($this->_changed,$this->option,$_option);
		$this->option = $_option;
		return $this;
	}

	public function setOnce($_once) {
		$this->_changed = utils::attrChanged($this->_changed,$this->once,$_once);
		$this->once = $_once;
		return $this;
	}

	public function getCache($_key = '', $_default = '') {
		$cache = cache::byKey('cronCacheAttr' . $this->getId())->getValue();
		return utils::getJsonAttr($cache, $_key, $_default);
	}

	public function setCache($_key, $_value = null) {
		cache::set('cronCacheAttr' . $this->getId(), utils::setJsonAttr(cache::byKey('cronCacheAttr' . $this->getId())->getValue(), $_key, $_value));
	}

	public function getChanged() {
		return $this->_changed;
	}

	public function setChanged($_changed) {
		$this->_changed = $_changed;
		return $this;
	}

}
