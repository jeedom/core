<?php
class scenarioExpressionTest extends \PHPUnit_Framework_TestCase {
	public function testCalcul() {
		$result = scenarioExpression::createAndExec('condition', '1+1');
		$this->assertEquals(2, $result);
	}
}
?>