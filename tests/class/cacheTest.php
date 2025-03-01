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


class cacheTest extends TestCase {
	public function testSave() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		cache::set('toto', 'toto');
		$this->assertTrue(true);
	}
	
	/**
	* @depends testSave
	*/
	public function testLoad() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$cache = cache::byKey('toto');
		$this->assertEquals('toto', $cache->getValue());
	}
	
	/**
	* @depends testLoad
	*/
	public function testRemove() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$cache = cache::byKey('toto');
		$cache->remove();
		$this->assertTrue(true);
	}
	
	/**
	* @depends testRemove
	*/
	public function testDefault() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$cache = cache::byKey('toto');
		$this->assertEquals(null, $cache->getValue());
	}
	
	/**
	* @depends testDefault
	*/
	public function testTime() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		cache::set('toto', 'toto', 1);
		$cache = cache::byKey('toto');
		$this->assertEquals('toto', $cache->getValue());
		sleep(2);
		$cache = cache::byKey('toto');
		$this->assertEquals(null, $cache->getValue());
	}
	
}
?>
