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

class logTest extends TestCase
{
    public static function getLogs(): iterable
    {
        return [
            ['StreamHandler', 'foo', false, true],
        ];
    }

    public static function getReturnListe(): iterable
    {
        return [
            ['StreamHandler', ['StreamHandler']],
        ];
    }

    public static function getLevels(): iterable
    {
        return [
            ['StreamHandler', 'debug'],
            ['StreamHandler', 'info'],
            ['StreamHandler', 'notice'],
            ['StreamHandler', 'warning'],
            ['StreamHandler', 'error'],
        ];
    }

    public static function getErrorReporting(): iterable
    {
        return [
            [100, E_ERROR | E_WARNING | E_PARSE | E_NOTICE],
            [200, E_ERROR | E_WARNING | E_PARSE | E_NOTICE],
            [250, E_ERROR | E_WARNING | E_PARSE | E_NOTICE],
            [300, E_ERROR | E_WARNING | E_PARSE],
            [400, E_ERROR | E_PARSE],
            [500, E_ERROR | E_PARSE],
            [600, E_ERROR | E_PARSE],
            [700, E_ERROR | E_PARSE],
        ];
    }

    /**
     * @dataProvider getLogs
     */
    public function test_add_get_remove(string $engin, string $message, bool $get, bool $removeAll): void
    {
        config::save('log::engine', $engin);
        log::remove($engin);
        log::add($engin, 'debug', $message); // <- Effet de bord!
        $this->assertSame($get, log::get($engin, 0, 1));
        $this->assertSame($removeAll, log::removeAll());
    }

    /**
     * @dataProvider getLevels
     */
    public function test_add_levels(string $engin, string $level): void
    {
        config::save('log::engine', $engin);
        log::remove($engin);
        log::add($engin, $level, 'testLevel');
        $this->assertTrue(true);
    }

    /**
     * @dataProvider getReturnListe
     */
    public function test_liste(string $engin, array $return): void
    {
        config::save('log::engine', $engin);
        log::add($engin, 'debug', 'toto');
        $this->assertSame($return, log::liste());
    }

    /**
     * @dataProvider getErrorReporting
     */
    public function test_error_reporting(int $level, int $result): void
    {
        log::define_error_reporting($level);
        $this->assertSame($result, error_reporting());
    }
}
