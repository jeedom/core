<?php

/** Plugin installation jobs. The native queue executes the native update engine. */
class pluginInstallation {

	public static function preview(int $marketId, string $version): array {
		if ($marketId < 1 || $marketId > 2147483647 || !in_array($version, array('stable', 'beta'), true)) {
			throw new InvalidArgumentException('Identifiant Market ou branche invalide.', 422);
		}
		if (config::byKey('market::enable') != 1) {
			throw new RuntimeException('Le dépôt Market est désactivé.', 409);
		}
		try {
			$market = repo_market::byId($marketId);
		} catch (Throwable $e) {
			throw new RuntimeException(self::errorMessage($e), in_array($e->getCode(), array(401, 403, 404), true) ? (int) $e->getCode() : 502);
		}
		if (!is_object($market) || $market->getType() !== 'plugin' || (int) $market->getId() !== $marketId) {
			throw new RuntimeException('Plugin Market introuvable.', 404);
		}
		$id = (string) $market->getLogicalId();
		self::validatePluginId($id);
		if (!$market->getPurchase() || !in_array($version, (array) $market->getAllowVersion(), true)) {
			throw new RuntimeException('Le compte Market configuré sur cette box ne permet pas d’installer cette branche du plugin.', 403);
		}
		if ((int) $market->getStatus($version) !== 1) {
			throw new RuntimeException('Cette branche du plugin n’est pas publiée.', 409);
		}
		$minimum = (string) $market->getMinJeedomVersion();
		if ($minimum !== '' && version_compare(jeedom::version(), $minimum, '<')) {
			throw new RuntimeException('Version de Jeedom incompatible : minimum ' . $minimum . '.', 409);
		}
		$os = (string) system::getOsVersion();
		foreach (array('min' => '<', 'max' => '>') as $bound => $operator) {
			$required = $market->getParameters('os::' . $version . '::' . $bound);
			if ($required !== '' && $required !== null && version_compare($os, (string) $required, $operator)) {
				throw new RuntimeException('Version du système incompatible : ' . $bound . ' ' . $required . '.', 409);
			}
		}
		$hardware = (array) $market->getHardwareCompatibility();
		$platform = jeedom::getHardwareName();
		if (array_key_exists($platform, $hardware) && (int) $hardware[$platform] !== 1) {
			throw new RuntimeException('Ce plugin n’est pas compatible avec cette plateforme.', 409);
		}
		$update = update::byTypeAndLogicalId('plugin', $id);
		if (is_object($update) && ($update->getSource() !== 'market' || $update->getConfiguration('doNotUpdate', 0) == 1)) {
			throw new RuntimeException('Le plugin est géré par une autre source ou ses mises à jour sont désactivées.', 409);
		}
		$installed = is_file(plugin::getPathById($id));
		return array('marketId' => $marketId, 'pluginId' => $id, 'name' => (string) $market->getName(), 'version' => $version,
			'publishedAt' => $market->getDatetime($version) ?: null, 'minJeedomVersion' => $minimum !== '' ? $minimum : null,
			'installed' => $installed, 'isEnable' => $installed && config::byKey('active', $id, 0) == 1,
			'installedVersion' => is_object($update) ? $update->getConfiguration('version', 'stable') : null,
			'box' => array('name' => (string) config::byKey('name'), 'version' => jeedom::version()));
	}

	public static function start(int $marketId, string $version, int $userId, string $requestKey): array {
		if (!preg_match('/\A[A-Za-z0-9_-]{16,128}\z/', $requestKey)) {
			throw new InvalidArgumentException('Clé de requête invalide.', 422);
		}
		$preview = self::preview($marketId, $version);
		$last = self::latest($preview['pluginId']);
		if ($last !== null && ($last['requestKey'] ?? null) === $requestKey) {
			if ($last['marketId'] !== $marketId || $last['version'] !== $version) {
				throw new RuntimeException('Cette clé de requête a déjà été utilisée pour une autre installation.', 409);
			}
			$existing = self::status($last['id']);
			if ($existing !== null) return $existing;
		}
		return self::withLock($preview['pluginId'], function () use ($preview, $userId, $requestKey) {
			$last = self::latest($preview['pluginId']);
			if ($last !== null && ($last['requestKey'] ?? null) === $requestKey) {
				if ($last['marketId'] !== $preview['marketId'] || $last['version'] !== $preview['version']) {
					throw new RuntimeException('Cette clé de requête a déjà été utilisée pour une autre installation.', 409);
				}
				return self::status($last['id']);
			}
			self::assertIdle($preview['pluginId']);
			if (config::byKey('enableQueue', 'core', 1, true) == 0) {
				throw new RuntimeException('Les tâches en queue sont désactivées.', 409);
			}
			$operation = array('id' => $preview['pluginId'] . '.' . bin2hex(random_bytes(16)), 'pluginId' => $preview['pluginId'],
				'marketId' => $preview['marketId'], 'version' => $preview['version'], 'state' => 'queued', 'userId' => $userId, 'requestKey' => $requestKey,
				'createdAt' => date('c'), 'startedAt' => null, 'finishedAt' => null, 'queueId' => null, 'error' => null);
			return self::enqueue($operation);
		});
	}

	public static function startFromRepository(string $id, string $source, array $configuration, int $userId, string $requestKey): array {
		if (!preg_match('/\A[A-Za-z0-9_-]{16,128}\z/', $requestKey)) throw new InvalidArgumentException('Clé de requête invalide.', 422);
		$configuration = pluginRepository::validate($id, $source, $configuration, false);
		$fingerprint = hash('sha256', json_encode(array($source, $configuration), JSON_THROW_ON_ERROR));
		return self::withLock($id, function () use ($id, $source, $configuration, $userId, $requestKey, $fingerprint) {
			$last = self::latest($id);
			if ($last !== null && ($last['requestKey'] ?? null) === $requestKey) {
				if (($last['fingerprint'] ?? null) !== $fingerprint) throw new RuntimeException('Cette clé a déjà été utilisée pour une autre installation.', 409);
				return self::status($last['id']);
			}
			self::assertIdle($id);
			pluginRepository::validate($id, $source, $configuration);
			if (config::byKey('enableQueue', 'core', 1, true) == 0) throw new RuntimeException('Les tâches en queue sont désactivées.', 409);
			return self::enqueue(array('id' => $id . '.' . bin2hex(random_bytes(16)), 'pluginId' => $id, 'marketId' => null,
				'source' => $source, 'version' => $configuration['version'] ?? null, 'state' => 'queued', 'userId' => $userId,
				'requestKey' => $requestKey, 'fingerprint' => $fingerprint,
				'parameters' => utils::encrypt(json_encode($configuration, JSON_THROW_ON_ERROR)),
				'createdAt' => date('c'), 'startedAt' => null, 'finishedAt' => null, 'queueId' => null, 'error' => null));
		});
	}

	private static function enqueue(array $operation): array {
		self::store($operation);
		try {
			$queue = new queue();
			$queue->setClass(__CLASS__);
			$queue->setFunction('execute');
			$queue->setArguments(array($operation['id']));
			$queue->setQueueId('rest-plugin-installations');
			$queue->setTimeout(1800);
			$queue->save();
			$operation['queueId'] = (int) $queue->getId();
			self::store($operation);
			$queue->start();
		} catch (Throwable $e) {
			self::fail($operation, $e);
			throw new RuntimeException('Impossible de planifier l’installation.', 503);
		}
		return $operation;
	}

	/** Last operation per plugin; old operation IDs cannot select a replacement job. */
	public static function status(string $operationId): ?array {
		if (!preg_match('/\A([A-Za-z0-9_][A-Za-z0-9_-]{0,126})\.[a-f0-9]{32}\z/', $operationId, $match)) {
			return null;
		}
		$operation = self::latest($match[1]);
		if (!is_array($operation) || $operation['id'] !== $operationId) {
			return null;
		}
		if (in_array($operation['state'], array('queued', 'running'), true) && $operation['queueId'] !== null) {
			$queue = queue::byId($operation['queueId']);
			$lost = !is_object($queue) || in_array($queue->getState(), array('error', 'Not found'), true)
				|| ($operation['state'] === 'running' && !$queue->running());
			if ($lost) {
				// A worker may have just completed between the first read and queue removal.
				$fresh = self::latest($match[1]);
				if ($fresh === null || $fresh['id'] !== $operationId) return null;
				if ($fresh['state'] !== $operation['state']) {
					return $fresh;
				}
				$operation['state'] = 'failed';
				$operation['error'] = array('code' => 'installation_interrupted', 'message' => 'La tâche d’installation a été interrompue.');
			}
		}
		return $operation;
	}

	public static function latest(string $pluginId): ?array {
		self::validatePluginId($pluginId);
		$value = config::byKey('rest::pluginInstallation::' . hash('sha256', $pluginId), 'core', null, true);
		return is_array($value) && isset($value['id'], $value['state']) ? $value : null;
	}

	public static function assertIdle(string $pluginId): void {
		$last = self::latest($pluginId);
		$operation = $last !== null ? self::status($last['id']) : null;
		if ($operation !== null && in_array($operation['state'], array('queued', 'running'), true)) {
			throw new RuntimeException('Une installation est déjà en cours : ' . $operation['id'], 409);
		}
	}

	public static function withLock(string $pluginId, callable $action) {
		self::validatePluginId($pluginId);
		$lock = fopen(jeedom::getTmpFolder('plugin-installations') . '/' . $pluginId . '.lock', 'c');
		if ($lock === false) {
			throw new RuntimeException('Impossible de verrouiller le plugin.', 503);
		}
		try {
			if (!flock($lock, LOCK_EX | LOCK_NB)) {
				throw new RuntimeException('Une opération sur ce plugin est déjà en cours.', 409);
			}
			return $action();
		} finally {
			flock($lock, LOCK_UN);
			fclose($lock);
		}
	}

	/** Native queue entry point. Never rethrow: failed installs must not be retried automatically. */
	public static function execute(string $operationId): void {
		$operation = self::status($operationId);
		if ($operation === null || $operation['state'] !== 'queued') {
			return;
		}
		try {
			self::withLock($operation['pluginId'], function () use ($operation) {
				$fresh = self::latest($operation['pluginId']);
				if ($fresh['id'] !== $operation['id'] || $fresh['state'] !== 'queued') {
					return;
				}
				$old = null;
				$update = null;
				$active = null;
				$operation['state'] = 'running';
				$operation['startedAt'] = date('c');
				self::store($operation);
				try {
					$actor = user::byId($operation['userId']);
					if (!is_object($actor) || !$actor->isValidAndEnabled() || $actor->getProfils() !== 'admin') {
						throw new RuntimeException('L’administrateur ayant demandé l’installation n’est plus autorisé.', 403);
					}
					unautorizedInDemo($actor);
					$configuration = null;
					if (($operation['source'] ?? 'market') !== 'market') {
						$configuration = json_decode(utils::decrypt($operation['parameters']), true, 512, JSON_THROW_ON_ERROR);
						$configuration = pluginRepository::validate($operation['pluginId'], $operation['source'], $configuration);
						if ($operation['source'] === 'file') $configuration['path'] = pluginRepository::archivePath($configuration['path'], $operation['pluginId']);
						$preview = array('pluginId' => $operation['pluginId'], 'name' => $operation['pluginId'],
							'isEnable' => is_file(plugin::getPathById($operation['pluginId'])) && config::byKey('active', $operation['pluginId'], 0) == 1);
					} else {
						$preview = self::preview($operation['marketId'], $operation['version']);
					}
					if ($preview['pluginId'] !== $operation['pluginId']) {
						throw new RuntimeException('La référence du plugin a changé sur le Market.', 409);
					}
					$update = update::byTypeAndLogicalId('plugin', $operation['pluginId']);
					$old = is_object($update) ? clone $update : null;
					$active = $preview['isEnable'] ? 1 : 0;
					if (!is_object($update)) {
						$update = new update();
						$update->setType('plugin');
						$update->setLogicalId($operation['pluginId']);
						$update->setName($preview['name']);
						$update->setSource('market');
					}
					if ($configuration !== null) {
						$update->setSource($operation['source']);
						foreach ($configuration as $key => $value) $update->setConfiguration($key, $value);
					} else {
						$update->setConfiguration('version', $operation['version']);
					}
					$update->save();
					$update->doUpdate();
					$path = plugin::getPathById($operation['pluginId']);
					$manifest = is_file($path) ? json_decode(file_get_contents($path), true) : null;
					if (!is_array($manifest) || ($manifest['id'] ?? null) !== $operation['pluginId']) {
						throw new RuntimeException('Le plugin installé est absent ou son manifeste est invalide.');
					}
					config::save('active', $active, $operation['pluginId']);
					$active = null;
					$operation['state'] = 'succeeded';
					$operation['finishedAt'] = date('c');
					self::clearParameters($operation);
					self::store($operation);
				} catch (Throwable $e) {
					try {
						if (is_object($old)) {
							$old->save();
						} elseif (is_object($update) && $update->getId()) {
							$update->remove();
						}
						if ($active !== null) config::save('active', $active, $operation['pluginId']);
					} catch (Throwable $restoreError) {
						$e = new RuntimeException(self::errorMessage($e) . ' Restauration de l’état : ' . self::errorMessage($restoreError));
					}
					self::fail($operation, $e);
				}
			});
		} catch (Throwable $e) {
			if ($e->getCode() !== 409) self::fail($operation, $e);
		}
	}

	private static function store(array $operation): void {
		config::save('rest::pluginInstallation::' . hash('sha256', $operation['pluginId']), $operation);
	}

	private static function fail(array $operation, Throwable $error): void {
		self::clearParameters($operation);
		$operation['state'] = 'failed';
		$operation['finishedAt'] = date('c');
		$operation['error'] = array('code' => 'installation_failed', 'message' => self::errorMessage($error));
		self::store($operation);
	}

	private static function clearParameters(array &$operation): void {
		if (!isset($operation['parameters'])) return;
		try {
			$configuration = json_decode(utils::decrypt($operation['parameters']), true, 512, JSON_THROW_ON_ERROR);
			if (($operation['source'] ?? null) === 'file') pluginRepository::removeArchive($configuration['path']);
		} catch (Throwable $ignored) { /* A missing or corrupt payload must not hide the installation result. */ }
		unset($operation['parameters']);
	}

	public static function errorMessage(Throwable $error): string {
		$message = preg_replace('/([?&](?:password|username|token|auth|apikey)=)[^&\s]+/i', '$1[masqué]', $error->getMessage());
		return preg_replace('~(https?://)[^/\s@]+@~i', '$1[masqué]@', $message);
	}

	public static function validatePluginId(string $id): void {
		if (!preg_match('/\A[A-Za-z0-9_][A-Za-z0-9_-]{0,126}\z/', $id)) {
			throw new InvalidArgumentException('Identifiant de plugin invalide.', 422);
		}
	}
}

