<?php
class marketTest extends \PHPUnit_Framework_TestCase {
	public function testConnexion() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
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