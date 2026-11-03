<?php

class frp {
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

	private static function checkNativePlatform() {
		$arch = system::getArch();
		if ($arch == 'arm' && php_uname('m') == 'armv7l') {
			$arch = 'armhf';
		}
		if (PHP_OS_FAMILY != 'Linux' || !in_array($arch, array('amd64', 'arm64', 'armhf'), true)) {
			throw new Exception(__('Architecture FRP non prise en charge', __FILE__));
		}
	}

	public static function availability() {
		try {
			if (self::manager() == 'jeego') {
				jeego::checkConfiguration();
			} else {
				self::checkNativePlatform();
			}
			return '';
		} catch (Exception $e) {
			return $e->getMessage();
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
		$fingerprint = hash('sha256', $fingerprint . hash_file('sha256', __DIR__ . '/../../resources/frp/client-update.sh'));
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
		$path = self::directory();
		$identity = hash('sha256', $settings['username'] . "\0" . $settings['password']);
		$stateFile = $path . '/' . $identity . '.state';
		if ($manual && is_file($stateFile) && strpos(file_get_contents($stateFile), 'blocked ') === 0) {
			// Only an explicit restart rearms exhausted downloads; keep the highest binary.
			$clientDirectory = $path . '/client-bin';
			if (!is_link($clientDirectory) && is_dir($clientDirectory)) {
				foreach (glob($clientDirectory . '/attempts-*') ?: array() as $attemptFile) {
					if (!is_link($attemptFile) && is_file($attemptFile)
						&& preg_match('/^attempts-[0-9]+\.[0-9]+\.[0-9]+-(amd64|arm64|armhf)$/D', basename($attemptFile))) {
						unlink($attemptFile);
					}
				}
			}
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
