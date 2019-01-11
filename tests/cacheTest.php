<?php
class cacheTest extends \PHPUnit_Framework_TestCase {
	public function testSave() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		cache::set('toto', 'toto');
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