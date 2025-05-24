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

use PHPUnit\Framework\TestCase;

class logTest extends TestCase {
	public static function getEngins() {
		return array(
			array('StreamHandler', 'Monolog\Handler\StreamHandler'),
			array('foo', 'Monolog\Handler\StreamHandler'),
		);
	}
	
	public static function getLogs() {
		return [
			['StreamHandler', 'foo', false, true],
		];
	}
	
	public static function getReturnListe() {
		return [
			['http.error', 'http.error', 'result contains: "http.error"']
		];
	}
	
	public static function getLevels() {
		return array(
			array('StreamHandler', 'debug'),
			array('StreamHandler', 'info'),
			array('StreamHandler', 'notice'),
			array('StreamHandler', 'warning'),
			array('StreamHandler', 'error'),
		);
	}
	
	public static function getErrorReporting() {
		return array(
			array(Monolog\Logger::DEBUG, E_ERROR | E_WARNING | E_PARSE | E_NOTICE),
			array(Monolog\Logger::INFO, E_ERROR | E_WARNING | E_PARSE | E_NOTICE),
			array(Monolog\Logger::NOTICE, E_ERROR | E_WARNING | E_PARSE | E_NOTICE),
			array(Monolog\Logger::WARNING, E_ERROR | E_WARNING | E_PARSE),
			array(Monolog\Logger::ERROR, E_ERROR | E_PARSE),
			array(Monolog\Logger::CRITICAL, E_ERROR | E_PARSE),
			array(Monolog\Logger::ALERT, E_ERROR | E_PARSE),
			array(Monolog\Logger::EMERGENCY, E_ERROR | E_PARSE),
		);
	}
	
	/**
	* @dataProvider getEngins
	* @param string $name
	* @param string $instance
	*/
	public function testLoggerHandler($name, $instance) {
		config::save('log::engine', $name);
		$logger = log::getLogger($name);
		$this->assertInstanceOf('Monolog\\Logger', $logger);
		$handler = $logger->popHandler();
		$this->assertInstanceOf($instance, $handler);
	}
	
	/**
	* @dataProvider getLogs
	* @param string $engin
	* @param string $message
	* @param string $get
	* @param string $removeAll
	*/
	public function testAddGetRemove($engin, $message, $get, $removeAll) {
		config::save('log::engine', $engin);
		log::remove($engin);
		$add = log::add($engin, 'debug', $message); // <- Effet de bord!
		$this->assertNull($add);
		$this->assertSame($get, log::get($engin, 0, 1));
		$this->assertSame($removeAll, log::removeAll());
	}
	
	/**
	* @dataProvider getLevels
	* @param string $engin
	* @param string $level
	*/
	public function testAddLevels($engin, $level) {
		config::save('log::engine', $engin);
		log::remove($engin);
		$add = log::add($engin, $level, 'testLevel');
		$this->assertTrue(true);
	}
	
	/**
	* @dataProvider getReturnListe
	* @param string $engin
	* @param string $return
	*/
	public function testListe($engin, $return, $msg) {
		config::save('log::engine', $engin);
		log::add($engin, 'debug', 'toto');
		$this->assertContains($return, log::liste(), $msg);
	}
	
	/**
	* @dataProvider getErrorReporting
	* @param int $level
	* @param int $result
	*/
	public function testErrorReporting($level, $result) {
		$this->assertNull(log::define_error_reporting($level));
		$this->assertSame($result, error_reporting());
	}
}
