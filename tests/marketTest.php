<?php
class marketTest extends \PHPUnit_Framework_TestCase {
	public function testConnexion() {
		try {
			$result = market::test();
		} catch (Exception $e) {
			if (strpos($e->getMessage(), 'Utilisateur non authentifié') !== false) {
				$result = array('ok');
			}
		}
		$this->assertSame('ok', $result[0]);
	}

}
?>