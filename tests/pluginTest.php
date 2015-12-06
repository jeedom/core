<?php
class pluginTest extends \PHPUnit_Framework_TestCase {
	public function testInstall() {
		try {
			$plugin = plugin::byId('virtual');
		} catch (Exception $e) {
			$plugin = market::byLogicalIdAndType('virtual', 'plugin');
			$plugin->install();
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
		$virtual = new virtual();
		$virtual->setEqType_name('virtual');
		$virtual->setName('virtual_test');
		$virtual->setLogicalId('virtual_test');
		$virtual->setIsEnable(1);
		$virtual->save();
		$this->assertTrue((is_numeric($virtual->getId()) && $virtual->getId() != ''));
	}

	/**
	 * @depends testCreateEqVirtual
	 */
	public function testCreateCmdVirtualBinary() {
		$virtual = virtual::byLogicalId('virtual_test', 'virtual');
		$cmd = new virtualCmd();
		$cmd->setName('test_calcul_binary');
		$cmd->setType('info');
		$cmd->setSubtype('binary');
		$cmd->setLogicalId('virtual_test_1');
		$cmd->setEqLogic_id($virtual->getId());
		$cmd->setConfiguration('calcul', '1');
		$cmd->save();
		$this->assertTrue((is_numeric($cmd->getId()) && $cmd->getId() != ''));
	}

	public function testCmdVirtualBinary() {
		$virtual = virtual::byLogicalId('virtual_test', 'virtual');
		$cmd = $virtual->getCmd(null, 'virtual_test_1');
		$this->assertSame(1, $cmd->execCmd());
		$cmd->event(0);
		$this->assertSame(0, $cmd->execCmd());
	}

	/**
	 * @depends testCreateEqVirtual
	 */
	public function testCreateCmdVirtualNumeric() {
		$virtual = virtual::byLogicalId('virtual_test', 'virtual');
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

	public function testCmdVirtualNumeric() {
		$virtual = virtual::byLogicalId('virtual_test', 'virtual');
		$cmd = $virtual->getCmd(null, 'virtual_test_2');
		$this->assertSame(2.0, $cmd->execCmd());
	}

	/**
	 * @depends testCreateEqVirtual
	 */
	public function testCreateCmdVirtualString() {
		$virtual = virtual::byLogicalId('virtual_test', 'virtual');
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

	public function testCmdVirtualString() {
		$virtual = virtual::byLogicalId('virtual_test', 'virtual');
		$cmd = $virtual->getCmd(null, 'virtual_test_3');
		$this->assertSame('toto', $cmd->execCmd());
		$cmd->event('tata');
		$this->assertSame('tata', $cmd->execCmd());
	}

	/**
	 * @depends testCreateEqVirtual
	 */
	public function testRemove() {
		$virtual = virtual::byLogicalId('virtual_test', 'virtual');
		$virtual->remove();
	}

}
?>