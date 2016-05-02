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
	public function getGetSets() {
		return array(
			array('Id', 'foo', 'foo'),
			array('Name', 'foo', 'foo'),
			array('State', 'foo', 'foo'),
			array('IsActive', true, true),
			array('Group', 'foo', 'foo'),
			array('LastLaunch', '', null),
			array('LastLaunch', 'foo', 'foo'),
			array('Type', 'foo', 'foo'),
			array('Mode', 'foo', 'foo'),
			array('Schedule', array('foo'=>'bar'), array('foo'=>'bar')),
			array('Schedule', '{"foo":"bar"}', array('foo'=>'bar')),
			array('Schedule', 'foo', 'foo'),
			array('PID', '', null),
			array('PID', 'foo', null),
			array('PID', 1, 1),
			array('ScenarioElement', array('foo'=>'bar'), array('foo'=>'bar')),
			array('ScenarioElement', '{"foo":"bar"}', array('foo'=>'bar')),
			array('ScenarioElement', 'foo', 'foo'),
			array('Trigger', array('foo'=>'bar'), array('foo'=>'bar')),
			array('Trigger', '{"foo":"bar"}', array('foo'=>'bar')),
			array('Trigger', 'foo', 'foo'),
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
		$path = dirname(__FILE__) . '/../../log/scenarioLog/scenarioTest.log';
		if (file_exists($path)) {
			$this->markTestSkipped('File "' . $path . '" already exists. Please remove it.');
		}
		$scenario = new scenario();
		$scenario->setId('Test');
		$scenario->persistLog();
		$this->assertTrue(file_exists($path));
		shell_exec('rm '.$path);
	}
	
	public function testHasRight() {
		$scenId = 50000;
		$r = 25;
		
		$scenario = new scenario();
		$config = config::byKey('rights::enable');
		config::save('rights::enable', 0);
		$this->assertTrue($scenario->hasRight('foo'));
		
		config::save('rights::enable', 1);
		$this->assertTrue($scenario->hasRight('foo'));
		$_SESSION['user'] = 'foo';
		$this->assertFalse($scenario->hasRight('foo'));
		$_SESSION['user'] = new user();
		$this->assertFalse($scenario->hasRight('foo'));
		$_SESSION['user']->setLogin('foo');
		$_SESSION['user']->setRights('admin');
		$_SESSION['user']->save();
		$userId = $_SESSION['user']->getId();
		$this->assertFalse($scenario->hasRight('foo'));
		
		
		$scenario->setId($scenId);
		$right = new rights();
		$right->setUser_id($userId);
		$right->setRight($r);
		
		$this->assertFalse($scenario->hasRight('x'));
		$right->setEntity('scenario' . $scenId . 'action');
		$right->save();
		$this->assertEquals($r, $scenario->hasRight('x'));
		
		$this->assertFalse($scenario->hasRight('w'));
		$right->setEntity('scenario' . $scenId . 'edit');
		$right->save();
		$this->assertEquals($r, $scenario->hasRight('w'));
		
		$this->assertFalse($scenario->hasRight('r'));
		$right->setEntity('scenario' . $scenId . 'view');
		$right->save();
		$this->assertEquals($r, $scenario->hasRight('r'));
		
		$right->remove();
		$_SESSION['user']->remove();
		config::save('rights::enable', $config);
	}
}