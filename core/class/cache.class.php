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

    /**
     * @return string
     */
    public static function getFolder(): string
    {
		return jeedom::getTmpFolder('cache');
	}

    /**
     * @param $_key
     * @param $_value
     * @param int $_lifetime
     * @param null $_options
     * @return bool
     */
    public static function set($_key, $_value, $_lifetime = 0, $_options = null): bool
    {
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

    /**
     * @param $_key
     */
    public static function delete($_key) {
		$cache = cache::byKey($_key);
		if (is_object($cache)) {
			$cache->remove();
		}
	}

    /**
     * @param false $_details
     * @return array|null
     * @throws Exception
     */
    public static function stats($_details = false): ?array
    {
		$return = self::getCache()->getStats();
		$return['count'] = __('Inconnu', __FILE__);
		$engine = config::byKey('cache::engine');
		if ($engine == 'FilesystemCache') {
			$return['count'] = 0;
			foreach (ls(self::getFolder()) as $folder) {
				foreach (ls(self::getFolder() . '/' . $folder) as $file) {
					if (strpos($file, 'swap') !== false) {
						continue;
					}
					$return['count']++;
				}
			}
		} else if($engine == 'RedisCache') {
			$return['count'] = self::$cache->getRedis()->dbSize();
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
     * @return type
     * @throws Exception
     * @access public
     * @static
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
			$redisAddr = config::byKey('cache::redisaddr');
			if (strncmp($redisAddr, '/', 1) === 0) {
				$redis->connect($redisAddr);
			} else {
				$redis->connect($redisAddr, config::byKey('cache::redisport'));
			}
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
     * @param $_key
     * @return cache
     * @throws Exception
     */
	public static function byKey($_key): cache
    {
		$cache = self::getCache()->fetch($_key);
		if (!is_object($cache)) {
			$cache = (new self())
			->setKey($_key)
			->setDatetime(date('Y-m-d H:i:s'));
		}
		return $cache;
	}

    public static function exist($_key): bool
    {
		return is_object(self::getCache()->fetch($_key));
	}

    public static function flush() {
        self::getCache()->deleteAll();
        shell_exec('rm -rf ' . self::getFolder() . ' 2>&1 > /dev/null');
    }

    public static function flushWidget() {
		foreach((eqLogic::all()) as $eqLogic) {
			try {
				$eqLogic->emptyCacheWidget();
			} catch (Exception $e) {

			}
		}
		foreach((scenario::all()) as $scenario) {
			try {
				$scenario->emptyCacheWidget();
			} catch (Exception $e) {

			}
		}
	}

    /**
     * @return array
     */
    public static function search(): array
    {
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
			$cmd .= system::getCmdSudo() . 'chmod 774 ' . __DIR__ . '/../../cache.tar.gz;';
			$cmd .= system::getCmdSudo() . 'chown ' . system::get('www-uid') . ':' . system::get('www-gid') . ' ' . __DIR__ . '/../../cache.tar.gz;';
			$cmd .= system::getCmdSudo() . 'chown -R ' . system::get('www-uid') . ':' . system::get('www-gid') . ' ' . $cache_dir . ';';
			$cmd .= system::getCmdSudo() . 'chmod 774 -R ' . $cache_dir . ' 2>&1 > /dev/null';
			com_shell::execute($cmd);
		} catch (Exception $e) {

		}

	}

    /**
     * @return bool
     */
    public static function isPersistOk(): bool
    {
		if (config::byKey('cache::engine') != 'FilesystemCache' && config::byKey('cache::engine') != 'PhpFileCache') {
			return true;
		}
		$filename = __DIR__ . '/../../cache.tar.gz';
		if (!file_exists($filename)) {
			return false;
		}
		if (filemtime($filename) < strtotime('-65min')) {
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

    /**
     * @return bool
     */
    public function save(): bool
    {
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

	public function hasExpired(): bool
    {
		return true;
	}

	/*     * **********************Getteur Setteur*************************** */

    /**
     * @return mixed
     */
    public function getKey() {
		return $this->key;
	}

    /**
     * @param $key
     * @return $this
     */
    public function setKey($key): cache
    {
		$this->key = $key;
		return $this;
	}

    /**
     * @param string $_default
     * @return string
     */
    public function getValue($_default = ''): string
    {
		return ($this->value === null || (is_string($this->value) && trim($this->value) === '')) ? $_default : $this->value;
	}

    /**
     * @param $value
     * @return $this
     */
    public function setValue($value): cache
    {
		$this->value = $value;
		return $this;
	}

    /**
     * @return int
     */
    public function getLifetime(): int
    {
		return $this->lifetime;
	}

    /**
     * @param $lifetime
     * @return $this
     */
    public function setLifetime($lifetime): cache
    {
		$this->lifetime = $lifetime;
		return $this;
	}

    /**
     * @return mixed
     */
    public function getDatetime() {
		return $this->datetime;
	}

    /**
     * @param $datetime
     * @return $this
     */
    public function setDatetime($datetime): cache
    {
		$this->datetime = $datetime;
		return $this;
	}

    /**
     * @param string $_key
     * @param string $_default
     * @return array|bool|mixed|string
     */
    public function getOptions($_key = '', $_default = '') {
		return utils::getJsonAttr($this->options, $_key, $_default);
	}

    /**
     * @param $_key
     * @param null $_value
     * @return $this
     */
    public function setOptions($_key, $_value = null): cache
    {
		$this->options = utils::setJsonAttr($this->options, $_key, $_value);
		return $this;
	}

}
