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

class com_shellTest extends \PHPUnit_Framework_TestCase {
	/******************* Base ********************/
	public function getBackgrounds() {
		return array(
				array(true),
				array(false),
		);
	}
	
	public function assertPreConditions()
	{
		// Fix bug : Call execute before getInstance
		$this->assertSame('ok', com_shell::execute('echo ok'));
	}
	
	public function testGetCmd() {
		$shell = new com_shell('ls');
		$this->assertSame('ls', $shell->getCmd());
	}
	
	public function testCommandExist() {
		$shell = new com_shell();
		$this->assertTrue($shell->commandExist('ls'));
		$this->assertFalse($shell->commandExist('foo'));
	}
	
	/**
	 * @dataProvider getBackgrounds
	 * @var bool $in
	 */
	public function testBackground($in) {
		$shell = new com_shell();
		$shell->setBackground($in);
		$this->assertSame($in, $shell->getBackground());
	}
	
	public function testExec() {
		if (file_exists('foo.txt'))
		{
			$this->markTestSkipped(
					'A file named foo.txt exist. Please remove it.'
			);
		}
		$shell = new com_shell('touch foo.txt');
		$return = $shell->exec();
		$this->assertEmpty($return);
		$this->assertTrue(file_exists('foo.txt'));
		
		$shell = new com_shell('rm foo.txt');
		$return = $shell->exec();
		$this->assertEmpty($return);
		$this->assertFalse(file_exists('foo.txt'));
		
		$shell = new com_shell('echo foo');
		$return = $shell->exec();
		$this->assertSame('foo', $return);
	}
	
	/*************** Improvement *****************/
	public function testInstance() {
		$shell = com_shell::getInstance();
		$this->assertInstanceOf('com_shell', $shell);
	}
	
	public function testExecute() {
		if (file_exists('bar.txt'))
		{
			$this->markTestSkipped(
					'A file named bar.txt exist. Please remove it.'
			);
		}
		$result = com_shell::execute('touch bar.txt');
		$this->assertEmpty($result);
		$this->assertTrue(file_exists('bar.txt'));
		
		$result = com_shell::execute('rm bar.txt');
		$this->assertEmpty($result);
		$this->assertFalse(file_exists('bar.txt'));
		
		$result = com_shell::execute('echo bar');
		$this->assertSame('bar', $result);
	}
	
	public function testCache() {
		$shell = com_shell::getInstance();
		$shell->clearHistory();
		$shell->addCmd('echo foo');
		$result = com_shell::execute('echo bar');
		$this->assertSame('bar', $result);
		$this->assertSame(array('echo bar'), $shell->getHistory());
		$result = $shell->exec();
		$this->assertSame('foo', $result);
	}
	
	public function testHistory() {
		$shell = com_shell::getInstance();
		$shell->clearHistory();
		$this->assertSame(array(), $shell->getHistory());
		com_shell::execute('echo foo');
		$this->assertSame(array('echo foo'), $shell->getHistory());
		$shell->addCmd('echo bar');
		$shell->addCmd('echo baz');
		$this->assertSame(array('echo foo'), $shell->getHistory());
		$shell->exec();
		$this->assertSame(array('echo foo', 'echo bar', 'echo baz'), $shell->getHistory());
		$shell->clearHistory();
		$this->assertSame(array(), $shell->getHistory());
	}
}