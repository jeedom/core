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

class shellTest extends TestCase
{
    /******************* Base ********************/
    public static function getBackgrounds()
    {
        return [
            [true],
            [false],
        ];
    }

    public function test_get_cmd()
    {
        $shell = new com_shell('ls');
        $this->assertSame('ls', $shell->getCmd());
    }

    public function test_command_exist()
    {
        $shell = new com_shell();
        $this->assertTrue($shell->commandExists('ls'));
        $this->assertFalse($shell->commandExists('foo'));
    }

    /**
     * @dataProvider getBackgrounds
     *
     * @var bool
     */
    public function test_background($in)
    {
        $shell = new com_shell();
        $shell->setBackground($in);
        $this->assertSame($in, $shell->getBackground());
    }

    public function test_exec()
    {
        if (file_exists('foo.txt')) {
            $this->markTestSkipped(
                'Un fichier foo.txt existe. Veuillez le supprimer.'
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
    public function test_instance()
    {
        $shell = com_shell::getInstance();
        $this->assertInstanceOf('com_shell', $shell);
    }

    public function test_execute()
    {
        if (file_exists('bar.txt')) {
            $this->markTestSkipped(
                'Un fichier bar.txt existe. Veuillez le supprimer.'
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

    public function test_cache()
    {
        $shell = com_shell::getInstance();
        $shell->clearHistory();
        $shell->addCmd('echo foo');
        $result = com_shell::execute('echo bar');
        $this->assertSame('bar', $result);
        $this->assertSame(['echo bar 2>&1'], $shell->getHistory());
        $result = $shell->exec();
        $this->assertSame('foo', $result);
    }

    public function test_history()
    {
        $shell = com_shell::getInstance();
        $shell->clearHistory();
        $this->assertSame([], $shell->getHistory());
        com_shell::execute('echo foo');
        $this->assertSame(['echo foo 2>&1'], $shell->getHistory());
        $shell->addCmd('echo bar');
        $shell->addCmd('echo baz');
        $this->assertSame(['echo foo 2>&1'], $shell->getHistory());
        $shell->exec();
        $this->assertSame(['echo foo 2>&1', 'echo bar 2>&1', 'echo baz 2>&1'], $shell->getHistory());
        $shell->clearHistory();
        $this->assertSame([], $shell->getHistory());
    }
}
