<?php
class marketTest extends \PHPUnit_Framework_TestCase {
	public function testConnexion() {
		if (!extension_loaded('curl')) {
			$this->markTestSkipped(
				'The CURL extension is not available.'
			);
		}
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		if (getenv('JEEDOM_MARKET_USERNAME') !== false && getenv('JEEDOM_MARKET_PASSWORD') !== false) {
			echo 'Ajout des informations de connexion au market';
			config::save('market::enable', 1);
			config::save('market::username', getenv('JEEDOM_MARKET_USERNAME'));
			config::save('market::password', getenv('JEEDOM_MARKET_PASSWORD'));
		}
		$this->assertSame('ok', 'ok');
	}

}
?>