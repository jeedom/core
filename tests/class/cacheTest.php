<?php
class cacheTest extends \PHPUnit_Framework_TestCase {
    protected function setUp() {
        try {
            DB::getConnection();
        } catch (\Exception $e) {
            $this->markTestSkipped(
                'La base de donnée n\'est pas accessible.'
            );
        }
    }

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
