<?php

class jeego {
	public static function isDetected(): bool {
		return (getenv('JEEGO_API_URL') !== false && getenv('JEEGO_API_URL') !== '') || (getenv('JEEGO_API_KEY') !== false && getenv('JEEGO_API_KEY') !== '');
	}

	public static function checkConfiguration(): void {
		self::credentials();
	}

	private static function credentials(): array {
		$url = rtrim((string) getenv('JEEGO_API_URL'), '/');
		$key = (string) getenv('JEEGO_API_KEY');
		$parts = parse_url($url);
		if (!is_array($parts) || ($parts['scheme'] ?? '') != 'http' || !in_array($parts['host'] ?? '', array('127.0.0.1', '[::1]', '::1'), true) || ($parts['path'] ?? '') != '/api/v1' || isset($parts['user']) || isset($parts['pass']) || isset($parts['query']) || isset($parts['fragment']) || !preg_match('/^[a-f0-9]{64}$/D', $key)) {
			throw new Exception(__('Configuration de l’API locale Jeego absente ou invalide', __FILE__));
		}
		return array('url' => $url, 'key' => $key);
	}

	public static function request(string $path, ?array $body = null, int $timeout = 8): array {
		$parts = parse_url($path);
		$route = is_array($parts) ? rawurldecode($parts['path'] ?? '') : '';
		if ($timeout < 1 || !is_array($parts) || $path === '' || $path[0] !== '/' || substr($path, 0, 2) === '//' || isset($parts['scheme']) || isset($parts['host']) || isset($parts['fragment']) || preg_match('/[\x00-\x20\x7f]/', $path) || strpos($route, "\\") !== false || preg_match('~(?:^|/)\.{1,2}(?:/|$)~', $route)) {
			throw new InvalidArgumentException(__('Requête API Jeego invalide', __FILE__));
		}
		$credentials = self::credentials();
		$http = new com_http($credentials['url'] . $path);
		$http->setHeader(array('Authorization: Bearer ' . $credentials['key'], 'Content-Type: application/json'));
		$http->setCURLOPT(array(CURLOPT_CONNECTTIMEOUT => min(2, $timeout), CURLOPT_FOLLOWLOCATION => false, CURLOPT_PROXY => ''));
		$http->setNoSslCheck(false);
		$http->setNoReportError(true);
		if ($body !== null) {
			$http->setPost(json_encode((object) $body, JSON_THROW_ON_ERROR));
		}
		$response = $http->exec($timeout, 1);
		$code = (int) $http->getHttpCode();
		$result = is_string($response) ? json_decode($response, true) : null;
		if ($code < 200 || $code >= 300 || !is_array($result)) {
			throw new Exception(__('API locale Jeego indisponible ou requête refusée', __FILE__) . ' (HTTP ' . $code . ')', $code);
		}
		return $result;
	}

	private static function identifier(string $value): string {
		if (!preg_match('/^[a-z][a-z0-9-]{0,31}$/D', $value)) {
			throw new InvalidArgumentException(__('Identifiant de service ou d’action Jeego invalide', __FILE__));
		}
		return $value;
	}

	public static function getService(string $service): array {
		return self::request('/services/' . self::identifier($service));
	}

	public static function serviceAction(string $service, string $action, array $request = array()): array {
		$task = self::request('/services/' . self::identifier($service) . '/actions/' . self::identifier($action), $request);
		return self::taskResponse($task);
	}

	private static function taskResponse(array $task, ?string $expectedId = null): array {
		if (!isset($task['id']) || !is_string($task['id']) || !preg_match('/^[a-f0-9]{32}$/D', $task['id']) || ($expectedId !== null && $task['id'] !== $expectedId) || !in_array($task['status'] ?? '', array('running', 'succeeded', 'failed', 'interrupted'), true)) {
			throw new Exception(__('Réponse de tâche Jeego invalide', __FILE__));
		}
		return $task;
	}

	public static function getTask(string $id, int $timeout = 8): array {
		if (!preg_match('/^[a-f0-9]{32}$/D', $id)) {
			throw new InvalidArgumentException(__('Identifiant de tâche Jeego invalide', __FILE__));
		}
		return self::taskResponse(self::request('/tasks/' . $id, null, $timeout), $id);
	}

	public static function waitTask(string $id, int $timeout = 20): array {
		if ($timeout < 1) {
			throw new InvalidArgumentException(__('Délai d’attente Jeego invalide', __FILE__));
		}
		$deadline = microtime(true) + $timeout;
		do {
			$remaining = max(1, (int) ceil($deadline - microtime(true)));
			$task = self::getTask($id, min(8, $remaining));
			if ($task['status'] !== 'running') {
				return $task;
			}
			$remaining = $deadline - microtime(true);
			if ($remaining <= 0) {
				break;
			}
			usleep((int) min(250000, $remaining * 1000000));
		} while (microtime(true) < $deadline);
		throw new Exception(__('Une opération est encore en cours dans Jeego', __FILE__));
	}
}
