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

class logTest extends \PHPUnit_Framework_TestCase {
	public function getEngins() {
		return array(
			array('StreamHandler', 'Monolog\Handler\StreamHandler'),
			array('SyslogHandler', 'Monolog\Handler\SyslogHandler'),
			array('SyslogUdp', 'Monolog\Handler\SyslogUdpHandler'),
			array('foo', 'Monolog\Handler\StreamHandler'),
		);
	}

	public function getLogs() {
		return array(
			array('StreamHandler', 'foo', false, true),
			array('SyslogHandler', 'bar', false, true),
			array('SyslogUdp', 'baz', false, true),
		);
	}

	public function getReturnListe() {
		return array(
			//	array('StreamHandler', array()),
			array('SyslogHandler', array()),
			array('SyslogUdp', array()),
		);
	}

	public function getLevels() {
		return array(
			array('StreamHandler', 'debug'),
			array('StreamHandler', 'info'),
			array('StreamHandler', 'notice'),
			array('StreamHandler', 'warning'),
			array('StreamHandler', 'error'),
			array('SyslogHandler', 'debug'),
			array('SyslogHandler', 'info'),
			array('SyslogHandler', 'notice'),
			array('SyslogHandler', 'warning'),
			array('SyslogHandler', 'error'),
			array('SyslogUdp', 'debug'),
			array('SyslogUdp', 'info'),
			array('SyslogUdp', 'notice'),
			array('SyslogUdp', 'warning'),
			array('SyslogUdp', 'error'),
		);
	}

	public function getErrorReporting() {
		return array(
			array(log::LEVEL_DEBUG, E_ERROR | E_WARNING | E_PARSE | E_NOTICE),
			array(log::LEVEL_INFO, E_ERROR | E_WARNING | E_PARSE | E_NOTICE),
			array(log::LEVEL_NOTICE, E_ERROR | E_WARNING | E_PARSE | E_NOTICE),
			array(log::LEVEL_WARNING, E_ERROR | E_WARNING | E_PARSE),
			array(log::LEVEL_ERROR, E_ERROR | E_PARSE),
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
	}

	/**
	 * @dataProvider getReturnListe
	 * @param string $engin
	 * @param string $return
	 */
	public function testListe($engin, $return) {
		config::save('log::engine', $engin);
		log::add($engin, 'debug', $message);
		$this->assertSame($return, log::liste());
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