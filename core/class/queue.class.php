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

class queue {
	/*     * *************************Attributs****************************** */

    private $id;
	private $class;
	private $function;
	private $arguments;
	private $createTime;
	private $options;
    private $queueId = null;
    private $timeout = -1;
    private $_changed = false;

    /*     * ***********************Méthodes statiques*************************** */


    public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM queue';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byId($_id) {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM queue
		WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

    public static function allQueueId() {
		$sql = 'SELECT distinct(queueId) as queueId FROM queue WHERE queueId IS NOT NULL';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
	}

    public static function byQueueId($_queueId) {
		if($_queueId === null){
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM queue
			WHERE queueId IS NULL
			ORDER BY createTime ASC, id ASC';
			return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
		}else{
			$values = array(
				'queueId' => $_queueId,
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM queue
			WHERE queueId=:queueId
			ORDER BY createTime ASC, id ASC';
			return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
		}
       
	}

    public static function firstByQueueId($_queueId) {
        $values = array(
			'queueId' => $_queueId,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM queue
        WHERE queueId=:queueId
        ORDER BY createTime ASC, id ASC
        LIMIT 1';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

    public static function cron(){
        $queues = self::byQueueId(null);
        foreach ($queues as $queue) {
            if(!$queue->canRun()){
                continue;
            }
            try {
                log::add('queue','debug',__('Lancement de '.$queue->getHumanName(),__FILE__));
                $queue->run();
            } catch (\Throwable $th) {
                log::add('queue','debug',__('Erreur sur le lancement de '.$queue->getHumanName().' => '.$th->getMessage(),__FILE__));
            }
        }
        $queueIds = self::allQueueId();
        foreach ($queueIds as $queueId) {
            log::add('queue','debug',__('Recherche des actions à faire pour '.$queueId['queueId'],__FILE__));
            $queue = self::firstByQueueId($queueId['queueId']);
            if(!$queue->canRun()){
                continue;
            }
            try {
                log::add('queue','debug',__('Lancement de '.$queue->getHumanName(),__FILE__));
                $queue->run();
            } catch (\Throwable $th) {
                log::add('queue','debug',__('Erreur sur le lancement de '.$queue->getHumanName().' => '.$th->getMessage(),__FILE__));
            }
        }
    }

    /*     * *********************Méthodes d'instance************************* */

    public function canRun(){
        if($this->running()){
            return false;
        }
        if($this->getCache('numberFailed',0) > $this->getCache('maxNumberFailed',10)){
            $this->remove();
            return false;
        }
        if($this->getCache('numberFailed',0) > 0){
            $diff = strtotime('now') - strtotime($this->getLastRun());
            if($diff < $this->getCache('numberFailed',0) * 120){
                return false;
            }
        }
        return true;
    }

    public function run($_noErrorReport = false) {
		$cmd = __DIR__ . '/../php/jeeQueue.php';
		$cmd .= ' "queue_id=' . $this->getId() . '"';
		if (!$this->running()) {
			system::php($cmd . ' >> ' . log::getPathToLog('queue_execution') . ' 2>&1 &');
		} else {
			if (!$_noErrorReport) {
				$this->halt();
				if (!$this->running()) {
					system::php($cmd . ' >> ' . log::getPathToLog('queue_execution') . ' 2>&1 &');
				} else {
					throw new Exception(__('Impossible d\'exécuter la tâche en queue car elle est déjà en cours d\'exécution (', __FILE__) . ' : ' . $cmd);
				}
			}
		}
	}

    public function start() {
		if (!$this->running()) {
			$this->setState('starting');
		} else {
			$this->setState('run');
		}
	}

    public function refresh() {
		if (($this->getState() == 'run' || $this->getState() == 'stoping') && !$this->running()) {
			$this->setState('stop');
			$this->setPID();
		}
		return true;
	}

    public function stop() {
		if ($this->running()) {
			$this->setState('stoping');
		}
	}

    public function halt() {
		if (!$this->running()) {
			$this->setState('stop');
			$this->setPID();
		} else {
			log::add('queue', 'info', __('Arrêt de', __FILE__) . ' ' . $this->getHumanName() . ', PID : ' . $this->getPID());
			if ($this->getPID() > 0) {
				system::kill($this->getPID());
			}
			if ($this->running()) {
				system::kill("queue_id=" . $this->getId() . "$");
				sleep(1);
				if ($this->running()) {
					system::kill("queue_id=" . $this->getId() . "$");
					sleep(1);
				}
				if ($this->running()) {
					$this->setState('error');
					$this->setPID();
					throw new Exception($this->getHumanName() . __(' : Impossible d\'arrêter la tâche en queue', __FILE__));
				}
			} else {
				$this->setState('stop');
				$this->setPID();
			}
		}
		return true;
	}

    public function running() {
		if (($this->getState() == 'run' || $this->getState() == 'stoping') && $this->getPID() > 0) {
			if (posix_getsid($this->getPID()) && (!file_exists('/proc/' . $this->getPID() . '/cmdline') || strpos(@file_get_contents('/proc/' . $this->getPID() . '/cmdline'), 'queue_id=' . $this->getId()) !== false)) {
				return true;
			}
		}
		if (count(system::ps('queue_id=' . $this->getId() . '$')) > 0) {
			return true;
		}
		return false;
	}

    public function preSave(){
        if($this->getClass() == '' && $this->getFunction() == ''){
            throw new Exception(__('La classe est la fonction ne peuvent etre vides',__FILE__));
        }
        if($this->getClass() != '' && !class_exists($this->getClass())){
            throw new Exception(__('La classe n\'existe pas',__FILE__));
        }
        if($this->getClass() == '' && $this->getFunction() != '' && !function_exists($this->getFunction())){
            throw new Exception(__('La fonction n\'existe pas',__FILE__));
        }
        if($this->getClass() != '' && $this->getFunction() != '' && !method_exists($this->getClass(),$this->getFunction())){
            throw new Exception(__('La methode n\'existe pas',__FILE__));
        }
        if($this->getCreateTime() == ''){
            $this->setCreateTime(date('Y-m-d H:i:s'));
        }
    }

    public function save($_direct = false){
      var_dump($this);
		DB::save($this, $_direct);
	}

	public function remove($halt_before = true) {
        if ($halt_before && $this->running()) {
			$this->halt();
		}
        cache::delete('queueCacheAttr' . $this->getId());
		return DB::remove($this);
	}

    public function getHumanName(){
        if($this->getClass() != ''){
            return $this->getClass() . '::' . $this->getFunction();
        }
        return $this->getFunction();
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

    public function setId($_id) {
		$this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
		$this->id = $_id;
		return $this;
	}

    
    public function getQueueId() {
		return $this->queueId;
	}

    public function setQueueId($_queueId) {
		$this->_changed = utils::attrChanged($this->_changed,$this->queueId,$_queueId);
		$this->queueId = $_queueId;
		return $this;
	}

    public function getClass() {
		return $this->class;
	}

    public function setClass($_class) {
        $this->_changed = utils::attrChanged($this->_changed,$this->class,$_class);
		$this->class = $_class;
		return $this;
	}

    public function getFunction() {
		return $this->function;
	}

    public function setFunction($_function) {
        $this->_changed = utils::attrChanged($this->_changed,$this->function,$_function);
		$this->function = $_function;
		return $this;
	}

    public function getCreateTime() {
		return $this->createTime;
	}

    public function setCreateTime($_createTime) {
        $this->_changed = utils::attrChanged($this->_changed,$this->createTime,$_createTime);
		$this->createTime = $_createTime;
		return $this;
	}

    public function getArguments() {
		return unserialize($this->arguments);
	}

	public function setArguments($_arguments) {
		$this->arguments = serialize($_arguments);
		return $this;
	}

    public function getOptions($_key = '', $_default = '') {
		return utils::getJsonAttr($this->options, $_key, $_default);
	}

	public function setOptions($_options) {
		$this->_changed = utils::attrChanged($this->_changed, $this->options, $_options);
		$this->options = $_options;
		return $this;
	}

    public function getTimeout() {
		$timeout = $this->timeout;
		return $timeout;
	}

	public function setTimeout($_timeout) {
		$this->_changed = utils::attrChanged($this->_changed,$this->timeout,$_timeout);
		$this->timeout = $_timeout;
		return $this;
	}

    public function getPID($_default = null) {
		return $this->getCache('pid', $_default);
	}

    public function getLastRun() {
		return $this->getCache('lastRun');
	}

	public function getState() {
		return $this->getCache('state', 'stop');
	}

    public function setLastRun($_lastRun) {
		$this->setCache('lastRun', $_lastRun);
	}

	public function setState($state) {
		$this->setCache('state', $state);
	}

	public function setPID($pid = null) {
		$this->setCache('pid', $pid);
	}

    public function getCache($_key = '', $_default = '') {
		$cache = cache::byKey('queueCacheAttr' . $this->getId())->getValue();
		return utils::getJsonAttr($cache, $_key, $_default);
	}

	public function setCache($_key, $_value = null) {
		cache::set('queueCacheAttr' . $this->getId(), utils::setJsonAttr(cache::byKey('queueCacheAttr' . $this->getId())->getValue(), $_key, $_value));
	}

}