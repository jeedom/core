<?php

/**
 * The credential file is the hand-off to an opted-in, locally installed daemon.
 * The daemon runs as the Jeedom service user and atomically persists rotations.
 */
final class daemonSession {
	private const PREFIX = '__rest_daemon_';

	public static function reservedLogin(string $login): bool {
		return str_starts_with(strtolower($login), self::PREFIX);
	}

	public static function allowed(user $account, string $plugin): bool {
		return self::validPlugin($plugin)
			&& $account->getLogin() === self::login($plugin)
			&& (int) config::byKey('active', $plugin, 0, true) === 1
			&& in_array(getClientIp(), ['127.0.0.1', '::1'], true);
	}

	public static function credentialFile(string $plugin, bool $reprovision = false): string {
		if (!self::validPlugin($plugin) || (int) config::byKey('active', $plugin, 0, true) !== 1) {
			throw new DomainException('Ce plugin est inactif.');
		}
		return self::locked($plugin, static function (string $path) use ($plugin, $reprovision): string {
			$account = user::byLogin(self::login($plugin));
			if ($account instanceof user && !$reprovision) {
				if (is_file($path) && !$account->getOptions('restDaemonProvisioning', 0) && session::connections($account) !== []) {
					return $path;
				}
				throw new DomainException('L’accès REST du démon doit être reprovisionné.');
			}
			if (!$account instanceof user) {
				$account = (new user())->setLogin(self::login($plugin))->setPassword(bin2hex(random_bytes(32)))
					->setProfils('restrict')->setEnable(1)->setOptions('api::mode', 'disable');
			}
			$account->setOptions('restDaemonProvisioning', 1)->save();
			session::revokeAll((int) $account->getId());
			$connection = session::create($account, false, '127.0.0.1', null, null, 'daemon', $plugin, $plugin, true);
			try {
				self::writeCredential($path, $connection['refreshToken']);
				$account->setOptions('restDaemonProvisioning', 0)->save();
			} catch (Throwable $error) {
				session::revoke($account, $connection['id']);
				throw $error;
			}
			return $path;
		});
	}

	public static function revoke(string $plugin): void {
		if (!self::validPlugin($plugin) || !user::byLogin(self::login($plugin)) instanceof user) {
			return;
		}
		self::locked($plugin, static function (string $path) use ($plugin): void {
			$account = user::byLogin(self::login($plugin));
			if ($account instanceof user) {
				session::revokeAll((int) $account->getId());
			}
			if (is_file($path)) {
				unlink($path);
			}
		});
	}

	private static function locked(string $plugin, callable $operation) {
		$directory = dirname(__DIR__, 2) . '/data/daemon-sessions';
		if (!is_dir($directory) && !@mkdir($directory, 0700) && !is_dir($directory)) {
			throw new RuntimeException('Impossible de créer le stockage des accès des démons.');
		}
		$path = $directory . '/' . $plugin . '.json';
		$lock = fopen($path . '.lock', 'c');
		if ($lock === false) {
			throw new RuntimeException('Impossible de verrouiller l’accès du démon.');
		}
		try {
			if (!flock($lock, LOCK_EX)) {
				throw new RuntimeException('Impossible de verrouiller l’accès du démon.');
			}
			return $operation($path);
		} finally {
			fclose($lock);
		}
	}

	private static function writeCredential(string $path, string $refreshToken): void {
		$temp = tempnam(dirname($path), '.credential-');
		if ($temp === false) {
			throw new RuntimeException('Impossible de préparer l’accès du démon.');
		}
		try {
			if (!chmod($temp, 0600) || file_put_contents($temp, json_encode(['refreshToken' => $refreshToken], JSON_THROW_ON_ERROR)) === false
				|| !rename($temp, $path)) {
				throw new RuntimeException('Impossible de remettre l’accès au démon.');
			}
		} finally {
			if (is_file($temp)) {
				unlink($temp);
			}
		}
	}

	private static function validPlugin(string $plugin): bool {
		return preg_match('/\A[a-zA-Z0-9_-]{1,80}\z/', $plugin) === 1;
	}

	private static function login(string $plugin): string {
		return self::PREFIX . substr(hash('sha256', $plugin), 0, 30);
	}
}
