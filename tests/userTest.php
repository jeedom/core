<?php
class userTest extends \PHPUnit_Framework_TestCase {
	public function testCalcul() {
		$user_array = array(
			'login' => 'test',
			'password' => 'test',
		);
		$user = new user();
		utils::a2o($user, $user_array);
		$user->save();
		$this->assertEquals(2, $user->getId());
	}
}
?>