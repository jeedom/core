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

/**
 * Main cache handling class implementing a flexible caching system
 *
 * Provides a facade for different cache backends (File, Redis, MariaDB) with consistent interface
 *
 * @see FileCache For file-based cache implementation
 * @see RedisCache For Redis-based cache implementation
 * @see MariadbCache For database-based cache implementation
 */
class cache {
	/*     * *************************Attributs****************************** */

    /** @var string Cache entry key */
    private $key;

    /** @var mixed|null Cache entry value */
    private $value = null;

    /** @var int Cache lifetime in seconds (0 for infinite) */
    private $lifetime = 0;

    /** @var int Unix timestamp of cache entry creation */
    private $timestamp;

    /** @var string|null Current cache engine class name */
    private static $_engine = null;

	/*     * ***********************Methode static*************************** */

    /**
     * Get current cache engine class name
     *
     * @return string Cache engine class name ('FileCache', 'RedisCache', or 'MariadbCache')
     */
	public static function getEngine(){
		if(self::$_engine != null){
			return self::$_engine;
		}
		self::$_engine = config::byKey('cache::engine');
		if(!class_exists(self::$_engine)){
			config::save('cache::engine','FileCache');
			self::$_engine = 'FileCache';
		}
		if(method_exists(self::$_engine,'isOk') && !self::$_engine::isOk()){
			config::save('cache::engine','FileCache');
			self::$_engine = 'FileCache';
		}
		return self::$_engine;
	}

    /**
     * Set a cache entry
     *
     * @param string $_key Cache key
     * @param mixed $_value Value to cache
     * @param int $_lifetime Cache lifetime in seconds (0 for infinite)
     * @return mixed Result depends on cache engine implementation
     */
	public static function set($_key, $_value, $_lifetime = 0) {
		return (new self())
			->setKey($_key)
			->setValue($_value)
			->setLifetime($_lifetime)
		    ->save();
	}

    /**
     * Delete a cache entry
     *
     * @param string $_key Cache key to delete
     * @return void
     */
	public static function delete($_key) {
		$cache = cache::byKey($_key);
		if (is_object($cache)) {
			$cache->remove();
		}
	}

    /**
     * Get cache entry by key
     *
     * @param string $_key Cache key
     * @return cache Always returns a cache object (value will be null if not found)
     */
	public static function byKey($_key) {
		$cache = self::getEngine()::fetch($_key);
		if (!is_object($cache)) {
			return (new self())
				->setKey($_key)
				->setTimestamp(strtotime('now'));
		}
		return $cache;
	}

    /**
     * Check if a cache entry exists and is valid
     *
     * @param string $_key Cache key
     * @return bool True if cache exists and is valid
     */
	public static function exist($_key){
		return (self::byKey($_key)->getValue(null) !== null);
	}

    /**
     * Delete all cache entries
     *
     * @return mixed Result depends on cache engine implementation
     */
	public static function flush() {
		return self::getEngine()::deleteAll();
	}

    /**
     * Persist cache to storage if supported by engine
     *
     * @return void
     */
	public static function persist() {
		if(method_exists(self::getEngine(),'persist')){
			self::getEngine()::persist();
		}
	}

    /**
     * Check if persistence is working correctly
     *
     * @return bool True if persistence is working
     */
	public static function isPersistOk(): bool {
		if(method_exists(self::getEngine(),'isPersistOk')){
			return self::getEngine()::isPersistOk();
		}
		return true;
	}

    /**
     * Restore cache from persistence if supported by engine
     *
     * @return void
     */
    public static function restore() {
		if(method_exists(self::getEngine(),'restore')){
			self::getEngine()::restore();
		}
	}

    /**
     * Clean expired entries and perform maintenance
     *
     * @return void
     */
	public static function clean() {
		if(method_exists(self::getEngine(),'clean')){
			self::getEngine()::clean();
		}
		$caches = self::getEngine()::all();
		foreach ($caches as $cache) {
			if(!is_object($cache)){
				continue;
			}
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

	/*     * *********************Methode d'instance************************* */

    /**
     * Save current cache entry
     *
     * @return mixed Result depends on cache engine implementation
     */
	public function save() {
		$this->setTimestamp(strtotime('now'));
		return self::getEngine()::save($this);
	}

    /**
     * Remove current cache entry
     *
     * @return mixed Result depends on cache engine implementation
     */
	public function remove() {
		return self::getEngine()::delete($this->getKey());
	}

	/*     * **********************Getteur Setteur*************************** */

    /**
     * Get cache entry key
     *
     * @return string Cache key
     */
	public function getKey() {
		return $this->key;
	}

    /**
     * Set cache entry key
     *
     * @param string $_key Cache key
     * @return self
     */
	public function setKey($_key): self {
		$this->key = $_key;
		return $this;
	}

    /**
     * Get cache entry value
     *
     * @param mixed $_default Default value if cache is empty
     * @return mixed Cached value or default
     */
	public function getValue($_default = '') {
		return ($this->value === null || (is_string($this->value) && trim($this->value) === '')) ? $_default : $this->value;
	}

    /**
     * Set cache entry value
     *
     * @param mixed $_value Value to cache
     * @return self
     */
	public function setValue($_value): self {
		$this->value = $_value;
		return $this;
	}

    /**
     * Get cache entry lifetime
     *
     * @return int Lifetime in seconds
     */
	public function getLifetime() {
		return $this->lifetime;
	}

    /**
     * Set cache entry lifetime
     *
     * @param int $_lifetime Lifetime in seconds
     * @return self
     */
	public function setLifetime($_lifetime): self {
		if ($_lifetime < 0) {
			$_lifetime = 0;
		}
		$this->lifetime = intval($_lifetime);
		return $this;
	}

    /**
     * Get cache entry datetime
     *
     * @return string Datetime in Y-m-d H:i:s format
     */
	public function getDatetime() {
		return date('Y-m-d H:i:s',(int) $this->timestamp);
	}

    /**
     * Set cache entry datetime
     *
     * @param string $_datetime Datetime in Y-m-d H:i:s format
     * @return self
     */
	public function setDatetime($_datetime): self {
		$this->timestamp = strtotime($_datetime);
		return $this;
	}

    /**
     * Get cache entry timestamp
     *
     * @return int Unix timestamp
     */
	public function getTimestamp(){
		return $this->timestamp;
	}

    /**
     * Set cache entry timestamp
     *
     * @param int $_timestamp Unix timestamp
     * @return self
     */
	public function setTimestamp($_timestamp){
		$this->timestamp = $_timestamp;
		return $this;
	}
}

/**
 * MariaDB cache engine implementation
 *
 * Stores cache entries in MariaDB/MySQL database
 *
 * @see cache Main cache class
 */
class MariadbCache {

    /**
     * Get all cache entries
     *
     * @return array<cache|null> Array of cache objects
     */
	public static function all(){
		$sql = 'SELECT `key`,`timestamp`,`value`,`lifetime`
		FROM cache';
		$results =  DB::Prepare($sql,array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS,'cache');
		foreach ($results as $cache) {
			$cache->setValue(unserialize($cache->getValue()));
		}
		return $results;
	}

    /**
     * Clean expired cache entries
     *
     * @return mixed DB query result
     */
	public static function clean(){
		$sql = 'DELETE 
		FROM cache
		WHERE `lifetime` > 0
			AND (`timestamp`+`lifetime`) < UNIX_TIMESTAMP()';
		return  DB::Prepare($sql,array(), DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS);
	}

    /**
     * Fetch a cache entry by key
     *
     * @param string $_key Cache key
     * @return cache|null Cache object if found, false if not found or expired
     */
	public static function fetch($_key){
		$sql = 'SELECT `key`,`timestamp`,`value`,`lifetime`
		FROM cache
		WHERE `key`=:key';
		$cache = DB::Prepare($sql,array('key' => $_key), DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS,'cache');
		if($cache === false || !is_object($cache)){
			return null;
		}
		if($cache->getLifetime() > 0 && ($cache->getTimestamp() + $cache->getLifetime()) < strtotime('now')){
			return null;
		}
		$cache->setValue(unserialize($cache->getValue()));
		return $cache;
	}

    /**
     * Delete a cache entry
     *
     * @param string $_key Cache key
     * @return mixed DB query result
     */
	public static function delete($_key){
		$sql = 'DELETE 
		FROM cache
		WHERE `key`=:key';
		return  DB::Prepare($sql,array('key' => $_key), DB::FETCH_TYPE_ROW);
	}

    /**
     * Delete all cache entries
     *
     * @return mixed DB query result
     */
	public static function deleteAll(){
		return  DB::Prepare('TRUNCATE cache',array(), DB::FETCH_TYPE_ROW);
	}


    /**
     * Save a cache entry
     *
     * @param cache $_cache Cache object to save
     * @return mixed DB query result
     */
	public static function save($_cache){
		$value = array(
			'key' => $_cache->getKey(),
			'value' => serialize($_cache->getValue()),
			'lifetime' =>$_cache->getLifetime(),
			'timestamp' => $_cache->getTimestamp()
		);
		$sql = 'REPLACE INTO cache SET `key`=:key, `value`=:value,`timestamp`=:timestamp,`lifetime`=:lifetime';
		return  DB::Prepare($sql,$value, DB::FETCH_TYPE_ROW);
	}

}

/**
 * Redis cache engine implementation
 *
 * Stores cache entries in Redis key-value store
 *
 * @see cache Main cache class
 */
class RedisCache {

    /** @var \Redis|null Redis connection instance */
	private static $connection = null;

    /**
     * Check if Redis extension is available
     *
     * @return bool True if Redis can be used
     */
	public static function isOk(){
		return class_exists('redis');
	}

    /**
     * Get Redis connection
     *
     * @return \Redis Redis connection instance
     */
	public static function getConnection(){
		if(static::$connection !== null){
			return static::$connection;
		}
		$redis = new Redis();
		$redis->connect(config::byKey('cache::redisaddr'), config::byKey('cache::redisport'));
		static::$connection = $redis;
		return static::$connection;
	}

    /**
     * Get all cache entries
     *
     * @return array<cache|null> Array of cache objects
     */
	public static function all(){
		$return  = array();
		$keys = self::getConnection()->keys('*');
		foreach ($keys as $key) {
			$return[] = self::fetch($key);
		}
		return $return;
	}

    /**
     * Fetch a cache entry by key
     *
     * @param string $_key Cache key
     * @return cache|null Cache object or null if not found
     */
	public static function fetch($_key){
		$data = self::getConnection()->get($_key);
		if($data === false){
			return null;
		}
		return @unserialize($data);
	}

    /**
     * Delete a cache entry
     *
     * @param string $_key Cache key
     * @return void
     */
	public static function delete($_key){
		self::getConnection()->del($_key);
	}

    /**
     * Delete all cache entries
     *
     * @return bool Success of operation
     */
	public static function deleteAll(){
		return  self::getConnection()->flushDb();
	}

    /**
     * Save a cache entry
     *
     * @param cache $_cache Cache object to save
     * @return void
     */
	public static function save($_cache){
		if($_cache->getLifetime() > 0){
			self::getConnection()->set($_cache->getKey(),serialize($_cache), $_cache->getLifetime());
		}else{
			self::getConnection()->set($_cache->getKey(),serialize($_cache));
		}
	}

}

/**
 * File-based cache engine implementation
 *
 * Stores cache entries as serialized files
 *
 * @see cache Main cache class
 */
class FileCache {

    /**
     * Get all cache entries
     *
     * @return array<cache|null> Array of cache objects
     */
	public static function all(){
		$return = array();
		foreach (ls(jeedom::getTmpFolder('cache'), '*',false,array('files')) as $file) {
			$return[] = self::fetch(base64_decode($file));
		}
		return $return;
	}

    /**
     * Clean expired cache entries
     *
     * @return void
     */
	public static function clean(){
		foreach (ls(jeedom::getTmpFolder('cache'), '*',false,array('files')) as $file) {
			$cache = unserialize(file_get_contents(jeedom::getTmpFolder('cache').'/'.$file));
			if(!is_object($cache)){
				unlink(jeedom::getTmpFolder('cache').'/'.$file);
				continue;
			}
			if($cache->getLifetime() > 0 && ($cache->getTimestamp() + $cache->getLifetime()) < strtotime('now')){
				unlink(jeedom::getTmpFolder('cache').'/'.$file);
			}
		}
	}

    /**
     * Fetch a cache entry by key
     *
     * @param string $_key Cache key
     * @return cache|null Cache object or null if not found
     */
	public static function fetch($_key){
		$data = @file_get_contents(jeedom::getTmpFolder('cache').'/'.base64_encode($_key));
        if($data === false){
        	return null;
        }
	    $cache = unserialize($data);
		if(!is_object($cache)){
			return null;
		}
		if($cache->getLifetime() > 0 && ($cache->getTimestamp() + $cache->getLifetime()) < strtotime('now')){
			self::delete($_key);
			return null;
		}
		return $cache;
	}

    /**
     * Delete a cache entry
     *
     * @param string $_key Cache key
     * @return void
     */
	public static function delete($_key){
		@unlink(jeedom::getTmpFolder('cache').'/'.base64_encode($_key));
	}

    /**
     * Delete all cache entries
     *
     * @return false|null|string Output of shell command or false/null on failure
     */
	public static function deleteAll(){
		return shell_exec(system::getCmdSudo().' rm -rf '.jeedom::getTmpFolder('cache'));
	}

    /**
     * Save a cache entry
     *
     * @param cache $_cache Cache object to save
     * @return void
     */
	public static function save($_cache){
		file_put_contents(jeedom::getTmpFolder('cache').'/'.base64_encode($_cache->getKey()),serialize($_cache));
	}


    /**
     * Persist cache to archive file
     *
     * @return void
     */
	public static function persist() {
		$cache_dir = jeedom::getTmpFolder('cache');
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
     * Check if persistence archive is valid
     *
     * @return bool True if persistence archive is valid
     */
	public static function isPersistOk(): bool {
		$filename = __DIR__ . '/../../cache.tar.gz';
		if (!file_exists($filename)) {
			return false;
		}
		if (filemtime($filename) < strtotime('-65min')) {
			return false;
		}
		return true;
	}

    /**
     * Restore cache from persistence archive
     *
     * @return void
     */
	public static function restore() {
		$cache_dir = jeedom::getTmpFolder('cache');
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

}
