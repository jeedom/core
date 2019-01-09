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

class scenarioTest extends \PHPUnit_Framework_TestCase
{
	protected function setUp() {
		if (!extension_loaded('mysqli')) {
			$this->markTestSkipped(
				'L\'extension MySQL n\'est pas disponible.'
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

	public function getGetSets() {
		return array(
			array('Id', 'foo', 'foo'),
			array('Name', 'foo', 'foo'),
			array('State', 'foo', 'foo'),
			array('IsActive', true, true),
			array('Group', 'foo', 'foo'),
			array('LastLaunch', 'foo', 'foo'),
			array('Type', 'foo', 'foo'),
			array('Mode', 'foo', 'foo'),
			array('Schedule', array('foo' => 'bar'), array('foo' => 'bar')),
			array('Schedule', '{"foo":"bar"}', array('foo' => 'bar')),
			array('Schedule', 'foo', 'foo'),
			array('PID', 1, 1),
			array('ScenarioElement', array('foo' => 'bar'), array('foo' => 'bar')),
			array('ScenarioElement', '{"foo":"bar"}', array('foo' => 'bar')),
			array('ScenarioElement', 'foo', 'foo'),
			array('Trigger', array('foo' => 'bar'), array('foo' => 'bar')),
			array('Trigger', '{"foo":"bar"}', array('foo' => 'bar')),
			array('Trigger', 'foo', array('foo')),
			array('Timeout', '', null),
			array('Timeout', 'foo', null),
			array('Timeout', 0.9, null),
			array('Timeout', 1.1, 1.1),
			array('Timeout', 15, 15),
			array('Object_id', null, null),
			array('Object_id', array('foo'), null),
			array('Object_id', 0, null),
			array('Object_id', 150, 150),
			array('IsVisible', true, 0),
			array('IsVisible', 5, 5),
			array('IsVisible', 'foo', 0),
			array('Description', 'foo', 'foo'),
			array('RealTrigger', 'foo', 'foo'),
		);
	}

	/**
	 * @dataProvider getGetSets
	 * @param unknown $attribute
	 * @param unknown $in
	 * @param unknown $out
	 */
	public function testGetterSetter($attribute, $in, $out) {
		$scenario = new scenario();
		$getter = 'get' . $attribute;
		$setter = 'set' . $attribute;
		$scenario->$setter($in);
		$this->assertSame($out, $scenario->$getter());
	}

	public function testPersistLog() {
		$path = __DIR__ . '/../../log/scenarioLog/scenarioTest.log';
		if (file_exists($path)) {
			$this->markTestSkipped('Le fichier "' . $path . '" existe déjà. Veuillez le supprimer.');
		}
		$scenario = new scenario();
		$scenario->setId('Test');
		$scenario->persistLog();
		$this->assertTrue(file_exists($path));
		shell_exec('rm ' . $path);
	}
}
