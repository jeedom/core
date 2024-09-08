<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

use PHPUnit\Framework\TestCase;

class scenarioExpressionTest extends TestCase
{
    public function test_calcul_condition()
    {
        $tests = [
            '1+1' => 2,
        ];
        foreach ($tests as $key => $value) {
            $result = scenarioExpression::createAndExec('condition', $key);
            $this->assertEquals(2, $value);
        }
    }

    public function test_variable()
    {
        scenarioExpression::createAndExec('action', 'variable', ['value' => 'plop', 'name' => 'test']);
        $result = scenarioExpression::createAndExec('condition', 'variable(test)');
        $this->assertEquals('plop', $result);
    }

    /**
     * @depends test_variable
     */
    public function test_string_condition()
    {
        $result = scenarioExpression::createAndExec('condition', 'variable(test) == "plop"');
        $this->assertTrue($result);
    }
}
