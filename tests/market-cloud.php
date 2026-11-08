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
class jeedom {
    public static $hardware = 'fixture-hardware-key';
    public static function getHardwareKey() { return self::$hardware; }
    public static function getTmpFolder($type) { global $directory; return $directory; }
    public static function health() { return array(); }
}
class com_shell {
    public static $commands = array();
    public static $fail = false;
    public static function execute($command) {
        self::$commands[] = $command;
        check(strpos($command, 'curl ') === false && strpos($command, 'wget ') === false, 'A Cloud credential reached a command line');
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
    public static $calls = array(), $replies = array(), $cloud = 'a', $archive = 'encrypted-fixture';
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
        } elseif (strpos($this->url, '/cloud-session') !== false) {
            check($this->options[CURLOPT_POSTFIELDS] === '{}', 'Provisioning must use an empty JSON object');
            if (self::$parallel) { file_put_contents($directory . '/issued', '1', FILE_APPEND); usleep(150000); }
            $reply = array(201, array('boxId' => 7, 'tokenType' => 'Bearer', 'accessToken' => str_repeat(self::$cloud, 64), 'expiresIn' => 86400, 'expiresAt' => time() + 86400));
        } elseif (strpos($this->url, '/box-sessions') !== false) {
            $reply = array(201, array('boxId' => 7, 'accessToken' => 'box_a_7_' . str_repeat('c', 64), 'refreshToken' => 'box_r_7_' . str_repeat('d', 64), 'expiresIn' => 3600));
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
function dav() {
    $body = '<d:multistatus xmlns:d="DAV:">';
    foreach (array('old.gpg' => '2025-01-01', 'new.gpg' => '2026-01-01', '../escape.gpg' => '2026-02-01', 'sub/deep.gpg' => '2026-02-01', 'plain.txt' => '2026-02-01') as $name => $date) {
        $body .= '<d:response><d:href>https://untrusted.invalid/webdav/alice/My%20box/' . $name . '</d:href><d:propstat><d:prop><d:resourcetype/><d:getcontentlength>3000000000</d:getcontentlength><d:getlastmodified>' . $date . '</d:getlastmodified></d:prop><d:status>HTTP/1.1 200 OK</d:status></d:propstat></d:response>';
    }
    $body .= '<d:response><d:href>/webdav/bob/Other/foreign.gpg</d:href><d:propstat><d:prop><d:resourcetype/></d:prop><d:status>HTTP/1.1 200 OK</d:status></d:propstat></d:response></d:multistatus>';
    return $body;
}
function resetState() {
    global $directory;
    $context = hash('sha256', "https://market.example.test\0alice\0" . jeedom::getHardwareKey());
    file_put_contents($directory . '/config.json', json_encode(array('market::address' => 'https://market.example.test', 'market::username' => 'alice', 'market::password' => 'private-legacy-hash',
        'market::restSession' => array('boxId' => 7, 'context' => $context, 'accessToken' => 'box_a_7_' . str_repeat('a', 64), 'refreshToken' => 'box_r_7_' . str_repeat('b', 64), 'expiresAt' => time() + 3600),
        'service::backup::url' => 'https://backup.example.test', 'service::backup::enable' => 1, 'market::cloud::backup::name' => 'My box',
        'market::cloud::backup::password' => 'fixture-encryption-password', 'market::cloud::backup::password_confirmation' => 'fixture-encryption-password', 'backup::path' => $directory)));
    com_http::$calls = com_http::$replies = array(); com_http::$cloud = 'a';
}
// Load the source without a configured Jeedom database or installed plugins.
$source = file_get_contents($root . '/core/repo/market.repo.php');
eval('?>' . str_replace("require_once __DIR__ . '/../../core/php/core.inc.php';", '', $source));
if ($child) { com_http::$parallel = true; echo repo_market::getCloudToken(); exit; }
try {
    resetState();
    $token = repo_market::getCloudToken();
    check($token === str_repeat('a', 64) && count(com_http::$calls) === 1, 'Initial token was not provisioned');
    check(repo_market::getCloudToken() === $token && count(com_http::$calls) === 1, 'Valid token was not reused');
    check(config::byKey('market::cloudSession')['expiresAt'] <= time() + 86400, 'Cloud lifetime extended');
    com_http::$cloud = 'b'; $new = repo_market::getCloudToken($token);
    check($new !== $token && count(com_http::$calls) === 2, 'Rejected token was reused');
    check(repo_market::getCloudToken($token) === $new && count(com_http::$calls) === 2, 'Concurrent renewal was not reused');
    $session = config::byKey('market::cloudSession'); $session['expiresAt'] = time() + 30; config::save('market::cloudSession', $session);
    repo_market::getCloudToken(); check(count(com_http::$calls) === 3, 'Near-expiry token not renewed');
    $session['expiresAt'] = time() + 3600; $session['boxId'] = 99; config::save('market::cloudSession', $session);
    repo_market::getCloudToken(); check(count(com_http::$calls) === 4, 'Another box token was reused');
    config::save('market::username', 'other'); failWith(401, function () { repo_market::getCloudToken(); });
    foreach (array(-86400, 86400) as $offset) {
        resetState(); com_http::$replies = array(array(201, array('boxId' => 7, 'tokenType' => 'Bearer', 'accessToken' => $token,
            'expiresIn' => 86400, 'expiresAt' => time() + $offset)));
        check(repo_market::getCloudToken() === $token, 'A Cloud clock offset refused a valid duration');
        check(abs(config::byKey('market::cloudSession')['expiresAt'] - time() - 86400) <= 1, 'The local cache used the Cloud clock');
    }
    resetState(); $session = config::byKey('market::restSession'); $session['expiresAt'] = 0; config::save('market::restSession', $session);
    check(repo_market::getCloudToken() === $token && count(com_http::$calls) === 2, 'Expired Market access was not refreshed');
    resetState(); com_http::$replies = array(array(401, array()), array(201, array('boxId' => 7, 'accessToken' => 'box_a_7_' . str_repeat('c', 64), 'refreshToken' => 'box_r_7_' . str_repeat('d', 64), 'expiresIn' => 3600)));
    check(repo_market::getCloudToken() === $token && count(com_http::$calls) === 3, '401 provisioning recovery failed');
    foreach (array('boxId' => 8, 'tokenType' => 'Basic', 'accessToken' => "private\r\nheader", 'expiresIn' => 86401, 'expiresAt' => 0) as $key => $value) {
        resetState(); $reply = array('boxId' => 7, 'tokenType' => 'Bearer', 'accessToken' => $token, 'expiresIn' => 86400, 'expiresAt' => time() + 86400); $reply[$key] = $value;
        com_http::$replies = array(array(201, $reply)); failWith(0, function () { repo_market::getCloudToken(); });
        check(config::byKey('market::cloudSession') === '', 'Invalid provisioning was stored');
    }
    resetState(); repo_market::getCloudToken(); com_http::$replies = array(array(502, array()));
    failWith(502, function () use ($token) { repo_market::getCloudToken($token); });
    check(config::byKey('market::cloudSession') === '', 'Rejected token retained after provisioning failure');
    resetState(); repo_market::getCloudToken();
    marketClient::applyBoxInformation(array('username' => 'Alice'));
    check(marketClient::connected(), 'Canonicalizing a login disconnected the box');
    check(config::byKey('market::username') === 'Alice' && config::byKey('market::cloudSession') === '', 'Canonical login/cache was not synchronized');
    repo_market::getCloudToken(); check(count(com_http::$calls) === 2, 'Canonical login forced another Market authentication');
    resetState(); repo_market::getCloudToken(); repo_market::disconnect();
    check(config::byKey('market::cloudSession') === '' && !marketClient::connected(), 'Disconnect retained Cloud authorization');
    resetState(); repo_market::getCloudToken();
    $open = new ReflectionMethod('marketClient', 'openSession'); $open->setAccessible(true); $open->invoke(null, 'alice', 'fixture-password', '');
    check(config::byKey('market::cloudSession') === '', 'Reconnection retained previous Cloud authorization');
    resetState(); check(repo_market::backup_list() === array('new.gpg', 'old.gpg'), 'WebDAV listing/isolation/order changed');
    foreach (com_http::$calls as $request) if (strpos($request->url, 'backup.example.test') !== false) {
        check(!$request->ssl && in_array('Authorization: Bearer ' . $token, $request->headers, true), 'Backup does not verify TLS/use Bearer');
    }
    com_http::$calls = array(); com_http::$replies = array(array(401, ''), array(201, array('boxId' => 7, 'tokenType' => 'Bearer', 'accessToken' => str_repeat('b', 64), 'expiresIn' => 86400, 'expiresAt' => time() + 86400)));
    check(repo_market::backup_list() === array('new.gpg', 'old.gpg'), 'Cloud 401 recovery failed');
    check(count(com_http::$calls) === 4, 'Cloud 401 triggered more than one renewal');
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
    com_http::$replies = array(array(401, 'first unauthorized'), array(201, array('boxId' => 7, 'tokenType' => 'Bearer', 'accessToken' => str_repeat('b', 64), 'expiresIn' => 86400, 'expiresAt' => time() + 86400)), array(401, 'second unauthorized'));
    failWith(401, function () use ($cloudRequest) { $cloudRequest->invoke(null, 'GET', 'https://backup.example.test/webdav/alice/My%20box/file.gpg'); });
    check(!com_http::$replies, 'A second Cloud 401 must stop retries');
    $download = $directory . '/streamed';
    com_http::$replies = array(array(401, 'unauthorized body'), array(201, array('boxId' => 7, 'tokenType' => 'Bearer', 'accessToken' => str_repeat('c', 64), 'expiresIn' => 86400, 'expiresAt' => time() + 86400)), array(200, 'valid encrypted body'));
    $cloudRequest->invoke(null, 'GET', 'https://backup.example.test/webdav/alice/My%20box/file.gpg', null, 30, null, $download);
    check(file_get_contents($download) === 'valid encrypted body', 'Download retry retained an error body');
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
    resetState(); putenv('JEEDOM_CLOUD_TEST_DIRECTORY=' . $directory);
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
        check($exit === 0 && $output === $token, 'Concurrent worker failed: exit=' . $exit . ' ' . $errors . ' stdout=' . $output);
    }
    check(file_get_contents($directory . '/issued') === '1', 'Parallel workers provisioned multiple Cloud tokens');
    echo 'OK Market Cloud: ' . $checks . " checks (sessions, refresh, flock, WebDAV, streaming, cleanup and GPG)\n";
} finally { rrmdir($directory); }
