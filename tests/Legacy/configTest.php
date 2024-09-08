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

class configTest extends TestCase
{
    public function test_config_is_empty_by_default(): void
    {
        $this->assertSame('', config::byKey('toto'));
    }

    public function test_define_default_value(): void
    {
        $this->assertEquals('plop', config::byKey('toto', 'core', 'plop'));
    }

    public function test_return_setted_value(): void
    {
        config::save('toto', 'titi');
        $this->assertEquals('titi', config::byKey('toto'));
    }

    public function test_remove_config(): void
    {
        config::save('toto', 'titi');
        config::remove('toto');
        $this->assertSame('', config::byKey('toto'));
    }
}
