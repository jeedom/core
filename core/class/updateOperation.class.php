<?php

/** Core/plugin updates requested by REST. No OS/package-manager operations. */
class updateOperation {

	private const KEY = 'rest::updateOperation';
	private static $executing = false;

	public static function start(array $ids, int $userId, string $requestKey, string $kind = 'update', bool $force = false): array {
		return self::locked('mutation', static function () use ($ids, $userId, $requestKey, $kind, $force) {
			$last = self::read();
			if ($last !== null && $last['requestKey'] === $requestKey) {
				$original = array_column($last['items'], 'id');
				$selected = $ids;
				sort($original); sort($selected);
				if ($original !== $selected || $last['userId'] !== $userId || $last['kind'] !== $kind || ($last['force'] ?? false) !== $force) throw new RuntimeException('Clé déjà utilisée pour une autre sélection.', 409);
				return self::latest();
			}
			self::assertIdle();
			self::assertNoInstallations();
			$items = array();
			foreach ($ids as $id) {
				$update = update::byId($id);
				self::validateUpdate($update, $force);
				$items[] = array('id' => $id, 'type' => $update->getType(), 'logicalId' => $update->getLogicalId(),
					'name' => $update->getType() === 'core' ? 'Core Jeedom' : plugin::byId($update->getLogicalId())->getName(), 'state' => 'queued', 'error' => null);
			}
			usort($items, static fn ($a, $b) => ($a['type'] === 'core' ? 0 : 1) <=> ($b['type'] === 'core' ? 0 : 1));
			$operation = array('id' => bin2hex(random_bytes(16)), 'kind' => $kind, 'state' => 'queued', 'items' => $items,
				'createdAt' => date('c'), 'startedAt' => null, 'finishedAt' => null, 'error' => null, 'progress' => null,
				'logName' => 'update', 'userId' => $userId, 'requestKey' => $requestKey, 'force' => $force);
			self::store($operation);
			try {
				system::php(escapeshellarg(__DIR__ . '/../php/jeeRestUpdate.php') . ' ' . escapeshellarg($operation['id']) . ' > /dev/null 2>&1 &');
			} catch (Throwable $e) {
				self::fail($operation, 'Impossible de lancer la mise à jour.');
				throw new RuntimeException('Impossible de lancer la mise à jour.', 503);
			}
			return self::publicOperation($operation);
		});
	}

	public static function latest(): ?array {
		$operation = self::read();
		if ($operation === null) return null;
		if (in_array($operation['state'], array('queued', 'running'), true)) {
			$lock = fopen(jeedom::getTmpFolder('rest-updates') . '/runner.lock', 'c');
			if ($lock !== false) {
				if (flock($lock, LOCK_EX | LOCK_NB)) {
					// Re-read under the worker lock: completion may have happened just now.
					$operation = self::read();
					if ($operation !== null && ($operation['state'] === 'running' || ($operation['state'] === 'queued' && time() - strtotime($operation['createdAt']) > 120))) {
						self::fail($operation, 'La tâche de mise à jour a été interrompue.');
					}
					flock($lock, LOCK_UN);
				}
				fclose($lock);
			}
		}
		return self::publicOperation($operation);
	}

	/** CLI worker, independent of browser requests and the cron/queue restart. */
	public static function execute(string $id): void {
		self::locked('runner', static function () use ($id) {
			self::locked('mutation', static function () use ($id) {
				$operation = self::read();
				if ($operation === null || $operation['id'] !== $id || $operation['state'] !== 'queued') return;
				self::$executing = true;
				$operation['state'] = 'running';
				$operation['startedAt'] = date('c');
				self::store($operation);
				try {
					$actor = user::byId($operation['userId']);
					if (!is_object($actor) || !$actor->isValidAndEnabled() || $actor->getProfils() !== 'admin') throw new RuntimeException('Administrateur désactivé ou droits retirés.');
					unautorizedInDemo($actor);
					log::clear('update');
					if ($operation['kind'] === 'check') {
						update::checkAllUpdate('core', true, false);
						update::checkAllUpdate('plugin', false);
					}
					foreach ($operation['items'] as $index => $item) {
						$operation['items'][$index]['state'] = 'running';
						self::store($operation);
						$update = update::byId($item['id']);
						if (!is_object($update) || $update->getType() !== $item['type'] || $update->getLogicalId() !== $item['logicalId']) throw new RuntimeException('Le composant sélectionné a changé.');
						self::validateUpdate($update, $operation['force'] ?? false);
						if ($item['type'] === 'core') {
							if (config::byKey('update::allowCore', 'core', 1) != 1) throw new RuntimeException('La mise à jour du Core est désactivée.');
							// A waiting shell would also match the native installer's process guard.
							system::php(escapeshellarg(__DIR__ . '/../../install/update.php') . ' core=1 plugins=0 ' . escapeshellarg('backup::before=1')
								. ' >> ' . escapeshellarg(log::getPathToLog('update')) . ' 2>&1 &');
							self::waitForCore();
							// The child replaced the version file; plugins must see the new Core version.
							clearstatcache();
							jeedom::version(true);
						} else {
							pluginInstallation::withLock($item['logicalId'], static function () use ($update, $item) {
								pluginInstallation::assertIdle($item['logicalId']);
								if ($update->getConfiguration('doNotUpdate', 0) == 1 || $update->getStatus() === 'hold') throw new RuntimeException('Les mises à jour de ce plugin sont désactivées.');
								$update->doUpdate();
							});
						}
						$operation['items'][$index]['state'] = 'succeeded';
						self::store($operation);
					}
					update::refreshUpdateMessage();
					$operation['state'] = 'succeeded';
					$operation['finishedAt'] = date('c');
					self::store($operation);
				} catch (Throwable $e) {
					$message = pluginInstallation::errorMessage($e);
					log::add('update', 'error', $message);
					self::fail($operation, $message);
				} finally { self::$executing = false; }
			}, LOCK_EX);
		});
	}

	/** Serialize admission with plugin installation/mutation; workers keep the exclusive lock. */
	public static function withPluginMutation(callable $action, bool $wait = false) {
		if (self::$executing) return $action();
		return self::locked('mutation', static function () use ($action) {
			self::assertIdle();
			return $action();
		}, $wait ? LOCK_SH : LOCK_SH | LOCK_NB);
	}

	public static function canReinstall(update $update): bool {
		if ($update->getStatus() === 'hold') return false;
		if ($update->getType() === 'core') {
			return $update->getLogicalId() === 'jeedom' && config::byKey('update::allowCore', 'core', 1) == 1;
		}
		if ($update->getType() !== 'plugin' || $update->getConfiguration('doNotUpdate', 0) == 1) return false;
		$source = $update->getSource();
		return config::byKey($source . '::enable') == 1 && method_exists('repo_' . $source, 'downloadObject');
	}

	private static function validateUpdate($update, bool $force): void {
		if (!is_object($update) || !in_array($update->getType(), array('core', 'plugin'), true)
			|| ($force ? !self::canReinstall($update) : $update->getStatus() !== 'update')) {
			throw new RuntimeException('Un composant sélectionné ne peut pas être mis à jour.', 409);
		}
		if ($update->getType() === 'core') {
			if ($update->getLogicalId() !== 'jeedom' || config::byKey('update::allowCore', 'core', 1) != 1) throw new RuntimeException('La mise à jour du Core est désactivée.', 409);
		} elseif ($update->getConfiguration('doNotUpdate', 0) == 1 || !is_file(plugin::getPathById($update->getLogicalId()))) {
			throw new RuntimeException('Plugin absent ou mises à jour désactivées.', 409);
		}
	}

	private static function assertNoInstallations(): void {
		foreach (queue::all() as $queue) {
			if ($queue->getClass() === 'pluginInstallation' && $queue->getFunction() === 'execute'
				&& !in_array($queue->getState(), array('error', 'Not found'), true)) throw new RuntimeException('Une installation de plugin est déjà en cours.', 409);
		}
	}

	/** The native installer may relaunch itself after updating its own script. */
	private static function waitForCore(): void {
		$deadline = time() + 3600;
		$idleSince = null;
		while (time() < $deadline) {
			$tail = self::tail();
			if (str_contains($tail, '[END UPDATE ERROR]')) break;
			if (str_contains($tail, '[END UPDATE SUCCESS]')) return;
			if (count(system::ps('install/update.php', 'sudo')) === 0) {
				$idleSince = $idleSince ?? time();
				if (time() - $idleSince >= 5) break;
			} else {
				$idleSince = null;
			}
			usleep(500000);
		}
		throw new RuntimeException('Échec ou interruption de la mise à jour du Core. Consultez les logs.');
	}

	private static function assertIdle(): void {
		$last = self::latest();
		if (($last !== null && in_array($last['state'], array('queued', 'running'), true)) || count(system::ps('install/update.php', 'sudo')) > 0) throw new RuntimeException('Une mise à jour est déjà en cours.', 409);
	}

	private static function read(): ?array {
		$value = config::byKey(self::KEY, 'core', null, true);
		return is_array($value) && isset($value['id'], $value['state']) ? $value : null;
	}

	private static function store(array $operation): void { config::save(self::KEY, $operation); }

	private static function fail(array &$operation, string $message): void {
		$operation['state'] = 'failed';
		$operation['finishedAt'] = date('c');
		$operation['error'] = $message;
		foreach ($operation['items'] as &$item) {
			if ($item['state'] === 'running') { $item['state'] = 'failed'; $item['error'] = $message; }
			elseif ($item['state'] === 'queued') $item['state'] = 'skipped';
		}
		unset($item);
		self::store($operation);
	}

	private static function publicOperation(array $operation): array {
		unset($operation['userId'], $operation['requestKey'], $operation['force']);
		foreach ($operation['items'] as $item) {
			if ($item['type'] === 'core' && $item['state'] === 'running' && preg_match_all('/\[PROGRESS\]\[(\d+)\]/', self::tail(), $matches)) {
				$operation['progress'] = min(100, (int) end($matches[1]));
			}
		}
		return $operation;
	}

	private static function tail(): string {
		$handle = @fopen(log::getPathToLog('update'), 'rb');
		if ($handle === false) return '';
		fseek($handle, max(0, (int) fstat($handle)['size'] - 16384));
		$value = stream_get_contents($handle);
		fclose($handle);
		return $value === false ? '' : $value;
	}

	private static function locked(string $name, callable $action, int $mode = LOCK_EX | LOCK_NB) {
		$lock = fopen(jeedom::getTmpFolder('rest-updates') . '/' . $name . '.lock', 'c');
		if ($lock === false) throw new RuntimeException('Verrou de mise à jour indisponible.', 503);
		try {
			if (!flock($lock, $mode)) throw new RuntimeException('Une opération est déjà en cours.', 409);
			return $action();
		} finally { flock($lock, LOCK_UN); fclose($lock); }
	}
}
