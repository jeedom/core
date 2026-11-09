<?php

final class session {
	public const REMEMBERED_LIFETIME = 90 * 86400;

	public static function create(user $actor, bool $remember, string $ip, ?string $previous): array {
		return self::withLockedAccount($actor, static function (user $user) use ($remember, $ip, $previous): array {
			$hours = (int) config::byKey('session_lifetime');
			$lifetime = $remember ? self::REMEMBERED_LIFETIME : ($hours > 0 ? $hours : 24) * 3600;
			$expires = time() + $lifetime;
			$token = bin2hex(random_bytes(32));
			$csrf = bin2hex(random_bytes(32));
			if (self::validId($previous)) {
				DB::Prepare('DELETE FROM `session` WHERE token_hash = :hash', ['hash' => hash('sha256', $previous)]);
			}
			DB::Prepare('INSERT INTO `session` (id, user_id, token_hash, csrf_token, credential_hash, ip, remembered, created_at, last_activity_at, expires_at)
				VALUES (:id, :user, :hash, :csrf, :credentials, :ip, :remembered, UTC_TIMESTAMP(), UTC_TIMESTAMP(), :expires)', [
				'id' => bin2hex(random_bytes(32)), 'user' => $user->getId(), 'hash' => hash('sha256', $token),
				'csrf' => $csrf, 'credentials' => self::credentials($user), 'ip' => filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '',
				'remembered' => (int) $remember, 'expires' => gmdate('Y-m-d H:i:s', $expires),
			]);
			return ['user' => $user, 'token' => $token, 'csrfToken' => $csrf, 'expires' => $expires];
		});
	}

	public static function resolve(?string $token): ?array {
		if (!self::validId($token)) {
			return null;
		}
		$hash = hash('sha256', $token);
		$row = self::activeByHash($hash);
		if (!is_array($row)) {
			return null;
		}
		$user = user::byId($row['user_id']);
		if (!$user instanceof user || !$user->isValidAndEnabled()) {
			return null;
		}
		$credentials = self::credentials($user);
		if (!hash_equals($row['credential_hash'], $credentials)) {
			// Le navigateur courant a pu changer son mot de passe entre les deux lectures.
			$row = self::activeByHash($hash);
			if (!is_array($row) || (int) $row['user_id'] !== (int) $user->getId() || !hash_equals($row['credential_hash'], $credentials)) {
				return null;
			}
		}
		return ['user' => $user, 'session' => $row];
	}

	private static function activeByHash(string $hash): ?array {
		$row = DB::Prepare('SELECT * FROM `session` WHERE token_hash = :hash AND expires_at > UTC_TIMESTAMP()', ['hash' => $hash]);
		return is_array($row) ? $row : null;
	}

	public static function touch(array $row): void {
		if (strtotime($row['last_activity_at'] . ' UTC') <= time() - 60) {
			DB::Prepare('UPDATE `session` SET last_activity_at = UTC_TIMESTAMP()
				WHERE id = :id AND last_activity_at <= UTC_TIMESTAMP() - INTERVAL 1 MINUTE', ['id' => $row['id']]);
		}
	}

	public static function connections(user $user, bool $remembered = false): array {
		return DB::Prepare('SELECT id, ip, last_activity_at, expires_at FROM `session`
			WHERE user_id = :user AND credential_hash = :credentials AND expires_at > UTC_TIMESTAMP()'
			. ($remembered ? ' AND remembered = 1' : '') . ' ORDER BY last_activity_at DESC, id', [
				'user' => $user->getId(), 'credentials' => self::credentials($user),
			], DB::FETCH_TYPE_ALL);
	}

	public static function revoke(user $user, string $id, bool $remembered = false): bool {
		if (!self::validId($id)) {
			return false;
		}
		$statement = DB::getConnection()->prepare('DELETE FROM `session` WHERE id = :id AND user_id = :user
			AND expires_at > UTC_TIMESTAMP()' . ($remembered ? ' AND remembered = 1' : ''));
		if (!$statement || !$statement->execute(['id' => $id, 'user' => $user->getId()])) {
			throw new RuntimeException('Impossible de révoquer la session.');
		}
		return $statement->rowCount() === 1;
	}

	public static function revokeAll(int $userId, bool $remembered = false): void {
		DB::Prepare('DELETE FROM `session` WHERE user_id = :user' . ($remembered ? ' AND remembered = 1' : ''), ['user' => $userId]);
	}

	public static function changeCredentials(user $actor, ?string $currentId, callable $change): user {
		return self::withLockedAccount($actor, static function (user $user) use ($currentId, $change): user {
			$params = ['user' => $user->getId(), 'id' => $currentId];
			if ($currentId !== null) {
				$current = DB::Prepare('SELECT credential_hash FROM `session` WHERE id = :id AND user_id = :user
					AND expires_at > UTC_TIMESTAMP() FOR UPDATE', $params);
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
				DB::Prepare('DELETE FROM `session` WHERE user_id = :user AND id <> :id', $params);
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
}
