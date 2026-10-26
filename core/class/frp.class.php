<?php

class frp {
	private const VERSION = '0.71.1';
	private const BINARIES = array(
		'amd64' => array('archive' => 'amd64', 'sha256' => '1d9875210972a5d1a62b2dc4c48df45ab0af05d3ca1b5e494a014428259df803'),
		'arm64' => array('archive' => 'arm64', 'sha256' => '8edf590b0c396fa42f9c147121a76af83cfc2002b08f5aef0ea1008c43482019'),
		'arm' => array('archive' => 'arm_hf', 'sha256' => 'a40116f15f2a05b7001f985f1f1ccb2570e2aa41329b06de76a82e71495786db'),
	);

	private static function directory() {
		$path = __DIR__ . '/../../data/frp';
		if (is_link($path) || (!is_dir($path) && !mkdir($path, 0700, true))) {
			throw new Exception(__('Impossible de créer le dossier privé FRP', __FILE__));
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

	public static function synchronize($callback) {
		$lock = fopen(self::directory() . '/control.lock', 'c');
		if ($lock === false || !flock($lock, LOCK_EX)) {
			throw new Exception(__('Verrou FRP indisponible', __FILE__));
		}
		try {
			return $callback();
		} finally {
			flock($lock, LOCK_UN);
			fclose($lock);
		}
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
			if (!is_string($server) || strlen($server) > 253 || !preg_match('/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?$/D', $server)) {
				throw new Exception(__('Adresse de relais FRP invalide', __FILE__));
			}
			$result[] = $server;
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
		$supported = isset(self::BINARIES[$arch]) && ($arch != 'arm' || php_uname('m') == 'armv7l');
		if (PHP_OS_FAMILY != 'Linux' || !$supported) {
			throw new Exception(__('Architecture FRP non prise en charge', __FILE__));
		}
		return array('version' => self::VERSION, 'arch' => $arch, 'sha256' => self::BINARIES[$arch]['sha256']);
	}

	public static function availability() {
		try {
			if (self::manager() == 'jeego') {
				jeego::checkConfiguration();
			} else {
				self::nativeSource();
				if (!function_exists('posix_kill')) {
					throw new Exception(__('FRP nécessite l’extension PHP POSIX', __FILE__));
				}
			}
			return '';
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	private static function download($name, $destination, $sha256) {
		$stream = fopen($destination, 'wb');
		if ($stream === false) {
			throw new Exception(__('Impossible de préparer le téléchargement FRP', __FILE__));
		}
		$http = new com_http('https://github.com/fatedier/frp/releases/download/v' . self::VERSION . '/' . $name . '.tar.gz');
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
		if ($response === false || $http->getHttpCode() != 200 || !hash_equals($sha256, hash_file('sha256', $destination))) {
			throw new Exception(__('Téléchargement FRP impossible ou empreinte incorrecte', __FILE__));
		}
	}

	public static function install() {
		$source = self::nativeSource();
		$installed = self::read('binary');
		$directory = self::directory() . '/bin';
		$binary = $directory . '/frpc';
		if (is_executable($binary)
			&& ($installed['source'] ?? array()) == $source
			&& isset($installed['sha256'])
			&& hash_equals($installed['sha256'], hash_file('sha256', $binary))) {
			return;
		}
		if (is_link($directory) || (!is_dir($directory) && !mkdir($directory, 0700))) {
			throw new Exception(__('Impossible de créer le dossier du binaire FRP', __FILE__));
		}
		$archive = tempnam(self::directory(), 'download-');
		$candidate = tempnam(self::directory(), 'frpc-');
		try {
			if ($archive === false || $candidate === false) {
				throw new Exception(__('Impossible de préparer le téléchargement FRP', __FILE__));
			}
			$name = 'frp_' . self::VERSION . '_linux_' . self::BINARIES[$source['arch']]['archive'];
			self::download($name, $archive, $source['sha256']);
			com_shell::execute('tar --no-same-owner -xOzf ' . escapeshellarg($archive) . ' ' . escapeshellarg($name . '/frpc') . ' > ' . escapeshellarg($candidate));
			if (!chmod($candidate, 0700) || !rename($candidate, $binary)) {
				throw new Exception(__('Impossible d’installer le binaire FRP', __FILE__));
			}
			self::write('binary', array('source' => $source, 'sha256' => hash_file('sha256', $binary)));
		} finally {
			if ($archive !== false) {
				@unlink($archive);
			}
			if ($candidate !== false) {
				@unlink($candidate);
			}
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

	private static function jeegoAction($action, $parameters, $fingerprint) {
		$body = array('confirm' => true);
		if ($parameters !== null) {
			$body['parameters'] = $parameters;
		}
		$task = jeego::serviceAction('frp-client', $action, $body);
		$state = array('manager' => 'jeego', 'fingerprint' => $fingerprint, 'task' => $task['id'], 'error' => '');
		self::write('control', $state);
		return $state;
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
			json_encode($settings) . $manager . self::VERSION
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
			$state = self::task($state, $manual && $changed);
			if (!empty($state['task'])) {
				return;
			}
		}
		self::stopNative();
		$status = jeego::getService('frp-client');
		$failed = !empty($state['error']);
		if (!$manual && !$changed && $failed) {
			return;
		}
		if (empty($status['installed']) || !empty($status['removed'])) {
			self::jeegoAction('install', $settings, $fingerprint);
			return;
		}
		if ($changed || $failed) {
			self::jeegoAction('configure', $settings, $fingerprint);
			return;
		}
		if ($manual && ($status['status'] ?? '') != 'running' && empty($status['pending'])) {
			self::jeegoAction('recreate', null, $fingerprint);
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
		$path = self::directory();
		$identity = hash('sha256', $settings['username'] . "\0" . $settings['password']);
		$stateFile = $path . '/' . $identity . '.state';
		if ($manual && is_file($stateFile) && strpos(file_get_contents($stateFile), 'blocked ') === 0) {
			unlink($stateFile);
		}
		$temp = tempnam($path, 'password-');
		if ($temp === false || file_put_contents($temp, $settings['password']) === false || !chmod($temp, 0600) || !rename($temp, $path . '/password')) {
			throw new Exception(__('Impossible de préparer les identifiants FRP', __FILE__));
		}
		$arguments = array(
			realpath(__DIR__ . '/../../resources/frp/run.sh'),
			$path,
			$settings['username'],
			$settings['relay'],
			log::getPathToLog('frp'),
		);
		$command = 'nohup bash ' . implode(' ', array_map('escapeshellarg', $arguments));
		$command .= ' < /dev/null > /dev/null 2>&1 & echo $!';
		self::write('control', array('manager' => 'core', 'fingerprint' => $fingerprint));
		$pid = (int) trim(com_shell::execute($command));
		usleep(100000);
		$info = self::processInfo($pid);
		if ($pid <= 1 || !isset($info['birth'])) {
			self::write('control', array('manager' => 'core', 'fingerprint' => $fingerprint, 'error' => __('Impossible de démarrer le client FRP', __FILE__)));
			throw new Exception(__('Impossible de démarrer le client FRP', __FILE__));
		}
		self::write('native', array('pid' => $pid, 'birth' => $info['birth']));
	}

	public static function stop() {
		$state = self::read('control');
		if (empty($state) && !self::isNativeProcess(self::read('native')) && !file_exists(__DIR__ . '/../../data/frp/password')) {
			return;
		}
		self::stopNative();
		if (($state['manager'] ?? '') == 'jeego') {
			$state = self::task($state, true);
			$status = jeego::getService('frp-client');
			$stopped = in_array($status['status'] ?? '', array('stopped', 'exited', 'dead', 'created', 'missing', 'not-installed'), true);
			$installed = !empty($status['installed']) && empty($status['removed']);
			if ($installed && (!empty($status['pending']) || !$stopped)) {
				$state = self::jeegoAction('stop', null, '');
				$state = self::task($state, true);
				if (!empty($state['error'])) {
					throw new Exception($state['error']);
				}
			}
		}
		self::write('control', array());
	}

	public static function status() {
		try {
			$state = self::read('control');
			if (($state['manager'] ?? '') == 'jeego') {
				$status = jeego::getService('frp-client');
				return array('running' => ($status['status'] ?? '') == 'running' && empty($status['pending']), 'manager' => 'jeego', 'state' => $status['status'] ?? 'unknown');
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

	public static function supervise() {
		if (config::byKey('dns::mode', 'core', 'openvpn') != 'frp') {
			return;
		}
		network::dns_start(false);
	}
}
