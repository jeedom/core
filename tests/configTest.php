<?php
class configTest extends \PHPUnit_Framework_TestCase {
	public function testSave() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		config::save('toto', 'toto');
	}

	/**
	 * @depends testSave
	 */
	public function testLoad() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$this->assertEquals('toto', config::byKey('toto'));
	}

	/**
	 * @depends testLoad
	 */
	public function testRemove() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		config::remove('toto');
	}

	/**
	 * @depends testRemove
	 */
	public function testDefault() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$this->assertEquals('plop', config::byKey('toto', 'core', 'plop'));
	}

}
?>