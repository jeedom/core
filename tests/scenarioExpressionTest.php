<?php
class scenarioExpressionTest extends \PHPUnit_Framework_TestCase {
	public function testCalculCondition() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$tests = array(
			'1+1' => 2,
		);
		foreach ($tests as $key => $value) {
			echo "\n\t " . $key . ' = ' . $value;
			$result = scenarioExpression::createAndExec('condition', $key);
			$this->assertEquals(2, $value);
		}
		echo "\n";
	}

	public function testVariable() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		scenarioExpression::createAndExec('action', 'variable', array('value' => 'plop', 'name' => 'test'));
		$result = scenarioExpression::createAndExec('condition', 'variable(test)');
		$this->assertEquals('plop', $result);
	}

	/**
	 * @depends testVariable
	 */
	public function testStringCondition() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$result = scenarioExpression::createAndExec('condition', 'variable(test) == "plop"');
		$this->assertTrue($result);
	}
}
?>