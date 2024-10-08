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

class userTest extends TestCase
{
    public function test_create()
    {
        $user = $this->createUser('test', 'test', 'test', 'test');

        $this->assertTrue(is_numeric($user->getId()) && '' != $user->getId());
        $this->assertEquals('test', $user->getLogin());
        $this->assertEquals(sha512('test'), $user->getPassword());
    }

    public function test_connect()
    {
        $_user = $this->createUser('test2', 'test');
        $user = user::connect('test2', 'test');
        $this->assertEquals($user->getId(), $_user->getId());
    }

    public function test_remove()
    {
        $_user = $this->createUser('test3', 'test');
        $id = $_user->getId();
        $_user->remove();
        $this->assertEquals(null, user::byId($id));
    }

    /**
     * @throws Exception
     */
    public function createUser(string $login, string $password): user
    {
        $user_array = [
            'login' => $login,
            'password' => $password,
        ];
        $user = new user();
        utils::a2o($user, $user_array);
        $user->setPassword(sha512($user_array['password']));
        $user->save();

        return $user;
    }
}
