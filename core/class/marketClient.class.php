<?php

/** REST transport and box session for the Market. Legacy plugin RPC remains in repo_market. */
class marketClient {

	public static function connected() {
		$session = config::byKey('market::restSession', 'core', array(), true);
		if (!is_array($session) || !is_string($session['context'] ?? null) || !is_string($session['refreshToken'] ?? null)
			|| $session['refreshToken'] === '') {
			return false;
		}
		try {
			return hash_equals($session['context'], self::context());
		} catch (Exception $exception) {
			return false;
		}
	}

	public static function status() {
		return array('connected' => self::connected(), 'login' => (string) config::byKey('market::username', 'core', '', true));
	}

	public static function disconnect() {
		return self::locked(function () {
			$stored = config::byKey('market::restSession', 'core', array(), true);
			$revoked = !is_array($stored) || empty($stored['refreshToken']);
			try {
				if (self::connected()) {
					$session = self::activeSession(null, 10);
					self::send('DELETE', '/boxes/' . $session['boxId'] . '/session', null, $session['accessToken'], 10);
					$revoked = true;
				}
			} catch (Exception $exception) {
				$revoked = $exception->getCode() === 401 || $exception->getCode() === 404;
			} finally {
				config::remove('market::restSession');
				config::remove('market::password');
				config::remove('market::allowBeta');
			}
			return self::status() + array('revoked' => $revoked);
		});
	}

	public static function connect($login, $password, $twoFactorCode = '') {
		if (!is_string($login) || trim($login) === '' || !is_string($password) || $password === '' || !is_string($twoFactorCode)) {
			throw new Exception(__('Renseignez votre identifiant et votre mot de passe Market.', __FILE__));
		}
		return self::locked(function () use ($login, $password, $twoFactorCode) {
			$data = array('login' => trim($login), 'password' => $password, 'hardwareKey' => jeedom::getHardwareKey());
			if ($twoFactorCode !== '') {
				$data['twoFactorCode'] = trim($twoFactorCode);
			}
			$tokens = self::send('POST', '/box-sessions', $data);
			// Keep the existing password hash for plugins and the separate cloud services.
			config::save('market::username', trim($login));
			config::save('market::password', sha1($password));
			self::store($tokens);
			return true;
		});
	}

	public static function request($method, $path, $data = null, $timeout = 30, $destination = null) {
		$session = self::session();
		for ($attempt = 0; $attempt < 2; ++$attempt) {
			try {
				$result = self::send($method, str_replace('{boxId}', (string) $session['boxId'], $path), $data,
					$session['accessToken'], $timeout, $destination);
				config::save('market::lastCommunication', date('Y-m-d H:i:s'));
				return $result;
			} catch (Exception $exception) {
				// Authentication middleware rejects the request before executing its action.
				if ($exception->getCode() !== 401 || $attempt !== 0) {
					throw $exception;
				}
				$session = self::session($session['accessToken']);
			}
		}
	}

	private static function session($rejectedToken = null) {
		return self::locked(function () use ($rejectedToken) { return self::activeSession($rejectedToken); });
	}

	private static function activeSession($rejectedToken = null, $timeout = 30) {
		// Another PHP worker may have renewed the pair while this one waited for the lock.
		$session = config::byKey('market::restSession', 'core', array(), true);
		if (!is_array($session) || !isset($session['context'], $session['refreshToken'], $session['accessToken'], $session['boxId'])
			|| !hash_equals($session['context'], self::context())) {
			throw self::reconnect();
		}
		if (($session['expiresAt'] ?? 0) > time() + 60
			&& ($rejectedToken === null || !hash_equals($session['accessToken'], $rejectedToken))) {
			return $session;
		}
		if (empty($session['refreshAttempt'])) {
			$session['refreshAttempt'] = bin2hex(random_bytes(16));
			config::save('market::restSession', $session);
		}
		try {
			$tokens = self::send('POST', '/box-sessions/refresh', array('refreshToken' => $session['refreshToken']),
				null, $timeout, null, array('Idempotency-Key: ' . $session['refreshAttempt']));
		} catch (Exception $exception) {
			if ($exception->getCode() === 401) {
				config::remove('market::restSession');
				throw self::reconnect();
			}
			// Keep the same refresh token and attempt key if the response was lost.
			throw $exception;
		}
		return self::store($tokens);
	}

	private static function store($tokens) {
		if (!is_array($tokens) || !isset($tokens['boxId'], $tokens['accessToken'], $tokens['refreshToken'], $tokens['expiresIn'])
			|| !is_int($tokens['boxId']) || $tokens['boxId'] < 1 || !is_int($tokens['expiresIn']) || $tokens['expiresIn'] < 0
			|| !is_string($tokens['accessToken']) || !is_string($tokens['refreshToken'])
			|| !preg_match('/\Abox_a_' . $tokens['boxId'] . '_[a-f0-9]{64}\z/', $tokens['accessToken'])
			|| !preg_match('/\Abox_r_' . $tokens['boxId'] . '_[a-f0-9]{64}\z/', $tokens['refreshToken'])) {
			throw new Exception(__('Réponse de connexion Market invalide.', __FILE__));
		}
		$session = array('boxId' => $tokens['boxId'], 'accessToken' => $tokens['accessToken'], 'refreshToken' => $tokens['refreshToken'],
			'expiresAt' => time() + $tokens['expiresIn'], 'context' => self::context());
		config::save('market::restSession', $session);
		config::save('register::id', $session['boxId']);
		return $session;
	}

	private static function locked($operation) {
		$lock = fopen(jeedom::getTmpFolder('market') . '/session.lock', 'c');
		if ($lock === false) {
			throw new Exception(__('Impossible de verrouiller la connexion Market.', __FILE__));
		}
		try {
			if (!flock($lock, LOCK_EX)) {
				throw new Exception(__('Impossible de verrouiller la connexion Market.', __FILE__));
			}
			return $operation();
		} finally {
			flock($lock, LOCK_UN);
			fclose($lock);
		}
	}

	private static function baseUrl() {
		$url = rtrim((string) config::byKey('market::address', 'core', '', true), '/');
		$parts = parse_url($url);
		if (!is_array($parts) || strtolower($parts['scheme'] ?? '') !== 'https' || empty($parts['host'])
			|| isset($parts['user']) || isset($parts['pass']) || isset($parts['query']) || isset($parts['fragment'])) {
			throw new Exception(__('L’adresse du Market doit être une URL HTTPS valide.', __FILE__));
		}
		return $url;
	}

	private static function context() {
		return hash('sha256', self::baseUrl() . "\0" . config::byKey('market::username', 'core', '', true) . "\0" . jeedom::getHardwareKey());
	}

	private static function reconnect() {
		return new Exception(__('Reconnectez votre compte Market depuis Configuration > Mises à jour/Market avec votre mot de passe et votre code de double authentification si activé.', __FILE__), 401);
	}

	private static function send($method, $path, $data = null, $token = null, $timeout = 30, $destination = null, $headers = array()) {
		$curl = curl_init(self::baseUrl() . '/api/v1' . $path);
		$headers[] = 'Accept: ' . ($destination === null ? 'application/json' : 'application/zip, application/json');
		if ($token !== null) {
			$headers[] = 'Authorization: Bearer ' . $token;
		}
		$options = array(CURLOPT_CUSTOMREQUEST => $method, CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_CONNECTTIMEOUT => 15, CURLOPT_TIMEOUT => $timeout, CURLOPT_SSL_VERIFYPEER => config::byKey('market::no_ssl_verify') != 1,
			CURLOPT_SSL_VERIFYHOST => config::byKey('market::no_ssl_verify') == 1 ? 0 : 2);
		if ($data !== null) {
			$headers[] = 'Content-Type: application/json';
			$options[CURLOPT_POSTFIELDS] = json_encode($data, JSON_THROW_ON_ERROR);
		}
		$options[CURLOPT_HTTPHEADER] = $headers;
		if (config::byKey('proxyEnabled') == 1) {
			$options[CURLOPT_PROXY] = config::byKey('proxyAddress');
			$options[CURLOPT_PROXYPORT] = config::byKey('proxyPort');
			$options[CURLOPT_PROXYUSERPWD] = config::byKey('proxyLogin') . ':' . config::byKey('proxyPassword');
		}
		$file = null;
		if ($destination !== null) {
			$file = fopen($destination, 'wb');
			if ($file === false) {
				curl_close($curl);
				throw new Exception(__('Impossible d’écrire le téléchargement Market.', __FILE__));
			}
			$options[CURLOPT_FILE] = $file;
		}
		try {
			curl_setopt_array($curl, $options);
			$body = curl_exec($curl);
			$status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$error = curl_error($curl);
			if (is_resource($file)) {
				fclose($file);
				$file = null;
			}
			if ($body === false) {
				throw new Exception(__('Le Market n’a pas répondu :', __FILE__) . ' ' . $error);
			}
			if ($destination !== null && $status >= 200 && $status < 300) {
				$zip = new ZipArchive();
				if ($zip->open($destination, ZipArchive::CHECKCONS) !== true) {
					throw new Exception(__('L’archive reçue du Market est invalide.', __FILE__));
				}
				$zip->close();
				return $destination;
			}
			if ($destination !== null) {
				$body = file_get_contents($destination, false, null, 0, 65536);
			}
			$result = json_decode((string) $body, true);
			if ($status < 200 || $status >= 300) {
				$message = is_string($result['error']['message'] ?? null) ? $result['error']['message'] : __('Erreur de communication avec le Market.', __FILE__);
				throw new Exception($message, $status);
			}
			if ($status === 204) {
				return null;
			}
			if (!is_array($result)) {
				throw new Exception(__('Réponse REST du Market invalide.', __FILE__));
			}
			return $result;
		} catch (Throwable $exception) {
			if ($destination !== null && is_file($destination)) {
				unlink($destination);
			}
			throw $exception;
		} finally {
			if (is_resource($file)) {
				fclose($file);
			}
			curl_close($curl);
		}
	}
}
