<?php
class configTest extends \PHPUnit_Framework_TestCase {
	public function testSave() {
		config::save('toto', 'toto');
	}

	/**
	 * @depends testSave
	 */
	public function testLoad() {
		$this->assertEquals('toto', config::byKey('toto'));
	}

	/**
	 * @depends testLoad
	 */
	public function testRemove() {
		config::remove('toto');
	}

	/**
	 * @depends testRemove
	 */
	public function testDefault() {
		$this->assertEquals('plop', config::byKey('toto', 'core', 'plop'));
	}

}
?>