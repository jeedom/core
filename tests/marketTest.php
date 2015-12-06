<?php
class marketTest extends \PHPUnit_Framework_TestCase {
	public function testConnexion() {
		$result = market::test();
		$this->assertSame('ok', $result[0]);
	}

}
?>