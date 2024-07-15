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

	/*     * ***********************Methode static*************************** */

	public static function getFolder() {
		return jeedom::getTmpFolder('cache');
	}

	public static function set($_key, $_value, $_lifetime = 0) {
		if ($_lifetime < 0) {
			$_lifetime = 0;
		}
		$cache = (new self())
			->setKey($_key)
			->setValue($_value)
			->setLifetime($_lifetime);
		return $cache->save();
	}

	public static function delete($_key) {
		$cache = cache::byKey($_key);
		if (is_object($cache)) {
			$cache->remove();
		}
	}

	/**
	 * @name getCache()
	 * @access public
	 * @static
	 * @param string $_engine to override the current cache defined in configuration
	 * @return \Doctrine\Common\Cache\CacheProvider
	 */
	public static function getCache($_engine = null) {
		if ($_engine === null && self::$cache !== null) {
			return self::$cache;
		}
		if( $_engine === null){
			// get current cache
			$engine = config::byKey('cache::engine');
		}else{
			// override existing configuration
			$engine = $_engine;
			config::save('cache::engine', $_engine);
		}
		switch ($engine) {
			case 'PhpFileCache':
				self::$cache = new \Doctrine\Common\Cache\FilesystemCache(self::getFolder());
				break;
			case 'MemcachedCache':
				// check if memcached extention is available
				if (!class_exists('memcached')) {
					log::add( __CLASS__, 'error', 'memcached extension not installed, fall back to FilesystemCache.');
					return self::getCache( 'FilesystemCache');
				}
				$memcached = new Memcached();
				$memcached->addServer(config::byKey('cache::memcacheaddr'), config::byKey('cache::memcacheport'));
				self::$cache = new \Doctrine\Common\Cache\MemcachedCache();
				self::$cache->setMemcached($memcached);
				break;
			default: // default is FilesystemCache
				self::$cache = new \Doctrine\Common\Cache\FilesystemCache(self::getFolder());
				break;
		}
		return self::$cache;
	}

	/**
	 *
	 * @param string $_key
	 * @return object
	 */
	public static function byKey($_key) {
        if(config::byKey('cache::engine') == 'MariadbCache'){
		  $cache =  MariadbCache::fetch($_key);
          if (!is_object($cache)) {
			$cache = (new self())
				->setKey($_key)
				->setDatetime(date('Y-m-d H:i:s'));
			}
          return $cache;
		}
		if(config::byKey('cache::engine') == 'RedisCache'){
			$cache =  RedisCache::fetch($_key);
			if (!is_object($cache)) {
			  $cache = (new self())
				  ->setKey($_key)
				  ->setDatetime(date('Y-m-d H:i:s'));
			  }
			return $cache;
		}
		if(config::byKey('cache::engine') == 'FileCache'){
			$cache =  FileCache::fetch($_key);
			if (!is_object($cache)) {
			  $cache = (new self())
				  ->setKey($_key)
				  ->setDatetime(date('Y-m-d H:i:s'));
			  }
			return $cache;
		}
		// Try/catch/debug to address issue https://github.com/jeedom/core/issues/2426
		try {
			$cache = self::getCache()->fetch($_key);
		} catch (Error $e) {
			log::add(__CLASS__, 'debug', 'Error in ' . __FUNCTION__ . '(): ' . $e->getMessage() . ', trace: ' . $e->getTraceAsString());
			$cache = null;
		}
		if (!is_object($cache)) {
			$cache = (new self())
				->setKey($_key)
				->setDatetime(date('Y-m-d H:i:s'));
		}
		return $cache;
	}

	public static function flush() {
		switch (config::byKey('cache::engine')) {
			case 'FilesystemCache':
			case 'PhpFileCache':
				self::getCache()->deleteAll();
				shell_exec('rm -rf ' . self::getFolder() . ' 2>&1 > /dev/null');
				break;
			case 'MariadbCache':
				MariadbCache::deleteAll();
			case 'RedisCache':
				RedisCache::deleteAll();
			case 'FileCache':
				FileCache::deleteAll();
			default:
				return;
		}
		
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

	public static function isPersistOk(): bool {
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
		if (config::byKey('cache::engine') == 'MariadbCache') {
			return MariadbCache::clean();
		}
		if (config::byKey('cache::engine') == 'FileCache') {
			return FileCache::clean();
		}
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
			if (isset($matches[1][0])) {
				if (!is_numeric($matches[1][0])) {
					continue;
				}
				$object = eqLogic::byId($matches[1][0]);
				if (!is_object($object)) {
					cache::delete($key);
				}
			}
			preg_match_all('/camera(\d*)(.*?)/', $key, $matches);
			if (isset($matches[1][0])) {
				if (!is_numeric($matches[1][0])) {
					continue;
				}
				$object = eqLogic::byId($matches[1][0]);
				if (!is_object($object)) {
					cache::delete($key);
				}
			}
			preg_match_all('/scenarioHtml(.*?)(\d*)/', $key, $matches);
			if (isset($matches[1][0])) {
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
			if (isset($matches[1][0])) {
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
		if(config::byKey('cache::engine') == 'MariadbCache'){
			return MariadbCache::save($this->getKey(),$this->getValue(),$this->getLifetime());
		}
		if(config::byKey('cache::engine') == 'RedisCache'){
			return RedisCache::save($this->getKey(),$this->getValue(),$this->getLifetime());
		}
		if(config::byKey('cache::engine') == 'FileCache'){
			return FileCache::save($this->getKey(),$this->getValue(),$this->getLifetime());
		}
		$this->setDatetime(date('Y-m-d H:i:s'));
		if ($this->getLifetime() == 0) {
			return self::getCache()->save($this->getKey(), $this);
		} else {
			return self::getCache()->save($this->getKey(), $this, $this->getLifetime());
		}
	}

	public function remove() {
		if(config::byKey('cache::engine') == 'MariadbCache'){
			return MariadbCache::delete($this->getKey());
		}
		if(config::byKey('cache::engine') == 'RedisCache'){
			return RedisCache::delete($this->getKey());
		}
		if(config::byKey('cache::engine') == 'FileCache'){
			return FileCache::delete($this->getKey());
		}
		try {
			self::getCache()->delete($this->getKey());
		} catch (Exception $e) {
		}
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getKey() {
		return $this->key;
	}

	public function setKey($key): self {
		$this->key = $key;
		return $this;
	}

	public function getValue($_default = '') {
		return ($this->value === null || (is_string($this->value) && trim($this->value) === '')) ? $_default : $this->value;
	}

	public function setValue($value): self {
		$this->value = $value;
		return $this;
	}

	public function getLifetime() {
		return $this->lifetime;
	}

	public function setLifetime($lifetime): self {
		$this->lifetime = $lifetime;
		return $this;
	}

	public function getDatetime() {
		return $this->datetime;
	}

	public function setDatetime($datetime): self {
		$this->datetime = $datetime;
		return $this;
	}
}


class MariadbCache {

	public static function clean(){
		$sql = 'DELETE 
		FROM cache
		WHERE (UNIX_TIMESTAMP(`datetime`)+`lifetime`) < UNIX_TIMESTAMP()';
		return  DB::Prepare($sql,array(), DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS);
	}

	public static function fetch($_key){
		$sql = 'SELECT `key`,`datetime`,`value`,`lifetime`
		FROM cache
		WHERE `key`=:key';
		$return = DB::Prepare($sql,array('key' => $_key), DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS,'cache');
		if($return === false){
			return null;
		}
		if($return->getLifetime() > 0 && (strtotime($return->getDatetime()) + $return->getLifetime()) < strtotime('now')){
			return null;
		}
		return $return;
	}

	public static function delete($_key){
		$sql = 'DELETE 
		FROM cache
		WHERE `key`=:key';
		return  DB::Prepare($sql,array('key' => $_key), DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS);
	}

	public static function deleteAll(){
		return  DB::Prepare('TRUNCATE cache',array(), DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS);
	}

	public static function save($_key, $_value, $_lifetime = -1){
		if(is_array($_value)){
			$_value = json_encode($_value, JSON_UNESCAPED_UNICODE);
		}
		$value = array(
			'key' => $_key,
			'value' => $_value,
			'lifetime' =>$_lifetime,
			'datetime' => date('Y-m-d H:i:s')
		);
		$sql = 'REPLACE INTO cache SET `key`=:key, `value`=:value,`datetime`=:datetime,`lifetime`=:lifetime';
		return  DB::Prepare($sql,$value, DB::FETCH_TYPE_ROW);
	}

}

class RedisCache {

	private static $connection = null;

	public static function getConnection(){
		if(static::$connection !== null){
			return static::$connection;
		}
		$redis = new Redis();
		$redis->connect(config::byKey('cache::redisaddr'), config::byKey('cache::redisport'));
		static::$connection = $redis;
		return static::$connection;
	}

	public static function fetch($_key){
		$value = self::getConnection()->get($_key);
		if($value === false){
			return null;
		}
		$data = json_decode($value,true);
		$cache = (new cache())
			->setKey($_key)
			->setLifetime($data['lifetime'])
			->setDatetime($data['datetime'])
			->setValue($data['value']);
		return $cache;
	}

	public static function delete($_key){
		self::getConnection()->del($_key);
	}

	public static function deleteAll(){
		return  self::getConnection()->flushDb();
	}

	public static function save($_key, $_value, $_lifetime = -1){
		$data = array(
			'value' => $_value,
			'lifetime' => $_lifetime,
			'datetime' => strtotime('now'),
		);
		if($_lifetime > 0){
			self::getConnection()->set($_key,json_encode($data, JSON_UNESCAPED_UNICODE), $_lifetime);
		}else{
			self::getConnection()->set($_key,json_encode($data, JSON_UNESCAPED_UNICODE));
		}
	}

}


class FileCache {

	public static function clean(){
		foreach (ls(jeedom::getTmpFolder('cache'), '*',false,array('files')) as $file) {
			$data = json_decode(file_get_contents(jeedom::getTmpFolder('cache').'/'.$file),true);
			if($data['lifetime'] > 0 && (strtotime($data['datetime']) + $data['lifetime']) < strtotime('now')){
				unlink(jeedom::getTmpFolder('cache').'/'.$file);
			}
		}
	}

	public static function fetch($_key){
		$data = json_decode(file_get_contents(jeedom::getTmpFolder('cache').'/'.base64_encode($_key)),true);
		if($data['lifetime'] > 0 && (strtotime($data['datetime']) + $data['lifetime']) < strtotime('now')){
			self::delete($_key);
			return null;
		}
		$cache = (new cache())
			->setKey($_key)
			->setLifetime($data['lifetime'])
			->setDatetime($data['datetime'])
			->setValue($data['value']);
		return $cache;
	}

	public static function delete($_key){
		unlink(jeedom::getTmpFolder('cache').'/'.base64_encode($_key));
	}

	public static function deleteAll(){
		return shell_exec(system::getCmdSudo().' rm -rf '.jeedom::getTmpFolder('cache'));
	}

	public static function save($_key, $_value, $_lifetime = -1){
		file_put_contents(jeedom::getTmpFolder('cache').'/'.base64_encode($_key),json_encode(array(
			'value' => $_value,
			'lifetime' => $_lifetime,
			'datetime' => strtotime('now'),
		), JSON_UNESCAPED_UNICODE));
	}

}