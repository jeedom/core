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
		return (getenv('JEEGO_API_URL') !== false && getenv('JEEGO_API_URL') !== '') || (getenv('JEEGO_API_KEY') !== false && getenv('JEEGO_API_KEY') !== '') || (self::read('control')['manager'] ?? '') == 'jeego' ? 'jeego' : 'core';
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
		if (count($servers) == 0 || config::byKey('dns::token') == '') {
			throw new Exception(__('Aucun relais ou identifiant FRP fourni par le Market', __FILE__));
		}
		if (preg_match('/[\r\n]/', config::byKey('dns::token'))) {
			throw new Exception(__('Identifiants FRP invalides', __FILE__));
		}
		return array('username' => jeedom::getHardwareKey(), 'password' => config::byKey('dns::token'), 'relay' => implode(',', $servers), 'local-ip' => '127.0.0.1', 'local-port' => '80');
	}

	public static function availability() {
		try {
			if (self::manager() == 'jeego') {
				self::jeegoCredentials();
			} else {
				$arch = system::getArch();
				if (PHP_OS_FAMILY != 'Linux' || !isset(self::BINARIES[$arch]) || ($arch == 'arm' && php_uname('m') != 'armv7l') || !function_exists('posix_kill')) {
					throw new Exception(__('FRP nécessite Linux amd64, arm64 ou armv7 et l’extension PHP POSIX', __FILE__));
				}
			}
			return '';
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	private static function installDependencies() {
		$commands = array('curl' => 'curl', 'jq' => 'jq', 'flock' => 'util-linux', 'setpriv' => 'util-linux', 'tar' => 'tar', 'sha256sum' => 'coreutils');
		$packages = array('apt' => array('ca-certificates' => array()));
		$deadline = time() + 900;
		while (system::installPackageInProgress()) {
			if (time() >= $deadline) {
				throw new Exception(__('Une installation de paquets est toujours en cours', __FILE__));
			}
			sleep(2);
		}
		foreach ($commands as $command => $package) {
			$packages['apt'][$package] = $packages['apt'][$package] ?? array();
			if (!com_shell::commandExists($command)) {
				$packages['apt'][$package]['reinstall'] = true;
			}
		}
		$install = false;
		foreach (system::checkAndInstall($packages) as $package) {
			$install = $install || $package['status'] == 0 || $package['reinstall'];
		}
		if (!$install) {
			return;
		}
		system::checkAndInstall($packages, true);
		$started = time();
		do {
			sleep(2);
			if (!system::installPackageInProgress() && time() - $started >= 10) {
				foreach ($commands as $command => $package) {
					if (!com_shell::commandExists($command)) {
						throw new Exception(__('Installation FRP incomplète ; vérifier le journal packages', __FILE__));
					}
				}
				return;
			}
		} while (time() < $deadline);
		throw new Exception(__('Installation des dépendances FRP trop longue ; vérifier le journal packages', __FILE__));
	}

	public static function install() {
		$arch = system::getArch();
		if (PHP_OS_FAMILY != 'Linux' || !isset(self::BINARIES[$arch]) || ($arch == 'arm' && php_uname('m') != 'armv7l')) {
			throw new Exception(__('Architecture FRP non prise en charge', __FILE__));
		}
		self::installDependencies();
		$source = array('version' => self::VERSION, 'arch' => $arch, 'sha256' => self::BINARIES[$arch]['sha256']);
		$installed = self::read('binary');
		$directory = self::directory() . '/bin';
		$binary = $directory . '/frpc';
		if (is_executable($binary) && ($installed['source'] ?? array()) == $source && isset($installed['sha256']) && hash_equals($installed['sha256'], hash_file('sha256', $binary))) {
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
			$stream = fopen($archive, 'wb');
			if ($stream === false) {
				throw new Exception(__('Impossible de préparer le téléchargement FRP', __FILE__));
			}
			$name = 'frp_' . self::VERSION . '_linux_' . self::BINARIES[$arch]['archive'];
			$http = new com_http('https://github.com/fatedier/frp/releases/download/v' . self::VERSION . '/' . $name . '.tar.gz');
			$http->setNoSslCheck(false);
			$http->setNoReportError(true);
			$http->setCURLOPT(array(CURLOPT_FILE => $stream, CURLOPT_FOLLOWLOCATION => true, CURLOPT_MAXREDIRS => 5, CURLOPT_PROTOCOLS => CURLPROTO_HTTPS, CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTPS, CURLOPT_CONNECTTIMEOUT => 10));
			try {
				$response = $http->exec(180, 1);
			} finally {
				fclose($stream);
			}
			if ($response === false || $http->getHttpCode() != 200 || !hash_equals($source['sha256'], hash_file('sha256', $archive))) {
				throw new Exception(__('Téléchargement FRP impossible ou empreinte incorrecte', __FILE__));
			}
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

	private static function jeegoCredentials() {
		$url = (string) getenv('JEEGO_API_URL');
		$key = (string) getenv('JEEGO_API_KEY');
		$credentials = array('url' => rtrim($url, '/'), 'key' => $key);
		$parts = parse_url($credentials['url']);
		if (!is_array($parts) || ($parts['scheme'] ?? '') != 'http' || !in_array($parts['host'] ?? '', array('127.0.0.1', '[::1]', '::1')) || ($parts['path'] ?? '') != '/api/v1' || isset($parts['user']) || isset($parts['pass']) || isset($parts['query']) || isset($parts['fragment']) || !preg_match('/^[a-f0-9]{64}$/D', ($credentials['key'] ?? ''))) {
			throw new Exception(__('Configuration de l’API locale Jeego absente ou invalide', __FILE__));
		}
		return $credentials;
	}

	private static function jeegoRequest($path, $body = null) {
		$credentials = self::jeegoCredentials();
		$http = new com_http($credentials['url'] . $path);
		$http->setHeader(array('Authorization: Bearer ' . $credentials['key'], 'Content-Type: application/json'));
		$http->setCURLOPT(array(CURLOPT_CONNECTTIMEOUT => 2, CURLOPT_FOLLOWLOCATION => false, CURLOPT_PROXY => ''));
		$http->setNoSslCheck(false);
		$http->setNoReportError(true);
		if ($body !== null) {
			$http->setPost(json_encode($body));
		}
		$response = $http->exec(8, 1);
		$code = $http->getHttpCode();
		$result = is_string($response) ? json_decode($response, true) : null;
		if ($code < 200 || $code >= 300 || !is_array($result)) {
			throw new Exception(__('API Jeego indisponible ou requête FRP refusée', __FILE__) . ' (HTTP ' . $code . ')');
		}
		return $result;
	}

	private static function task($state, $wait = false) {
		if (empty($state['task'])) {
			return $state;
		}
		$deadline = microtime(true) + ($wait ? 20 : 0);
		do {
			$task = self::jeegoRequest('/tasks/' . rawurlencode($state['task']));
			if (($task['status'] ?? '') != 'running') {
				unset($state['task']);
				$state['error'] = ($task['status'] ?? '') == 'succeeded' ? '' : __('L’opération FRP a échoué dans Jeego', __FILE__);
				self::write('control', $state);
				return $state;
			}
			if (!$wait) {
				return $state;
			}
			usleep(250000);
		} while (microtime(true) < $deadline);
		throw new Exception(__('Une opération FRP est encore en cours dans Jeego', __FILE__));
	}

	private static function jeegoAction($action, $parameters, $fingerprint) {
		$body = array('confirm' => true);
		if ($parameters !== null) {
			$body['parameters'] = $parameters;
		}
		$task = self::jeegoRequest('/services/frp-client/actions/' . $action, $body);
		if (!isset($task['id']) || !preg_match('/^[a-f0-9]{32}$/D', $task['id'])) {
			throw new Exception(__('Identifiant de tâche Jeego invalide', __FILE__));
		}
		$state = array('manager' => 'jeego', 'fingerprint' => $fingerprint, 'task' => $task['id'], 'error' => '');
		self::write('control', $state);
		return $state;
	}

	private static function process($state) {
		$pid = (int) ($state['pid'] ?? 0);
		if ($pid <= 1 || !isset($state['birth'])) {
			return false;
		}
		$stat = @file_get_contents('/proc/' . $pid . '/stat');
		$command = @file_get_contents('/proc/' . $pid . '/cmdline');
		if (!is_string($stat) || !is_string($command)) {
			return false;
		}
		$fields = explode(' ', substr($stat, strrpos($stat, ')') + 2));
		$arguments = explode("\0", $command);
		return ($fields[0] ?? '') != 'Z' && ($fields[19] ?? '') === $state['birth'] && (in_array(realpath(__DIR__ . '/../../resources/frp/run.sh'), $arguments, true) || in_array(realpath(__DIR__ . '/../../resources/frp/client.sh'), $arguments, true));
	}

	private static function stopNative() {
		$state = self::read('native');
		if (self::process($state)) {
			system::kill((int) $state['pid'], false);
			$deadline = microtime(true) + 22;
			while (self::process($state) && microtime(true) < $deadline) {
				usleep(100000);
			}
			if (self::process($state)) {
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
		$fingerprint = hash('sha256', json_encode($settings) . self::manager() . self::VERSION . hash_file('sha256', __DIR__ . '/../../resources/ca_frp.crt') . hash_file('sha256', __DIR__ . '/../../resources/frp/client.sh'));
		$state = self::read('control');
		if (($state['manager'] ?? '') == 'jeego') {
			$state = self::task($state, $manual && ($state['fingerprint'] ?? '') != $fingerprint);
			if (!empty($state['task'])) {
				return;
			}
		}
		if (self::manager() == 'jeego') {
			self::stopNative();
			$status = self::jeegoRequest('/services/frp-client');
			if (($state['fingerprint'] ?? '') == $fingerprint && !empty($state['error']) && !$manual) {
				return;
			}
			if (empty($status['installed']) || !empty($status['removed'])) {
				self::jeegoAction('install', $settings, $fingerprint);
			} elseif (($state['fingerprint'] ?? '') != $fingerprint || !empty($state['error'])) {
				self::jeegoAction('configure', $settings, $fingerprint);
			} elseif (($status['status'] ?? '') != 'running' && empty($status['pending'])) {
				if ($manual) {
					self::jeegoAction('recreate', null, $fingerprint);
				}
			}
			return;
		}
		if (($state['manager'] ?? '') == 'jeego') {
			throw new Exception(__('Arrêter le service FRP Jeego avant de retirer sa configuration API', __FILE__));
		}
		$native = self::read('native');
		if (self::process($native) && ($state['fingerprint'] ?? '') == $fingerprint) {
			return;
		}
		self::stopNative();
		$path = self::directory();
		if (!$manual && ($state['fingerprint'] ?? '') == $fingerprint && (!empty($state['error']) || (self::read('status')['state'] ?? '') == 'blocked')) {
			return;
		}
		$identity = hash('sha256', $settings['username'] . "\0" . $settings['password']);
		if ($manual && is_file($path . '/' . $identity . '.state') && strpos(file_get_contents($path . '/' . $identity . '.state'), 'blocked ') === 0) {
			unlink($path . '/' . $identity . '.state');
		}
		$temp = tempnam($path, 'password-');
		if ($temp === false || file_put_contents($temp, $settings['password']) === false || !chmod($temp, 0600) || !rename($temp, $path . '/password')) {
			throw new Exception(__('Impossible de préparer les identifiants FRP', __FILE__));
		}
		$script = realpath(__DIR__ . '/../../resources/frp/run.sh');
		$command = 'nohup bash ' . escapeshellarg($script) . ' ' . escapeshellarg($path) . ' ' . escapeshellarg($settings['username']) . ' ' . escapeshellarg($settings['relay']) . ' ' . escapeshellarg(log::getPathToLog('frp')) . ' < /dev/null > /dev/null 2>&1 & echo $!';
		self::write('control', array('manager' => 'core', 'fingerprint' => $fingerprint));
		$pid = (int) trim(com_shell::execute($command));
		usleep(100000);
		$stat = @file_get_contents('/proc/' . $pid . '/stat');
		$fields = is_string($stat) ? explode(' ', substr($stat, strrpos($stat, ')') + 2)) : array();
		if ($pid <= 1 || !isset($fields[19])) {
			self::write('control', array('manager' => 'core', 'fingerprint' => $fingerprint, 'error' => __('Impossible de démarrer le client FRP', __FILE__)));
			throw new Exception(__('Impossible de démarrer le client FRP', __FILE__));
		}
		self::write('native', array('pid' => $pid, 'birth' => $fields[19]));
	}

	public static function stop() {
		self::stopNative();
		$state = self::read('control');
		if (($state['manager'] ?? '') == 'jeego') {
			self::task($state, true);
			$status = self::jeegoRequest('/services/frp-client');
			if (!empty($status['installed']) && empty($status['removed'])) {
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
				$status = self::jeegoRequest('/services/frp-client');
				return array('running' => ($status['status'] ?? '') == 'running' && empty($status['pending']), 'manager' => 'jeego', 'state' => $status['status'] ?? 'unknown');
			}
			$status = self::read('status');
			$native = self::read('native');
			$status['running'] = self::process($native) && ($status['pid'] ?? 0) == ($native['pid'] ?? -1) && !empty($status['registered']) && !empty($status['published']) && time() - ($status['updated'] ?? 0) < 25;
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
