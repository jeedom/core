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

class listener {
	/*     * *************************Attributs****************************** */

	private $id;
	private $event;
	private $class;
	private $function;
	private $option;
	private bool $_changed = false;

	/*     * ***********************Méthodes statiques*************************** */

    /**
     * @throws Exception
     */
    public static function clean(){
		foreach((self::all()) as $listener) {
			$events = $listener->getEvent();
			if(count($events) > 0){
				$listener->emptyEvent();
				foreach ($events as $event) {
					$cmd = cmd::byId(str_replace('#','',$event));
					if(is_object($cmd)){
						$listener->addEvent($cmd->getId());
					}
				}
				$listener->save();
				$events = $listener->getEvent();
			}
			if(count($events) == 0){
				log::add('listener','debug','Remove listener : '.json_encode(utils::o2a($listener)));
				$listener->remove();
			}
		}
	}

    /**
     * @return array|null
     * @throws ReflectionException
     */
    public static function all(): ?array
    {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM listener';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_id
     * @return array|null
     * @throws ReflectionException
     */
    public static function byId($_id): ?array
    {
		$value = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM listener
		WHERE id=:id';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_class
     * @return array|null
     * @throws ReflectionException
     */
    public static function byClass($_class): ?array
    {
		$value = array(
			'class' => $_class,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM listener
		WHERE class=:class';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_class
     * @param $_function
     * @param string $_option
     * @return array|null
     * @throws ReflectionException
     */
    public static function byClassAndFunction($_class, $_function, $_option = ''): ?array
    {
		$value = array(
			'class' => $_class,
			'function' => $_function,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM listener
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
     * @param $_class
     * @param $_function
     * @param string $_option
     * @return array|null
     * @throws ReflectionException
     */
    public static function searchClassFunctionOption($_class, $_function, $_option = ''): ?array
    {
		$value = array(
			'class' => $_class,
			'function' => $_function,
			'option' => '%' . $_option . '%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM listener
		WHERE class=:class
		AND `function`=:function
		AND `option` LIKE :option';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_class
     * @param $_function
     * @param $_event
     * @return array|null
     * @throws ReflectionException
     */
    public static function byClassFunctionAndEvent($_class, $_function, $_event): ?array
    {
		$value = array(
			'class' => $_class,
			'function' => $_function,
			'event' => $_event,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM listener
		WHERE class=:class
		AND `function`=:function
		AND event=:event';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_class
     * @param $_function
     * @param $_event
     * @param string $_option
     * @throws Exception
     */
    public static function removeByClassFunctionAndEvent($_class, $_function, $_event, $_option = '') {
		$value = array(
			'class' => $_class,
			'function' => $_function,
			'event' => $_event,
		);
		$sql = 'DELETE FROM listener
		WHERE class=:class
		AND `function`=:function
		AND event=:event';
		if ($_option != '') {
			$_option = json_encode($_option, JSON_UNESCAPED_UNICODE);
			$value['option'] = $_option;
			$sql .= ' AND `option`=:option';
		}
		DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW);
	}

    /**
     * @param $_event
     * @return array|null
     * @throws ReflectionException
     */
    public static function searchEvent($_event): ?array
    {
		if (strpos($_event, '#') !== false) {
			$value = array(
				'event' => '%' . $_event . '%',
			);
		} else {
			$value = array(
				'event' => '%#' . $_event . '#%',
			);
		}
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM listener
		WHERE `event` LIKE :event';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_event
     * @param $_value
     * @param null $_datetime
     * @throws ReflectionException
     */
    public static function check($_event, $_value, $_datetime = null) {
		$listeners = self::searchEvent($_event);
		if (is_array($listeners) && count($listeners) > 0) {
			foreach ($listeners as $listener) {
				$listener->run(str_replace('#', '', $_event), $_value, $_datetime);
			}
		}
	}

    /**
     * @param $_event
     * @throws Exception
     */
    public static function backgroundCalculDependencyCmd($_event) {
		if (count(cmd::byValue($_event, 'info')) == 0) {
			return;
		}
		$cmd = __DIR__ . '/../php/jeeListener.php';
		$cmd .= ' event_id=' . $_event;
		system::php($cmd . ' >> /dev/null 2>&1 &');
	}

    /**
     * @return string
     */
    public function getName(): string
    {
		if ($this->getClass() != '') {
			return $this->getClass() . '::' . $this->getFunction() . '()';
		}
		return $this->getFunction() . '()';
	}

	/*     * *********************Méthodes d'instance************************* */

    /**
     * @param $_event
     * @param $_value
     * @param null $_datetime
     */
    public function run($_event, $_value, $_datetime = null) {
		$option = array();
		if (count($this->getOption()) > 0) {
			$option = $this->getOption();
		}
		if (isset($option['background']) && $option['background'] == false) {
			$this->execute($_event, $_value,$_datetime);
		} else {
			$cmd = __DIR__ . '/../php/jeeListener.php';
			$cmd .= ' listener_id=' . $this->getId() . ' event_id=' . $_event . ' "value=' . escapeshellarg($_value) . '"';
			if ($_datetime !== null) {
				$cmd .= ' "datetime=' . escapeshellarg($_datetime) . '"';
			}
			system::php($cmd . ' >> ' . log::getPathToLog('listener_execution') . ' 2>&1 &');
		}
	}

    /**
     * @param $_event
     * @param $_value
     * @param string $_datetime
     */
    public function execute($_event, $_value, $_datetime = '') {
		try {
			$option = array();
			if (count($this->getOption()) > 0) {
				$option = $this->getOption();
			}
			$option['event_id'] = $_event;
			$option['value'] = $_value;
			$option['datetime'] = $_datetime;
			$option['listener_id'] = $this->getId();
			if ($this->getClass() != '') {
				$class = $this->getClass();
				$function = $this->getFunction();
				if (class_exists($class) && method_exists($class, $function)) {
					$class::$function($option);
				} else {
					log::add('listener', 'debug', __('[Erreur] Classe ou fonction non trouvée ', __FILE__) . json_encode(utils::o2a($this)));
					$this->remove();
					return;
				}
			} else {
				$function = $this->getFunction();
				if (function_exists($function)) {
					$function($option);
				} else {
					log::add('listener', 'error', __('[Erreur] Non trouvée ', __FILE__) . json_encode(utils::o2a($this)));
					return;
				}
			}
		} catch (Exception $e) {
			log::add(init('plugin_id', 'plugin'), 'error', $e->getMessage());
		}
	}

    /**
     * @throws Exception
     */
    public function preSave() {
		if ($this->getFunction() == '') {
			throw new Exception(__('La fonction ne peut pas être vide', __FILE__));
		}
	}

    /**
     * @param false $_once
     * @return bool
     * @throws Exception
     */
    public function save($_once = false): bool
    {
		if ($_once) {
			self::removeByClassFunctionAndEvent($this->getClass(), $this->getFunction(), $this->event, $this->getOption());
		}
		DB::save($this);
		return true;
	}

    /**
     * @return bool
     * @throws Exception
     */
    public function remove(): bool
    {
		return DB::remove($this);
	}

	public function emptyEvent() {
		$this->event = array();
	}

    /**
     * @param $_id
     * @param string $_type
     */
    public function addEvent($_id, $_type = 'cmd') {
		$event = $this->getEvent();
		if (!is_array($event)) {
			$event = array();
		}
		if ($_type == 'cmd') {
			$id = str_replace('#', '', $_id);
		}
		if (!in_array('#' . $id . '#', $event)) {
			$event[] = '#' . $id . '#';
		}
		$this->setEvent($event);
	}

	/*     * **********************Getteur Setteur*************************** */

    /**
     * @return mixed
     */
    public function getId() {
		return $this->id;
	}

    /**
     * @return array|bool|mixed
     */
    public function getEvent() {
		return is_json($this->event, array());
	}

    /**
     * @return mixed
     */
    public function getClass() {
		return $this->class;
	}

    /**
     * @return mixed
     */
    public function getFunction() {
		return $this->function;
	}

    /**
     * @param string $_key
     * @param string $_default
     * @return array|bool|mixed|string
     */
    public function getOption($_key = '', $_default = '') {
		return utils::getJsonAttr($this->option, $_key, $_default);
	}

    /**
     * @param $_id
     * @return $this
     */
    public function setId($_id): listener
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
		$this->id = $_id;
		return $this;
	}

	public function setEvent($_event): listener
    {
		$event = json_encode($_event, JSON_UNESCAPED_UNICODE);
		$this->_changed = utils::attrChanged($this->_changed,$this->event,$event);
		$this->event = $event;
		return $this;
	}

    /**
     * @param $_class
     * @return $this
     */
    public function setClass($_class): listener
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->class,$_class);
		$this->class = $_class;
		return $this;
	}

    /**
     * @param $_function
     * @return $this
     */
    public function setFunction($_function): listener
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->function,$_function);
		$this->function = $_function;
		return $this;
	}

    /**
     * @param $_key
     * @param string $_value
     * @return $this
     */
    public function setOption($_key, $_value = ''): listener
    {
		$option = utils::setJsonAttr($this->option, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed,$this->option,$option);
		$this->option = $option;
		return $this;
	}

    /**
     * @return bool
     */
    public function getChanged(): bool
    {
		return $this->_changed;
	}

    /**
     * @param $_changed
     * @return $this
     */
    public function setChanged($_changed): listener
    {
		$this->_changed = $_changed;
		return $this;
	}

}
