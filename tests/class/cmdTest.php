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
use PHPUnit\Framework\Attributes\Group;

#[Group('unit')]
class cmdTest extends TestCase {
    public function testFormatValueNumericRoundWithNonNumericCalculResult() {
        $cmd = new cmd();
        $cmd->setType('info');
        $cmd->setSubType('numeric');
        $cmd->setConfiguration('historizeRound', 1);
        $cmd->setConfiguration('calculValueOffset', '#value# * 2 ~ \'mm\'');

        $actualResult = $cmd->formatValue('1,2');

        self::assertSame(2.4, $actualResult);
    }

    public function testFormatValueNumericAppliesBounds() {
        $cmd = new cmd();
        $cmd->setType('info');
        $cmd->setSubType('numeric');
        $cmd->setConfiguration('minValue', 10);
        $cmd->setConfiguration('maxValue', 20);

        self::assertSame(10.0, $cmd->formatValue(5));
        self::assertSame(20.0, $cmd->formatValue(25));
    }

    public function testFormatValueBinaryRecognizesCommonValues() {
        $cmd = new cmd();
        $cmd->setType('info');
        $cmd->setSubType('binary');

        self::assertSame(1, $cmd->formatValue('on'));
        self::assertSame(0, $cmd->formatValue('off'));
        self::assertSame(1, $cmd->formatValue(1));
        self::assertSame(0, $cmd->formatValue(0));
    }
}
