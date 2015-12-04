<?php
class userTest extends \PHPUnit_Framework_TestCase {
	public function testCreate() {
		$user_array = array(
			'login' => 'test',
			'password' => 'test',
		);
		$user = new user();
		utils::a2o($user, $user_array);
		$user->setPassword(sha1($user_array['password']));
		$user->save();
		$this->assertEquals(2, $user->getId());
		$this->assertEquals($user_array['login'], $user->getLogin());
		$this->assertEquals(sha1($user_array['password']), $user->getPassword());
		return $user;
	}

	/**
	 * @depends testCreate
	 */
	public function testGet($_user) {
		$user = user::byId($_user->getId());
		$this->assertEquals($user, $_user);

		$user = user::byLogin($_user->getLogin());
		$this->assertEquals($user, $_user);
	}
}
?>