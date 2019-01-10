<?php
class scenarioExpressionTest extends \PHPUnit_Framework_TestCase {
	protected function setUp() {
		if (!extension_loaded('curl')) {
			$this->markTestSkipped(
					'L\'extension CURL n\'est pas disponible.'
			);
		}

        try {
            DB::getConnection();
        } catch (\Exception $e) {
            $this->markTestSkipped(
                'La base de donnée n\'est pas accessible.'
            );
        }
	}

	public function testCalculCondition() {
		$tests = array(
			'1+1' => 2,
		);
		foreach ($tests as $key => $value) {
			$result = scenarioExpression::createAndExec('condition', $key);
			$this->assertEquals(2, $value);
		}
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
