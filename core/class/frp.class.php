<?php

class frp {
	private const VERSION = '0.71.1';

	private static function directory() {
		// Persistent state and binary only; credentials are passed on stdin.
		$path = __DIR__ . '/../../data/frp';
		if (is_link($path) || (!is_dir($path) && !mkdir($path, 0700, true))) {
			throw new Exception(__('Impossible de créer le dossier FRP', __FILE__));
		}
		chmod($path, 0700);
		return $path;
	}

	private static function read($name) {
		if ($name == 'status') {
			$path = self::directory() . '/status.json';
			if (!file_exists($path)) {
				return array();
			}
			$value = json_decode(file_get_contents($path), true);
		} else {
			$value = config::byKey('dns::frp::' . $name, 'core', array());
		}
		if (!is_array($value)) {
			throw new Exception(__('État FRP illisible', __FILE__));
		}
		return $value;
	}

	private static function write($name, $value) {
		config::save('dns::frp::' . $name, $value);
	}

	public static function manager() {
		return jeego::isDetected() || (self::read('control')['manager'] ?? '') == 'jeego' ? 'jeego' : 'core';
	}

	public static function servers($servers = null) {
		if ($servers === null) {
			$servers = config::byKey('dns::frp::servers', 'core', array());
		}
		if (!is_array($servers) || count($servers) > 16) {
			throw new Exception(__('Liste des relais FRP invalide', __FILE__));
		}
		$result = array();
		foreach ($servers as $server) {
			$validHostname = is_string($server) && strpos($server, '.') !== false
				&& substr($server, -1) != '.'
				&& filter_var($server, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME);
			if (!$validHostname) {
				throw new Exception(__('Adresse de relais FRP invalide', __FILE__));
			}
			$result[] = strtolower($server);
		}
		return array_values(array_unique($result));
	}

	private static function settings() {
		$servers = self::servers();
		$token = config::byKey('dns::token');
		if (count($servers) == 0 || $token == '') {
			throw new Exception(__('Aucun relais ou identifiant FRP fourni par le Market', __FILE__));
		}
		if (preg_match('/[\r\n]/', $token)) {
			throw new Exception(__('Identifiants FRP invalides', __FILE__));
		}
		return array(
			'username' => jeedom::getHardwareKey(),
			'password' => $token,
			'relay' => implode(',', $servers),
			'local-ip' => '127.0.0.1',
			'local-port' => '80',
		);
	}

	private static function nativeSource() {
		$arch = system::getArch();
		if ($arch == 'arm' && php_uname('m') == 'armv7l') {
			$arch = 'armhf';
		}
		// Binary hashes are derived from the official release archives, themselves
		// checked against https://github.com/fatedier/frp/releases/tag/v0.71.1.
		$releases = array(
			'amd64' => array(
				'archive' => 'amd64',
				'sha256' => '35c6054d4549bbcf2f02875543895fd9e44c2aae2925f61cd8ff5a693703c28b',
				'archive_sha256' => '1d9875210972a5d1a62b2dc4c48df45ab0af05d3ca1b5e494a014428259df803',
			),
			'arm64' => array(
				'archive' => 'arm64',
				'sha256' => '651de3428bdc7f37b4bad76ed3236b221939a989c433cd7daee79d2817519f8b',
				'archive_sha256' => '8edf590b0c396fa42f9c147121a76af83cfc2002b08f5aef0ea1008c43482019',
			),
			'armhf' => array(
				'archive' => 'arm_hf',
				'sha256' => 'b022fabaf90362c2d105f5622a729208307d9140d497383fc85856d77b58ebc4',
				'archive_sha256' => 'a40116f15f2a05b7001f985f1f1ccb2570e2aa41329b06de76a82e71495786db',
			),
		);
		if (PHP_OS_FAMILY != 'Linux' || !isset($releases[$arch])) {
			throw new Exception(__('Architecture FRP non prise en charge', __FILE__));
		}
		$release = $releases[$arch];
		$member = 'frp_' . self::VERSION . '_linux_' . $release['archive'];
		return array(
			'url' => 'https://images.jeedom.com/resources/frp/frpc_' . $arch,
			'sha256' => $release['sha256'],
			'archive_sha256' => $release['archive_sha256'],
			'archive_url' => 'https://github.com/fatedier/frp/releases/download/v' . self::VERSION . '/' . $member . '.tar.gz',
			'member' => $member . '/frpc',
		);
	}

	public static function availability() {
		try {
			if (self::manager() == 'jeego') {
				jeego::checkConfiguration();
			} else {
				self::nativeSource();
			}
			return '';
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	private static function download($url, $destination) {
		$stream = fopen($destination, 'wb');
		if ($stream === false) {
			throw new Exception(__('Impossible de préparer le téléchargement FRP', __FILE__));
		}
		$http = new com_http($url);
		$http->setNoSslCheck(false);
		$http->setNoReportError(true);
		$http->setCURLOPT(array(
			CURLOPT_FILE => $stream,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_MAXREDIRS => 5,
			CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
			CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTPS,
			CURLOPT_CONNECTTIMEOUT => 10,
		));
		try {
			$response = $http->exec(180, 1);
		} finally {
			fclose($stream);
		}
		if ($response === false || $http->getHttpCode() != 200 || filesize($destination) == 0) {
			throw new Exception(__('Téléchargement FRP impossible', __FILE__));
		}
	}

	public static function install() {
		$source = self::nativeSource();
		$directory = self::directory() . '/bin';
		$binary = $directory . '/frpc';
		if (is_link($directory) || (!is_dir($directory) && !mkdir($directory, 0700))) {
			throw new Exception(__('Impossible de créer le dossier du binaire FRP', __FILE__));
		}
		if (!is_link($binary) && is_file($binary) && is_executable($binary) && hash_equals($source['sha256'], hash_file('sha256', $binary))) {
			return;
		}
		$candidate = tempnam($directory, 'frpc-');
		try {
			if ($candidate === false) {
				throw new Exception(__('Impossible de préparer le téléchargement FRP', __FILE__));
			}
			self::downloadNative($source, $candidate);
			if (!chmod($candidate, 0700) || !rename($candidate, $binary)) {
				throw new Exception(__('Impossible d’installer le binaire FRP', __FILE__));
			}
		} finally {
			if ($candidate !== false) {
				@unlink($candidate);
			}
		}
	}

	private static function downloadNative($source, $destination) {
		try {
			self::download($source['url'], $destination);
			if (hash_equals($source['sha256'], hash_file('sha256', $destination))) {
				return;
			}
		} catch (Exception $e) {
			// The official archive also covers a temporary mirror outage.
		}
		log::add('frp', 'warning', __('Miroir FRP indisponible ou empreinte différente ; récupération de la version officielle attendue', __FILE__));
		$archive = tempnam(dirname($destination), 'frp-');
		if ($archive === false) {
			throw new Exception(__('Impossible de préparer le téléchargement FRP', __FILE__));
		}
		try {
			self::download($source['archive_url'], $archive);
			if (!hash_equals($source['archive_sha256'], hash_file('sha256', $archive))) {
				throw new Exception(__('Empreinte de l’archive FRP invalide', __FILE__));
			}
			// Extract only the expected binary to stdout, never archive paths.
			$process = proc_open(array('tar', '-xOzf', $archive, '--', $source['member']), array(
				0 => array('file', '/dev/null', 'r'),
				1 => array('file', $destination, 'w'),
				2 => array('file', '/dev/null', 'a'),
			), $pipes);
			if (!is_resource($process) || proc_close($process) != 0) {
				throw new Exception(__('Impossible d’extraire le client FRP', __FILE__));
			}
			if (!hash_equals($source['sha256'], hash_file('sha256', $destination))) {
				throw new Exception(__('Empreinte du binaire FRP invalide', __FILE__));
			}
		} finally {
			@unlink($archive);
		}
	}

	private static function task($state, $wait = false) {
		if (empty($state['task'])) {
			return $state;
		}
		$task = $wait ? jeego::waitTask($state['task']) : jeego::getTask($state['task']);
		if ($task['status'] != 'running') {
			unset($state['task']);
			$state['error'] = $task['status'] == 'succeeded' ? '' : __('L’opération FRP a échoué dans Jeego', __FILE__);
			self::write('control', $state);
		}
		return $state;
	}

	private static function jeegoAction($action, $parameters, $fingerprint, $wait = false) {
		$body = array('confirm' => true);
		if ($parameters !== null) {
			$body['parameters'] = $parameters;
		}
		$task = jeego::serviceAction('frp-client', $action, $body);
		$state = array('manager' => 'jeego', 'fingerprint' => $fingerprint, 'task' => $task['id'], 'action' => $action, 'error' => '');
		self::write('control', $state);
		if ($wait) {
			$state = self::task($state, true);
			if (!empty($state['error'])) {
				throw new Exception($state['error']);
			}
		}
		return $state;
	}

	private static function jeegoService($wait = false) {
		for ($attempt = 0; $attempt < 3; $attempt++) {
			$status = jeego::getService('frp-client');
			if (empty($status['observation_busy']) && ($status['status'] ?? '') != 'busy') {
				if (empty($status['installed']) && empty($status['removed']) && ($status['status'] ?? '') != 'not-installed') {
					throw new Exception(__('État du service FRP indisponible dans Jeego', __FILE__));
				}
				return $status;
			}
			if (!$wait || $attempt == 2) {
				throw new Exception(__('Une opération est encore en cours dans Jeego', __FILE__));
			}
			sleep(2);
		}
	}

	private static function processInfo($pid) {
		$stat = @file_get_contents('/proc/' . $pid . '/stat');
		if (!is_string($stat)) {
			return array();
		}
		$fields = explode(' ', substr($stat, strrpos($stat, ')') + 2));
		return array('state' => $fields[0], 'birth' => $fields[19] ?? null);
	}

	private static function isNativeProcess($state) {
		$pid = (int) ($state['pid'] ?? 0);
		if ($pid <= 1 || !isset($state['birth'])) {
			return false;
		}
		$info = self::processInfo($pid);
		if (($info['state'] ?? 'Z') == 'Z' || ($info['birth'] ?? null) !== $state['birth']) {
			return false;
		}
		$command = @file_get_contents('/proc/' . $pid . '/cmdline');
		if (!is_string($command)) {
			return false;
		}
		$arguments = explode("\0", $command);
		foreach (array('run.sh', 'client.sh') as $script) {
			if (in_array(realpath(__DIR__ . '/../../resources/frp/' . $script), $arguments, true)) {
				return true;
			}
		}
		return false;
	}

	private static function stopNative() {
		$state = self::read('native');
		if (self::isNativeProcess($state)) {
			system::kill((int) $state['pid'], false);
			$deadline = microtime(true) + 22;
			while (self::isNativeProcess($state) && microtime(true) < $deadline) {
				usleep(100000);
			}
			if (self::isNativeProcess($state)) {
				throw new Exception(__('Le client FRP ne s’est pas arrêté ; bascule annulée', __FILE__));
			}
		}
		@unlink(self::directory() . '/password');
	}

	public static function start($manual = true) {
		$error = self::availability();
		if ($error != '') {
			throw new Exception($error);
		}
		$settings = self::settings();
		$manager = self::manager();
		$fingerprint = hash('sha256',
			json_encode($settings) . $manager
			. hash_file('sha256', __DIR__ . '/../../resources/ca_frp.crt')
			. hash_file('sha256', __DIR__ . '/../../resources/frp/client.sh')
		);
		if ($manager == 'jeego') {
			self::startJeego($settings, $fingerprint, $manual);
			return;
		}
		self::startNative($settings, $fingerprint, $manual);
	}

	private static function startJeego($settings, $fingerprint, $manual) {
		$state = self::read('control');
		$changed = ($state['fingerprint'] ?? '') != $fingerprint;
		if (($state['manager'] ?? '') == 'jeego') {
			$state = self::task($state, $manual);
			if (!empty($state['task'])) {
				return;
			}
		}
		self::stopNative();
		$status = self::jeegoService($manual);
		$failed = !empty($state['error']);
		if (!$manual && !$changed && $failed) {
			return;
		}
		if (empty($status['installed']) || !empty($status['removed'])) {
			self::jeegoAction('install', $settings, $fingerprint, $manual);
			return;
		}
		if ($changed || $failed) {
			self::jeegoAction('configure', $settings, $fingerprint, $manual);
			return;
		}
		if ($manual && ($status['status'] ?? '') != 'running' && empty($status['pending'])) {
			self::jeegoAction('recreate', null, $fingerprint, true);
		}
	}

	private static function startNative($settings, $fingerprint, $manual) {
		$state = self::read('control');
		$sameConfig = ($state['fingerprint'] ?? '') == $fingerprint;
		if ($sameConfig && self::isNativeProcess(self::read('native'))) {
			return;
		}
		self::stopNative();
		if (!$manual && $sameConfig) {
			if (!empty($state['error']) || (self::read('status')['state'] ?? '') == 'blocked') {
				return;
			}
		}
		try {
			self::install();
		} catch (Throwable $e) {
			self::write('control', array('manager' => 'core', 'fingerprint' => $fingerprint, 'error' => $e->getMessage()));
			throw $e;
		}
		$path = self::directory();
		$identity = hash('sha256', $settings['username'] . "\0" . $settings['password']);
		$stateFile = $path . '/' . $identity . '.state';
		if ($manual && is_file($stateFile) && strpos(file_get_contents($stateFile), 'blocked ') === 0) {
			unlink($stateFile);
		}
		$arguments = array(
			realpath(__DIR__ . '/../../resources/frp/run.sh'),
			$path,
			$settings['username'],
			$settings['relay'],
			log::getPathToLog('frp'),
		);
		self::write('control', array('manager' => 'core', 'fingerprint' => $fingerprint));
		$pid = self::launchNative($arguments, $settings['password']);
		usleep(100000);
		$info = self::processInfo($pid);
		if ($pid <= 1 || !isset($info['birth'])) {
			self::write('control', array('manager' => 'core', 'fingerprint' => $fingerprint, 'error' => __('Impossible de démarrer le client FRP', __FILE__)));
			throw new Exception(__('Impossible de démarrer le client FRP', __FILE__));
		}
		self::write('native', array('pid' => $pid, 'birth' => $info['birth']));
	}

	private static function launchNative($arguments, $password) {
		$command = 'nohup bash ' . implode(' ', array_map('escapeshellarg', $arguments));
		$command .= ' <&3 3<&- > /dev/null 2>&1 & echo $!';
		$process = proc_open($command, array(
			0 => array('file', '/dev/null', 'r'),
			1 => array('pipe', 'w'),
			2 => array('file', '/dev/null', 'a'),
			3 => array('pipe', 'r'),
		), $pipes);
		if (!is_resource($process)) {
			throw new Exception(__('Impossible de démarrer le client FRP', __FILE__));
		}
		try {
			// Keep the DNS token out of the command line and the web directory.
			if (fwrite($pipes[3], $password . "\n") !== strlen($password) + 1) {
				throw new Exception(__('Impossible de transmettre les identifiants FRP', __FILE__));
			}
			fclose($pipes[3]);
			return (int) trim(stream_get_contents($pipes[1]));
		} finally {
			foreach ($pipes as $pipe) {
				if (is_resource($pipe)) {
					fclose($pipe);
				}
			}
			proc_close($process);
		}
	}

	public static function stop() {
		$state = self::read('control');
		if (empty($state) && !self::isNativeProcess(self::read('native')) && !file_exists(__DIR__ . '/../../data/frp/password')) {
			return;
		}
		self::stopNative();
		if (($state['manager'] ?? '') == 'jeego') {
			$state = self::task($state, true);
			$status = self::jeegoService(true);
			$stopped = in_array($status['status'] ?? '', array('stopped', 'exited', 'dead', 'created', 'missing', 'not-installed'), true);
			$installed = !empty($status['installed']) && empty($status['removed']);
			if ($installed && (!empty($status['pending']) || !$stopped)) {
				self::jeegoAction('stop', null, '', true);
			}
		}
		self::write('control', array());
	}

	public static function status() {
		try {
			$state = self::read('control');
			if (($state['manager'] ?? '') == 'jeego') {
				$error = $state['error'] ?? '';
				if (!empty($state['task'])) {
					$task = jeego::getTask($state['task']);
					if ($task['status'] == 'running') {
						$action = $state['action'] ?? substr($task['kind'] ?? '', strlen('frp-client-'));
						return array('running' => false, 'manager' => 'jeego', 'state' => $action == 'install' ? 'installing' : 'connecting');
					}
					$error = $task['status'] == 'succeeded' ? '' : __('L’opération FRP a échoué dans Jeego', __FILE__);
				}
				if ($error != '') {
					return array('running' => false, 'manager' => 'jeego', 'state' => 'error', 'error' => $error);
				}
				$status = jeego::getService('frp-client');
				$busy = !empty($status['observation_busy']) || ($status['status'] ?? '') == 'busy';
				return array('running' => !$busy && ($status['status'] ?? '') == 'running' && empty($status['pending']), 'manager' => 'jeego', 'state' => $busy || !empty($status['pending']) ? 'connecting' : ($status['status'] ?? 'unknown'));
			}
			$status = self::read('status');
			$native = self::read('native');
			$status['running'] = self::isNativeProcess($native)
				&& ($status['pid'] ?? 0) == ($native['pid'] ?? -1)
				&& !empty($status['registered']) && !empty($status['published'])
				&& time() - ($status['updated'] ?? 0) < 25;
			if (!$status['running'] && !empty($state['error'])) {
				$status['state'] = 'blocked';
				$status['error'] = $state['error'];
			}
			$status['manager'] = 'core';
			return $status;
		} catch (Exception $e) {
			return array('running' => false, 'state' => 'error', 'error' => $e->getMessage());
		}
	}
}
