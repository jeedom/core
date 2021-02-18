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

class userTest extends TestCase {
	public function testCreate() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$user_array = array(
			'login' => 'test',
			'password' => 'test',
		);
		$user = new user();
		utils::a2o($user, $user_array);
		$user->setPassword(sha512($user_array['password']));
		$user->save();
		
		$this->assertTrue((is_numeric($user->getId()) && $user->getId() != ''));
		$this->assertEquals($user_array['login'], $user->getLogin());
		$this->assertEquals(sha512($user_array['password']), $user->getPassword());
		return $user;
	}
	
	/**
	* @depends testCreate
	*/
	public function testConnect($_user) {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$user = user::connect('test', 'test');
		$this->assertEquals($user->getId(), $_user->getId());
	}
	
	/**
	* @depends testCreate
	*/
	public function testRemove($_user) {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$id = $_user->getId();
		$_user->remove();
		$this->assertEquals(null,user::byId($id));
	}
	
}
?>
