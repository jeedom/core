<?php
class userTest extends \PHPUnit_Framework_TestCase {
	public function testCreate() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__;
		$user_array = array(
			'login' => 'test',
			'password' => 'test',
		);
		$user = new user();
		utils::a2o($user, $user_array);
		$user->setPassword(sha1($user_array['password']));
		$user->save();

		$this->assertTrue((is_numeric($user->getId()) && $user->getId() != ''));
		$this->assertEquals($user_array['login'], $user->getLogin());
		$this->assertEquals(sha1($user_array['password']), $user->getPassword());
		return $user;
	}

	/**
	 * @depends testCreate
	 */
	public function testGet($_user) {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__;
		$user = user::byId($_user->getId());
		$this->assertEquals($user, $_user);

		$user = user::byLogin($_user->getLogin());
		$this->assertEquals($user, $_user);
	}

	/**
	 * @depends testCreate
	 */
	public function testConnect($_user) {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__;
		$user = user::connect('test', 'test');
		$this->assertEquals($user->getId(), $_user->getId());
	}

	/**
	 * @depends testCreate
	 */
	public function testRemove($_user) {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__;
		$_user->remove();
	}

}
?>