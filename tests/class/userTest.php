<?php
class userTest extends \PHPUnit_Framework_TestCase {
	protected function setUp() {
		try {
			DB::getConnection();
		} catch (\Exception $e) {
			$this->markTestSkipped(
				'La base de donnée n\'est pas accessible.'
			);
		}
	}

	public function testCreate() {
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
	public function testGet($_user) {
		$user = user::byId($_user->getId());
		$this->assertEquals($user, $_user);

		$user = user::byLogin($_user->getLogin());
		$this->assertEquals($user, $_user);
	}

	/**
	 * @depends testCreate
	 */
	public function testConnect($_user) {
		$user = user::connect('test', 'test');
		$this->assertEquals($user->getId(), $_user->getId());
	}

	/**
	 * @depends testCreate
	 */
	public function testRemove($_user) {
		$_user->remove();
	}

}
