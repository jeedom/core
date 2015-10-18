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

class cron {
	/*     * *************************Attributs****************************** */

	private $id;
	private $server;
	private $enable = 1;
	private $class = '';
	private $function;
	private $lastRun = '0000-00-00 00:00:00';
	private $duration = '0';
	private $state = 'stop';
	private $pid = null;
	private $schedule = '';
	private $timeout;
	private $deamon = 0;
	private $deamonSleepTime;
	private $option;
	private $once = 0;

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
        AND function=:function';
		if ($_option != '') {
			$_option = json_encode($_option, JSON_UNESCAPED_UNICODE);
			$value['option'] = $_option;
			$sql .= ' AND `option`=:option';
		}
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function searchClassAndFunction($_class, $_function, $_option = '') {
		$value = array(
			'class' => $_class,
			'function' => $_function,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM cron
        WHERE class=:class
        AND function=:function';
		if ($_option != '') {
			$value['option'] = '%' . $_option . '%';
			$sql .= ' AND `option` LIKE :option';
		}
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function clean() {
		$crons = self::all();
		foreach ($crons as $cron) {
			$c = new Cron\CronExpression($cron->getSchedule(), new Cron\FieldFactory);
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
		return shell_exec('(ps ax || ps w) | grep jeeCron.php | grep -v "grep" | grep -v "sudo" | grep -v "shell=/bin/bash - " | grep -v "/bin/bash -c " | grep -v "/bin/sh -c " | grep -v ' . posix_getppid() . ' | grep -v ' . getmypid() . ' | wc -l');
	}

	/**
	 * Return number of process on system
	 * @return int
	 */
	public static function nbProcess() {
		$result = shell_exec('ps ax | wc -l');
		return $result;
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
		$path = '/tmp/jeeCron.pid';
		$fp = fopen($path, 'w');
		fwrite($fp, getmypid());
		fclose($fp);
	}

	/**
	 * Return the current pid of jeecron or empty if not running
	 * @return int
	 */
	public static function getPidFile() {
		$path = '/tmp/jeeCron.pid';
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

	public static function ok() {
		$sql = 'SELECT UNIX_TIMESTAMP(max(`lastRun`)) as `time`
        FROM cron';
		$result = DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		if ((strtotime('now') - $result['time']) > 3600) {
			return false;
		}
		return true;
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
			throw new Exception(__('La programmation ne peut pas être vide : ', __FILE__) . print_r($this, true));
		}
	}

	/**
	 * Save cron object
	 * @return boolean
	 */
	public function save() {
		return DB::save($this, false, true);
	}

	/**
	 * Remove cron object
	 * @return boolean
	 */
	public function remove($halt_before = true) {
		if ($halt_before && $this->running()) {
			$this->halt();
		}
		return DB::remove($this);
	}

	/**
	 * Set cron to be start
	 */
	public function start() {
		if (!$this->running()) {
			$this->setState('starting');
			$this->save();
		} else {
			$this->setState('run');
			$this->save();
		}
	}

	/**
	 * Launch cron (this method must be only call by jeecron master)
	 * @throws Exception
	 */
	public function run($_noErrorReport = false) {
		$cmd = '/usr/bin/php ' . dirname(__FILE__) . '/../php/jeeCron.php';
		$cmd .= ' cron_id=' . $this->getId();
		if (!$this->running()) {
			exec($cmd . ' >> ' . log::getPathToLog('cron_execution') . ' 2>&1 &');
		} else {
			if (!$_noErrorReport) {
				$this->halt();
				if (!$this->running()) {
					exec($cmd . ' >> /dev/null 2>&1 &');
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
			if (posix_getsid($this->getPID()) && (!file_exists('/proc/' . $this->getPID() . '/cmdline') || strpos(file_get_contents('/proc/' . $this->getPID() . '/cmdline'), 'cron_id=' . $this->getId()) !== false)) {
				return true;
			}
		}
		if (shell_exec('(ps ax || ps w) | grep -ie "cron_id=' . $this->getId() . '$" | grep -v grep | wc -l') > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Refresh DB state of this cron
	 * @return boolean
	 */
	public function refresh() {
		$result = DB::refresh($this);
		if ($result && ($this->getState() == 'run' || $this->getState() == 'stoping') && !$this->running()) {
			$this->setState('stop');
			$this->setPID();
			$this->setServer('');
			$this->save();
		}
		return $result;
	}

	/*
	 * Set this cron to stop
	 */

	public function stop() {
		if ($this->running()) {
			$this->setState('stoping');
			$this->save();
		}
	}

	/*
	 * Stop immediatly cron (this method must be only call by jeecron master)
	 */

	public function halt() {
		if (!$this->running()) {
			$this->setState('stop');
			$this->setPID();
			$this->setServer('');
			$this->save();
		} else {
			log::add('cron', 'info', __('Arrêt de ', __FILE__) . $this->getClass() . '::' . $this->getFunction() . '(), PID : ' . $this->getPID());
			if ($this->getPID() > 0) {
				$kill = posix_kill($this->getPID(), 15);
				$retry = 0;
				while (!$kill && $retry < (config::byKey('deamonsSleepTime') + 5)) {
					sleep(1);
					$kill = posix_kill($this->getPID(), 9);
					$retry++;
				}
				$retry = 0;
				while (!$kill && $retry < (config::byKey('deamonsSleepTime') + 5)) {
					sleep(1);
					exec('kill -9 ' . $this->getPID());
					$kill = $this->running();
					$retry++;
				}
			}
			if ($this->running()) {
				exec("(ps ax || ps w) | grep -ie 'cron_id=" . $this->getId() . "$' | grep -v grep | awk '{print $2}' | xargs kill -9 > /dev/null 2>&1");
				if ($this->running()) {
					$this->setState('error');
					$this->setServer('');
					$this->setPID();
					$this->save();
					throw new Exception($this->getClass() . '::' . $this->getFunction() . __('() : Impossible d\'arrêter la tâche', __FILE__));
				}
			} else {
				$this->setState('stop');
				$this->setDuration(-1);
				$this->setPID();
				$this->setServer('');
				$this->save();
			}
		}
		return true;
	}

	/**
	 * Check if it's time to launch cron
	 * @return boolean
	 */
	public function isDue() {
		//check if already sent on that minute
		$last = strtotime($this->getLastRun());
		$now = time();
		$now = ($now - $now % 60);
		$last = ($last - $last % 60);
		if ($now == $last) {
			return false;
		}
		try {
			$c = new Cron\CronExpression($this->getSchedule(), new Cron\FieldFactory);
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
		return $this->lastRun;
	}

	public function getState() {
		return $this->state;
	}

	public function getEnable() {
		return $this->enable;
	}

	public function getDuration() {
		return $this->duration;
	}

	public function getPID() {
		return $this->pid;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setEnable($enable) {
		$this->enable = $enable;
	}

	public function setClass($class) {
		$this->class = $class;
	}

	public function setFunction($function) {
		$this->function = $function;
	}

	public function setLastRun($lastRun) {
		$this->lastRun = $lastRun;
	}

	public function setDuration($duration) {
		$this->duration = $duration;
	}

	public function setState($state) {
		$this->state = $state;
	}

	public function setPID($pid = null) {
		if (trim($pid) == '') {
			$pid = null;
		}
		$this->pid = $pid;
	}

	public function getServer() {
		return $this->server;
	}

	public function setServer($server) {
		$this->server = $server;
	}

	public function getSchedule() {
		return $this->schedule;
	}

	public function setSchedule($schedule) {
		$this->schedule = $schedule;
	}

	public function getDeamon() {
		return $this->deamon;
	}

	public function setDeamon($deamons) {
		$this->deamon = $deamons;
	}

	public function getTimeout() {
		$timeout = $this->timeout;
		if ($timeout == 0) {
			$timeout = config::byKey('maxExecTimeCrontask');
		}
		return $timeout;
	}

	public function setTimeout($timeout) {
		$this->timeout = $timeout;
	}

	public function getDeamonSleepTime() {
		$deamonSleepTime = $this->deamonSleepTime;
		if ($deamonSleepTime == 0) {
			$deamonSleepTime = config::byKey('deamonsSleepTime');
		}
		return $deamonSleepTime;
	}

	public function setDeamonSleepTime($deamonSleepTime) {
		$this->deamonSleepTime = $deamonSleepTime;
	}

	public function getOption() {
		return json_decode($this->option, true);
	}

	public function getOnce() {
		return $this->once;
	}

	public function setOption($option) {
		$this->option = json_encode($option, JSON_UNESCAPED_UNICODE);
	}

	public function setOnce($once) {
		$this->once = $once;
	}

}

?>
