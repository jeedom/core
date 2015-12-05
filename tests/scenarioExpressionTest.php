<?php
class scenarioExpressionTest extends \PHPUnit_Framework_TestCase {
	public function testCalculCondition() {
		$result = scenarioExpression::createAndExec('condition', '1+1');
		$this->assertEquals(2, $result);
	}

	public function testVariable() {
		scenarioExpression::createAndExec('action', 'variable', array('value' => 'plop', 'name' => 'test'));
		$result = scenarioExpression::createAndExec('condition', 'variable(test)');
		$this->assertEquals('plop', $result);
	}

	/**
	 * @depends testVariable
	 */
	public function testStringCondition() {
		$result = scenarioExpression::createAndExec('condition', 'variable(test) == "plop"');
		$this->assertTrue($result);
	}
}
?>