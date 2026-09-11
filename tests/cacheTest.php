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
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;

#[Group('integration')]
class cacheTest extends TestCase {
	public function testSave() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		cache::set('toto', 'toto');
		$this->assertTrue(true);
	}

	#[Depends('testSave')]
	public function testLoad() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$cache = cache::byKey('toto');
		$this->assertEquals('toto', $cache->getValue());
	}

	#[Depends('testLoad')]
	public function testRemove() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$cache = cache::byKey('toto');
		$cache->remove();
		$this->assertTrue(true);
	}

	#[Depends('testRemove')]
	public function testDefault() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$cache = cache::byKey('toto');
		$this->assertEquals(null, $cache->getValue());
	}

	#[Depends('testDefault')]
	public function testTime() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		cache::set('toto', 'toto', 1);
		$cache = cache::byKey('toto');
		$this->assertEquals('toto', $cache->getValue());
		sleep(2);
		$cache = cache::byKey('toto');
		$this->assertEquals(null, $cache->getValue());
	}

	public function testExistAndDelete() {
		$key = 'cache_test_' . bin2hex(random_bytes(4));
		cache::set($key, 'value');
		$this->assertTrue(cache::exist($key));
		cache::delete($key);
		$this->assertFalse(cache::exist($key));
	}

	#[RunInSeparateProcess]
	public function testFlush() {
		$key = 'cache_test_' . bin2hex(random_bytes(4));
		cache::set($key, 'value');
		$this->assertTrue(cache::exist($key));
		cache::flush();
		$this->assertFalse(cache::exist($key));
		mkdir(jeedom::getTmpFolder() . '/cache', 0774, true);
	}
}
