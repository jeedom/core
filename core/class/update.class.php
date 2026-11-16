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

/* * ***************************Includes********************************* */
require_once __DIR__ . '/../../core/php/core.inc.php';

class update {
	/*     * *************************Attributs****************************** */

	private $id;
	private $type = 'plugin';
	private $logicalId;
	private $name;
	private $localVersion;
	private $remoteVersion;
	private $status;
	private $configuration;
	private $source = 'market';
	private $updateDate;
	private $_changeUpdate = false;
	private $_changed = false;

	/*     * ***********************Méthodes statiques*************************** */

	public static function checkAllUpdate($_filter = '', $_findNewObject = true, bool $_saveLastCheck = true) {
		$findCore = false;
		if ($_findNewObject) {
			self::findNewUpdateObject();
		}
		$updates = self::all($_filter);
		$updates_sources = array();
		if (is_array($updates)) {
			foreach ($updates as $update) {
				if ($update->getType() == 'core') {
					if ($findCore) {
						$update->remove();
						continue;
					}
					$findCore = true;
					$update->setType('core');
					$update->setLogicalId('jeedom');
					$update->setSource(config::byKey('core::repo::provider'));
					$update->setLocalVersion(jeedom::version());
					$update->save();
					$update->checkUpdate();
				} else {
					if ($update->getStatus() != 'hold' && $update->getConfiguration('doNotUpdate', 0) != 1
						&& ($update->getType() !== 'plugin' || is_file(plugin::getPathById($update->getLogicalId())))) {
						if (!isset($updates_sources[$update->getSource()])) {
							$updates_sources[$update->getSource()] = array();
						}
						$updates_sources[$update->getSource()][] = $update;
					}
				}
			}
		}
		if (!$findCore && ($_filter == '' || $_filter == 'core')) {
			$update = new update();
			$update->setType('core');
			$update->setLogicalId('jeedom');
			$update->setSource(config::byKey('core::repo::provider'));
			$update->setLocalVersion(jeedom::version());
			$update->save();
			$update->checkUpdate();
		}
		foreach ($updates_sources as $source => $updates) {
			$class = self::repositoryClass($source, 'checkUpdate');
			if ($class !== null) {
				$class::checkUpdate($updates);
			}
		}
		if ($_saveLastCheck) {
			config::save('update::lastCheck', date('Y-m-d H:i:s'));
		}
	}

	public static function listRepo() {
		$return = array();
		foreach (ls(__DIR__ . '/../repo', '*.repo.php') as $file) {
			if (substr_count($file, '.') != 2) {
				continue;
			}
			$class = 'repo_' . str_replace('.repo.php', '', $file);
			$return[str_replace('.repo.php', '', $file)] = array(
				'name' => $class::$_name,
				'class' => $class,
				'configuration' => (method_exists($class, 'getConfigurationOption')) ? $class::getConfigurationOption() : array(),
				'scope' => $class::$_scope,
			);
		}
		return $return;
	}

	public static function repoById($_id) {
		$class = 'repo_' . $_id;
		$return = array(
			'name' => $class::$_name,
			'configuration' => (method_exists($class, 'getConfigurationOption')) ? $class::getConfigurationOption() : array(),
			'scope' => $class::$_scope,
		);
		return $return;
	}

	/** Repositories whose backup archives are available to list or retrieve. */
	public static function listBackupRepos(): array {
		return array_filter(self::listRepo(), static fn ($repo, $id) => !empty($repo['scope']['backup'])
			&& self::repositoryClass($id, 'backup_list') !== null
			&& ($id !== 'samba' || repo_samba::backupEnabled()), ARRAY_FILTER_USE_BOTH);
	}

	public static function updateAll($_filter = '') {
		if ($_filter == 'core') {
			foreach (self::byType($_filter) as $update) {
				$update->doUpdate();
			}
		} else {
			$error = false;
			if ($_filter == '') {
				$updates = self::all();
			} else {
				$updates = self::byType($_filter);
			}
			if (is_array($updates)) {
				foreach ($updates as $update) {
					if ($update->getStatus() != 'hold' && $update->getStatus() == 'update' && $update->getType() != 'core') {
						try {
							$update->doUpdate();
						} catch (\Throwable $e) {
							log::add(__CLASS__, 'alert', log::exception($e));
							$error = true;
						}
					}
				}
			}
			return $error;
		}
	}

	public static function refreshUpdateMessage() {
		$toUpdate = [];
		foreach (self::byStatus('update') as $update) {
			if ($update->getConfiguration('doNotUpdate', 0) == 0) {
				$toUpdate[] = $update->getLogicalId();
			}
		}
		if (!empty($toUpdate)) {
			$msg = __('De nouvelles mises à jour sont disponibles', __FILE__) . ' : ' . implode(', ', $toUpdate);
			$action = '<a href="/index.php?v=d&p=update">' . __('Centre de mise à jour', __FILE__) . '</a>';
			foreach (message::byPlugin(__CLASS__) as $updateMessage) {
				if ($updateMessage->getMessage() !== $msg) {
					$updateMessage->remove();
				}
			}
			message::add(__CLASS__, $msg, $action, 'newUpdate');
		} else {
			message::removeAll(__CLASS__);
		}
	}

	public static function byId($_id) {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM `update`
		WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byStatus($_status) {
		$values = array(
			'status' => $_status,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM `update`
		WHERE status=:status';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byLogicalId($_logicalId) {
		$values = array(
			'logicalId' => $_logicalId,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM `update`
		WHERE logicalId=:logicalId';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byType($_type) {
		$values = array(
			'type' => $_type,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM `update`
		WHERE type=:type';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byTypeAndLogicalId($_type, $_logicalId) {
		$values = array(
			'logicalId' => $_logicalId,
			'type' => $_type,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM `update`
		WHERE logicalId=:logicalId
		AND type=:type';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	/**
	 *
	 * @return array de tous les utilisateurs
	 */
	public static function all($_filter = '') {
		$values = array();
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM `update`';
		if ($_filter != '') {
			$values['type'] = $_filter;
			$sql .= ' WHERE `type`=:type';
		}
		$sql .= ' ORDER BY FIELD( `status`, "update","ok","depreciated") ASC,FIELD( `type`,"plugin","core") DESC, `name` ASC';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function nbNeedUpdate() {
		$count = 0;
		foreach (self::byStatus('update') as $update) {
			if ($update->getConfiguration('doNotUpdate', 0) == 0) {
				$count++;
			}
		}
		return $count;
	}

	public static function findNewUpdateObject() {
		foreach ((plugin::listPlugin()) as $plugin) {
			$plugin_id = $plugin->getId();
			$update = self::byTypeAndLogicalId('plugin', $plugin_id);
			if (!is_object($update)) {
				$update = new update();
				$update->setLogicalId($plugin_id);
				$update->setType('plugin');
				$update->setLocalVersion(date('Y-m-d H:i:s'));
				$update->save();
			}
			$find = array();
			if (method_exists($plugin_id, 'listMarketObject')) {
				foreach (($plugin_id::listMarketObject()) as $logical_id) {
					$find[$logical_id] = true;
					$update = self::byTypeAndLogicalId($plugin_id, $logical_id);
					if (!is_object($update)) {
						$update = new update();
						$update->setLogicalId($logical_id);
						$update->setType($plugin_id);
						$update->setLocalVersion(date('Y-m-d H:i:s'));
						$update->save();
					}
				}
				foreach (self::byType($plugin_id) as $update) {
					if (!isset($find[$update->getLogicalId()])) {
						$update->remove();
					}
				}
			} else {
				$values = array(
					'type' => $plugin_id,
				);
				$sql = 'DELETE FROM `update`
				WHERE type=:type';
				DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
			}
		}
	}

	public static function listCoreUpdate() {
		return ls(__DIR__ . '/../../install/update', '*');
	}

	/** @return list<string> The official Core branches offered by both interfaces. */
	public static function listCoreBranches(): array {
		$key = 'core::branch::default::list';
		$cached = cache::byKey($key)->getValue([]);
		if (is_array($cached['branches'] ?? null)) {
			return $cached['branches'];
		}
		$branches = [];
		for ($page = 1; ; $page++) {
			$request = new com_http('https://api.github.com/repos/jeedom/core/branches?per_page=100&page=' . $page);
			$request->setUserAgent('Jeedom')->setNoSslCheck(false)->setCURLOPT([CURLOPT_FAILONERROR => true]);
			$items = json_decode($request->exec(10, 1), true, 512, JSON_THROW_ON_ERROR);
			if (!is_array($items) || !array_is_list($items)) {
				throw new RuntimeException('Liste des branches GitHub invalide.');
			}
			foreach ($items as $item) {
				$name = $item['name'] ?? null;
				if (is_string($name) && self::isCoreBranchName($name) && $name !== 'V4-stable') {
					$branches[] = $name;
				}
			}
			if (count($items) < 100) {
				break;
			}
		}
		$branches = array_unique([...$branches, 'master']);
		sort($branches, SORT_NATURAL | SORT_FLAG_CASE);
		cache::set($key, ['branches' => $branches], 86400);
		return $branches;
	}

	public static function isCoreBranchName(string $branch): bool {
		return $branch !== '' && strlen($branch) <= 255 && $branch !== '@'
			&& !str_starts_with($branch, '-') && !str_starts_with($branch, '/')
			&& !preg_match('/[\x00-\x20\x7f~^:?*\[\\\\]/', $branch)
			&& !preg_match('~\.\.|@\{|(?:^|/)\.|\.lock(?:/|$)|//|/\z|\.\z~', $branch);
	}

	public static function officialCoreRef(): string {
		$branch = (string) config::byKey('core::branch', 'core', 'master');
		$isTag = str_starts_with($branch, 'tag::');
		$name = $isTag ? substr($branch, 5) : $branch;
		return ($isTag ? 'refs/tags/' : 'refs/heads/') . implode('/', array_map('rawurlencode', explode('/', $name)));
	}

	/* Opérations de mise à jour */

	private const OPERATION_KEY = 'rest::updateOperation';
	private static bool $_executingOperation = false;

	/** @return list<string> Installed Core migration versions that can be reapplied. */
	public static function coreMigrations(): array {
		$versions = [];
		$installed = jeedom::version();
		foreach (self::listCoreUpdate() as $file) {
			if (preg_match('/\A(\d+\.\d+\.\d+)\.php\z/', $file, $match)
				&& version_compare($match[1], $installed, '<=')) {
				$versions[] = $match[1];
			}
		}
		usort($versions, static fn ($first, $second) => version_compare($second, $first));
		return $versions;
	}

	public static function startOperation(array $ids, int $userId, string $requestKey, string $kind = 'update', bool $force = false, array $options = []): array {
		return self::withOperationLock('mutation', static function () use ($ids, $userId, $requestKey, $kind, $force, $options) {
			$last = self::readOperation();
			if ($last !== null && $last['requestKey'] === $requestKey) {
				$original = array_column($last['items'], 'id');
				$selected = $ids;
				sort($original); sort($selected);
				if ($original !== $selected || $last['userId'] !== $userId || $last['kind'] !== $kind
					|| ($last['force'] ?? false) !== $force || ($last['options'] ?? []) !== $options) {
					throw new RuntimeException('Clé déjà utilisée pour une autre demande.', 409);
				}
				return self::latestOperation();
			}
			self::assertOperationIdle();
			self::assertNoInstallations();
			if (isset($options['target']['pluginId'])) {
				self::checkablePlugin($options['target']['pluginId']);
			}
			if ($kind === 'maintenance') {
				self::validateMaintenance($options);
			}
			$items = array();
			foreach ($ids as $id) {
				$update = self::byId($id);
				self::validateUpdate($update, $force);
				$items[] = array('id' => $id, 'type' => $update->getType(), 'logicalId' => $update->getLogicalId(),
					'name' => $update->getType() === 'core' ? 'Core Jeedom' : plugin::byId($update->getLogicalId())->getName(), 'state' => 'queued', 'error' => null);
			}
			usort($items, static fn ($a, $b) => ($a['type'] === 'core' ? 0 : 1) <=> ($b['type'] === 'core' ? 0 : 1));
			if (isset($options['backupBefore']) && ($items[0]['type'] ?? null) !== 'core') {
				throw new RuntimeException('Le choix de sauvegarde est réservé aux mises à jour du Core.', 422);
			}
			$operation = array('id' => bin2hex(random_bytes(16)), 'kind' => $kind, 'state' => 'queued', 'items' => $items,
				'createdAt' => date('c'), 'startedAt' => null, 'finishedAt' => null, 'error' => null, 'progress' => null,
				'logName' => 'update', 'userId' => $userId, 'requestKey' => $requestKey, 'force' => $force, 'options' => $options);
			self::storeOperation($operation);
			try {
				system::php(escapeshellarg(__DIR__ . '/../php/jeeRestUpdate.php') . ' ' . escapeshellarg($operation['id']) . ' > /dev/null 2>&1 &');
			} catch (Throwable $e) {
				self::failOperation($operation, 'Impossible de lancer la mise à jour.');
				throw new RuntimeException('Impossible de lancer la mise à jour.', 503);
			}
			return self::publicOperation($operation);
		});
	}

	public static function latestOperation(): ?array {
		$operation = self::readOperation();
		if ($operation === null) return null;
		if (in_array($operation['state'], array('queued', 'running'), true)) {
			$lock = fopen(jeedom::getTmpFolder('rest-updates') . '/runner.lock', 'c');
			if ($lock !== false) {
				if (flock($lock, LOCK_EX | LOCK_NB)) {
					// Re-read under the worker lock: completion may have happened just now.
					$operation = self::readOperation();
					if ($operation !== null && ($operation['state'] === 'running' || ($operation['state'] === 'queued' && time() - strtotime($operation['createdAt']) > 120))) {
						self::failOperation($operation, 'La tâche de mise à jour a été interrompue.');
					}
					flock($lock, LOCK_UN);
				}
				fclose($lock);
			}
		}
		return self::publicOperation($operation);
	}

	/** CLI worker, independent of browser requests and the cron/queue restart. */
	public static function executeOperation(string $id): void {
		self::withOperationLock('runner', static function () use ($id) {
			self::withOperationLock('mutation', static function () use ($id) {
				$operation = self::readOperation();
				if ($operation === null || $operation['id'] !== $id || $operation['state'] !== 'queued') return;
				self::$_executingOperation = true;
				$operation['state'] = 'running';
				$operation['startedAt'] = date('c');
				self::storeOperation($operation);
				try {
					$actor = user::byId($operation['userId']);
					if (!is_object($actor) || !$actor->isValidAndEnabled() || $actor->getProfils() !== 'admin') throw new RuntimeException('Administrateur désactivé ou droits retirés.');
					log::clear('update');
					$options = $operation['options'] ?? [];
					if ($operation['kind'] === 'check') {
						self::executeCheck($options['target'] ?? null);
					} elseif ($operation['kind'] === 'maintenance') {
						self::validateMaintenance($options);
						$params = match ($options['action']) {
							'force' => ['force=1', 'mode=force', 'backup::before=0'],
							'preUpdate' => ['backup::before=1', 'preUpdate=1'],
							'reapply' => ['backup::before=1', 'update::reapply=' . $options['version']],
						};
						self::runCoreInstaller($params);
					}
					foreach ($operation['items'] as $index => $item) {
						$operation['items'][$index]['state'] = 'running';
						self::storeOperation($operation);
						$update = self::byId($item['id']);
						if (!is_object($update) || $update->getType() !== $item['type'] || $update->getLogicalId() !== $item['logicalId']) throw new RuntimeException('Le composant sélectionné a changé.');
						self::validateUpdate($update, $operation['force'] ?? false);
						if ($item['type'] === 'core') {
							self::runCoreInstaller(['backup::before=' . (int) ($options['backupBefore'] ?? true)]);
						} else {
							plugin::withInstallationLock($item['logicalId'], static function () use ($update, $item) {
								plugin::assertInstallationIdle($item['logicalId']);
								if ($update->getConfiguration('doNotUpdate', 0) == 1 || $update->getStatus() === 'hold') throw new RuntimeException('Les mises à jour de ce plugin sont désactivées.');
								$update->doUpdate();
							});
						}
						$operation['items'][$index]['state'] = 'succeeded';
						self::storeOperation($operation);
					}
					self::refreshUpdateMessage();
					$operation['state'] = 'succeeded';
					$operation['finishedAt'] = date('c');
					self::storeOperation($operation);
				} catch (Throwable $e) {
					$message = self::operationErrorMessage($e);
					log::add('update', 'error', $message);
					self::failOperation($operation, $message);
				} finally { self::$_executingOperation = false; }
			}, LOCK_EX);
		});
	}

	private static function checkablePlugin(string $pluginId): ?self {
		if (!is_file(plugin::getPathById($pluginId))) {
			throw new RuntimeException('Plugin absent.', 404);
		}
		$update = self::byTypeAndLogicalId('plugin', $pluginId);
		if (is_object($update) && ($update->getStatus() === 'hold' || $update->getConfiguration('doNotUpdate', 0) == 1)) {
			throw new RuntimeException('Les mises à jour de ce plugin sont désactivées.', 409);
		}
		return $update ?: null;
	}

	private static function executeCheck(?array $target): void {
		if ($target === null) {
			self::checkAllUpdate('core', true, false);
			self::checkAllUpdate('plugin', false);
		} elseif ($target['type'] === 'core') {
			self::checkAllUpdate('core', false, false);
		} else {
			$pluginId = $target['pluginId'];
			$update = self::checkablePlugin($pluginId);
			if ($update === null) {
				$update = new self();
				$update->setLogicalId($pluginId);
				$update->setType('plugin');
				$update->setLocalVersion(date('Y-m-d H:i:s'));
				$update->save();
			}
			$update->checkUpdate();
		}
	}

	private static function validateMaintenance(array $options): void {
		if (config::byKey('update::allowCore', 'core', 1) != 1) {
			throw new RuntimeException('La mise à jour du Core est désactivée.', 409);
		}
		if ($options['action'] === 'reapply' && !in_array($options['version'], self::coreMigrations(), true)) {
			throw new RuntimeException('Script de migration du Core indisponible.', 422);
		}
	}

	private static function runCoreInstaller(array $params): void {
		// A waiting shell would also match the native installer's process guard.
		$arguments = implode(' ', array_map('escapeshellarg', $params));
		system::php(escapeshellarg(__DIR__ . '/../../install/update.php') . ' core=1 plugins=0 ' . $arguments
			. ' >> ' . escapeshellarg(log::getPathToLog('update')) . ' 2>&1 &');
		self::waitForCore();
		clearstatcache();
		jeedom::version(true);
	}

	/** Serialize admission with plugin installation/mutation; workers keep the exclusive lock. */
	public static function withPluginMutation(callable $action, bool $wait = false) {
		if (self::$_executingOperation) return $action();
		return self::withOperationLock('mutation', static function () use ($action) {
			self::assertOperationIdle();
			return $action();
		}, $wait ? LOCK_SH : LOCK_SH | LOCK_NB);
	}

	private static function repositoryClass(?string $source, string $method): ?string {
		$class = 'repo_' . $source;
		if (!method_exists($class, $method) || ($source === 'market' && !repo_market::isConnected())) {
			return null;
		}
		return $class;
	}

	public static function canReinstall(update $update): bool {
		if ($update->getStatus() === 'hold') return false;
		if ($update->getType() === 'core') {
			return $update->getLogicalId() === 'jeedom' && config::byKey('update::allowCore', 'core', 1) == 1;
		}
		if ($update->getType() !== 'plugin' || $update->getConfiguration('doNotUpdate', 0) == 1) return false;
		$source = $update->getSource();
		return self::repositoryClass($source, 'downloadObject') !== null;
	}

	public static function operationErrorMessage(Throwable $error): string {
		$message = preg_replace('/([?&](?:password|username|token|auth|apikey)=)[^&\s]+/i', '$1[masqué]', $error->getMessage());
		return preg_replace('~(https?://)[^/\s@]+@~i', '$1[masqué]@', $message);
	}

	private static function validateUpdate($update, bool $force): void {
		if (!is_object($update) || !self::canReinstall($update) || (!$force && $update->getStatus() !== 'update')) {
			throw new RuntimeException('Un composant sélectionné ne peut pas être mis à jour.', 409);
		}
		if ($update->getType() === 'plugin' && !is_file(plugin::getPathById($update->getLogicalId()))) {
			throw new RuntimeException('Plugin absent.', 409);
		}
	}

	private static function assertNoInstallations(): void {
		foreach (queue::all() as $queue) {
			if ($queue->getClass() === plugin::class && $queue->getFunction() === 'executeInstallation'
				&& !in_array($queue->getState(), array('error', 'Not found'), true)) throw new RuntimeException('Une installation de plugin est déjà en cours.', 409);
		}
	}

	/** The native installer may relaunch itself after updating its own script. */
	private static function waitForCore(): void {
		$deadline = time() + 3600;
		$idleSince = null;
		while (time() < $deadline) {
			$tail = self::operationLogTail();
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

	private static function assertOperationIdle(): void {
		$last = self::latestOperation();
		if (($last !== null && in_array($last['state'], array('queued', 'running'), true)) || count(system::ps('install/update.php', 'sudo')) > 0) throw new RuntimeException('Une mise à jour est déjà en cours.', 409);
	}

	private static function readOperation(): ?array {
		$value = config::byKey(self::OPERATION_KEY, 'core', null, true);
		return is_array($value) && isset($value['id'], $value['state']) ? $value : null;
	}

	private static function storeOperation(array $operation): void { config::save(self::OPERATION_KEY, $operation); }

	private static function failOperation(array &$operation, string $message): void {
		$operation['state'] = 'failed';
		$operation['finishedAt'] = date('c');
		$operation['error'] = $message;
		foreach ($operation['items'] as &$item) {
			if ($item['state'] === 'running') { $item['state'] = 'failed'; $item['error'] = $message; }
			elseif ($item['state'] === 'queued') $item['state'] = 'skipped';
		}
		unset($item);
		self::storeOperation($operation);
	}

	private static function publicOperation(array $operation): array {
		unset($operation['userId'], $operation['requestKey'], $operation['force'], $operation['options']);
		$runningCore = $operation['kind'] === 'maintenance'
			|| ($operation['items'][0]['type'] ?? null) === 'core' && $operation['items'][0]['state'] === 'running';
		if ($runningCore && $operation['state'] === 'running'
			&& preg_match_all('/\[PROGRESS\]\[(\d+)\]/', self::operationLogTail(), $matches)) {
			$operation['progress'] = min(100, (int) end($matches[1]));
		}
		return $operation;
	}

	private static function operationLogTail(): string {
		$handle = @fopen(log::getPathToLog('update'), 'rb');
		if ($handle === false) return '';
		fseek($handle, max(0, (int) fstat($handle)['size'] - 16384));
		$value = stream_get_contents($handle);
		fclose($handle);
		return $value === false ? '' : $value;
	}

	private static function withOperationLock(string $name, callable $action, int $mode = LOCK_EX | LOCK_NB) {
		$lock = fopen(jeedom::getTmpFolder('rest-updates') . '/' . $name . '.lock', 'c');
		if ($lock === false) throw new RuntimeException('Verrou de mise à jour indisponible.', 503);
		try {
			if (!flock($lock, $mode)) throw new RuntimeException('Une opération est déjà en cours.', 409);
			return $action();
		} finally { flock($lock, LOCK_UN); fclose($lock); }
	}

	/*     * *********************Méthodes d'instance************************* */

	public function getInfo() {
		if ($this->getType() != 'core') {
			$class = 'repo_' . $this->getSource();
			if (method_exists($class, 'objectInfo')) {
				return $class::objectInfo($this);
			}
		}
		return array();
	}

	public function doUpdate() {
		if ($this->getConfiguration('doNotUpdate') == 1  && $this->getType() != 'core') {
			log::add(__CLASS__, 'alert', __('Vérification des mises à jour, mise à jour et réinstallation désactivées sur', __FILE__) . ' ' . $this->getLogicalId());
			return;
		}
		if ($this->getType() == 'core') {
			jeedom::update();
		} else {
			log::add(__CLASS__, 'alert', __('Début de la mise à jour de :', __FILE__) . ' ' . $this->getLogicalId() . "\n");
			$class = self::repositoryClass($this->getSource(), 'downloadObject');
			if ($class !== null) {
				$cibDir = jeedom::getTmpFolder('market') . '/' . $this->getLogicalId();
				if (file_exists($cibDir)) {
					rrmdir($cibDir);
				}
				mkdir($cibDir);
				if (!file_exists($cibDir) && !mkdir($cibDir, 0775, true)) {
					throw new Exception(__('Impossible de créer le dossier', __FILE__) . ' : ' . $cibDir . '. ' . __('Problème de droits ?', __FILE__));
				}
				log::add(__CLASS__, 'alert', __('Téléchargement du plugin (source', __FILE__) . ' : ' . $this->getSource() . ')...');
				$info = $class::downloadObject($this);
				if ($info['path'] !== false) {
					$tmp = $info['path'];
					log::add(__CLASS__, 'alert', __("OK\n", __FILE__));

					if (filesize($tmp) < 100) {
						throw new Exception(__("Echec lors du téléchargement du plugin (taille inférieure à 100 octets), veuillez réessayer plus tard. Cela peut être dû à une absence de connexion au market (effectuez un test de connexion depuis la configuration générale), lié à un manque d'espace disque, une version minimale requise ou un souci sur le plugin ou son achat, etc...", __FILE__));
					}
					$extension = strtolower(strrchr($tmp, '.'));
					if (!in_array($extension, array('.zip'))) {
						throw new Exception(__('Extension du fichier non valide (autorisé .zip) :', __FILE__) . ' ' . $extension);
					}
					log::add(__CLASS__, 'alert', __('Décompression du zip...', __FILE__));
					$zip = new ZipArchive;
					$res = $zip->open($tmp);
					if ($res === TRUE) {
						if (!$zip->extractTo($cibDir . '/')) {
							$content = file_get_contents($tmp);
							throw new Exception(__("Impossible d'installer le plugin. Les fichiers n'ont pas pu être décompressés", __FILE__) . ' : ' . substr($content, 255));
						}
						$zip->close();
						unlink($tmp);
						if (!file_exists($cibDir . '/plugin_info')) {
							$files = ls($cibDir, '*');
							if (count($files) == 1 && file_exists($cibDir . '/' . $files[0] . 'plugin_info')) {
								$cibDir = $cibDir . '/' . $files[0];
							}
						}
						if (is_file($cibDir . '/plugin_info/info.json') && is_array($data = json_decode(file_get_contents($cibDir . '/plugin_info/info.json'), true)) && isset($data['require'])) {
							$vJeedom = jeedom::version();
							if (version_compare($data['require'], $vJeedom, '>')) {
								log::add(__CLASS__, 'alert', 'KO ' . __("Version minimale requise", __FILE__) . ' (' . $data['require'] . ') > ' . __("Version Jeedom", __FILE__) . ' (' . $vJeedom . ')');
								rrmdir($cibDir);
								$cibDir = jeedom::getTmpFolder('market') . '/' . $this->getLogicalId();
								if (file_exists($cibDir)) {
									rrmdir($cibDir);
								}
								throw new Exception($this->getLogicalId() . ' : ' . __('Version du core Jeedom non supportée, installation annulée', __FILE__));
							}
						}
						log::add(__CLASS__, 'alert', __("OK\n", __FILE__));
						$this->preInstallUpdate();
						try {
							if (file_exists(__DIR__ . '/../../plugins/' . $this->getLogicalId() . '/doc')) {
								shell_exec('sudo rm -rf ' . __DIR__ . '/../../plugins/' . $this->getLogicalId() . '/doc');
							}
							if (file_exists(__DIR__ . '/../../plugins/' . $this->getLogicalId() . '/docs')) {
								shell_exec('sudo rm -rf ' . __DIR__ . '/../../plugins/' . $this->getLogicalId() . '/docs');
							}
						} catch (Exception $e) {
						}
						shell_exec('find ' . $cibDir . '/ -exec touch {} +');
						rmove($cibDir . '/', __DIR__ . '/../../plugins/' . $this->getLogicalId(), false, array(), true);
						rrmdir($cibDir);
						$cibDir = jeedom::getTmpFolder('market') . '/' . $this->getLogicalId();
						if (file_exists($cibDir)) {
							rrmdir($cibDir);
						}
					} else {
						throw new Exception(__("Impossible de décompresser l'archive zip", __FILE__) . ' : ' . $tmp . ' => ' . ZipErrorMessage($res));
					}
				}
				$this->postInstallUpdate($info);
			} else {
				throw new Exception(sprintf(__('La source "%s" est introuvable ou inactive.', __FILE__), $this->getSource()));
			}
		}
		$this->refresh();
		$this->checkUpdate();
	}

	public function deleteObjet() {
		if ($this->getType() == 'core') {
			throw new Exception(__('Vous ne pouvez pas supprimer le core de Jeedom', __FILE__));
		} else {
			switch ($this->getType()) {
				case 'plugin':
					try {
						$plugin = plugin::byId($this->getLogicalId());
						if (is_object($plugin)) {
							foreach (eqLogic::byType($this->getLogicalId()) as $eqLogic) {
								try {
									$eqLogic->remove();
								} catch (\Throwable $e) {
								}
							}
							try {
								$plugin->setIsEnable(0);
							} catch (\Throwable $e) {
							}
						}
						config::remove('*', $this->getLogicalId());
					} catch (\Throwable $e) {
					}
					break;
			}
			try {
				$class = self::repositoryClass($this->getSource(), 'deleteObjet');
				if ($class !== null) {
					$class::deleteObjet($this);
				}
			} catch (\Throwable $e) {
			}
			switch ($this->getType()) {
				case 'plugin':
					$cibDir = __DIR__ . '/../../plugins/' . $this->getLogicalId();
					if (file_exists($cibDir)) {
						rrmdir($cibDir);
					}
					break;
			}
			$this->remove();
		}
	}

	public function preInstallUpdate() {
		if (!file_exists(__DIR__ . '/../../plugins')) {
			mkdir(__DIR__ . '/../../plugins');
		}
		shell_exec(system::getCmdSudo() . ' chown -R ' . system::get('www-uid') . ':' . system::get('www-gid') . ' ' . __DIR__ . '/../../plugins');
		@chmod(__DIR__ . '/../../plugins', 0775);
		$cibDir = __DIR__ . '/../../plugins/' . $this->getLogicalId();
		switch ($this->getType()) {
			case 'plugin':
				if (!file_exists($cibDir) && !mkdir($cibDir, 0775, true)) {
					throw new Exception(__('Impossible de créer le dossier  :', __FILE__) . ' ' . $cibDir . __('Problème de droits ?', __FILE__));
				}
				try {
					$plugin = plugin::byId($this->getLogicalId());
					if (is_object($plugin)) {
						log::add(__CLASS__, 'alert', __('Action de pré-update...', __FILE__));
						$plugin->callInstallFunction('pre_update');
						log::add(__CLASS__, 'alert', __("OK\n", __FILE__));
					}
				} catch (\Throwable $e) {
				}
		}
	}

	public function postInstallUpdate($_infos) {
		log::add(__CLASS__, 'alert', __('Post-installation de', __FILE__) . ' ' . $this->getLogicalId() . '...');
		try {
			if (function_exists('opcache_reset')) {
				opcache_reset();
			}
		} catch (\Exception $e) {
		}
		switch ($this->getType()) {
			case 'plugin':
				try {
					$plugin = plugin::byId($this->getLogicalId());
					$cibDir = __DIR__ . '/../../plugins/' . $this->getLogicalId();
					log::add(__CLASS__, 'alert',  __('Vérification des droits sur les fichiers...', __FILE__));
					$cmd = system::getCmdSudo() . 'chown -R ' . system::get('www-uid') . ':' . system::get('www-gid') . ' ' . $cibDir . ';';
					$cmd .= system::getCmdSudo() . 'chmod 775 -R ' . $cibDir . ';';
					$cmd .= system::getCmdSudo() . 'chmod 775 -R ' . $cibDir . '/.*;';
					exec($cmd);
					log::add(__CLASS__, 'alert', __("OK", __FILE__) . "\n");
					log::add(__CLASS__, 'alert',  __('Suppression des fichiers inutiles...', __FILE__));
					foreach (array('3rdparty', '3rparty', 'desktop', 'mobile', 'core', 'docs', 'install', 'script', 'plugin_info') as $folder) {
						if (!file_exists($cibDir . '/' . $folder)) {
							continue;
						}
						shell_exec('find ' . $cibDir . '/' . $folder . '/* -mtime +7 -type f ! -iname "custom.*" ! -iname "common.config.php" ! -path "./vendor/*"  -delete 2>/dev/null');
					}
				} catch (\Throwable $e) {
					$this->remove();
					throw new Exception(__("Impossible d'installer le plugin. Le nom du plugin est différent de l'ID ou le plugin n'est pas correctement formé. Veuillez contacter l'auteur", __FILE__));
				}
				$plugin->callInstallFunction('post_plugin_install');
				if (is_object($plugin) && $plugin->isActive()) {
					$plugin->setIsEnable(1);
				}
				$plugin->setCache('usedSpace', null);
				break;
		}
		if (isset($_infos['localVersion'])) {
			$this->setLocalVersion($_infos['localVersion']);
		}
		$this->setUpdateDate(date('Y-m-d H:i:s'));
		$this->save();
		log::add(__CLASS__, 'alert', __("OK", __FILE__) . "\n");
		log::add(__CLASS__, 'alert', __("END UPDATE SUCCESS", __FILE__) . "\n");
	}

	public static function getLastAvailableVersion() {
		try {
			$url = 'https://raw.githubusercontent.com/jeedom/core/' . self::officialCoreRef() . '/core/config/version';
			$request_http = new com_http($url);
			$request_http->setNoSslCheck(false)->setCURLOPT([CURLOPT_FAILONERROR => true]);
			return trim($request_http->exec(30));
		} catch (\Throwable $e) {
			log::add(__CLASS__, 'error', __('Erreur lors de la récuperation de la derniere version de Jeedom, url :', __FILE__) . ' ' . $url . ' => ' . log::exception($e));
		}
		return null;
	}

	public function checkUpdate() {
		if ($this->getConfiguration('doNotUpdate') == 1 && $this->getType() != 'core') {
			log::add(__CLASS__, 'alert', __('Vérification des mises à jour, mise à jour et réinstallation désactivées sur', __FILE__) . ' ' . $this->getLogicalId());
			return;
		}
		if ($this->getType() == 'core') {
			if (config::byKey('update::allowCore', 'core', 1) != 1) {
				return;
			}
			if (config::byKey('core::repo::provider') == 'default') {
				$this->setRemoteVersion(self::getLastAvailableVersion());
			} else {
				$class = self::repositoryClass(config::byKey('core::repo::provider'), 'versionCore');
				if ($class === null) {
					$version = $this->getLocalVersion();
				} else {
					$version = $class::versionCore();
					if ($version === null) {
						$version = $this->getLocalVersion();
					}
				}
				$this->setRemoteVersion($version);
			}
			if (version_compare($this->getRemoteVersion(), $this->getLocalVersion(), '>')) {
				$this->setStatus('update');
			} else {
				$this->setStatus('ok');
			}
			$this->save();
		} else {
			try {
				$class = self::repositoryClass($this->getSource(), 'checkUpdate');
				if ($class !== null) {
					$class::checkUpdate($this);
				}
			} catch (\Throwable $ex) {
			}
		}
	}

	public function preSave() {
		if ($this->getLogicalId() == '') {
			throw new Exception(__("L'identifiant du plugin ne peut pas être vide", __FILE__));
		}
		if ($this->getName() == '') {
			$this->setName($this->getLogicalId());
		}
		$repo = self::listRepo();
		if ($this->getSource() != 'default' && !isset($repo[$this->getSource()])) {
			throw new Exception(__('Source de mise à jour invalide : ', __FILE__) . $this->getSource());
		}

		$allSourceKeys = [
			'market' => ['market', 'version'],
			'github' => ['user', 'repository', 'token', 'version'],
			'url'    => ['url'],
			'samba'  => ['path'],
			'file'   => ['path'],
		];
		$currentSourceKeys = $allSourceKeys[$this->getSource()] ?? [];
		$sourceKeys = array_unique(array_merge(...array_values($allSourceKeys)));
		foreach ($sourceKeys as $key) {
			if (!in_array($key, $currentSourceKeys, true)) {
				$this->setConfiguration($key, null);
			}
		}
	}

	public function save() {
		return DB::save($this);
	}

	public function postSave() {
		if ($this->_changeUpdate) {
			event::add('update::refreshUpdateNumber');
		}
	}

	public function remove() {
		return DB::remove($this);
	}

	public function postRemove() {
		event::add('update::refreshUpdateNumber');
	}

	public function refresh() {
		DB::refresh($this);
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getStatus() {
		return $this->status;
	}

	public function getConfiguration($_key = '', $_default = '') {
		return utils::getJsonAttr($this->configuration, $_key, $_default);
	}

	public function setId($_id) {
		$this->_changed = utils::attrChanged($this->_changed, $this->id, $_id);
		$this->id = $_id;
		return $this;
	}

	public function setName($_name) {
		$this->_changed = utils::attrChanged($this->_changed, $this->name, $_name);
		$this->name = $_name;
		return $this;
	}

	public function setStatus($_status) {
		if ($_status != $this->status) {
			$this->_changeUpdate = true;
			$this->_changed = true;
		}
		$this->status = $_status;
		return $this;
	}

	public function setConfiguration($_key, $_value) {
		$configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed, $this->configuration, $configuration);
		$this->configuration = $configuration;
		return $this;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($_type) {
		$this->_changed = utils::attrChanged($this->_changed, $this->type, $_type);
		$this->type = $_type;
		return $this;
	}

	public function getLocalVersion() {
		return $this->localVersion;
	}

	public function getRemoteVersion() {
		return $this->remoteVersion;
	}

	public function setLocalVersion($_localVersion) {
		$this->_changed = utils::attrChanged($this->_changed, $this->localVersion, $_localVersion);
		$this->localVersion = $_localVersion;
		return $this;
	}

	public function setRemoteVersion($_remoteVersion) {
		$this->_changed = utils::attrChanged($this->_changed, $this->remoteVersion, $_remoteVersion);
		$this->remoteVersion = $_remoteVersion;
		return $this;
	}

	public function getLogicalId() {
		return $this->logicalId;
	}

	public function setLogicalId($_logicalId) {
		$this->_changed = utils::attrChanged($this->_changed, $this->logicalId, $_logicalId);
		$this->logicalId = $_logicalId;
		return $this;
	}

	public function getSource() {
		return $this->source;
	}

	public function setSource($_source) {

		$this->_changed = utils::attrChanged($this->_changed, $this->source, $_source);
		$this->source = $_source;
		return $this;
	}

	public function getUpdateDate() {
		return $this->updateDate;
	}

	public function setUpdateDate($_updateDate) {
		$this->_changed = utils::attrChanged($this->_changed, $this->updateDate, $_updateDate);
		$this->updateDate = $_updateDate;
		return $this;
	}

	public function getChanged() {
		return $this->_changed;
	}

	public function setChanged($_changed) {
		$this->_changed = $_changed;
		return $this;
	}
}
