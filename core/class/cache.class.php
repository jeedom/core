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

class cache {
	/*     * *************************Attributs****************************** */

	private static $cache = null;

	private $key;
	private $value = null;
	private $lifetime = 0;
	private $datetime;
	private $options = null;

	/*     * ***********************Methode static*************************** */

	public static function set($_key, $_value, $_lifetime = 60, $_options = null) {
		if ($_lifetime < 0) {
			$_lifetime = 0;
		}
		$cache = new self();
		$cache->setKey($_key);
		$cache->setValue($_value);
		$cache->setLifetime($_lifetime);
		if ($_options != null) {
			$cache->options = json_encode($_options, JSON_UNESCAPED_UNICODE);
		}
		return $cache->save();
	}

	public static function getCache() {
		if (self::$cache !== null) {
			return self::$cache;
		}
		self::$cache = new \Doctrine\Common\Cache\FilesystemCache("/tmp/jeedom-cache");
		return self::$cache;
	}

	public static function byKey($_key, $_noRemove = false) {
		$cache = self::getCache()->fetch($_key);
		if (!is_object($cache)) {
			$cache = new self();
			$cache->setKey($_key);
			$cache->setDatetime(date('Y-m-d H:i:s'));
		}
		return $cache;
	}

	public static function flush() {
		self::getCache()->deleteAll();
		shell_exec('sudo rm -rf /tmp/jeedom-cache 2>&1 > /dev/null');
		shell_exec('rm -rf /tmp/jeedom-cache 2>&1 > /dev/null');
	}

	/*     * *********************Methode d'instance************************* */

	public function save() {
		$this->setDatetime(date('Y-m-d H:i:s'));
		if ($this->getLifetime() < 2) {
			return self::getCache()->save($this->getKey(), $this);
		} else {
			return self::getCache()->save($this->getKey(), $this, $this->getLifetime());
		}
	}

	public function remove() {
		self::getCache()->delete($this->getKey());
	}

	public function hasExpired() {
		return true;
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getKey() {
		return $this->key;
	}

	public function setKey($key) {
		$this->key = $key;
	}

	public function getValue($_default = '') {
		return ($this->value === null || trim($this->value) === '') ? $_default : $this->value;
	}

	public function setValue($value) {
		$this->value = $value;
	}

	public function getLifetime() {
		return $this->lifetime;
	}

	public function setLifetime($lifetime) {
		$this->lifetime = $lifetime;
	}

	public function getDatetime() {
		return $this->datetime;
	}

	public function setDatetime($datetime) {
		$this->datetime = $datetime;
	}

	public function getOptions($_key = '', $_default = '') {
		return utils::getJsonAttr($this->options, $_key, $_default);
	}

	public function setOptions($_key, $_value) {
		$this->options = utils::setJsonAttr($this->options, $_key, $_value);
	}

}

?>
