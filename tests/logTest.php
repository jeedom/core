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
use PHPUnit\Framework\Attributes\Group;

#[Group('integration')]
class logTest extends TestCase {
	public static function getEngines() {
		return array(
			array('StreamHandler'),
			array('foo'),
		);
	}

	public static function getLogs() {
		return array(
			array('StreamHandler', 'foo'),
		);
	}

	public static function getReturnListe() {
		return array(
			array('StreamHandler', array('StreamHandler')),
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

	public static function getConvertedLogLevels() {
		return array(
			array(100, 'debug'),
			array(400, 'error'),
			array(600, 'none'),
			array(999, 'none'),
		);
	}

	/**
	 * @param string $name
	 */
	#[DataProvider('getEngines')]
	public function testLoggerHandler($name) {
		config::save('log::engine', $name);
		$logger = log::getLogger($name);
		$this->assertInstanceOf(log::class, $logger);
	}

	/**
	 * @param string $engine
	 * @param string $message
	 */
	#[DataProvider('getLogs')]
	public function testAddGetRemove($engine, $message) {
		config::save('log::engine', $engine);
		log::remove($engine);
		log::add($engine, 'error', $message);
		$path = log::getPathToLog($engine);
		$this->assertFileExists($path);
		$this->assertStringContainsString($message, file_get_contents($path));
		log::removeAll();
		$this->assertFileDoesNotExist($path);
	}

	/**
	 * @param string $engine
	 * @param string $level
	 */
	#[DataProvider('getLevels')]
	public function testAddLevels($engine, $level) {
		config::save('log::engine', $engine);
		log::remove($engine);
		$add = log::add($engine, $level, 'testLevel');
		$this->assertTrue(true);
	}

	/**
	 * @param string $engine
	 * @param string $return
	 */
	#[DataProvider('getReturnListe')]
	public function testListe($engine, $return) {
		config::save('log::engine', $engine);
		log::add($engine, 'debug', 'toto');
		$this->assertSame($return, log::liste());
	}

	/**
	 * @param int $level
	 * @param int $result
	 */
	#[DataProvider('getErrorReporting')]
	public function testErrorReporting($level, $result) {
		log::define_error_reporting($level);
		$this->assertSame($result, error_reporting());
	}

	#[DataProvider('getConvertedLogLevels')]
	public function testConvertLogLevel($level, $expected) {
		$this->assertSame($expected, log::convertLogLevel($level));
	}

	public function testAuthorizeClearLog() {
		$name = 'log_test_' . bin2hex(random_bytes(4));
		$path = log::getPathToLog($name);
		$this->assertFalse(log::authorizeClearLog($name));
		file_put_contents($path, 'content');
		try {
			$this->assertTrue(log::authorizeClearLog($name));
			$this->assertFalse(log::authorizeClearLog('.htaccess'));
		} finally {
			unlink($path);
		}
	}
}
