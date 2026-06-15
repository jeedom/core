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


class updateBranchTest extends TestCase {

	private $providerBackup;
	private $branchBackup;

	protected function setUp(): void {
		$this->providerBackup = config::byKey('core::repo::provider');
		$this->branchBackup = config::byKey('core::branch');
		config::save('core::repo::provider', 'default');
		// Liste distante simulée (branches + tags) pour éviter tout appel réseau.
		cache::set('core::branch::default::list', array(
			'branchs' => array(
				array('name' => 'master'),
				array('name' => 'beta'),
				array('name' => 'alpha'),
			),
			'tags' => array(
				array('name' => '4.4.0'),
				array('name' => '4.4.1'),
			),
		), 86400);
	}

	protected function tearDown(): void {
		config::save('core::repo::provider', $this->providerBackup);
		config::save('core::branch', $this->branchBackup);
		cache::byKey('core::branch::default::list')->remove();
	}

	public function testStableBranchIsAlwaysValid() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		config::save('core::branch', 'master');
		$this->assertTrue(update::isCoreBranchValid());
	}

	public function testExistingBranchIsValid() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		config::save('core::branch', 'beta');
		$this->assertTrue(update::isCoreBranchValid());
	}

	public function testMissingBranchIsInvalid() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		config::save('core::branch', 'branche-supprimee');
		$this->assertFalse(update::isCoreBranchValid());
	}

	public function testExistingTagIsValid() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		config::save('core::branch', 'tag::4.4.0');
		$this->assertTrue(update::isCoreBranchValid());
	}

	public function testMissingTagIsInvalid() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		config::save('core::branch', 'tag::9.9.9');
		$this->assertFalse(update::isCoreBranchValid());
	}

	public function testCustomProviderIsAlwaysValid() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		config::save('core::repo::provider', 'custom');
		config::save('core::branch', 'branche-supprimee');
		$this->assertTrue(update::isCoreBranchValid());
	}

	public function testUnreachableListDoesNotRaiseFalsePositive() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		// Liste distante indisponible (réseau KO) : on ne doit pas alerter à tort.
		cache::set('core::branch::default::list', array('branchs' => array(), 'tags' => array()), 86400);
		config::save('core::branch', 'branche-inconnue');
		$this->assertTrue(update::isCoreBranchValid());
	}
}
?>
