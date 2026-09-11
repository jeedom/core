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
use PHPUnit\Framework\Attributes\DataProvider;

class logTest extends TestCase {
	public static function getEngins() {
		return array(
			array('StreamHandler'),
			array('foo'),
		);
	}

	public static function getLogs() {
		return array(
			array('StreamHandler', 'foo', false, true),
		);
	}

	public static function getReturnListe() {
		return array(
			array('StreamHandler', array('http.error')),
		);
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
			array(100, E_ERROR | E_WARNING | E_PARSE | E_NOTICE),
			array(200, E_ERROR | E_WARNING | E_PARSE | E_NOTICE),
			array(250, E_ERROR | E_WARNING | E_PARSE | E_NOTICE),
			array(300, E_ERROR | E_WARNING | E_PARSE),
			array(400, E_ERROR | E_PARSE),
			array(500, E_ERROR | E_PARSE),
			array(550, E_ERROR | E_PARSE),
			array(600, E_ERROR | E_PARSE),
		);
	}

	/**
	 * @param string $name
	 */
	#[DataProvider('getEngins')]
	public function testLoggerHandler($name) {
		config::save('log::engine', $name);
		$logger = log::getLogger($name);
		$this->assertInstanceOf(log::class, $logger);
	}

	/**
	 * @param string $engin
	 * @param string $message
	 * @param string $get
	 * @param string $removeAll
	 */
	#[DataProvider('getLogs')]
	public function testAddGetRemove($engin, $message, $get, $removeAll) {
		config::save('log::engine', $engin);
		log::remove($engin);
		$add = log::add($engin, 'debug', $message); // <- Effet de bord!
		$this->assertNull($add);
		$this->assertSame($get, log::get($engin, 0, 1));
		$this->assertSame($removeAll, log::removeAll());
	}

	/**
	 * @param string $engin
	 * @param string $level
	 */
	#[DataProvider('getLevels')]
	public function testAddLevels($engin, $level) {
		config::save('log::engine', $engin);
		log::remove($engin);
		$add = log::add($engin, $level, 'testLevel');
		$this->assertTrue(true);
	}

	/**
	 * @param string $engin
	 * @param string $return
	 */
	#[DataProvider('getReturnListe')]
	public function testListe($engin, $return) {
		config::save('log::engine', $engin);
		log::add($engin, 'debug', 'toto');
		$this->assertSame($return, log::liste());
	}

	/**
	 * @param int $level
	 * @param int $result
	 */
	#[DataProvider('getErrorReporting')]
	public function testErrorReporting($level, $result) {
		$this->assertNull(log::define_error_reporting($level));
		$this->assertSame($result, error_reporting());
	}
}
