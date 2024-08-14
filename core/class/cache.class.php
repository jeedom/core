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
		return (new self())
			->setKey($_key)
			->setValue($_value)
			->setLifetime($_lifetime)
		    ->save();
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
		$engine = config::byKey('cache::engine');
		if(in_array($engine,array('MariadbCache','FileCache','RedisCache'))){
			$cache = $engine::fetch($_key);
			if (!is_object($cache)) {
				return (new self())
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

	public static function exist($_key){
		return (self::byKey($_key)->getValue(null) !== null);
	}

	public static function flush() {
		$engine = config::byKey('cache::engine');
		if(in_array($engine,array('MariadbCache','FileCache','RedisCache'))){
			return $engine::deleteAll();
		}
		switch (config::byKey('cache::engine')) {
			case 'FilesystemCache':
			case 'PhpFileCache':
				self::getCache()->deleteAll();
				shell_exec('rm -rf ' . self::getFolder() . ' 2>&1 > /dev/null');
				break;
			default:
				return;
		}
		
	}

	public static function persist() {
		switch (config::byKey('cache::engine')) {
			case 'FileCache':
			case 'PhpFileCache':
			case 'FilesystemCache':
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
		if(!in_array(config::byKey('cache::engine'),array('FilesystemCache','PhpFileCache','FileCache'))){
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
			case 'FileCache':
			case 'FilesystemCache':
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
		$engine = config::byKey('cache::engine');
		if(in_array($engine,array('MariadbCache','FileCache'))){
			$engine::clean();
		}
		if(in_array($engine,array('MariadbCache','FileCache','RedisCache'))){
			$caches = $engine::all();
			foreach ($caches as $cache) {
				$matches = null;
				preg_match_all('/camera(\d*)(.*?)/',  $cache->getKey(), $matches);
				if (isset($matches[1][0])) {
					if (!is_numeric($matches[1][0])) {
						continue;
					}
					$object = eqLogic::byId($matches[1][0]);
					if (!is_object($object)) {
						cache::delete($cache->getKey());
					}
				}
				if (strpos($cache->getKey(), 'cmd') !== false) {
					$id = str_replace('cmd', '', $cache->getKey());
					if (is_numeric($id)) {
						cache::delete($cache->getKey());
					}
					continue;
				}
				preg_match_all('/dependancy(.*)/', $cache->getKey(), $matches);
				if (isset($matches[1][0])) {
					try {
						$plugin = plugin::byId($matches[1][0]);
						if (!is_object($plugin)) {
							cache::delete($cache->getKey());
						}
					} catch (Exception $e) {
						cache::delete($cache->getKey());
					}
				}
			}
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
		foreach ($result as $key) {
			$matches = null;
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
		$this->setDatetime(strtotime('now'));
		$engine = config::byKey('cache::engine');
		if(in_array($engine,array('MariadbCache','FileCache','RedisCache'))){
			return $engine::save($this);
		}
		if ($this->getLifetime() == 0) {
			return self::getCache()->save($this->getKey(), $this);
		} else {
			return self::getCache()->save($this->getKey(), $this, $this->getLifetime());
		}
	}

	public function remove() {
		$engine = config::byKey('cache::engine');
		if(in_array($engine,array('MariadbCache','FileCache','RedisCache'))){
			return $engine::delete($this->getKey());
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
		if ($lifetime < 0) {
			$lifetime = 0;
		}
		$this->lifetime = intval($lifetime);
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

	public static function all(){
		$sql = 'SELECT `key`,`datetime`,`value`,`lifetime`
		FROM cache';
		$results =  DB::Prepare($sql,array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS,'cache');
		foreach ($results as $cache) {
			$cache->setValue(unserialize($cache->getValue()));
		}
		return $results;
	}

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
		$cache = DB::Prepare($sql,array('key' => $_key), DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS,'cache');
		if($cache === false){
			return null;
		}
		if($cache->getLifetime() > 0 && ($cache->getDatetime() + $cache->getLifetime()) < strtotime('now')){
			return null;
		}
		$cache->setValue(unserialize($cache->getValue()));
		return $cache;
	}

	public static function delete($_key){
		$sql = 'DELETE 
		FROM cache
		WHERE `key`=:key';
		return  DB::Prepare($sql,array('key' => $_key), DB::FETCH_TYPE_ROW);
	}

	public static function deleteAll(){
		return  DB::Prepare('TRUNCATE cache',array(), DB::FETCH_TYPE_ROW);
	}

	public static function save($_cache){
		$value = array(
			'key' => $_cache->getKey(),
			'value' => serialize($_cache->getValue()),
			'lifetime' =>$_cache->getLifetime(),
			'datetime' => $_cache->getDatetime()
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

	public static function all(){
		$return  = array();
		$keys = self::getConnection()->keys('*');
		foreach ($keys as $key) {
			$return[] = self::fetch($key);
		}
		return $return;
	}

	public static function fetch($_key){
		$data = self::getConnection()->get($_key);
		if($data === false){
			return null;
		}
		return unserialize($data);
	}

	public static function delete($_key){
		self::getConnection()->del($_key);
	}

	public static function deleteAll(){
		return  self::getConnection()->flushDb();
	}

	public static function save($_cache){
		if($_cache->getLifetime() > 0){
			self::getConnection()->set($_cache->getKey(),serialize($_cache), $_cache->getLifetime());
		}else{
			self::getConnection()->set($_cache->getKey(),serialize($_cache));
		}
	}

}

class FileCache {

	public static function all(){
		$return = array();
		foreach (ls(jeedom::getTmpFolder('cache'), '*',false,array('files')) as $file) {
			$return[] = self::fetch(base64_decode($file));
		}
		return $return;
	}

	public static function clean(){
		foreach (ls(jeedom::getTmpFolder('cache'), '*',false,array('files')) as $file) {
			$cache = unserialize(file_get_contents(jeedom::getTmpFolder('cache').'/'.$file));
			if($cache->getLifetime() > 0 && ($cache->getDatetime() + $cache->getLifetime()) < strtotime('now')){
				unlink(jeedom::getTmpFolder('cache').'/'.$file);
			}
		}
	}

	public static function fetch($_key){
		$data = @file_get_contents(jeedom::getTmpFolder('cache').'/'.base64_encode($_key));
        if($data === false){
        	return null;
        }
	    $cache = unserialize($data);
		if(!is_object($cache)){
			return null;
		}
		if($cache->getLifetime() > 0 && ($cache->getDatetime() + $cache->getLifetime()) < strtotime('now')){
			self::delete($_key);
			return null;
		}
		return $cache;
	}

	public static function delete($_key){
		@unlink(jeedom::getTmpFolder('cache').'/'.base64_encode($_key));
	}

	public static function deleteAll(){
		return shell_exec(system::getCmdSudo().' rm -rf '.jeedom::getTmpFolder('cache'));
	}

	public static function save($_cache){
		file_put_contents(jeedom::getTmpFolder('cache').'/'.base64_encode($_cache->getKey()),serialize($_cache));
	}
}
