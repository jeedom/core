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

class com_shellTest extends \PHPUnit_Framework_TestCase
{
	public function getBackgrounds()
	{
		return array(
				array(true),
				array(false),
		);
	}
	
	public function testGetCmd()
	{
		$shell = new com_shell('ls');
		$this->assertSame('ls', $shell->getCmd());
	}
	
	public function testCommandExistValid()
	{
		$shell = new com_shell();
		$this->assertTrue($shell->commandExist('ls'));
		$this->assertFalse($shell->commandExist('foo'));
	}
	
	/**
	 * @dataProvider getBackgrounds
	 * @var bool $in
	 */
	public function testBackground($in)
	{
		$shell = new com_shell();
		$shell->setBackground($in);
		$this->assertSame($in, $shell->getBackground());
	}
	
	public function testExec()
	{
		if (file_exists('foo.txt'))
		{
			$this->markTestSkipped(
					'A file named foo.txt exist. Please remove it.'
			);
		}
		$shell = new com_shell('touch foo.txt');
		$return = $shell->exec();
		$this->assertTrue(file_exists('foo.txt'));
		$shell = new com_shell('rm foo.txt');
		$return = $shell->exec();
		$this->assertFalse(file_exists('foo.txt'));
	}
}