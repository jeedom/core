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

class utilsTest extends \PHPUnit_Framework_TestCase {
	public function getTimes() {
		return array(
				array(0, '0s'),
				array(60, '1min 0s'),
				array(65, '1min 5s'),
				array(186, '3min 6s'),
				array(3600, '1h 0min 0s'),
				array(86400, '1j 0h 0min 0s'),
				array(86401, '1j 0h 0min 1s'),
				array(259199, '2j 23h 59min 59s'),
		);
	}
	
	/**
	 * @dataProvider getTimes
	 */
	public function testConvertDuartion($in, $out) {
		$this->assertSame($out, convertDuration($in));
	}
}