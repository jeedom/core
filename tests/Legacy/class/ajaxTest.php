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

class ajaxTest extends TestCase
{
    public static function getSuccessResponses(): iterable
    {
        yield [
            ['foo' => 'bar', 'bar' => 'baz'],
            '{"state":"ok","result":{"foo":"bar","bar":"baz"}}',
        ];
    }

    public static function getErrorResponses(): iterable
    {
        yield [
            ['foo' => 'bar', 'bar' => 'baz'],
            1234,
            '{"state":"error","result":{"foo":"bar","bar":"baz"},"code":1234}',
        ];
    }

    /**
     * @dataProvider getSuccessResponses
     */
    public function test_success(array $data, string $out): void
    {
        $response = ajax::getResponse($data);
        $this->assertEquals($out, $response);
    }

    /**
     * @dataProvider getErrorResponses
     */
    public function test_error(array $data, int $code, string $out): void
    {
        $response = ajax::getResponse($data, $code);
        $this->assertEquals($out, $response);
    }
}
