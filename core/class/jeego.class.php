<?php

class jeego {
	public static function isDetected(): bool {
		return (string) getenv('JEEGO_API_URL') !== '' || (string) getenv('JEEGO_API_KEY') !== '';
	}

	public static function checkConfiguration(): void {
		self::credentials();
	}

	private static function credentials(): array {
		$url = rtrim((string) getenv('JEEGO_API_URL'), '/');
		$key = (string) getenv('JEEGO_API_KEY');
		$parts = parse_url($url) ?: array();
		$localApi = ($parts['scheme'] ?? '') === 'http'
			&& in_array($parts['host'] ?? '', array('127.0.0.1', '[::1]'), true)
			&& ($parts['path'] ?? '') === '/api/v1';

		if (!$localApi || $key === '') {
			throw new Exception(__('Configuration de l’API locale Jeego absente ou invalide', __FILE__));
		}
		return array('url' => $url, 'key' => $key);
	}

	public static function request(string $path, ?array $body = null, int $timeout = 8): array {
		if ($timeout < 1 || $path === '' || $path[0] !== '/') {
			throw new InvalidArgumentException(__('Requête API Jeego invalide', __FILE__));
		}
		$credentials = self::credentials();
		$http = new com_http($credentials['url'] . $path);
		$http->setHeader(array('Authorization: Bearer ' . $credentials['key'], 'Content-Type: application/json'));
		$http->setCURLOPT(array(
			CURLOPT_CONNECTTIMEOUT => min(2, $timeout),
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_PROXY => '',
		));
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
		$path = '/services/' . self::identifier($service) . '/actions/' . self::identifier($action);
		return self::request($path, $request);
	}

	public static function getTask(string $id, int $timeout = 8): array {
		if (!preg_match('/^[a-f0-9]{32}$/D', $id)) {
			throw new InvalidArgumentException(__('Identifiant de tâche Jeego invalide', __FILE__));
		}
		return self::request('/tasks/' . $id, null, $timeout);
	}

	public static function waitTask(string $id, int $timeout = 20): array {
		if ($timeout < 1) {
			throw new InvalidArgumentException(__('Délai d’attente Jeego invalide', __FILE__));
		}
		$deadline = microtime(true) + $timeout;
		while (($remaining = $deadline - microtime(true)) > 0) {
			$task = self::getTask($id, min(8, (int) ceil($remaining)));
			if ($task['status'] !== 'running') {
				return $task;
			}
			$remaining = max(0, $deadline - microtime(true));
			usleep((int) min(250000, $remaining * 1000000));
		}
		throw new Exception(__('Une opération est encore en cours dans Jeego', __FILE__));
	}
}
