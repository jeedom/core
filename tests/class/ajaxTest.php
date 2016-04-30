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

class ajaxTest extends \PHPUnit_Framework_TestCase
{
	public function getSuccessResponses()
	{
		return array(
			array(
				array('foo'=>'bar','bar'=>'baz'),
				'{"state":"ok","result":{"foo":"bar","bar":"baz"}}',
			),
		);
	}
	
	public function getErrorResponses()
	{
		return array(
				array(
						array('foo'=>'bar','bar'=>'baz'),
						1234,
						'{"state":"error","result":{"foo":"bar","bar":"baz"},"code":1234}',
				),
		);
	}
	
	/**
	 * @dataProvider getSuccessResponses
	 * @param mixed $data
	 * @param string $out
	 */
	public function testSuccess($data, $out)
	{
		$response = ajax::getResponse($data);
		$this->assertEquals($out, $response);
	}
	
	/**
	 * @dataProvider getErrorResponses
	 * @param mixed $data
	 * @param int $code
	 * @param string $out
	 */
	public function testError($data, $code, $out)
	{
		$response = ajax::getResponse($data, $code);
		$this->assertEquals($out, $response);
	}
}