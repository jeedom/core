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
	}

}
?>