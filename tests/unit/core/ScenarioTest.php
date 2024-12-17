<?php
namespace Tests\Unit\Core;

use PHPUnit\Framework\TestCase;

class ScenarioTest extends TestCase
{
    private $scenario;

    protected function setUp(): void
    {
        $this->scenario = new \Scenario('HomeArrival');
    }

    public function testScenarioExecution()
    {
        $result = $this->scenario->execute();
        $this->assertTrue($result);
    }

    public function testScenarioConditions()
    {
        $this->scenario->addCondition('time', '18:00');
        $this->assertTrue($this->scenario->checkConditions());
    }
}
