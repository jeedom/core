<?php

class jeego {
	private const HARDWARE_CONFIG = 'jeego::hardware';

	public static function isDetected(): bool {
		return (string) getenv('JEEGO_API_URL') !== '' || (string) getenv('JEEGO_API_KEY') !== '';
	}

	public static function getHardwareName(): string {
		$hardware = config::byKey(self::HARDWARE_CONFIG, 'core', array());
		$name = $hardware['name'] ?? 'diy';
		if (($hardware['refreshAt'] ?? 0) > time()) {
			return $name;
		}

		$lock = self::openHardwareLock();
		if ($lock === false) {
			return $name;
		}
		try {
			if (!flock($lock, LOCK_EX | LOCK_NB)) {
				return $name;
			}
			$hardware = config::byKey(self::HARDWARE_CONFIG, 'core', array(), true);
			$name = $hardware['name'] ?? 'diy';
			if (($hardware['refreshAt'] ?? 0) > time()) {
				return $name;
			}

			$detected = null;
			try {
				$info = self::request('/info', null, 2);
				$detected = self::normalizeHardware($info['host'] ?? array());
			} catch (Exception $e) {
				// Keep the last result while the local API is unavailable.
			}
			$name = $detected ?? $name;
			config::save(self::HARDWARE_CONFIG, array('name' => $name, 'refreshAt' => time() + ($detected === null ? 60 : 86400)));
			return $name;
		} finally {
			fclose($lock);
		}
	}

	public static function resetHardwareName(): void {
		$lock = self::openHardwareLock();
		if ($lock === false) {
			config::remove(self::HARDWARE_CONFIG);
			return;
		}
		try {
			flock($lock, LOCK_EX);
			config::remove(self::HARDWARE_CONFIG);
		} finally {
			fclose($lock);
		}
	}

	private static function openHardwareLock() {
		return @fopen(jeedom::getTmpFolder() . '/jeego-hardware.lock', 'c');
	}

	private static function normalizeHardware($host): ?string {
		if (!is_array($host)) {
			return null;
		}
		$type = $host['machine_type'] ?? null;
		if (!is_string($type)) {
			return null;
		}
		if ($type !== 'Raspberry Pi') {
			$types = array('Luna' => 'Luna', 'Atlas' => 'Atlas', 'Smart' => 'smart', 'Odroid' => 'odroid', 'DIY' => 'diy');
			return $types[$type] ?? null;
		}

		$model = $host['model'] ?? '';
		if (!is_string($model)) {
			return 'rpi';
		}
		// Keep the identifiers used by existing Core and plugin compatibility checks.
		$models = array(
			'Raspberry Pi Model A' => 'RPI 1 A',
			'Raspberry Pi Model A+' => 'RPI 1 A+',
			'Raspberry Pi Model B' => 'RPI 1 B',
			'Raspberry Pi Model B+' => 'RPI 1 B+',
			'Raspberry Pi 2 Model B' => 'RPI 2 B',
			'Raspberry Pi 3 Model A+' => 'RPI 3 A+',
			'Raspberry Pi 3 Model B' => 'RPI 3 B',
			'Raspberry Pi 3 Model B+' => 'RPI 3 B+',
			'Raspberry Pi 4 Model B' => 'RPI 4 B',
			'Raspberry Pi 5 Model B' => 'RPI 5',
			'Raspberry Pi Zero' => 'RPI Zero',
			'Raspberry Pi Zero W' => 'RPI Zero W',
			'Raspberry Pi Zero 2 W' => 'RPI Zero 2 W',
			'Raspberry Pi Compute Module' => 'RPI 1 Compute',
			'Raspberry Pi Compute Module 3' => 'RPI 3 Compute',
			'Raspberry Pi Compute Module 3 Plus' => 'RPI 3 Compute+',
			'Raspberry Pi Compute Module 4' => 'RPI 4 Compute',
			'Raspberry Pi Compute Module 4S' => 'RPI 4S Compute',
			'Raspberry Pi Compute Module 5' => 'RPI 5 Compute',
			'Raspberry Pi 400' => 'RPI 400',
			'Raspberry Pi 500' => 'RPI 500',
			'Raspberry Pi 500+' => 'RPI 500+',
		);
		return $models[preg_replace('/ Rev [0-9.]+$/D', '', $model)] ?? 'rpi';
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
		list($code, $response) = self::exchange($path, $body, $timeout);
		$result = is_string($response) ? json_decode($response, true) : null;
		if ($code < 200 || $code >= 300 || !is_array($result)) {
			throw new Exception(__('API locale Jeego indisponible ou requête refusée', __FILE__) . ' (HTTP ' . $code . ')', $code);
		}
		return $result;
	}

	/** @return array{0: int, 1: mixed} */
	public static function requestResponse(string $path, ?array $body = null, int $timeout = 8): array {
		list($code, $response) = self::exchange($path, $body, $timeout);
		return array($code, is_string($response) ? json_decode($response) : null);
	}

	private static function exchange(string $path, ?array $body, int $timeout): array {
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
		return array((int) $http->getHttpCode(), $response);
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
