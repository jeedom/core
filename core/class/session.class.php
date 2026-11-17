<?php

final class session {
	public const ACCESS_LIFETIME = 86400;
	public const REFRESH_LIFETIME = 90 * 86400;
	private const RETRY_WINDOW = 60;
	private const REFRESH_MATCH = '(refresh_hash = :hash OR (previous_refresh_hash = :previous
		AND previous_refresh_expires_at > UTC_TIMESTAMP()))';

	public static function create(user $actor, bool $remember, string $ip, ?string $previous, ?int $expiresBefore = null,
		string $mode = 'web', string $name = 'Core UI', string $scope = '', bool $refreshOnly = false, string $userAgent = '',
		bool $recordLastConnection = false, ?string $replacedSessionId = null): array {
		$connection = self::withLockedAccount($actor, static function (user $user) use ($remember, $ip, $previous, $expiresBefore, $mode, $name, $scope, $refreshOnly, $userAgent, $recordLastConnection, $replacedSessionId): array {
			$id = self::secret();
			$csrf = self::secret();
			$pair = self::pair(self::secret(), self::secret(), $expiresBefore);
			$now = time();
			if ($pair['expires'] <= $now) {
				throw new DomainException('La session a expiré.');
			}
			if ($mode === 'web' && self::validId($previous)) {
				$old = DB::Prepare("SELECT id FROM `session` WHERE user_id = :user AND mode = 'web' AND refresh_hash = :hash FOR UPDATE",
					['user' => $user->getId(), 'hash' => self::digest($previous)]);
				if (is_array($old)) {
					self::revoke($user, $old['id']);
				}
			}
			if ($replacedSessionId !== null) {
				self::revoke($user, $replacedSessionId);
			}
			DB::Prepare('INSERT INTO `session` (id, user_id, token_hash, refresh_hash, csrf_token, credential_hash, ip, user_agent, remembered,
				mode, client_name, scope, created_at, last_activity_at, access_expires_at, expires_at, hard_expires_at)
				VALUES (:id, :user, :hash, :refresh, :csrf, :credentials, :ip, :userAgent, :remembered, :mode, :name, :scope,
				UTC_TIMESTAMP(), UTC_TIMESTAMP(), :accessExpires, :expires, :hardExpires)', [
				'id' => $id, 'user' => $user->getId(), 'hash' => self::digest($pair['token']),
				'refresh' => self::digest($pair['refreshToken']), 'csrf' => $csrf, 'credentials' => self::credentials($user),
				'remembered' => (int) $remember,
				'mode' => $mode, 'name' => $name, 'scope' => $scope, 'accessExpires' => $refreshOnly ? null : self::date($pair['expires']),
				'expires' => self::date($pair['refreshExpires']), 'hardExpires' => $expiresBefore === null ? null : self::date($expiresBefore),
			] + self::clientMetadata($refreshOnly ? '' : $ip, $refreshOnly ? '' : $userAgent));
			if ($recordLastConnection) {
				$user->setOptions('lastConnection', date('Y-m-d H:i:s', $now))->save();
			}
			return ['id' => $id, 'user' => $user, 'csrfToken' => $csrf, 'remembered' => $remember] + $pair;
		});
		log::audit('REST session created', ['sessionId' => $connection['id'], 'userId' => $actor->getId(), 'mode' => $mode]);
		return $connection;
	}

	public static function resolve(?string $token, string $transport = 'web', bool $refresh = false): ?array {
		if (!self::validId($token)) {
			return null;
		}
		$field = $refresh ? 'refresh_hash' : 'token_hash';
		$expiry = $refresh ? '' : ' AND access_expires_at > UTC_TIMESTAMP()';
		$row = DB::Prepare("SELECT * FROM `session` WHERE $field = :hash AND expires_at > UTC_TIMESTAMP()$expiry",
			['hash' => self::digest($token)]);
		if (!is_array($row) || !self::matchesTransport($row, $transport)) {
			return null;
		}
		$user = user::byId($row['user_id']);
		return self::validAccount($user, $row) ? ['user' => $user, 'session' => $row] : null;
	}

	public static function retryContext(?string $token, string $attempt): ?array {
		if (!self::validId($token) || $attempt === '') {
			return null;
		}
		$row = DB::Prepare("SELECT * FROM `session` WHERE previous_refresh_hash = :hash AND previous_refresh_expires_at > UTC_TIMESTAMP()
			AND expires_at > UTC_TIMESTAMP() AND mode = 'web'", ['hash' => self::digest($token)]);
		$pair = is_array($row) ? self::retry($row, $token, $attempt) : null;
		return $pair === null ? null : self::resolve($pair['refreshToken'], 'web', true);
	}

	/**
	 * Lock the account before the session, in the same order as credential changes.
	 * Retry secrets are derived from the presented secret and a nonce, so a database
	 * copy contains no recoverable access or refresh token.
	 */
	public static function refresh(string $token, string $transport, string $attempt, callable $authorize, string $ip = '', string $userAgent = ''): ?array {
		if (!self::validId($token)) {
			return null;
		}
		$hash = self::digest($token);
		$tokens = ['hash' => $hash, 'previous' => $hash];
		$current = DB::Prepare('SELECT id, user_id FROM `session` WHERE ' . self::REFRESH_MATCH, $tokens);
		if (!is_array($current)) {
			return null;
		}

		DB::beginTransaction();
		try {
			$user = user::byId($current['user_id'], true);
			$row = DB::Prepare('SELECT * FROM `session` WHERE id = :id AND expires_at > UTC_TIMESTAMP() AND '
				. self::REFRESH_MATCH . ' FOR UPDATE', ['id' => $current['id']] + $tokens);
			$result = null;
			$replay = false;
			if (is_array($row) && self::matchesTransport($row, $transport) && self::validAccount($user, $row)) {
				$authorize($user, $row);
				if (hash_equals($row['refresh_hash'], $hash)) {
					$result = self::rotate($row, $token, $attempt, $ip, $userAgent);
				} else {
					$result = self::retry($row, $token, $attempt);
					if ($result === null) {
						DB::Prepare('DELETE FROM `session` WHERE id = :id', ['id' => $row['id']]);
						$replay = true;
					}
				}
			}
			DB::commit();
		} catch (Throwable $error) {
			DB::rollBack();
			throw $error;
		}
		if ($replay) {
			log::audit('REST refresh replay detected; session revoked', ['sessionId' => $current['id'], 'userId' => $current['user_id']]);
		}
		return $result === null ? null : $result + ['user' => $user, 'csrfToken' => $row['csrf_token'], 'remembered' => (bool) $row['remembered']];
	}

	private static function rotate(array $row, string $token, string $attempt, string $ip, string $userAgent): array {
		$nonce = self::secret();
		[$access, $refresh] = self::successors($token, $nonce);
		$pair = self::pair($access, $refresh, self::timestamp($row['hard_expires_at']));
		$client = $row['access_expires_at'] === null
			? self::clientMetadata($ip, $userAgent)
			: ['ip' => $row['ip'], 'userAgent' => $row['user_agent']];
		DB::Prepare('UPDATE `session` SET token_hash = :access, refresh_hash = :refresh, access_expires_at = :accessExpires,
			previous_refresh_hash = :previous, previous_refresh_expires_at = :previousExpires,
			refresh_retry_attempt_hash = :attempt, refresh_retry_nonce = :nonce, refresh_retry_until = :retry,
			expires_at = :expires, last_activity_at = UTC_TIMESTAMP(), ip = :ip, user_agent = :userAgent WHERE id = :id', [
			'access' => self::digest($access), 'refresh' => self::digest($refresh), 'accessExpires' => self::date($pair['expires']),
			'previous' => $row['refresh_hash'], 'previousExpires' => $row['expires_at'],
			'attempt' => self::digest($attempt), 'nonce' => $nonce, 'retry' => self::date(time() + self::RETRY_WINDOW),
			'expires' => self::date($pair['refreshExpires']), 'id' => $row['id'],
		] + $client);
		return $pair;
	}

	private static function clientMetadata(string $ip, string $userAgent): array {
		$agent = preg_replace('/[\x00-\x1F\x7F]/', '', mb_scrub($userAgent, 'UTF-8'));
		$agent = trim(mb_strcut($agent, 0, 512, 'UTF-8'));
		return ['ip' => filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '', 'userAgent' => $agent === '' ? null : $agent];
	}

	private static function retry(array $row, string $token, string $attempt): ?array {
		if (self::timestamp($row['refresh_retry_until']) <= time() || !hash_equals($row['refresh_retry_attempt_hash'], self::digest($attempt))) {
			return null;
		}
		[$access, $refresh] = self::successors($token, $row['refresh_retry_nonce']);
		if (!hash_equals($row['refresh_hash'], self::digest($refresh)) || !hash_equals($row['token_hash'], self::digest($access))) {
			return null;
		}
		return ['token' => $access, 'refreshToken' => $refresh, 'expires' => self::timestamp($row['access_expires_at']),
			'refreshExpires' => self::timestamp($row['expires_at'])];
	}

	private static function successors(string $token, string $nonce): array {
		return [hash_hmac('sha256', 'access:' . $nonce, $token), hash_hmac('sha256', 'refresh:' . $nonce, $token)];
	}

	private static function pair(string $access, string $refresh, ?int $cap): array {
		$now = time();
		return ['token' => $access, 'refreshToken' => $refresh, 'expires' => min($now + self::ACCESS_LIFETIME, $cap ?? PHP_INT_MAX),
			'refreshExpires' => min($now + self::REFRESH_LIFETIME, $cap ?? PHP_INT_MAX)];
	}

	private static function matchesTransport(array $row, string $transport): bool {
		return $row['mode'] === $transport || ($transport === 'api' && $row['mode'] === 'daemon');
	}

	private static function validAccount($user, array $row): bool {
		return $user instanceof user && $user->isValidAndEnabled() && hash_equals($row['credential_hash'], self::credentials($user));
	}

	public static function touch(array $row): void {
		if (self::timestamp($row['last_activity_at']) <= time() - 60) {
			DB::Prepare('UPDATE `session` SET last_activity_at = UTC_TIMESTAMP()
				WHERE id = :id AND last_activity_at <= UTC_TIMESTAMP() - INTERVAL 1 MINUTE', ['id' => $row['id']]);
		}
	}

	public static function connections(user $user, bool $remembered = false): array {
		return DB::Prepare("SELECT id, ip, last_activity_at, expires_at, client_name, mode, scope FROM `session`
			WHERE user_id = :user AND credential_hash = :credentials AND expires_at > UTC_TIMESTAMP() AND mode <> 'legacy'"
			. ($remembered ? " AND remembered = 1 AND mode = 'web'" : '') . ' ORDER BY last_activity_at DESC, id', [
				'user' => $user->getId(), 'credentials' => self::credentials($user),
			], DB::FETCH_TYPE_ALL);
	}

	public static function revoke(user $user, string $id, bool $remembered = false): bool {
		if (!self::validId($id)) {
			return false;
		}
		$statement = DB::getConnection()->prepare('DELETE FROM `session` WHERE id = :id AND user_id = :user
			AND expires_at > UTC_TIMESTAMP()' . ($remembered ? " AND remembered = 1 AND mode = 'web'" : ''));
		if (!$statement || !$statement->execute(['id' => $id, 'user' => $user->getId()])) {
			throw new RuntimeException('Impossible de révoquer la session.');
		}
		if ($statement->rowCount() !== 1) {
			return false;
		}
		log::audit('REST session revoked', ['sessionId' => $id, 'userId' => $user->getId()]);
		return true;
	}

	/** @param array<int, user> $accounts */
	public static function activeConnections(array $accounts): array {
		$rows = DB::Prepare("SELECT id, user_id, credential_hash, ip, user_agent, last_activity_at, expires_at, client_name, mode, scope
			FROM `session` WHERE expires_at > UTC_TIMESTAMP() AND mode IN ('web', 'api', 'daemon')", [], DB::FETCH_TYPE_ALL);
		$active = [];
		foreach ($rows as $row) {
			if (self::validAccount($accounts[$row['user_id']] ?? null, $row)) {
				unset($row['credential_hash']);
				$active[] = $row;
			}
		}
		return $active;
	}

	public static function revokeAll(int $userId, bool $remembered = false): void {
		$where = ' WHERE user_id = :user' . ($remembered ? " AND remembered = 1 AND mode = 'web'" : '');
		$params = ['user' => $userId];
		$rows = DB::Prepare('SELECT id FROM `session`' . $where, $params, DB::FETCH_TYPE_ALL);
		DB::Prepare('DELETE FROM `session`' . $where, $params);
		foreach ($rows as $row) {
			log::audit('REST session revoked', ['sessionId' => $row['id'], 'userId' => $userId]);
		}
	}

	public static function changeCredentials(user $actor, ?string $currentId, callable $change): user {
		return self::withLockedAccount($actor, static function (user $user) use ($currentId, $change): user {
			$params = ['user' => $user->getId(), 'id' => $currentId];
			if ($currentId !== null) {
				$current = DB::Prepare('SELECT credential_hash FROM `session` WHERE id = :id AND user_id = :user
					AND access_expires_at > UTC_TIMESTAMP() FOR UPDATE', $params);
				if (!is_array($current) || !hash_equals($current['credential_hash'], self::credentials($user))) {
					throw new DomainException('Session expirée, reconnectez-vous.');
				}
			}
			$change($user);
			if ($currentId === null) {
				self::revokeAll((int) $user->getId());
			} else {
				DB::Prepare('UPDATE `session` SET credential_hash = :credentials WHERE id = :id AND user_id = :user',
					['credentials' => self::credentials($user)] + $params);
				$rows = DB::Prepare('SELECT id FROM `session` WHERE user_id = :user AND id <> :id', $params, DB::FETCH_TYPE_ALL);
				DB::Prepare('DELETE FROM `session` WHERE user_id = :user AND id <> :id', $params);
				foreach ($rows as $row) {
					log::audit('REST session revoked', ['sessionId' => $row['id'], 'userId' => $user->getId()]);
				}
			}
			return $user;
		});
	}

	public static function clean(): void {
		DB::Prepare('DELETE FROM `session` WHERE expires_at <= UTC_TIMESTAMP()', []);
	}

	private static function withLockedAccount(user $actor, callable $operation) {
		DB::beginTransaction();
		try {
			$user = user::byId($actor->getId(), true);
			if (!$user instanceof user || !$user->isValidAndEnabled() || !hash_equals(self::credentials($actor), self::credentials($user))) {
				throw new DomainException('Les identifiants ont changé. Reconnectez-vous.');
			}
			$result = $operation($user);
			DB::commit();
			return $result;
		} catch (Throwable $error) {
			if (DB::getConnection()->inTransaction()) {
				DB::rollBack();
			}
			throw $error;
		}
	}

	private static function credentials(user $user): string {
		return hash('sha256', implode("\0", [
			$user->getPassword(), (int) $user->getOptions('twoFactorAuthentification', 0),
			(string) $user->getOptions('twoFactorAuthentificationSecret'),
		]));
	}

	private static function validId(?string $value): bool {
		return $value !== null && preg_match('/\A[a-f0-9]{64}\z/', $value) === 1;
	}

	private static function secret(): string {
		return bin2hex(random_bytes(32));
	}

	private static function digest(string $token): string {
		return hash('sha256', $token);
	}

	private static function date(int $timestamp): string {
		return gmdate('Y-m-d H:i:s', $timestamp);
	}

	private static function timestamp(?string $date): ?int {
		return $date === null ? null : strtotime($date . ' UTC');
	}
}
