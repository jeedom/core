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

class pluginTest extends TestCase {
	public static function getSources() {
		return array(
			array('market', array(
				'version' => 'stable',
			))
		);
	}
	
	/**
	* @dataProvider getSources
	*/
	public function testInstall($source, $config) {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		config::save('github::enable', 1);
		config::save('market::enable', 1);
		try {
			$plugin = plugin::byId('virtual');
		} catch (Exception $e) {
			$update = new update();
			$update->setLogicalId('virtual');
			$update->setSource($source);
			foreach ($config as $key => $value) {
				$update->setConfiguration($key, $value);
			}
			$update->save();
			$update->doUpdate();
			$plugin = plugin::byId('virtual');
		}
		if (!$plugin->isActive()) {
			$plugin->setIsEnable(1);
		}
		$this->assertSame('1', $plugin->isActive());
	}
	
	/**
	* @depends testInstall
	*/
	public function testCreateEqVirtual() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		require_once __DIR__ .'/../plugins/virtual/core/class/virtual.class.php';
		$virtual = virtual::byLogicalId('virtual_test', 'virtual');
		if (is_object($virtual)) {
			$virtual->remove();
		}
		$virtual = new virtual();
		$virtual->setEqType_name('virtual');
		$virtual->setName('virtual_test');
		$virtual->setLogicalId('virtual_test');
		$virtual->setIsEnable(1);
		$virtual->save();
		$this->assertTrue((is_numeric($virtual->getId()) && $virtual->getId() != ''));
		return $virtual;
	}
	
	/**
	* @depends testCreateEqVirtual
	*/
	public function testCreateCmdVirtualBinary($virtual) {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$cmd = new virtualCmd();
		$cmd->setName('test_calcul_binary');
		$cmd->setType('info');
		$cmd->setSubtype('binary');
		$cmd->setLogicalId('virtual_test_1');
		$cmd->setEqLogic_id($virtual->getId());
		$cmd->setConfiguration('calcul', 1);
		$cmd->save();
		$this->assertTrue((is_numeric($cmd->getId()) && $cmd->getId() != ''));
	}
	
	/**
	* @depends testCreateEqVirtual
	*/
	public function testCreateCmdVirtualNumeric($virtual) {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$cmd = new virtualCmd();
		$cmd->setName('test_calcul_numeric');
		$cmd->setType('info');
		$cmd->setSubtype('numeric');
		$cmd->setLogicalId('virtual_test_2');
		$cmd->setEqLogic_id($virtual->getId());
		$cmd->setConfiguration('calcul', '1+1');
		$cmd->save();
		$this->assertTrue((is_numeric($cmd->getId()) && $cmd->getId() != ''));
	}
	
	/**
	* @depends testCreateEqVirtual
	*/
	public function testCmdVirtualNumeric($virtual) {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$cmd = $virtual->getCmd(null, 'virtual_test_2');
		$this->assertSame(2.0, $cmd->execCmd());
	}
	
	/**
	* @depends testCreateEqVirtual
	*/
	public function testCreateCmdVirtualString($virtual) {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$cmd = new virtualCmd();
		$cmd->setName('test_calcul_string');
		$cmd->setType('info');
		$cmd->setSubtype('string');
		$cmd->setLogicalId('virtual_test_3');
		$cmd->setEqLogic_id($virtual->getId());
		$cmd->setConfiguration('calcul', 'toto');
		$cmd->save();
		$this->assertTrue((is_numeric($cmd->getId()) && $cmd->getId() != ''));
	}
	
	/**
	* @depends testCreateEqVirtual
	*/
	public function testCmdVirtualString($virtual) {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$cmd = $virtual->getCmd(null, 'virtual_test_3');
		$this->assertSame('toto', $cmd->execCmd());
		$cmd->event('tata');
		$this->assertSame('tata', $cmd->execCmd());
	}
	
	/**
	* @depends testCreateEqVirtual
	*/
	public function testCreateCmdVirtualActionOther($virtual) {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$cmd = new virtualCmd();
		$cmd->setName('test_action_other_on');
		$cmd->setType('action');
		$cmd->setSubtype('other');
		$cmd->setLogicalId('virtual_test_4');
		$cmd->setEqLogic_id($virtual->getId());
		$cmd->setConfiguration('infoName', 'test_action_other_info');
		$cmd->setConfiguration('value', 1);
		$cmd->save();
		$this->assertTrue((is_numeric($cmd->getId()) && $cmd->getId() != ''));
		
		$virtual = virtual::byLogicalId('virtual_test', 'virtual');
		$cmd = new virtualCmd();
		$cmd->setName('test_action_other_off');
		$cmd->setType('action');
		$cmd->setSubtype('other');
		$cmd->setLogicalId('virtual_test_5');
		$cmd->setEqLogic_id($virtual->getId());
		$cmd->setConfiguration('infoName', 'test_action_other_info');
		$cmd->setConfiguration('value', 0);
		$cmd->save();
		$this->assertTrue((is_numeric($cmd->getId()) && $cmd->getId() != ''));
		
		$virtual = virtual::byLogicalId('virtual_test', 'virtual');
		$cmd = new virtualCmd();
		$cmd->setName('test_action_other_string');
		$cmd->setType('action');
		$cmd->setSubtype('other');
		$cmd->setLogicalId('virtual_test_6');
		$cmd->setEqLogic_id($virtual->getId());
		$cmd->setConfiguration('infoName', 'test_action_other_info');
		$cmd->setConfiguration('value', 'plop');
		$cmd->save();
		$this->assertTrue((is_numeric($cmd->getId()) && $cmd->getId() != ''));
		
		$info = virtualCmd::byEqLogicIdCmdName($virtual->getId(), 'test_action_other_info');
		$virtual = virtual::byLogicalId('virtual_test', 'virtual');
		$cmd = new virtualCmd();
		$cmd->setName('test_action_other_toggle');
		$cmd->setType('action');
		$cmd->setSubtype('other');
		$cmd->setLogicalId('virtual_test_7');
		$cmd->setEqLogic_id($virtual->getId());
		$cmd->setConfiguration('infoName', 'test_action_other_info');
		$cmd->setConfiguration('value', 'not(#' . $info->getId() . '#)');
		$cmd->save();
		$this->assertTrue((is_numeric($cmd->getId()) && $cmd->getId() != ''));
	}
	
	/**
	* @depends testCreateEqVirtual
	*/
	public function testCmdVirtualActionOther($virtual) {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$info = virtualCmd::byEqLogicIdCmdName($virtual->getId(), 'test_action_other_info');
		$action_on = $virtual->getCmd(null, 'virtual_test_4');
		$action_on->execCmd();
		$this->assertSame(1, intval($info->execCmd()));
		
		$action_off = $virtual->getCmd(null, 'virtual_test_5');
		$action_off->execCmd();
		$this->assertSame(0, intval($info->execCmd()));
		
		$action_toggle = $virtual->getCmd(null, 'virtual_test_7');
		$action_toggle->execCmd();
		$this->assertSame(1, intval($info->execCmd()));
		
		$action_other = $virtual->getCmd(null, 'virtual_test_6');
		$action_other->execCmd();
		$this->assertSame('plop', $info->execCmd());
	}
	
	/**
	* @depends testCreateEqVirtual
	*/
	public function testCreateCmdVirtualActionNumeric($virtual) {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$cmd = new virtualCmd();
		$cmd->setName('test_action_slider');
		$cmd->setType('action');
		$cmd->setSubtype('slider');
		$cmd->setLogicalId('virtual_test_8');
		$cmd->setEqLogic_id($virtual->getId());
		$cmd->setConfiguration('infoName', 'test_action_slider_info');
		$cmd->save();
		$this->assertTrue((is_numeric($cmd->getId()) && $cmd->getId() != ''));
	}
	
	/**
	* @depends testCreateEqVirtual
	*/
	public function testCmdVirtualActionNumeric($virtual) {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$action = $virtual->getCmd(null, 'virtual_test_8');
		$info = virtualCmd::byEqLogicIdCmdName($virtual->getId(), 'test_action_slider_info');
		$action->execCmd(array('slider' => 12));
		$this->assertSame(12, intval($info->execCmd()));
		$action->execCmd(array('slider' => 95));
		$this->assertSame(95, intval($info->execCmd()));
	}
	
	/**
	* @depends testCreateEqVirtual
	*/
	public function testCreateCmdVirtualActionColor($virtual) {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$cmd = new virtualCmd();
		$cmd->setName('test_action_color');
		$cmd->setType('action');
		$cmd->setSubtype('color');
		$cmd->setLogicalId('virtual_test_9');
		$cmd->setEqLogic_id($virtual->getId());
		$cmd->setConfiguration('infoName', 'test_action_color_info');
		$cmd->save();
		$this->assertTrue((is_numeric($cmd->getId()) && $cmd->getId() != ''));
	}
	
	/**
	* @depends testCreateEqVirtual
	*/
	public function testCmdVirtualActionColor($virtual) {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$action = $virtual->getCmd(null, 'virtual_test_9');
		$info = virtualCmd::byEqLogicIdCmdName($virtual->getId(), 'test_action_color_info');
		$action->execCmd(array('color' => '#451256'));
		$this->assertSame('#451256', $info->execCmd());
		$action->execCmd(array('color' => '#895475'));
		$this->assertSame('#895475', $info->execCmd());
	}
	
	/**
	* @depends testCreateEqVirtual
	*/
	public function testRemove($virtual) {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$id = $virtual->getId();
		$virtual->remove();
		$this->assertEquals(null,virtual::byId($id));
	}
	
}
?>
