<?php

// Run with: php .github/tests/frp-jeego.php (no Jeedom, Docker or API required).
if (PHP_SAPI !== 'cli') {
	exit(1);
}

function __($text, $file = '') { return $text; }

class config {
	public static $values = array();
	public static function byKey($key, $plugin = 'core', $default = '') {
		return self::$values[$key] ?? $default;
	}
	public static function save($key, $value) { self::$values[$key] = $value; }
}

class jeedom {
	public static function getHardwareKey() { return 'test-box'; }
}

class jeego {
	public static $service, $tasks, $actions, $waits, $reads, $fail, $timeout, $busyReads;
	public static function isDetected() { return true; }
	public static function checkConfiguration() {}
	public static function getService($id) {
		self::$reads++;
		if (self::$busyReads > 0) {
			self::$busyReads--;
			return array('status' => 'busy', 'observation_busy' => true);
		}
		return self::$service;
	}
	public static function serviceAction($service, $action, $body) {
		if ($action == 'install' && !empty(self::$service['installed'])) {
			throw new Exception('service déjà installé');
		}
		self::$actions[] = $action;
		$id = str_pad((string) count(self::$actions), 32, '0', STR_PAD_LEFT);
		return self::$tasks[$id] = array('id' => $id, 'kind' => 'frp-client-' . $action, 'status' => 'running');
	}
	public static function getTask($id) { return self::$tasks[$id]; }
	public static function complete($id, $success = true) {
		self::$tasks[$id]['status'] = $success ? 'succeeded' : 'failed';
		if ($success) {
			$stopped = self::$tasks[$id]['kind'] == 'frp-client-stop';
			self::$service = array('installed' => true, 'status' => $stopped ? 'exited' : 'running');
		}
		return self::$tasks[$id];
	}
	public static function waitTask($id) {
		self::$waits++;
		if (self::$timeout) {
			throw new Exception('Une opération est encore en cours dans Jeego');
		}
		return self::complete($id, !self::$fail);
	}
}

function check($condition, $message) {
	if (!$condition) { throw new Exception($message); }
}

function fails($callback, $message) {
	try { $callback(); } catch (Exception $e) {
		check(strpos($e->getMessage(), $message) !== false, 'Unexpected error: ' . $e->getMessage());
		return;
	}
	throw new Exception('Expected error: ' . $message);
}

function resetFixture() {
	config::$values = array('dns::token' => 'test-only', 'dns::frp::servers' => array('relay.example.test'));
	jeego::$service = array('installed' => false, 'status' => 'not-installed');
	jeego::$tasks = jeego::$actions = array();
	jeego::$waits = jeego::$reads = jeego::$busyReads = 0;
	jeego::$fail = jeego::$timeout = false;
}

$source = $argv[1] ?? dirname(__DIR__, 2);
$temporary = sys_get_temp_dir() . '/frp-jeego-test-' . bin2hex(random_bytes(8));
foreach (array('core/class/frp.class.php', 'resources/ca_frp.crt', 'resources/frp/client.sh') as $file) {
	mkdir(dirname($temporary . '/' . $file), 0700, true);
	copy($source . '/' . $file, $temporary . '/' . $file);
}
require $temporary . '/core/class/frp.class.php';

$tests = array(
	'manual install waits for success' => function () {
		frp::start();
		check(jeego::$actions == array('install') && jeego::$waits == 1, 'Install was only queued');
		check(empty(config::byKey('dns::frp::control')['task']) && frp::status()['running'], 'Successful installation not visible');
	},
	'background task remains visible and is not duplicated' => function () {
		frp::start(false);
		check(frp::status()['state'] == 'installing', 'Pending installation shown as stopped');
		frp::start(false);
		check(jeego::$actions == array('install') && jeego::$waits == 0, 'Duplicate background installation');
	},
	'manual start waits for the same pending configuration' => function () {
		frp::start(false);
		frp::start();
		check(jeego::$actions == array('install') && jeego::$waits == 1 && frp::status()['running'], 'Pending task not awaited');
	},
	'changed settings wait before configuration' => function () {
		frp::start(false);
		config::$values['dns::token'] = 'changed-test-only';
		frp::start();
		check(jeego::$actions == array('install', 'configure') && jeego::$waits == 2, 'Configuration raced with install');
	},
	'save then restart installs only once' => function () {
		frp::start();
		frp::stop();
		frp::start();
		check(jeego::$actions == array('install', 'stop', 'configure'), 'Restart attempted a second installation');
		check(jeego::$waits == 3 && frp::status()['running'], 'Restart returned before completion');
	},
	'manual failure is reported and supervision does not retry' => function () {
		jeego::$fail = true;
		fails(function () { frp::start(); }, 'échoué');
		check(frp::status()['state'] == 'error', 'Task failure hidden');
		frp::start(false);
		check(jeego::$actions == array('install'), 'Failed task retried automatically');
	},
	'timeout keeps the accepted task for the next request' => function () {
		jeego::$timeout = true;
		fails(function () { frp::start(); }, 'encore en cours');
		$id = config::byKey('dns::frp::control')['task'];
		check(frp::status()['state'] == 'installing', 'Timed out task lost');
		frp::start(false);
		jeego::$timeout = false;
		frp::start();
		check(jeego::$actions == array('install') && jeego::$tasks[$id]['status'] == 'succeeded', 'Timed out install duplicated');
	},
	'unavailable observation never means not installed' => function () {
		jeego::$service = array('status' => 'unavailable');
		fails(function () { frp::start(); }, 'indisponible');
		check(empty(jeego::$actions), 'Installed from an unavailable observation');
	},
	'busy observation never means not installed' => function () {
		jeego::$busyReads = 1;
		fails(function () { frp::start(false); }, 'encore en cours');
		check(empty(jeego::$actions), 'Installed from a busy observation');
	},
	'manual observation retries a temporary lock' => function () {
		jeego::$busyReads = 1;
		frp::start();
		check(jeego::$reads == 2 && jeego::$actions == array('install'), 'Temporary lock not retried');
	},
	'stop failure keeps the control state' => function () {
		frp::start();
		jeego::$fail = true;
		fails(function () { frp::stop(); }, 'échoué');
		check(config::byKey('dns::frp::control')['manager'] == 'jeego' && frp::status()['state'] == 'error', 'Failed stop forgotten');
	},
	'background failure overrides a running observation' => function () {
		frp::start(false);
		jeego::complete(config::byKey('dns::frp::control')['task'], false);
		jeego::$service = array('installed' => true, 'status' => 'running');
		check(frp::status()['state'] == 'error' && !frp::status()['running'], 'Failed task shown as running');
	},
	'busy observation cannot confirm a running service' => function () {
		frp::start();
		jeego::$service['observation_busy'] = true;
		check(!frp::status()['running'] && frp::status()['state'] == 'connecting', 'Stale running observation accepted');
	},
	'existing stopped service is recreated and awaited' => function () {
		frp::start();
		jeego::$service['status'] = 'exited';
		frp::start();
		check(jeego::$actions == array('install', 'recreate') && jeego::$waits == 2, 'Recreate was only queued');
	},
);

try {
	foreach ($tests as $name => $test) {
		resetFixture();
		$test();
		echo 'PASS ' . $name . "\n";
	}
	echo count($tests) . " scenarios passed\n";
} finally {
	$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($temporary, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
	foreach ($files as $file) {
		$file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
	}
	rmdir($temporary);
}
