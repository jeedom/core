<?php
class cacheTest extends \PHPUnit_Framework_TestCase {
	public function testSave() {
		cache::set('toto', 'toto');
	}

	/**
	 * @depends testSave
	 */
	public function testLoad() {
		$cache = cache::byKey('toto');
		$this->assertEquals('toto', $cache->getValue());
	}

	/**
	 * @depends testLoad
	 */
	public function testRemove() {
		$cache = cache::byKey('toto');
		$cache->remove();
	}

	/**
	 * @depends testRemove
	 */
	public function testDefault() {
		$cache = cache::byKey('toto');
		$this->assertEquals(null, $cache->getValue());
	}

	/**
	 * @depends testDefault
	 */
	public function testTime() {
		cache::set('toto', 'toto', 1);
		$cache = cache::byKey('toto');
		$this->assertEquals('toto', $cache->getValue());
		sleep(2);
		$cache = cache::byKey('toto');
		$this->assertEquals(null, $cache->getValue());
	}

}
?>