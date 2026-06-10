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

abstract class AjaxIntegrationTestCase extends TestCase {

	/** @var callable[] */
	private $cleanupStack = array();

	/** @var int|null */
	private $authenticatedUserId = null;

	protected function tearDown(): void {
		foreach (array_reverse($this->cleanupStack) as $callback) {
			try {
				$callback();
			} catch (\Throwable $e) {
				fwrite(STDERR, "Cleanup failed: " . $e->getMessage() . "\n");
			}
		}
		$this->cleanupStack = [];
		$this->authenticatedUserId = null;
	}

	protected function givenAdminIsLoggedIn(): void {
		$admin = new user();
		$admin->setLogin('test_admin_' . bin2hex(random_bytes(4)));
		$admin->setPassword(sha512('test_password_' . bin2hex(random_bytes(4))));
		$admin->setProfils('admin');
		$admin->setEnable(1);
		$admin->save();

		$this->registerCleanup(function () use ($admin) {
			$admin->remove();
		});
		$this->authenticatedUserId = (int)$admin->getId();
	}

	/**
	 * Creates a view + zone + viewData ready to be targeted by setComponentOrder.
	 * The link_id intentionally points to a non-existent id so that the
	 * eqLogic::byId() branch of the handler is short-circuited: we only test
	 * the order UPDATE query, not the side-effects.
	 */
	protected function givenAViewDataWith(string $type, int $initialOrder = 5): object {
		$view = new view();
		$view->setName('test_view_' . bin2hex(random_bytes(4)));
		$view->save();
		$this->registerCleanup(function () use ($view) {
			$view->remove();
		});

		$viewZone = new viewZone();
		$viewZone->setView_id($view->getId());
		$viewZone->setName('test_zone');
		$viewZone->save();

		$fakeLinkId = 987654321;
		$viewData = new viewData();
		$viewData->setviewZone_id($viewZone->getId());
		$viewData->setType($type);
		$viewData->setLink_id($fakeLinkId);
		$viewData->setOrder($initialOrder);
		$viewData->save();

		return (object)[
			'viewId'       => (int)$view->getId(),
			'viewZoneId'   => (int)$viewZone->getId(),
			'viewDataId'   => (int)$viewData->getId(),
			'linkId'       => $fakeLinkId,
			'initialOrder' => $initialOrder,
		];
	}

	protected function whenSetComponentOrderIsCalledWith(array $components): array {
		return $this->callAjaxEndpoint('view.ajax.php', [
			'action'     => 'setComponentOrder',
			'components' => json_encode($components),
		]);
	}

	protected function thenResponseIsJsonSuccess(array $response): void {
		$this->assertSame(
			0,
			$response['exitCode'],
			"Subprocess exited non-zero.\nStdout: {$response['body']}\nStderr: {$response['stderr']}"
		);
		$this->assertNotNull(
			$response['json'],
			"Expected JSON response.\nStdout: {$response['body']}\nStderr: {$response['stderr']}"
		);
		$this->assertSame(
			'ok',
			$response['json']['state'] ?? null,
			"Expected state=ok.\nBody: {$response['body']}"
		);
	}

	protected function thenViewDataOrderIs(int $viewDataId, int $expectedOrder): void {
		$row = DB::Prepare(
			'SELECT `order` FROM viewData WHERE id = :id',
			['id' => $viewDataId],
			DB::FETCH_TYPE_ROW
		);
		$this->assertIsArray($row, "viewData id={$viewDataId} not found");
		$this->assertSame(
			$expectedOrder,
			(int)$row['order'],
			"viewData.order was modified unexpectedly"
		);
	}

	protected function thenTableStillExists(string $table): void {
		$row = DB::Prepare(
			'SHOW TABLES LIKE :table',
			['table' => $table],
			DB::FETCH_TYPE_ROW
		);
		$this->assertNotEmpty($row, "Table '{$table}' no longer exists");
	}

	private function registerCleanup(callable $callback): void {
		$this->cleanupStack[] = $callback;
	}

	private function callAjaxEndpoint(string $ajaxFile, array $postData): array {
		$this->assertNotNull(
			$this->authenticatedUserId,
			'Call givenAdminIsLoggedIn() before calling any when...()'
		);

		$payload = json_encode([
			'file'    => $ajaxFile,
			'post'    => $postData,
			'user_id' => $this->authenticatedUserId,
		]);

		$runner = __DIR__ . '/ajax_runner.php';
		$descriptors = [
			0 => ['pipe', 'r'],
			1 => ['pipe', 'w'],
			2 => ['pipe', 'w'],
		];
		$process = proc_open('php ' . escapeshellarg($runner), $descriptors, $pipes);
		if (!is_resource($process)) {
			$this->fail('Failed to spawn ajax_runner.php subprocess');
		}

		fwrite($pipes[0], $payload);
		fclose($pipes[0]);
		$stdout = stream_get_contents($pipes[1]);
		$stderr = stream_get_contents($pipes[2]);
		fclose($pipes[1]);
		fclose($pipes[2]);
		$exitCode = proc_close($process);

		return [
			'body'     => $stdout,
			'stderr'   => $stderr,
			'exitCode' => $exitCode,
			'json'     => json_decode($stdout, true),
		];
	}
}
