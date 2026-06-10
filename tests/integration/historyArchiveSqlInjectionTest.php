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

class historyArchiveSqlInjectionTest extends TestCase {

	/** @var callable[] */
	private $cleanupStack = array();

	/** @var mixed */
	private $savedArchiveTime;

	/** @var mixed */
	private $savedArchivePackage;

	protected function setUp(): void {
		$this->savedArchiveTime = config::byKey('historyArchiveTime');
		$this->savedArchivePackage = config::byKey('historyArchivePackage');
	}

	protected function tearDown(): void {
		foreach (array_reverse($this->cleanupStack) as $callback) {
			try {
				$callback();
			} catch (\Throwable $e) {
				fwrite(STDERR, "Cleanup failed: " . $e->getMessage() . "\n");
			}
		}
		$this->cleanupStack = array();
		config::save('historyArchiveTime', $this->savedArchiveTime);
		config::save('historyArchivePackage', $this->savedArchivePackage);
	}

	public function testLegitimateHistorizeModeArchivesData(): void {
		$cmdId = $this->givenAHistorizedNumericCmd('avg', 2);
		$this->givenAHistoryRowOlderThanArchiveTime($cmdId, 42.5);

		history::archive();

		$this->thenRowExistsInHistoryArch($cmdId);
	}

	public function testMaliciousHistorizeModeFallsBackToDefault(): void {
		$cmdId = $this->givenAHistorizedNumericCmd('AVG(value)); DROP TABLE historyArch; --', 2);
		$this->givenAHistoryRowOlderThanArchiveTime($cmdId, 10.0);

		history::archive();

		$this->thenTableStillExists('historyArch');
		$this->thenRowExistsInHistoryArch($cmdId);
	}

	public function testUnionBasedSqlInjectionIsNeutralized(): void {
		$cmdId = $this->givenAHistorizedNumericCmd('MAX(value) UNION SELECT 1,NOW(),password FROM user --', 2);
		$this->givenAHistoryRowOlderThanArchiveTime($cmdId, 7.0);

		history::archive();

		$this->thenRowExistsInHistoryArch($cmdId);
	}

	public function testOutOfRangeHistorizeRoundIsBounded(): void {
		$cmdId = $this->givenAHistorizedNumericCmd('avg', 99);
		$this->givenAHistoryRowOlderThanArchiveTime($cmdId, 3.14);

		history::archive();

		$this->thenRowExistsInHistoryArch($cmdId);
	}

	public function testNegativeHistorizeRoundIsBounded(): void {
		$cmdId = $this->givenAHistorizedNumericCmd('avg', -5);
		$this->givenAHistoryRowOlderThanArchiveTime($cmdId, 2.71);

		history::archive();

		$this->thenRowExistsInHistoryArch($cmdId);
	}

	public function testEmptyHistorizeModeDoesNotBreakArchive(): void {
		$cmdId = $this->givenAHistorizedNumericCmd('', 2);
		$this->givenAHistoryRowOlderThanArchiveTime($cmdId, 1.23);

		history::archive();

		$this->thenRowExistsInHistoryArch($cmdId);
	}

	/**
	 * Insert an eqLogic + a numeric, historized cmd directly via SQL.
	 * Going through DB::Prepare instead of `new eqLogic()/save()` avoids pulling
	 * in a plugin class and lets us exercise the vulnerable code path with a
	 * minimal fixture. subType=numeric has canBeSmooth=true which reaches the
	 * REPLACE INTO historyArch(...) branch where the injection lives.
	 *
	 * @param string     $historizeMode  value stored into cmd.configuration.historizeMode
	 * @param int|string $historizeRound value stored into cmd.configuration.historizeRound
	 * @return int the newly inserted cmd id
	 */
	private function givenAHistorizedNumericCmd(string $historizeMode, $historizeRound): int {
		$eqLogicName = 'test_hist_inj_' . bin2hex(random_bytes(4));
		DB::Prepare(
			'INSERT INTO eqLogic (name, eqType_name, isEnable, isVisible, configuration) VALUES (:name, "core", 1, 1, "{}")',
			array('name' => $eqLogicName),
			DB::FETCH_TYPE_ROW
		);
		$eqLogicId = (int) DB::getLastInsertId();
		$this->registerCleanup(function () use ($eqLogicId) {
			DB::Prepare('DELETE FROM eqLogic WHERE id = :id', array('id' => $eqLogicId), DB::FETCH_TYPE_ROW);
		});

		$configuration = json_encode(array(
			'historizeMode' => $historizeMode,
			'historizeRound' => $historizeRound,
		));
		$cmdName = 'test_cmd_' . bin2hex(random_bytes(4));
		DB::Prepare(
			'INSERT INTO cmd (eqLogic_id, name, type, subType, eqType, isHistorized, configuration) VALUES (:eqLogic_id, :name, "info", "numeric", "core", 1, :configuration)',
			array('eqLogic_id' => $eqLogicId, 'name' => $cmdName, 'configuration' => $configuration),
			DB::FETCH_TYPE_ROW
		);
		$cmdId = (int) DB::getLastInsertId();
		$this->registerCleanup(function () use ($cmdId) {
			DB::Prepare('DELETE FROM cmd WHERE id = :id', array('id' => $cmdId), DB::FETCH_TYPE_ROW);
		});

		return $cmdId;
	}

	/**
	 * Insert one row in history with a datetime older than the archive window
	 * so that history::archive() picks it up. Default archiveTime is -1 hour,
	 * so -3 hours is safely in range.
	 */
	private function givenAHistoryRowOlderThanArchiveTime(int $cmdId, float $value): void {
		$datetime = date('Y-m-d H:i:s', strtotime('-3 hours'));
		DB::Prepare(
			'REPLACE INTO history SET cmd_id=:cmd_id, `datetime`=:datetime, value=:value',
			array('cmd_id' => $cmdId, 'datetime' => $datetime, 'value' => $value),
			DB::FETCH_TYPE_ROW
		);
		$this->registerCleanup(function () use ($cmdId) {
			DB::Prepare('DELETE FROM history WHERE cmd_id=:cmd_id', array('cmd_id' => $cmdId), DB::FETCH_TYPE_ROW);
			DB::Prepare('DELETE FROM historyArch WHERE cmd_id=:cmd_id', array('cmd_id' => $cmdId), DB::FETCH_TYPE_ROW);
		});
	}

	private function thenRowExistsInHistoryArch(int $cmdId): void {
		$row = DB::Prepare(
			'SELECT COUNT(*) as cnt FROM historyArch WHERE cmd_id=:cmd_id',
			array('cmd_id' => $cmdId),
			DB::FETCH_TYPE_ROW
		);
		$this->assertGreaterThan(
			0,
			(int) $row['cnt'],
			"Row was not archived for cmd_id={$cmdId} - archive may have failed (injection attempt caused SQL error)"
		);
	}

	private function thenTableStillExists(string $table): void {
		$row = DB::Prepare(
			'SHOW TABLES LIKE :table',
			array('table' => $table),
			DB::FETCH_TYPE_ROW
		);
		$this->assertNotEmpty($row, "Table '{$table}' no longer exists");
	}

	private function registerCleanup(callable $callback): void {
		$this->cleanupStack[] = $callback;
	}
}
