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

class utilsTest extends TestCase
{
    public static function getTemplates()
    {
        return [
            ['Vous êtes sur {{Nom}} version {{Version}}', 'Vous êtes sur Jeedom version 1.2.3'],
            ['{{La poule}} {{pond}}', 'L\'oeuf est pondu'],
        ];
    }

    /**
     * @dataProvider getTemplates
     */
    public function test_templace_replace($template, $out)
    {
        $rules = [
            '{{Nom}}' => 'Jeedom',
            '{{Version}}' => '1.2.3',
            '{{La poule}}' => 'L\'oeuf',
            '{{pond}}' => 'est pondu',
        ];
        $result = template_replace($rules, $template);
        $this->assertSame($out, $result);
    }

    public function test_init()
    {
        $_GET['get'] = 'foo';
        $_POST['post'] = 'bar';
        $_REQUEST['request'] = 'baz';
        $this->assertSame('foo', init('get'));
        $this->assertSame('bar', init('post'));
        $this->assertSame('baz', init('request'));
        $this->assertSame('foobar', init('default', 'foobar'));
    }

    public static function getTimes()
    {
        return [
            [0, '0s'],
            [60, '1min 0s'],
            [65, '1min 5s'],
            [186, '3min 6s'],
            [3600, '1h 0min 0s'],
            [86400, '1j 0h 0min 0s'],
            [86401, '1j 0h 0min 1s'],
            [259199, '2j 23h 59min 59s'],
        ];
    }

    /**
     * @dataProvider getTimes
     */
    public function test_convert_duartion($in, $out)
    {
        $this->assertSame($out, convertDuration($in));
    }

    public static function getJsons()
    {
        return [
            [json_encode(['foo', 'bar']), true],
            [json_encode(['foo' => 'bar']), true],
            ['{"foo":"bar"}', true],
            ['foo bar', false],
        ];
    }

    /**
     * @dataProvider getJsons
     */
    public function test_is_json($in, $out)
    {
        $this->assertSame($out, is_json($in));
    }

    public static function getPaths()
    {
        return [
            ['/home/user/doc/../../me/docs', '/home/me/docs'],
        ];
    }

    /**
     * @dataProvider getPaths
     */
    public function test_clean_path($in, $out)
    {
        $this->assertSame($out, cleanPath($in));
    }
}
