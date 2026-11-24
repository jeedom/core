<?php
// CLI regression suite: real Market client, inert storage/HTTP boundaries.
$root = dirname(__DIR__);
$directory = getenv('JEEDOM_CLOUD_TEST_DIRECTORY') ?: sys_get_temp_dir() . '/jeedom-cloud-' . bin2hex(random_bytes(8));
if (!is_dir($directory)) mkdir($directory, 0700, true);
$child = ($argv[1] ?? '') === 'worker';
$checks = 0;
function check($value, $message) { global $checks; ++$checks; if (!$value) throw new RuntimeException($message); }
function failWith($code, $action) {
    try { $action(); } catch (Exception $e) { check($e->getCode() === $code, 'Wrong failure: ' . $e->getMessage()); return; }
    throw new RuntimeException('Expected failure ' . $code);
}
function __($text, $file = '') { return $text; }
function calculPath($path) { return $path; }
function is_json($value, $default) { return is_string($value) ? (json_decode($value, true) ?? $default) : $default; }
function rrmdir($path) {
    if (!is_dir($path)) return;
    foreach (scandir($path) as $name) if ($name !== '.' && $name !== '..') {
        $file = $path . '/' . $name;
        if (is_dir($file) && !is_link($file)) rrmdir($file); else unlink($file);
    }
    rmdir($path);
}
class config {
    public static function all() { global $directory; return json_decode(file_get_contents($directory . '/config.json'), true); }
    public static function byKey($key, $plugin = 'core', $default = '', $fresh = false) { $data = self::all(); return $data[$key] ?? $default; }
    public static function save($key, $value, $plugin = 'core') {
        global $directory; $data = self::all(); $data[$key] = $value;
        file_put_contents($directory . '/config.json', json_encode($data));
    }
    public static function remove($key) { global $directory; $data = self::all(); unset($data[$key]); file_put_contents($directory . '/config.json', json_encode($data)); }
}
class cache {
    private static $values = array();
    public static function set($key, $value) { self::$values[$key] = $value; }
    public static function delete($key) { unset(self::$values[$key]); }
    public static function byKey($key) { return new self($key); }
    private $key;
    private function __construct($key) { $this->key = $key; }
    public function getValue($default = '') { return self::$values[$this->key] ?? $default; }
}
class jeedom {
    public static $hardware = 'fixture-hardware-key';
    public static function getHardwareKey() { return self::$hardware; }
    public static function getTmpFolder($type) { global $directory; return $directory; }
    public static function health() { return array(); }
    public static function version() { return '5.0.0'; }
    public static function getApiKey($name) { return 'fixture-market-key'; }
    public static function getHardwareName() { return 'diy'; }
    public static function ensureBackupDir() {
        $directory = calculPath(config::byKey('backup::path'));
        if (!is_dir($directory) && !@mkdir($directory, 0770, true) && !is_dir($directory)) throw new RuntimeException('Backup directory fixture failure');
        return $directory;
    }
}
class network {
    public static function getNetworkAccess($scope, $part = '') {
        return $part === 'ip' ? '192.0.2.10' : 'https://box.example.test';
    }
}
class message {
    public static $entries = array();
    public static function nbMessage() { return count(self::$entries); }
    public static function add($plugin, $text, $action, $id) {
        global $directory;
        $lock = fopen($directory . '/session.lock', 'c');
        check(flock($lock, LOCK_EX | LOCK_NB), 'Message actions would run under the Market session lock');
        fclose($lock);
        self::$entries[] = array($plugin, $text, $action, $id);
    }
    public static function removeAll($plugin, $id) {
        self::$entries = array_values(array_filter(self::$entries, static function ($entry) use ($plugin, $id) {
            return $entry[0] !== $plugin || $entry[3] !== $id;
        }));
    }
}
class update { public static function nbNeedUpdate() { return 0; } }
class plugin { public static function listPlugin(...$args) { return array(); } }
class log {
    public static $entries = array();
    public static function add(...$entry) { self::$entries[] = $entry; }
}
class com_shell {
    public static $commands = array();
    public static $fail = false;
    public static function execute($command) {
        self::$commands[] = $command;
        check(strpos($command, 'curl ') === false && strpos($command, 'wget ') === false, 'A Cloud credential reached a command line');
        $password = config::byKey('market::cloud::backup::password');
        check(strpos($command, $password) === false, 'The backup password reached the GPG command line');
        check(preg_match("/--passphrase-file '([^']+)'/", $command, $passphrase) === 1
            && (fileperms($passphrase[1]) & 0777) === 0600
            && file_get_contents($passphrase[1]) === $password, 'GPG did not receive the password in a private file');
        if (self::$fail) throw new RuntimeException('GPG fixture failure');
        if (getenv('JEEDOM_TEST_FAKE_GPG')) {
            preg_match("/--output '([^']+)'/", $command, $match);
            file_put_contents($match[1], 'encrypted-fixture');
        } else {
            exec($command . ' 2>&1', $lines, $code);
            check($code === 0, 'GPG operation failed: ' . implode("\n", $lines));
        }
    }
}
class com_http {
    public static $calls = array(), $replies = array(), $archive = 'encrypted-fixture';
    public static $parallel = false;
    public $url, $headers = array(), $options = array(), $ssl = true, $quiet = false, $status = 200;
    public function __construct($url, $username = '', $password = '') { $this->url = $url; check($username === '' && $password === '', 'Basic authentication remains'); }
    public function setHeader($headers) { $this->headers = $headers; }
    public function setCURLOPT($options) { $this->options = $options; }
    public function setNoSslCheck($value) { $this->ssl = $value; }
    public function setNoReportError($value) { $this->quiet = $value; }
    public function getHttpCode() { return $this->status; }
    public function exec($timeout, $attempts) {
        global $directory;
        check($attempts === 1 && $this->quiet, 'Transport retries/logging changed');
        check($this->options[CURLOPT_FOLLOWLOCATION] === false, 'Redirects may forward a Bearer');
        self::$calls[] = $this;
        if (self::$replies) {
            $reply = array_shift(self::$replies);
            if (is_callable($reply)) $reply = $reply($this);
        } elseif (strpos($this->url, '/box-sessions') !== false) {
            if (self::$parallel) {
                check(strpos($this->url, '/box-sessions/refresh') !== false, 'A worker bypassed the normal Market refresh');
                file_put_contents($directory . '/issued', '1', FILE_APPEND);
                usleep(150000);
            }
            $reply = array(201, array('boxId' => 7, 'accessToken' => 'box_a_7_' . str_repeat('c', 64), 'refreshToken' => 'box_r_7_' . str_repeat('d', 64), 'expiresIn' => 86400));
        } elseif ($this->options[CURLOPT_CUSTOMREQUEST] === 'PROPFIND') {
            $reply = array(207, dav());
        } elseif (isset($this->options[CURLOPT_INFILE])) {
            check(is_resource($this->options[CURLOPT_INFILE]), 'Upload is not streamed');
            self::$archive = stream_get_contents($this->options[CURLOPT_INFILE]);
            check(strlen(self::$archive) === $this->options[CURLOPT_INFILESIZE], 'Upload size changed');
            $reply = array(201, '');
        } elseif (isset($this->options[CURLOPT_FILE])) {
            $reply = array(200, self::$archive);
        } else { $reply = array(204, ''); }
        $this->status = $reply[0]; $body = is_array($reply[1]) ? json_encode($reply[1]) : $reply[1];
        if (isset($this->options[CURLOPT_FILE])) { fwrite($this->options[CURLOPT_FILE], $body); return true; }
        return $body;
    }
}
function dav(array $sizes = array()) {
    $body = '<d:multistatus xmlns:d="DAV:">';
    foreach (array('old.gpg' => '2025-01-01', 'new.gpg' => '2026-01-01', '../escape.gpg' => '2026-02-01', 'sub/deep.gpg' => '2026-02-01', 'plain.txt' => '2026-02-01') as $name => $date) {
        $size = array_key_exists($name, $sizes) ? $sizes[$name] : 3000000000;
        $length = $size === null ? '' : '<d:getcontentlength>' . $size . '</d:getcontentlength>';
        $body .= '<d:response><d:href>https://untrusted.invalid/webdav/alice/My%20box/' . $name . '</d:href><d:propstat><d:prop><d:resourcetype/>' . $length . '<d:getlastmodified>' . $date . '</d:getlastmodified></d:prop><d:status>HTTP/1.1 200 OK</d:status></d:propstat></d:response>';
    }
    $body .= '<d:response><d:href>/webdav/bob/Other/foreign.gpg</d:href><d:propstat><d:prop><d:resourcetype/></d:prop><d:status>HTTP/1.1 200 OK</d:status></d:propstat></d:response></d:multistatus>';
    return $body;
}
function resetState() {
    global $directory;
    $context = hash('sha256', "https://market.example.test\0alice\0" . jeedom::getHardwareKey());
    file_put_contents($directory . '/config.json', json_encode(array('market::address' => 'https://market.example.test', 'market::username' => 'alice', 'market::password' => 'private-legacy-hash',
        'market::restSession' => array('boxId' => 7, 'context' => $context, 'accessToken' => 'box_a_7_' . str_repeat('a', 64), 'refreshToken' => 'box_r_7_' . str_repeat('b', 64), 'expiresAt' => time() + 86400),
        'service::backup::url' => 'https://backup.example.test', 'service::backup::enable' => 1, 'market::cloud::backup::name' => 'My box',
        'market::cloud::backup::password' => 'fixture-encryption-password', 'market::cloud::backup::password_confirmation' => 'fixture-encryption-password', 'backup::path' => $directory)));
    com_http::$calls = com_http::$replies = array();
    message::$entries = array();
}
// Load the source without a configured Jeedom database or installed plugins.
$utils = file_get_contents($root . '/core/class/utils.class.php');
eval('?>' . str_replace("require_once __DIR__ . '/../php/core.inc.php';", '', $utils));
$source = file_get_contents($root . '/core/repo/market.repo.php');
eval('?>' . str_replace("require_once __DIR__ . '/../../core/php/core.inc.php';", '', $source));
if ($child) { com_http::$parallel = true; echo repo_market::getCloudToken(); exit; }
try {
    resetState();
    com_http::$replies = array(array(200, array(
        'id' => 123, 'name' => 'Plugin', 'type' => 'plugin', 'logicalId' => 'fixture', 'description' => '', 'categorie' => '', 'author' => '',
        'cost' => 0, 'realCost' => 0, 'purchase' => true, 'certification' => '', 'utilization' => '', 'documentation' => '', 'changelog' => '',
        'rating' => 0, 'ratingCount' => 0, 'userRating' => 0, 'minJeedomVersion' => '6.0',
        'versions' => array('stable' => array('publishedAt' => '2026-11-08', 'published' => true, 'minOsVersion' => '12', 'maxOsVersion' => '14')),
    )));
    $plugin = repo_market::byId(123);
    check($plugin->getMinJeedomVersion() === '6.0', 'The REST installation preview lost the minimum Core version');
    check($plugin->getParameters('os::stable::min') === '12' && $plugin->getParameters('os::stable::max') === '14', 'The REST installation preview lost the OS bounds');
    resetState();
    $synchronizationDue = new ReflectionMethod('marketClient', 'synchronizationDue');
    $synchronizationDue->setAccessible(true);
    marketClient::request('GET', '/me');
    check(config::byKey('market::lastCommunication') !== '' && $synchronizationDue->invoke(null),
        'An authenticated Market call postponed the first telemetry synchronization');
    com_http::$replies = array(array(200, array('boxId' => 7, 'url' => '')));
    check(repo_market::synchronize() === 'ok' && !$synchronizationDue->invoke(null)
        && strpos(end(com_http::$calls)->url, '/boxes/7/telemetry') !== false,
        'Successful telemetry did not record its own synchronization time');
    $previousSynchronization = time() - 86400;
    config::save('market::lastSynchronization', $previousSynchronization);
    marketClient::request('GET', '/me');
    check($synchronizationDue->invoke(null), 'A recent Market call postponed telemetry after 24 hours');
    com_http::$replies = array(array(503, ''));
    failWith(503, function () { repo_market::synchronize(); });
    check(config::byKey('market::lastSynchronization') === $previousSynchronization && $synchronizationDue->invoke(null),
        'Failed telemetry postponed the next synchronization');
    resetState();
    $token = 'box_a_7_' . str_repeat('a', 64);
    $renewed = 'box_a_7_' . str_repeat('c', 64);
    check(repo_market::getCloudToken() === $token && !com_http::$calls, 'Cloud did not reuse the Market bearer locally');
    config::save('market::username', 'other');
    failWith(401, function () { repo_market::getCloudToken(); });
    resetState();
    $session = config::byKey('market::restSession');
    $session['expiresAt'] = 0;
    config::save('market::restSession', $session);
    check(repo_market::getCloudToken() === $renewed && repo_market::getCloudToken() === $renewed, 'Expired Market bearer was not renewed and reused');
    check(count(com_http::$calls) === 1 && strpos(com_http::$calls[0]->url, '/box-sessions/refresh') !== false, 'Cloud made a request beyond the normal Market refresh');
    $invalidBoxToken = array(401, array('error' => array('code' => 'invalid_box_token', 'message' => 'Session rejected')));
    resetState();
    com_http::$replies = array($invalidBoxToken, array(401, '<html>Gateway authentication failed</html>'), array(401, ''));
    failWith(502, function () { marketClient::request('GET', '/me'); });
    check(marketClient::connected() && config::byKey('market::restSession')['accessToken'] === $token && log::$entries === array(),
        'An intermediary HTTP 401 invalidated the Market session');
    check(message::$entries === array(), 'An intermediary HTTP 401 created a reconnection message');
    $failedRefresh = com_http::$calls[1];
    $replayedRefresh = com_http::$calls[2];
    check($failedRefresh->headers === $replayedRefresh->headers && $failedRefresh->options[CURLOPT_POSTFIELDS] === $replayedRefresh->options[CURLOPT_POSTFIELDS],
        'The immediate renewal retry changed the attempt key or refresh token');
    check(repo_market::getCloudToken() === $renewed && end(com_http::$calls)->headers === $failedRefresh->headers,
        'A later request did not resume an interrupted renewal');

    resetState();
    com_http::$replies = array($invalidBoxToken, array(504, ''), array(200, array('boxId' => 7, 'accessToken' => $renewed,
        'refreshToken' => 'box_r_7_' . str_repeat('d', 64), 'expiresIn' => 86400)), array(200, array('id' => 123)));
    check(marketClient::request('GET', '/me') === array('id' => 123) && count(com_http::$calls) === 4,
        'The Market request did not recover after a lost renewal response');
    check(com_http::$calls[1]->headers === com_http::$calls[2]->headers && in_array('Authorization: Bearer ' . $renewed, com_http::$calls[3]->headers, true),
        'Recovery did not use the same attempt key and the renewed bearer');

    resetState();
    com_http::$replies = array($invalidBoxToken, $invalidBoxToken);
    failWith(401, function () { marketClient::request('GET', '/me'); });
    check(config::byKey('market::restSession') === array('rejected' => true)
        && repo_market::connectionStatus() === array('connected' => false, 'login' => 'alice', 'reason' => 'session_rejected'),
        'An explicit rejection did not remove the tokens and preserve the status reason');
    check(count(log::$entries) === 1 && log::$entries[0][0] === 'market' && strpos(log::$entries[0][2], 'invalid_box_token') !== false,
        'The rejection was not logged in the Market log');
    failWith(401, function () { repo_market::getCloudToken(); });
    check(count(message::$entries) === 1 && message::$entries[0][0] === 'market' && message::$entries[0][3] === 'restReconnection'
        && strpos(message::$entries[0][2], '#updatetab') !== false, 'The rejection did not produce a single actionable message');
    check(repo_market::disconnect() === array('connected' => false, 'login' => 'alice', 'revoked' => true),
        'Explicit disconnection did not clear the rejection status');
    check(message::$entries === array(), 'Explicit disconnection did not clear the reconnection message');
    resetState();
    $session = config::byKey('market::restSession');
    $session['expiresAt'] = 0;
    config::save('market::restSession', $session);
    com_http::$replies = array($invalidBoxToken);
    check(repo_market::disconnect()['revoked'] && message::$entries === array(), 'Signing out with a rejected token created a message');
    resetState();
    marketClient::applyBoxInformation(array('username' => 'Alice'));
    check(marketClient::connected() && config::byKey('market::username') === 'Alice', 'Canonicalizing a login disconnected the box');
    check(repo_market::getCloudToken() === $token && !com_http::$calls, 'Canonical login forced another Market authentication');
    config::save('market::lastSynchronization', time());
    repo_market::disconnect();
    check(config::byKey('market::restSession') === '' && !marketClient::connected(), 'Disconnect retained the shared bearer');
    check($synchronizationDue->invoke(null), 'Disconnect retained the synchronization time');
    failWith(401, function () { repo_market::getCloudToken(); });
    resetState();
    config::save('market::lastSynchronization', time());
    message::add('market', 'Previous reconnection notice', '', 'restReconnection');
    $open = new ReflectionMethod('marketClient', 'openSession'); $open->setAccessible(true); $open->invoke(null, 'alice', 'fixture-password', '');
    check(repo_market::getCloudToken() === $renewed && count(com_http::$calls) === 1, 'Reconnection did not replace the shared bearer');
    check(message::$entries === array(), 'Successful login did not clear the reconnection message');
    check($synchronizationDue->invoke(null), 'A new session inherited the last synchronization of the previous session');
    resetState();
    config::save('service::cloud::url', 'https://cloud.example.test/');
    $quota = array('kind' => 'requests', 'unit' => 'requests', 'total' => 30, 'remaining' => 0, 'windowSeconds' => 86400);
    $storage = array('kind' => 'storage', 'unit' => 'bytes', 'total' => 3221225472, 'remaining' => null);
    $services = array(array('id' => 'notif', 'quotas' => array($quota)), array('id' => 'webdav', 'quotas' => array($storage)),
        array('id' => 'saint', 'quotas' => array()));
    $remoteServices = $services;
    $remoteServices[0]['token'] = 'private-remote-field';
    $remoteServices[0]['quotas'][0]['detail'] = 'private-remote-field';
    com_http::$replies = array(array(200, array('state' => 'ok', 'services' => $remoteServices)));
    check(repo_market::cloudServices() === $services, 'Cloud catalog lost quota information or exposed extra fields');
    $http = com_http::$calls[0];
    check(count(com_http::$calls) === 1 && $http->url === 'https://cloud.example.test/me/services'
        && $http->options[CURLOPT_CUSTOMREQUEST] === 'GET' && in_array('Authorization: Bearer ' . $token, $http->headers, true),
        'Cloud catalog must reuse the authenticated Market transport');
    com_http::$replies = array(array(200, array('state' => 'ok', 'services' => array('id' => 'invalid'))));
    failWith(0, function () { repo_market::cloudServices(); });
    com_http::$replies = array(array(401, 'remote-private-response'));
    failWith(401, function () { repo_market::cloudServices(); });
    resetState(); check(repo_market::backup_list() === array('new.gpg', 'old.gpg'), 'WebDAV listing/isolation/order changed');
    foreach (com_http::$calls as $request) if (strpos($request->url, 'backup.example.test') !== false) {
        check(!$request->ssl && in_array('Authorization: Bearer ' . $token, $request->headers, true), 'Backup does not verify TLS/use Bearer');
    }
    resetState();
    com_http::$replies = array(array(207, dav()), array(207, dav(array('old.gpg' => null))));
    $backups = repo_market::backup_list_details();
    check(array_column($backups, 'name') === array('new.gpg', 'old.gpg'), 'Detailed listing changed order or isolation');
    check(array_column($backups, 'size') === array(3000000000, null), 'Cloud file sizes were lost or missing sizes became zero');
    check(count(com_http::$calls) === 2, 'Cloud file sizes triggered additional WebDAV requests');
    com_http::$calls = array();
    com_http::$replies = array(array(401, ''));
    failWith(401, function () { repo_market::backup_list(); });
    check(count(com_http::$calls) === 1 && repo_market::getCloudToken() === $token, 'Cloud 401 triggered a Market refresh');
    check(message::$entries === array(), 'A Cloud 401 created a Market reconnection message');
    com_http::$replies = array(array(403, 'private-cloud-response'));
    failWith(403, function () { repo_market::backup_list(); }); check(!com_http::$replies, '403 response not consumed');
    com_http::$replies = array(array(207, ''), array(207, '<broken>'));
    failWith(0, function () { repo_market::backup_list(); });
    com_http::$replies = array(array(404, ''), array(405, ''), array(207, dav()));
    repo_market::backup_createFolderIsNotExist(); check(!com_http::$replies, 'Concurrent MKCOL recovery changed');
    config::save('service::backup::url', 'http://backup.example.test'); failWith(0, function () { repo_market::backup_list(); });
    resetState();
    $cloudRequest = new ReflectionMethod('marketClient', 'cloudRequest'); $cloudRequest->setAccessible(true);
    repo_market::getCloudToken(); com_http::$replies = array(array(200, array('state' => 'ok')));
    $body = $cloudRequest->invoke(null, 'POST', 'https://monitoring.example.test/service/monitoring', '{"health":[]}', 60, null, null, array('Content-Type: application/json'));
    $last = end(com_http::$calls);
    check(json_decode($body, true)['state'] === 'ok' && $last->options[CURLOPT_POSTFIELDS] === '{"health":[]}', 'Monitoring payload changed');
    check(in_array('Authorization: Bearer ' . $token, $last->headers, true) && !$last->ssl, 'Monitoring retained the historical account key');
    check(!isset(repo_market::connectionStatus()['monitoring']), 'Monitoring advertised without the service right');

    $slot = new ReflectionMethod('repo_market', 'monitoringCronSlot');
    $slot->setAccessible(true);
    $delay = new ReflectionMethod('marketClient', 'monitoringCronDelay');
    $delay->setAccessible(true);
    $hardware = jeedom::$hardware;
    $slots = array();
    for ($i = 0; $i < 2000; ++$i) {
        jeedom::$hardware = 'monitoring-fixture-' . $i;
        $offset = $slot->invoke(null);
        $slots[$offset] = true;
        if ($i === 0) {
            $schedule = repo_market::monitoringCronSchedule();
            check($offset === $slot->invoke(null) && $schedule === intdiv($offset, 60) . '-59/5 * * * *',
                'The dedicated cron minute must match the fixed box slot');
        }
    }
    jeedom::$hardware = $hardware;
    check(count($slots) === 300 && min(array_keys($slots)) === 0 && max(array_keys($slots)) === 299,
        'Monitoring slots must span all five minutes');
    foreach (array('12:02:03' => 14, '12:07:03' => 14, '12:07:18' => 0, '12:08:00' => 0) as $time => $expected) {
        check($delay->invoke(null, 137, new DateTimeImmutable('2026-11-24 ' . $time)) === $expected,
            'Unexpected monitoring delay at ' . $time);
    }
    check(!method_exists('repo_market', 'cron5'), 'The shared cron must no longer send monitoring reports');

    config::save('service::monitoring::enable', 1);
    config::save('service::monitoring::url', 'https://monitoring.example.test');
    $receipt = '2026-11-24T09:00:00Z';
    $paused = array('state' => 'ok', 'enabled' => false, 'lastReceivedAt' => $receipt);
    com_http::$replies = array(array(503, ''));
    failWith(503, function () { repo_market::monitoring(false); });
    check(repo_market::connectionStatus()['monitoring']['enabled'], 'A failed suspension stopped the heartbeat');
    com_http::$replies = array(array(200, $paused));
    check(repo_market::monitoring(false)['lastReceivedAt'] === $receipt, 'Missing Cloud receipt');
    $last = end(com_http::$calls);
    check($last->options[CURLOPT_CUSTOMREQUEST] === 'PUT' && json_decode($last->options[CURLOPT_POSTFIELDS], true)
        === array('enabled' => false, 'hwkey' => jeedom::getHardwareKey()), 'Suspension did not identify the box');
    check(!repo_market::connectionStatus()['monitoring']['enabled'], 'A confirmed suspension was lost');
    com_http::$replies = array(array(200, array('state' => 'ok', 'channels' => array(array('channel' => 'mail', 'status' => 'accepted')))));
    check(repo_market::testMonitoring('mail')['channels'][0]['status'] === 'accepted', 'Manual test failed during suspension');
    $last = end(com_http::$calls);
    check(json_decode($last->options[CURLOPT_POSTFIELDS], true)['channel'] === 'mail', 'Notification test ignored its channel');
    $paused['enabled'] = true;
    com_http::$replies = array(array(200, $paused));
    check(repo_market::monitoring(true)['enabled'], 'A confirmed resume did not enable reporting');
    config::save('market::cloud::monitoring::disable', 1);
    $paused['enabled'] = false;
    com_http::$replies = array(array(200, $paused));
    repo_market::cronMonitoring();
    check(end(com_http::$calls)->options[CURLOPT_CUSTOMREQUEST] === 'PUT', 'Legacy opt-out was not sent to the Cloud');
    $callCount = count(com_http::$calls);
    repo_market::cronMonitoring();
    check(count(com_http::$calls) === $callCount, 'An opted-out box sent another request');
    com_http::$replies = array(array(200, array_replace($paused, array('enabled' => true))));
    repo_market::monitoring(true);
    com_http::$replies = array(array(200, array('state' => 'ok', 'receivedAt' => $receipt)));
    marketClient::cronMonitoring(0);
    $last = end(com_http::$calls);
    check($last->url === 'https://monitoring.example.test/service/monitoring'
        && $last->options[CURLOPT_CUSTOMREQUEST] === 'POST'
        && repo_market::connectionStatus()['monitoring']['lastReceivedAt'] === $receipt,
        'The monitoring cron did not post and record the Cloud receipt');
    $download = $directory . '/streamed';
    com_http::$replies = array(array(200, 'valid encrypted body'));
    $cloudRequest->invoke(null, 'GET', 'https://backup.example.test/webdav/alice/My%20box/file.gpg', null, 30, null, $download);
    check(file_get_contents($download) === 'valid encrypted body', 'Download did not stream the response body');
    com_http::$replies = array(array(403, 'private error body'));
    failWith(403, function () use ($cloudRequest, $download) { $cloudRequest->invoke(null, 'GET', 'https://backup.example.test/webdav/alice/My%20box/file.gpg', null, 30, null, $download); });
    check(!file_exists($download), 'Failed download was retained as a backup');
    resetState(); $file = $directory . '/backup with spaces.tar.gz'; file_put_contents($file, str_repeat('test-data', 4096));
    repo_market::backup_send($file); check(!is_file($file . '.gpg'), 'Encrypted upload temporary file retained');
    repo_market::backup_restore('backup with spaces.tar.gz.gpg');
    if (!getenv('JEEDOM_TEST_FAKE_GPG')) check(file_get_contents($directory . '/cloud-backup with spaces.tar.gz') === file_get_contents($file), 'GPG backup round trip changed its contents');
    check(count(glob($directory . '/gnupg-*')) === 0 && count(glob($directory . '/cloud-download-*')) === 0, 'Restore retained temporary material');
    repo_market::backup_clean($file);
    $deletes = array_filter(com_http::$calls, function ($http) { return $http->options[CURLOPT_CUSTOMREQUEST] === 'DELETE'; });
    check(count($deletes) === 1 && strpos(end($deletes)->url, '/My%20box/old.gpg') !== false, 'Cleanup escaped the current folder or order');
    com_shell::$fail = true; failWith(0, function () use ($file) { repo_market::backup_send($file); }); com_shell::$fail = false;
    check(count(glob($directory . '/gnupg-*')) === 0, 'Failed encryption retained a shared GPG directory');
    failWith(0, function () { repo_market::backup_restore('../unsafe.gpg'); });
    // Separate PHP workers share only on-disk configuration and the existing flock.
    resetState();
    $session = config::byKey('market::restSession');
    $session['expiresAt'] = 0;
    config::save('market::restSession', $session);
    putenv('JEEDOM_CLOUD_TEST_DIRECTORY=' . $directory);
    $php = array(PHP_BINARY, '-n', '-d', 'extension_dir=' . ini_get('extension_dir'), '-d', 'extension=curl');
    // JSON is a separate module on PHP 7.4 and built in from PHP 8 onward.
    if (is_file(ini_get('extension_dir') . '/json.' . PHP_SHLIB_SUFFIX)) {
        $php[] = '-d'; $php[] = 'extension=json';
    }
    $workers = array();
    for ($i = 0; $i < 3; ++$i) {
        $process = proc_open(array_merge($php, array(__FILE__, 'worker')), array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('pipe', 'w')), $pipes);
        fclose($pipes[0]); $workers[] = array($process, $pipes);
    }
    foreach ($workers as $worker) {
        $output = stream_get_contents($worker[1][1]); $errors = stream_get_contents($worker[1][2]); fclose($worker[1][1]); fclose($worker[1][2]);
        $exit = proc_close($worker[0]);
        check($exit === 0 && $output === $renewed, 'Concurrent worker failed: exit=' . $exit . ' ' . $errors . ' stdout=' . $output);
    }
    check(file_get_contents($directory . '/issued') === '1', 'Parallel workers refreshed the Market session more than once');
    echo 'OK Market Cloud: ' . $checks . " checks (sessions, refresh, flock, WebDAV, streaming, cleanup and GPG)\n";
} finally { rrmdir($directory); }
