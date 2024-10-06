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

class scenarioTest extends TestCase
{
    public static function getGetSets()
    {
        return [
            ['Id', 'foo', 'foo'],
            ['Name', 'foo', 'foo'],
            //            array('State', 'foo', 'foo'), // TODO: not working on ci
            ['IsActive', true, true],
            ['Group', 'foo', 'foo'],
            //            array('LastLaunch', 'foo', 'foo'), // TODO: not working on ci
            ['Mode', 'foo', 'foo'],
            ['Schedule', ['foo' => 'bar'], ['foo' => 'bar']],
            ['Schedule', '{"foo":"bar"}', ['foo' => 'bar']],
            ['Schedule', 'foo', 'foo'],
            //            array('PID', 1, 1), // TODO: not working on ci
            ['ScenarioElement', ['foo' => 'bar'], ['foo' => 'bar']],
            ['ScenarioElement', '{"foo":"bar"}', ['foo' => 'bar']],
            ['ScenarioElement', 'foo', 'foo'],
            ['Trigger', ['foo' => 'bar'], ['foo' => 'bar']],
            ['Trigger', '{"foo":"bar"}', ['foo' => 'bar']],
            ['Trigger', 'foo', ['foo']],
            ['Timeout', '', 0],
            ['Timeout', 'foo', 0],
            ['Timeout', 0.9, 0],
            ['Timeout', 1.1, 1.1],
            ['Timeout', 15, 15],
            ['Object_id', null, null],
            ['Object_id', ['foo'], null],
            //			array('Object_id', 0, 0),
            ['Object_id', 150, 150],
            ['IsVisible', true, 0],
            ['IsVisible', 5, 5],
            ['IsVisible', 'foo', 0],
            ['Description', 'foo', 'foo'],
        ];
    }

    /**
     * @dataProvider getGetSets
     */
    public function test_getter_setter($attribute, $in, $out)
    {
        $scenario = new scenario();
        $getter = 'get'.$attribute;
        $setter = 'set'.$attribute;
        $scenario->$setter($in);
        $this->assertSame($out, $scenario->$getter());
    }

    public function test_persist_log()
    {
        $path = dirname(__DIR__, 3).'/log/scenarioLog/scenarioTest.log';
        if (file_exists($path)) {
            $this->markTestSkipped('Le fichier "'.$path.'" existe déjà. Veuillez le supprimer.');
        }
        $scenario = new scenario();
        $scenario->setId('Test');
        $scenario->persistLog();
        $this->assertTrue(file_exists($path));
        shell_exec('rm '.$path);
    }
}
