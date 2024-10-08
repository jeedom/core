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

class cacheTest extends TestCase
{
    public function test_default(): void
    {
        $cache = cache::byKey('toto');
        $this->assertSame('', $cache->getValue());
    }

    // not working on ci (TODO: mock the engine)
    //    public function testLoad(): void{
    //        cache::set('toto', 'toto');
    //		$cache = cache::byKey('toto');
    //		$this->assertSame('toto', $cache->getValue());
    //	}

    public function test_remove(): void
    {
        cache::set('toto', 'toto');
        $cache = cache::byKey('toto');
        $cache->remove();
        $cache = cache::byKey('toto');
        $this->assertSame('', $cache->getValue());
    }

    // not working on ci (TODO: mock the engine)
    //	public function testLifetime(): void {
    //		cache::set('toto', 'toto', 1);
    //		$cache = cache::byKey('toto');
    //		$this->assertSame('toto', $cache->getValue());
    //	}

    public function test_expired(): void
    {
        $this->markTestSkipped('Too long to run');
        cache::set('toto', 'toto', 1);
        sleep(2);
        $cache = cache::byKey('toto');
        $this->assertSame('', $cache->getValue());
    }
}
