<?php

final class supportAccess {
	public const LOGIN = 'jeedom_support_session';
	private const KEY = 'support::authorization';

	public static function current(): array {
		$state = self::stored();
		return empty($state['pending']) ? $state : array();
	}

	public static function withTicket(callable $submit): string {
		$grant = self::locked(static function (): array {
			$state = self::stored();
			if (!$state || (!empty($state['pending']) && $state['expiresAt'] <= time() + 7 * 86400 - 300)) {
				$state = array('grant' => bin2hex(random_bytes(32)), 'expiresAt' => time() + 7 * 86400, 'pending' => true);
				config::save(self::KEY, $state);
			}
			unset($state['pending']);
			return $state;
		});
		$result = $submit($grant);
		$active = self::locked(static function () use ($grant): bool {
			$state = self::stored();
			if (($state['grant'] ?? null) !== $grant['grant']) {
				return false;
			}
			if (!empty($state['pending'])) {
				config::save(self::KEY, $grant);
			}
			return true;
		});
		if (!$active) {
			self::notifyClosed($grant['grant']);
		}
		return $result;
	}

	public static function close(): void {
		$grantId = self::locked(static function (): ?string {
			$grant = config::byKey(self::KEY, 'core', array(), true);
			DB::beginTransaction();
			try {
				config::remove(self::KEY);
				$account = user::byLogin(self::LOGIN);
				if ($account instanceof user) {
					session::revokeAll((int) $account->getId());
				}
				DB::commit();
			} catch (Throwable $error) {
				DB::rollBack();
				throw $error;
			}
			return is_array($grant) && self::validCode($grant['grant'] ?? null) ? $grant['grant'] : null;
		});
		if ($grantId !== null) {
			self::notifyClosed($grantId);
		}
	}

	private static function notifyClosed(string $grant): void {
		try {
			repo_market::closeTemporarySupport($grant);
		} catch (Exception $exception) {
			// Local access is already revoked, including when the Market is unavailable.
			log::audit('Support access closed locally', array('marketNotified' => false));
		}
	}

	private static function stored(): array {
		$grant = config::byKey(self::KEY, 'core', array(), true);
		return is_array($grant) && self::validCode($grant['grant'] ?? null)
			&& is_int($grant['expiresAt'] ?? null) && $grant['expiresAt'] > time() ? $grant : array();
	}

	public static function connect(string $grantId, string $code, ?string $previous, string $ip): array {
		if (!self::validCode($code) || !self::validCode($grantId)
			|| !hash_equals(self::current()['grant'] ?? '', $grantId)) {
			throw new DomainException('Accès support indisponible.');
		}
		$result = repo_market::redeemTemporarySupport($grantId, $code);
		if (!is_int($result['operatorId'] ?? null) || $result['operatorId'] <= 0) {
			throw new DomainException('Accès support indisponible.');
		}
		return self::locked(static function () use ($grantId, $result, $previous, $ip): array {
			$grant = self::current();
			if (!hash_equals($grant['grant'] ?? '', $grantId)) {
				throw new DomainException('Accès support indisponible.');
			}
			$account = user::byLogin(self::LOGIN);
			if (!$account instanceof user) {
				$account = new user();
				$account->setLogin(self::LOGIN);
				$account->setPassword(bin2hex(random_bytes(32)));
				$account->setProfils('admin');
				$account->setEnable(1);
				$account->save();
			}
			$connection = session::create($account, false, $ip, $previous, min(time() + 3600, $grant['expiresAt']));
			log::audit('Support session opened', array('marketOperatorId' => $result['operatorId'], 'ip' => $ip));
			return $connection;
		});
	}

	private static function validCode(mixed $value): bool {
		return is_string($value) && preg_match('/\A[a-f0-9]{64}\z/', $value) === 1;
	}

	private static function locked(callable $operation): mixed {
		$lock = fopen(jeedom::getTmpFolder('market') . '/support-access.lock', 'c');
		if ($lock === false) {
			throw new RuntimeException('Impossible de verrouiller l’accès support.');
		}
		try {
			if (!flock($lock, LOCK_EX)) {
				throw new RuntimeException('Impossible de verrouiller l’accès support.');
			}
			return $operation();
		} finally {
			flock($lock, LOCK_UN);
			fclose($lock);
		}
	}
}
