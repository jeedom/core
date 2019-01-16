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

class cache {
	/*     * *************************Attributs****************************** */

	private static $cache = null;

	private $key;
	private $value = null;
	private $lifetime = 0;
	private $datetime;
	private $options = null;

	/*     * ***********************Methode static*************************** */

	public static function getFolder() {
		return jeedom::getTmpFolder('cache');
	}

	public static function set($_key, $_value, $_lifetime = 0, $_options = null) {
		if ($_lifetime < 0) {
			$_lifetime = 0;
		}
		$cache = (new self())
			->setKey($_key)
			->setValue($_value)
			->setLifetime($_lifetime);
		if ($_options != null) {
			$cache->options = json_encode($_options, JSON_UNESCAPED_UNICODE);
		}
		return $cache->save();
	}

	public static function delete($_key) {
		$cache = cache::byKey($_key);
		if (is_object($cache)) {
			$cache->remove();
		}
	}

	public static function stats($_details = false) {
		$return = self::getCache()->getStats();
		$return['count'] = __('Inconnu', __FILE__);
		if (config::byKey('cache::engine') == 'FilesystemCache') {
			$return['count'] = 0;
			foreach (ls(self::getFolder()) as $folder) {
				foreach (ls(self::getFolder() . '/' . $folder) as $file) {
					if (strpos($file, 'swap') !== false) {
						continue;
					}
					$return['count']++;
				}
			}
		}
		if ($_details) {
			$re = '/s:\d*:(.*?);s:\d*:"(.*?)";s/';
			$result = array();
			foreach (ls(self::getFolder()) as $folder) {
				foreach (ls(self::getFolder() . '/' . $folder) as $file) {
					$path = self::getFolder() . '/' . $folder . '/' . $file;
					$str = (string) str_replace("\n", '', file_get_contents($path));
					preg_match_all($re, $str, $matches);
					if (!isset($matches[2]) || !isset($matches[2][0]) || trim($matches[2][0]) == '') {
						continue;
					}
					$result[] = $matches[2][0];
				}
			}
			$return['details'] = $result;
		}
		return $return;
	}
	/**
	 * @name getCache()
	 * @access public
	 * @static
	 * @return type
	 */
	public static function getCache() {
		if (self::$cache !== null) {
			return self::$cache;
		}
		$engine = config::byKey('cache::engine');
		if ($engine == 'MemcachedCache' && !class_exists('memcached')) {
			$engine = 'FilesystemCache';
			config::save('cache::engine', 'FilesystemCache');
		}
		if ($engine == 'RedisCache' && !class_exists('redis')) {
			$engine = 'FilesystemCache';
			config::save('cache::engine', 'FilesystemCache');
		}
		switch ($engine) {
			case 'FilesystemCache':
				self::$cache = new \Doctrine\Common\Cache\FilesystemCache(self::getFolder());
				break;
			case 'PhpFileCache':
				self::$cache = new \Doctrine\Common\Cache\FilesystemCache(self::getFolder());
				break;
			case 'MemcachedCache':
				$memcached = new Memcached();
				$memcached->addServer(config::byKey('cache::memcacheaddr'), config::byKey('cache::memcacheport'));
				self::$cache = new \Doctrine\Common\Cache\MemcachedCache();
				self::$cache->setMemcached($memcached);
				break;
			case 'RedisCache':
				$redis = new Redis();
				$redis->connect(config::byKey('cache::redisaddr'), config::byKey('cache::redisport'));
				self::$cache = new \Doctrine\Common\Cache\RedisCache();
				self::$cache->setRedis($redis);
				break;
			default:
				self::$cache = new \Doctrine\Common\Cache\FilesystemCache(self::getFolder());
				break;
		}
		return self::$cache;
	}

	/**
	 *
	 * @param type $_key
	 * @return type
	 */
	public static function byKey($_key) {
		$cache = self::getCache()->fetch($_key);
		if (!is_object($cache)) {
			$cache = (new self())
				->setKey($_key)
				->setDatetime(date('Y-m-d H:i:s'));
		}
		return $cache;
	}

	public static function exist($_key) {
		return is_object(self::getCache()->fetch($_key));
	}

	public static function flush() {
		self::getCache()->deleteAll();
		shell_exec('rm -rf ' . self::getFolder() . ' 2>&1 > /dev/null');
	}

	public static function search() {
		return array();
	}

	public static function persist() {
		switch (config::byKey('cache::engine')) {
			case 'FilesystemCache':
				$cache_dir = self::getFolder();
				break;
			case 'PhpFileCache':
				$cache_dir = self::getFolder();
				break;
			default:
				return;
		}
		try {
			$cmd = system::getCmdSudo() . 'rm -rf ' . __DIR__ . '/../../cache.tar.gz;cd ' . $cache_dir . ';';
			$cmd .= system::getCmdSudo() . 'tar cfz ' . __DIR__ . '/../../cache.tar.gz * 2>&1 > /dev/null;';
			// $cmd .= system::getCmdSudo() . 'chmod 774 ' . __DIR__ . '/../../cache.tar.gz;';
			// $cmd .= system::getCmdSudo() . 'chown ' . system::get('www-uid') . ':' . system::get('www-gid') . ' ' . __DIR__ . '/../../cache.tar.gz;';
			// $cmd .= system::getCmdSudo() . 'chown -R ' . system::get('www-uid') . ':' . system::get('www-gid') . ' ' . $cache_dir . ';';
			// $cmd .= system::getCmdSudo() . 'chmod 774 -R ' . $cache_dir . ' 2>&1 > /dev/null';
			com_shell::execute($cmd);
		} catch (Exception $e) {

		}

	}

	public static function isPersistOk() {
		if (config::byKey('cache::engine') != 'FilesystemCache' && config::byKey('cache::engine') != 'PhpFileCache') {
			return true;
		}
		$filename = __DIR__ . '/../../cache.tar.gz';
		if (!file_exists($filename)) {
			return false;
		}
		if (filemtime($filename) < strtotime('-35min')) {
			return false;
		}
		return true;
	}

	public static function restore() {
		switch (config::byKey('cache::engine')) {
			case 'FilesystemCache':
				$cache_dir = self::getFolder();
				break;
			case 'PhpFileCache':
				$cache_dir = self::getFolder();
				break;
			default:
				return;
		}
		if (!file_exists(__DIR__ . '/../../cache.tar.gz')) {
			$cmd = 'mkdir ' . $cache_dir . ';';
			$cmd .= 'chmod -R 777 ' . $cache_dir . ';';
			com_shell::execute($cmd);
			return;
		}
		$cmd = 'rm -rf ' . $cache_dir . ';';
		$cmd .= 'mkdir ' . $cache_dir . ';';
		$cmd .= 'cd ' . $cache_dir . ';';
		$cmd .= 'tar xfz ' . __DIR__ . '/../../cache.tar.gz;';
		$cmd .= 'chmod -R 777 ' . $cache_dir . ' 2>&1 > /dev/null;';
		com_shell::execute($cmd);
	}

	public static function clean() {
		if (config::byKey('cache::engine') != 'FilesystemCache') {
			return;
		}
		$re = '/s:\d*:(.*?);s:\d*:"(.*?)";s/';
		$result = array();
		foreach (ls(self::getFolder()) as $folder) {
			foreach (ls(self::getFolder() . '/' . $folder) as $file) {
				$path = self::getFolder() . '/' . $folder . '/' . $file;
				if (strpos($file, 'swap') !== false) {
					unlink($path);
					continue;
				}
				$str = (string) str_replace("\n", '', file_get_contents($path));
				preg_match_all($re, $str, $matches);
				if (!isset($matches[2]) || !isset($matches[2][0]) || trim($matches[2][0]) == '') {
					continue;
				}
				$result[] = $matches[2][0];
			}
		}
		$cleanCache = array(
			'cmdCacheAttr' => 'cmd',
			'cmd' => 'cmd',
			'eqLogicCacheAttr' => 'eqLogic',
			'eqLogicStatusAttr' => 'eqLogic',
			'scenarioCacheAttr' => 'scenario',
			'cronCacheAttr' => 'cron',
			'cron' => 'cron',
		);
		foreach ($result as $key) {
			$matches = null;
			if (strpos($key, '::lastCommunication') !== false) {
				cache::delete($key);
				continue;
			}
			if (strpos($key, '::state') !== false) {
				cache::delete($key);
				continue;
			}
			if (strpos($key, '::numberTryWithoutSuccess') !== false) {
				cache::delete($key);
				continue;
			}
			foreach ($cleanCache as $kClean => $value) {
				if (strpos($key, $kClean) !== false) {
					$id = str_replace($kClean, '', $key);
					if (!is_numeric($id)) {
						continue;
					}
					$object = $value::byId($id);
					if (!is_object($object)) {
						cache::delete($key);
					}
					continue;
				}
			}
			preg_match_all('/widgetHtml(\d*)(.*?)/', $key, $matches);
			if (isset($matches[1]) && isset($matches[1][0])) {
				if (!is_numeric($matches[1][0])) {
					continue;
				}
				$object = eqLogic::byId($matches[1][0]);
				if (!is_object($object)) {
					cache::delete($key);
				}
			}
			preg_match_all('/camera(\d*)(.*?)/', $key, $matches);
			if (isset($matches[1]) && isset($matches[1][0])) {
				if (!is_numeric($matches[1][0])) {
					continue;
				}
				$object = eqLogic::byId($matches[1][0]);
				if (!is_object($object)) {
					cache::delete($key);
				}
			}
			preg_match_all('/scenarioHtml(.*?)(\d*)/', $key, $matches);
			if (isset($matches[1]) && isset($matches[1][0])) {
				if (!is_numeric($matches[1][0])) {
					continue;
				}
				$object = scenario::byId($matches[1][0]);
				if (!is_object($object)) {
					cache::delete($key);
				}
			}
			if (strpos($key, 'widgetHtmlmobile') !== false) {
				$id = str_replace('widgetHtmlmobile', '', $key);
				if (is_numeric($id)) {
					cache::delete($key);
				}
				continue;
			}
			if (strpos($key, 'widgetHtmldashboard') !== false) {
				$id = str_replace('widgetHtmldashboard', '', $key);
				if (is_numeric($id)) {
					cache::delete($key);
				}
				continue;
			}
			if (strpos($key, 'widgetHtmldplan') !== false) {
				$id = str_replace('widgetHtmldplan', '', $key);
				if (is_numeric($id)) {
					cache::delete($key);
				}
				continue;
			}
			if (strpos($key, 'widgetHtml') !== false) {
				$id = str_replace('widgetHtml', '', $key);
				if (is_numeric($id)) {
					cache::delete($key);
				}
				continue;
			}
			if (strpos($key, 'cmd') !== false) {
				$id = str_replace('cmd', '', $key);
				if (is_numeric($id)) {
					cache::delete($key);
				}
				continue;
			}
			preg_match_all('/dependancy(.*)/', $key, $matches);
			if (isset($matches[1]) && isset($matches[1][0])) {
				try {
					$plugin = plugin::byId($matches[1][0]);
					if (!is_object($plugin)) {
						cache::delete($key);
					}
				} catch (Exception $e) {
					cache::delete($key);
				}
			}
		}
	}

	/*     * *********************Methode d'instance************************* */

	public function save() {
		$this->setDatetime(date('Y-m-d H:i:s'));
		if ($this->getLifetime() == 0) {
			return self::getCache()->save($this->getKey(), $this);
		} else {
			return self::getCache()->save($this->getKey(), $this, $this->getLifetime());
		}
	}

	public function remove() {
		try {
			self::getCache()->delete($this->getKey());
		} catch (Exception $e) {

		}
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
		return $this;
	}

	public function getValue($_default = '') {
		return ($this->value === null || (is_string($this->value) && trim($this->value) === '')) ? $_default : $this->value;
	}

	public function setValue($value) {
		$this->value = $value;
		return $this;
	}

	public function getLifetime() {
		return $this->lifetime;
	}

	public function setLifetime($lifetime) {
		$this->lifetime = $lifetime;
		return $this;
	}

	public function getDatetime() {
		return $this->datetime;
	}

	public function setDatetime($datetime) {
		$this->datetime = $datetime;
		return $this;
	}

	public function getOptions($_key = '', $_default = '') {
		return utils::getJsonAttr($this->options, $_key, $_default);
	}

	public function setOptions($_key, $_value = null) {
		$this->options = utils::setJsonAttr($this->options, $_key, $_value);
		return $this;
	}

}
