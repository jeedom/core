<?php
/** Standalone REST integration suite: real Slim routes/Auth/JSON, isolated native services. */
if (PHP_SAPI !== 'cli') throw new RuntimeException('Run directly with php tests/rest/plugins.php.');
require dirname(__DIR__, 2) . '/vendor/autoload.php';
require __DIR__ . '/operations-fixtures.php';

use Jeedom\Api\Rest\App;
use Slim\Psr7\Factory\ServerRequestFactory;

$testRoot = sys_get_temp_dir() . '/jeedom-plugin-rest-' . bin2hex(random_bytes(8));
mkdir($testRoot);
register_shutdown_function(static function () use ($testRoot): void {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($testRoot, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($iterator as $file) $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
    rmdir($testRoot);
});
class config {
    public static array $values = ['core' => ['name' => 'Maison', 'enableQueue' => 1]];
    public static function byKey($key, $plugin = 'core', $default = '', $refresh = false) { return self::$values[$plugin][$key] ?? $default; }
    public static function save($key, $value, $plugin = 'core') { self::$values[$plugin][$key] = $value; }
}
class user {
    public static bool $enabled = true;
    private ?int $enable = null;
    public function __construct(private int $id) {}
    public static function byHash($key) { return match ($key) { 'admin' => new self(1), 'member' => new self(2), default => null }; }
    public static function byId($id) { return in_array($id, [1, 2], true) ? new self($id) : null; }
    public static function byProfils($profile, $enabled) { return [new self(1)]; }
    public function getId() { return $this->id; }
    public function getLogin() { return 'fixture-' . $this->id; }
    public function getProfils() { return $this->id === 1 ? 'admin' : 'user'; }
    public function getEnable() { return $this->enable ?? (self::$enabled ? 1 : 0); }
    public function isValidAndEnabled() { return $this->getEnable() === 1; }
    public function setEnable(int $enable): void { $this->enable = $enable; }
    public function getOptions($key, $default = '') { return $default; }
    public function save(): void {}
}
class session {
    public static function resolve(string $token): ?array {
        return $token === 'browser-token' ? ['user' => user::byId(1), 'session' => [
            'id' => 'browser-id', 'csrf_token' => 'browser-csrf', 'remembered' => 0,
        ]] : null;
    }
    public static function touch(array $row): void {}
    public static function revoke(user $user, string $id): bool { return false; }
}
class jeedom {
    public static function version() { return trim(file_get_contents(dirname(__DIR__, 2) . '/core/config/version')); }
    public static function getHardwareName() { return 'docker'; }
    public static function getTmpFolder($name) { global $testRoot; $path = $testRoot . '/' . $name; if (!is_dir($path)) mkdir($path); return $path; }
}
class system {
    public static function ps(...$args) { return []; }
    public static function getOsVersion() { return '13'; }
    public static function getDistrib() { return 'debian'; }
}
class log { public static function add(...$args) {} public static function audit(...$args) {} }
class network { public static function getUserLocation() { return 'internal'; } }
class eqLogic {}
class fixture extends eqLogic {}
defineRestOperationsFixture('plugin', <<<'PHP'
    public static int $dependencyInstalls = 0;
    public static array $daemon = ['state' => 'nok', 'launchable' => 'ok', 'launchable_message' => '', 'auto' => 1, 'log' => 'fixture', 'last_launch' => '2026-11-08'];
    public static function getPluginPath($id) { return jeedom::getTmpFolder('plugins') . '/' . $id; }
    public static function getPathById($id) { return self::getPluginPath($id) . '/plugin_info/info.json'; }
    public static function byId($id, $full = false) { if (!is_file(self::getPathById($id))) throw new RuntimeException('Absent'); return new self($id, $full); }
    public static function listPlugin(...$args) { return [self::byId('fixture')]; }
    public function __construct(private string $id, private bool $full = false) {}
    public function getId() { return $this->id; }
    public function getName() { return 'Plugin de recette'; }
    public function getCategory() { return 'energy'; }
    public function isActive() { return config::byKey('active', $this->id, 0); }
    public function setIsEnable($state) { config::save('active', $state, $this->id); }
    public function getPathImgIcon() { return is_file(self::getPluginPath($this->id) . '/plugin_info/icon.png') ? 'plugins/' . $this->id . '/plugin_info/icon.png' : 'core/img/no-image-plugin.png'; }
    public function getDescription() { return '<b>Description</b>'; }
    public function getAuthor() { return 'Développeur'; }
    public function getLicense() { return 'AGPL'; }
    public function getRequire() { return '4.4'; }
    public function getRequireOsVersion() { return '12'; }
    public function getPathToConfigurationById() { return ''; }
    public function getHasDependency() { return 1; }
    public function getHasOwnDeamon() { return 1; }
    public function getUsedSpace() { return $this->full ? 1234 : null; }
    public function getInstallation() { return '<p>Installation</p>'; }
    public function getDocumentation() { return 'https://doc.example.org/'; }
    public function getChangelog() { return 'javascript:alert(1)'; }
    public function getIssue() { return 'https://user:secret@example.org/'; }
    public function getFunctionality() { return ['cron' => ['exists' => 1, 'controlable' => 1], 'health' => ['exists' => 1, 'controlable' => 0]]; }
    public function getLogList() { return ['fixture', '../secret', '/etc/passwd']; }
    public function dependancy_info() { return ['state' => self::$dependencyInstalls ? 'in_progress' : 'ok', 'log' => 'fixture_dep', 'auto' => config::byKey('dependancyAutoMode', $this->id, 1), 'progression' => 42, 'duration' => 2]; }
    public function dependancy_install($force) { if (self::$dependencyInstalls) throw new RuntimeException('Déjà en cours', 409); self::$dependencyInstalls++; }
    public function dependancy_changeAutoMode($auto) { config::save('dependancyAutoMode', $auto, $this->id); }
    public function deamon_info() { return self::$daemon; }
    public function deamon_start($force) { self::$daemon['state'] = 'ok'; }
    public function deamon_stop() { self::$daemon['state'] = 'nok'; }
    public function deamon_changeAutoMode($auto) { self::$daemon['auto'] = $auto; }
    public function getCommunityPost() { return ['url' => 'https://community.jeedom.com/new-topic?body=fixture', 'plugin' => $this->getName()]; }
PHP);
defineRestOperationsFixture('update', <<<'PHP'
    public static function listRepo() {
        $fields = [
            'github' => ['user' => ['name' => 'Utilisateur', 'type' => 'input'], 'repository' => ['name' => 'Dépôt', 'type' => 'input'], 'token' => ['name' => 'Token', 'type' => 'input'], 'version' => ['name' => 'Branche', 'type' => 'input', 'default' => 'master']],
            'url' => ['url' => ['name' => 'URL', 'type' => 'input']],
            'file' => ['path' => ['name' => 'Archive', 'type' => 'file']],
        ];
        $repos = [];
        foreach ($fields as $id => $parameters) $repos[$id] = ['name' => $id, 'scope' => ['plugin' => true], 'configuration' => ['parameters_for_add' => $parameters]];
        $repos['samba'] = ['name' => 'Samba', 'scope' => ['plugin' => false, 'backup' => true]];
        $repos['market'] = ['name' => 'Market', 'scope' => ['plugin' => true]];
        return $repos;
    }
    public static array $items = [];
    public static int $installs = 0;
    public static bool $fail = false;
    public static $duringInstall = null;
    private array $values = ['id' => 0, 'type' => 'plugin', 'logicalId' => '', 'source' => 'market', 'name' => ''];
    private array $configuration = [];
    public static function byTypeAndLogicalId($type, $id) { return self::$items[$id] ?? null; }
    public function __call($method, $args) { $key = lcfirst(substr($method, 3)); if (str_starts_with($method, 'set')) $this->values[$key] = $args[0]; else return $this->values[$key] ?? ''; }
    public function getConfiguration($key, $default = '') { return $this->configuration[$key] ?? $default; }
    public function setConfiguration($key, $value) { $this->configuration[$key] = $value; }
    public function save() { $this->values['id'] = $this->values['id'] ?: count(self::$items) + 1; self::$items[$this->values['logicalId']] = $this; }
    public function remove() { unset(self::$items[$this->values['logicalId']]); }
    public function deleteObjet() { if (!$this->values['id']) throw new RuntimeException('Update record must be saved'); unlink(plugin::getPathById($this->values['logicalId'])); $this->remove(); }
    public function doUpdate() {
        self::$installs++;
        if (self::$duringInstall) (self::$duringInstall)();
        if (self::$fail) throw new RuntimeException('Téléchargement refusé https://market.example.org/?password=secret&username=fixture&other=ok');
        manifest($this->values['logicalId']);
        config::save('active', 1, $this->values['logicalId']);
        $this->values['localVersion'] = '2026-11-08';
    }
PHP);
class repo_market {
    public static int $reads = 0;
    public static bool $connected = true;
    public static int $synchronizations = 0;
    public static bool $revoked = true;
    public static function isConnected(): bool { return self::$connected; }
    public static function connectionStatus(): array { return ['connected' => self::$connected, 'login' => 'fixture']; }
    public static function synchronize(): void { self::$synchronizations++; }
    public static function disconnect(): array {
        self::$connected = false;
        return self::connectionStatus() + ['revoked' => self::$revoked];
    }
    public static bool $purchase = true;
    public static array $allowed = ['stable', 'beta'];
    public static int $published = 1;
    public static string $minimum = '4.4';
    public static array $parameters = [];
    public static array $hardware = [];
    public static string $type = 'plugin';
    public static string $logicalId = 'newplugin';
    public static function byId($id) { self::$reads++; if ($id !== 123) throw new RuntimeException('Plugin absent', 404); return new self(); }
    public function getId() { return 123; }
    public function getType() { return self::$type; }
    public function getLogicalId() { return self::$logicalId; }
    public function getName() { return 'Nouveau plugin'; }
    public function getPurchase() { return self::$purchase; }
    public function getAllowVersion() { return self::$allowed; }
    public function getStatus($version) { return self::$published; }
    public function getMinJeedomVersion() { return self::$minimum; }
    public function getParameters($key) { return self::$parameters[$key] ?? ''; }
    public function getHardwareCompatibility() { return self::$hardware; }
    public function getDatetime($version) { return '2026-11-08'; }
}
class queue {
    public static array $items = [];
    public static array $dispatches = [];
    public static bool $failDispatch = false;
    private int $id = 0;
    private string $class;
    private string $function;
    public string $state = 'stop';
    public bool $running = false;
    public array $arguments = [];
    public function __call($method, $args) {}
    public function setClass($class) { $this->class = $class; }
    public function getClass() { return $this->class; }
    public function setFunction($function) { $this->function = $function; }
    public function getFunction() { return $this->function; }
    public static function all() { return self::$items; }
    public function setArguments($args) { $this->arguments = $args; }
    public function save() { $this->id = count(self::$items) + 1; self::$items[$this->id] = $this; }
    public function start() { $this->state = 'starting'; }
    public static function runNext(string $queueId): void {
        self::$dispatches[] = $queueId;
        if (self::$failDispatch) throw new RuntimeException('Worker unavailable');
    }
    public function getId() { return $this->id; }
    public static function byId($id) { return self::$items[$id] ?? null; }
    public function getState() { return $this->state; }
    public function running() { return $this->running; }
}
function manifest($id) { $path = plugin::getPathById($id); if (!is_dir(dirname($path))) mkdir(dirname($path), 0775, true); file_put_contents($path, json_encode(['id' => $id])); }
class utils {
    public static function encrypt($value) { return base64_encode(openssl_encrypt($value, 'aes-256-cbc', str_repeat('k', 32), OPENSSL_RAW_DATA, str_repeat('i', 16))); }
    public static function decrypt($value) { return openssl_decrypt(base64_decode($value), 'aes-256-cbc', str_repeat('k', 32), OPENSSL_RAW_DATA, str_repeat('i', 16)); }
}
require dirname(__DIR__, 2) . '/core/class/pluginRepository.class.php';
manifest('fixture');
$app = App::create();
$checks = 0;
function check($condition, $message) { global $checks; $checks++; if (!$condition) throw new RuntimeException($message); }
function call($method, $path, $status = 200, $body = null, $token = 'admin', $headers = [], $cookies = [], $files = []) {
    global $app;
    $request = (new ServerRequestFactory())->createServerRequest($method, 'http://localhost/api/v1' . $path);
    $query = parse_url($path, PHP_URL_QUERY); if ($query) { parse_str($query, $params); $request = $request->withQueryParams($params); }
    if ($body !== null) { $request->getBody()->write(json_encode($body)); $request = $request->withHeader('Content-Type', 'application/json'); }
    if ($token !== null) $request = $request->withHeader('Authorization', 'Bearer ' . $token);
    if ($files) $request = $request->withUploadedFiles($files);
    foreach ($headers as $name => $value) $request = $request->withHeader($name, $value);
    $response = $app->handle($request->withCookieParams($cookies));
    $raw = (string) $response->getBody();
    check($response->getStatusCode() === $status, "$method $path: expected $status, got " . $response->getStatusCode() . ': ' . $raw);
    check(!isset($GLOBALS['_USER_GLOBAL']), 'REST user leaked between requests');
    if ($response->getHeaderLine('Content-Type') === 'image/png') return $raw;
    return $raw !== '' ? json_decode($raw, true, 512, JSON_THROW_ON_ERROR) : null;
}
function runJob($operation) {
    $queue = queue::byId(plugin::latestInstallation($operation['pluginId'])['queueId']);
    $queue->state = 'run'; $queue->running = true;
    $worker = [$queue->getClass(), $queue->getFunction()];
    $worker(...$queue->arguments);
    $queue->running = false; unset(queue::$items[$queue->getId()]);
}
$key = ['Idempotency-Key' => 'request-key-00000001'];
check(jeedom::version() === '5.0.0', 'Wrong Core version');
call('POST', '/market/connection/synchronize', 403, token: 'member');
call('DELETE', '/market/connection', 403, token: 'member');
check(repo_market::$synchronizations === 0 && repo_market::$connected, 'A non-admin changed the Market connection');
check(call('POST', '/market/connection/synchronize')['connected'] === true && repo_market::$synchronizations === 1, 'Market synchronization not delegated');
repo_market::$revoked = false;
$marketConnection = call('DELETE', '/market/connection');
check($marketConnection['connected'] === false && isset($marketConnection['warning']), 'Local disconnection must report an unconfirmed remote revocation');
check(call('POST', '/market/connection/synchronize', 409)['error']['code'] === 'market_not_connected' && repo_market::$synchronizations === 1, 'Disconnected account was synchronized');
repo_market::$revoked = true;
check(call('DELETE', '/market/connection') === ['connected' => false, 'login' => 'fixture'], 'Disconnection must support retries');
repo_market::$connected = true;
call('GET', '/plugins', 401, token: null);
call('GET', '/plugins', 200, token: 'member');
call('GET', '/plugins?enabled=nonsense', 422);
$detail = call('GET', '/plugins/fixture?full=true');
check($detail['usedSpace'] === 1234 && $detail['description'] === 'Description', 'Full plugin metadata incorrect');
check($detail['logs'] === ['fixture'] && $detail['links']['changelog'] === null && $detail['links']['issue'] === null, 'Unsafe plugin metadata exposed');
check($detail['update'] === null && $detail['compatible'], 'Local plugin without update not supported');
foreach (['/plugins/fixture', '/plugins/fixture/dependencies', '/plugins/fixture/daemon', '/plugins/fixture/community-post', '/plugins/installations/preview?marketId=123&version=stable', '/plugins/fixture/installations/latest'] as $path) call('GET', $path, 403, token: 'member');
foreach (['/plugins/absent', '/plugins/..%2Fpasswd'] as $path) call('GET', $path, 404);
call('GET', '/plugins/fixture?full=invalid', 422);
call('PATCH', '/plugins/fixture', body: ['isEnable' => true, 'functionality' => ['cron' => false], 'heartbeatDelay' => 5, 'heartbeatRestartDaemon' => true, 'logLevel' => 'debug']);
check(config::byKey('active', 'fixture') === 1 && config::byKey('functionality::cron::enable', 'fixture') === 0, 'Native activation/options not saved');
foreach ([['isEnable' => 1], ['heartbeatDelay' => -1], ['heartbeatDelay' => '5'], ['logLevel' => null], ['apiKey' => 'secret'], ['functionality' => ['health' => false]], ['functionality' => ['unknown' => true]], ['isEnable' => false, 'logLevel' => 'invalid']] as $invalid) call('PATCH', '/plugins/fixture', 422, $invalid);
check(config::byKey('active', 'fixture') === 1, 'Invalid request partly applied');
call('GET', '/plugins/fixture/dependencies');
call('PATCH', '/plugins/fixture/dependencies', body: ['auto' => false]);
call('POST', '/plugins/fixture/dependencies/install', 202, (object) []);
call('POST', '/plugins/fixture/dependencies/install', 409, (object) []);
call('PATCH', '/plugins/fixture/dependencies', 422, ['auto' => 1]);
check(call('POST', '/plugins/fixture/daemon/start', 202, ['forceRestart' => true])['state'] === 'ok', 'Daemon start not delegated');
check(call('POST', '/plugins/fixture/daemon/stop', body: (object) [])['state'] === 'nok', 'Daemon stop not delegated');
call('PATCH', '/plugins/fixture/daemon', body: ['auto' => false]);
call('POST', '/plugins/fixture/daemon/start', 422, ['forceRestart' => null]);
call('GET', '/plugins/fixture/community-post');
call('GET', '/plugins/installations/preview?marketId=123&version=beta');
check(update::$installs === 0 && queue::$items === [] && queue::$dispatches === [], 'GET prepared an installation with side effects');
foreach (['', '?marketId=123', '?marketId=123&version=pro', '?marketId=abc&version=stable', '?marketId=2147483648&version=stable'] as $query) call('GET', '/plugins/installations/preview' . $query, 422);
repo_market::$purchase = false; call('GET', '/plugins/installations/preview?marketId=123&version=stable', 403); repo_market::$purchase = true;
repo_market::$allowed = ['stable']; call('GET', '/plugins/installations/preview?marketId=123&version=beta', 403); repo_market::$allowed[] = 'beta';
repo_market::$published = 0; call('GET', '/plugins/installations/preview?marketId=123&version=stable', 409); repo_market::$published = 1;
repo_market::$minimum = '6.0'; call('GET', '/plugins/installations/preview?marketId=123&version=stable', 409); repo_market::$minimum = '4.4';
repo_market::$parameters = ['os::stable::min' => '14']; call('GET', '/plugins/installations/preview?marketId=123&version=stable', 409); repo_market::$parameters = [];
repo_market::$hardware = ['docker' => 0]; call('GET', '/plugins/installations/preview?marketId=123&version=stable', 409); repo_market::$hardware = [];
repo_market::$type = 'service'; call('GET', '/plugins/installations/preview?marketId=123&version=stable', 404); repo_market::$type = 'plugin';
repo_market::$logicalId = '../bad'; call('GET', '/plugins/installations/preview?marketId=123&version=stable', 422); repo_market::$logicalId = 'newplugin';
call('POST', '/plugins/installations', 422, ['marketId' => 123, 'version' => 'stable']);
call('POST', '/plugins/installations', 422, ['marketId' => '123', 'version' => 'stable'], headers: $key);
call('POST', '/plugins/installations', 422, ['marketId' => 123, 'version' => 'stable', 'url' => 'https://attacker.invalid'], headers: $key);
$operation = call('POST', '/plugins/installations', 202, ['marketId' => 123, 'version' => 'beta'], headers: $key);
check($operation['version'] === 'beta' && array_intersect_key($operation, array_flip(['queueId', 'userId', 'requestKey'])) === [], 'Wrong public operation projection');
check(update::$installs === 0, 'POST did not queue the installation');
check(call('POST', '/plugins/installations', 202, ['marketId' => 123, 'version' => 'beta'], headers: $key)['id'] === $operation['id'] && count(queue::$items) === 1, 'Duplicate request created another task');
check(queue::$dispatches === ['rest-plugin-installations'], 'The first confirmation must dispatch the queue once');
call('POST', '/updates/check', 409, headers: $key);
call('POST', '/plugins/installations', 409, ['marketId' => 123, 'version' => 'stable'], headers: $key);
call('POST', '/plugins/installations', 409, ['marketId' => 123, 'version' => 'beta'], headers: ['Idempotency-Key' => 'request-key-00000002']);
call('GET', '/plugins/installations/' . $operation['id'] . '/status');
call('GET', '/plugins/newplugin/installations/latest');
update::$duringInstall = static function () use ($key, $operation): void {
    check(call('GET', '/plugins/installations/' . $operation['id'] . '/status')['state'] === 'running', 'Running state not visible');
    call('POST', '/plugins/installations', 202, ['marketId' => 123, 'version' => 'beta'], headers: $key);
    call('POST', '/plugins/installations', 409, ['marketId' => 123, 'version' => 'beta'], headers: ['Idempotency-Key' => 'request-key-00000003']);
};
runJob($operation); update::$duringInstall = null;
check(call('GET', '/plugins/installations/' . $operation['id'] . '/status')['state'] === 'succeeded', 'Successful installation not visible');
check(update::$installs === 1 && config::byKey('active', 'newplugin') === 0, 'First installation activated the plugin or ran twice');
call('POST', '/plugins/installations', 200, ['marketId' => 123, 'version' => 'beta'], headers: $key);
check(update::$installs === 1 && queue::$items === [], 'Completed request replay installed again');
config::save('active', 1, 'newplugin'); config::save('private-setting', 'retained', 'newplugin');
$operation = call('POST', '/plugins/installations', 202, ['marketId' => 123, 'version' => 'stable'], headers: ['Idempotency-Key' => 'request-key-00000004']);
runJob($operation);
check(config::byKey('active', 'newplugin') === 1 && config::byKey('private-setting', 'newplugin') === 'retained', 'Reinstallation lost configuration or active state');
update::$fail = true;
$operation = call('POST', '/plugins/installations', 202, ['marketId' => 123, 'version' => 'beta'], headers: ['Idempotency-Key' => 'request-key-00000005']);
runJob($operation); update::$fail = false;
$failed = call('GET', '/plugins/installations/' . $operation['id'] . '/status');
check($failed['state'] === 'failed' && !str_contains(json_encode($failed), 'secret'), 'Failure hidden or credentials exposed');
check(update::byTypeAndLogicalId('plugin', 'newplugin')->getConfiguration('version') === 'stable', 'Failed install changed installed branch');
check(config::byKey('active', 'newplugin') === 1, 'Failed install changed active state');
$operation = call('POST', '/plugins/installations', 202, ['marketId' => 123, 'version' => 'stable'], headers: ['Idempotency-Key' => 'request-key-00000006']);
repo_market::$purchase = false; runJob($operation); repo_market::$purchase = true;
check(call('GET', '/plugins/installations/' . $operation['id'] . '/status')['state'] === 'failed', 'Worker did not revalidate Market rights');
$operation = call('POST', '/plugins/installations', 202, ['marketId' => 123, 'version' => 'stable'], headers: ['Idempotency-Key' => 'request-key-00000007']);
user::$enabled = false; runJob($operation); user::$enabled = true;
check(call('GET', '/plugins/installations/' . $operation['id'] . '/status')['state'] === 'failed', 'Worker accepted revoked administrator');
$operation = call('POST', '/plugins/installations', 202, ['marketId' => 123, 'version' => 'stable'], headers: ['Idempotency-Key' => 'request-key-00000008']);
queue::$items = [];
check(call('GET', '/plugins/installations/' . $operation['id'] . '/status')['state'] === 'failed', 'Lost task reported as successful');
call('GET', '/plugins/installations/invalid/status', 404);
$csrf = 'browser-csrf';
$cookies = ['JeedomSession' => 'browser-token'];
call('PATCH', '/plugins/fixture', 401, ['isEnable' => false], token: null, cookies: ['PHPSESSID' => 'browser-token']);
call('PATCH', '/plugins/fixture', 403, ['isEnable' => false], token: null, cookies: $cookies);
call('PATCH', '/plugins/fixture', body: ['isEnable' => false], token: null, cookies: $cookies, headers: ['X-CSRF-Token' => $csrf]);
call('DELETE', '/me/sessions/unknown', 404, token: null, headers: ['X-CSRF-Token' => $csrf], cookies: $cookies);
call('DELETE', '/me/sessions/browser-id', 409, token: null, headers: ['X-CSRF-Token' => $csrf], cookies: $cookies);
$disabled = call('PATCH', '/users/2', body: ['enable' => false], token: null, headers: ['X-CSRF-Token' => $csrf], cookies: $cookies);
check($disabled['enable'] === false, 'REST must disable accounts without using legacy PHP sessions');
call('DELETE', '/plugins/fixture', 403, token: 'member');
call('DELETE', '/plugins/fixture', 204);
check(!is_file(plugin::getPathById('fixture')), 'Native plugin removal not used');
// Direct installation: metadata, rights, validation, queued native updates, replay and archives.
manifest('fixture');
call('GET', '/plugins/fixture/icon', 401, token: null);
call('GET', '/plugins/fixture/icon', 404);
$png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIHWP4z8DwHwAFgAI/ScLbtAAAAABJRU5ErkJggg==');
$icon = dirname(plugin::getPathById('fixture')) . '/icon.png';
file_put_contents($icon, $png);
check(call('GET', '/plugins/fixture/icon', token: null, cookies: $cookies) === $png, 'Plugin image not served for the browser session');
manifest('installations');
file_put_contents(dirname(plugin::getPathById('installations')) . '/icon.png', $png);
check(call('GET', '/plugins/installations/icon') === $png, 'Installation status must not shadow the icon of a plugin named installations');
unlink($icon);
$outside = jeedom::getTmpFolder('plugins') . '/outside.png';
file_put_contents($outside, $png);
symlink($outside, $icon);
call('GET', '/plugins/fixture/icon', 404);
unlink($icon);
call('GET', '/plugins?management=invalid', 422);
call('GET', '/plugins?management=true', 403, token: 'member');
call('GET', '/plugins/repositories', 403, token: 'member');
check(!isset(call('GET', '/plugins')['items'][0]['management']), 'Management metadata leaked to the menu list');
$managed = call('GET', '/plugins?management=true')['items'][0]['management'];
call('GET', '/plugins/management', 403, token: 'member');
$managementPage = call('GET', '/plugins/management');
check($managementPage['core']['version'] === jeedom::version() && $managementPage['operation'] === null, 'Core or resumable operation missing');
check($managementPage['items'][0]['id'] === 'fixture' && array_key_exists('update', $managementPage['items'][0]['management']), 'Installed inventory missing update information');
check($managed['hasOwnDaemon'] && $managed['daemonState'] === null, 'Inactive daemon must not be reported as broken');
$repos = call('GET', '/plugins/repositories')['items'];
check(count($repos) === 4 && $repos[0]['fields'][2]['type'] === 'password' && !$repos[0]['fields'][2]['required'], 'Repository schema or secret field incorrect');
check(array_column($repos, 'id') === ['github', 'url', 'file', 'market'], 'Plugin sources should include Market and exclude backup-only Samba');
repo_market::$connected = false;
check(array_column(call('GET', '/plugins/repositories')['items'], 'id') === ['github', 'url', 'file'], 'Only Market must depend on the account connection');
call('GET', '/plugins/installations/preview?marketId=123&version=stable', 409);
repo_market::$connected = true;
$direct = ['pluginId' => 'directplugin', 'source' => 'github', 'configuration' => ['user' => 'jeedom', 'repository' => 'plugin-test', 'token' => 'github_secret', 'version' => 'main']];
$directKey = ['Idempotency-Key' => 'direct-request-key-0001'];
call('POST', '/plugins/installations/repository', 403, $direct, token: 'member', headers: $directKey);
call('POST', '/plugins/installations/repository', 403, $direct, token: null, cookies: $cookies, headers: $directKey);
call('POST', '/plugins/installations/repository', 422, $direct);
foreach ([['pluginId' => '../bad'], ['source' => 'market'], ['configuration' => ['user' => 'x;touch']], ['configuration' => ['user' => 'jeedom', 'repository' => 'repo', 'version' => '$(cmd)']], ['configuration' => ['user' => 'jeedom', 'repository' => 'repo', 'version' => 'main', 'unknown' => 'value']]] as $change) call('POST', '/plugins/installations/repository', 422, array_replace($direct, $change), headers: $directKey);
$dispatchCount = count(queue::$dispatches);
queue::$failDispatch = true;
$op = call('POST', '/plugins/installations/repository', 202, $direct, token: null, cookies: $cookies, headers: $directKey + ['X-CSRF-Token' => $csrf]);
queue::$failDispatch = false;
check(count(queue::$dispatches) === $dispatchCount + 1 && end(queue::$dispatches) === 'rest-plugin-installations' && $op['state'] === 'queued', 'An immediate launch failure must leave the direct installation queued');
check($op['source'] === 'github' && !str_contains(json_encode($op), 'github_secret') && !str_contains(json_encode(plugin::latestInstallation('directplugin')), 'github_secret'), 'Direct installation exposed plaintext parameters');
check(call('POST', '/plugins/installations/repository', 202, $direct, headers: $directKey)['id'] === $op['id'], 'Direct replay created another job');
call('POST', '/plugins/installations/repository', 409, $direct, headers: ['Idempotency-Key' => 'direct-request-key-0002']);
$different = $direct; $different['configuration']['version'] = 'beta';
call('POST', '/plugins/installations/repository', 409, $different, headers: $directKey);
runJob($op);
check(call('GET', '/plugins/installations/' . $op['id'] . '/status')['state'] === 'succeeded', 'Direct installation failed');
$installed = update::byTypeAndLogicalId('plugin', 'directplugin');
check($installed->getSource() === 'github' && $installed->getConfiguration('version') === 'main' && config::byKey('active', 'directplugin') === 0, 'Native source/branch/activation not preserved');
check(!isset(plugin::latestInstallation('directplugin')['parameters']), 'Completed job retained its encrypted input');
call('POST', '/plugins/installations/repository', 200, $direct, headers: $directKey);
config::save('active', 1, 'directplugin');
$op = call('POST', '/plugins/installations/repository', 202, $different, headers: ['Idempotency-Key' => 'direct-request-key-0003']);
update::$fail = true; runJob($op); update::$fail = false;
check(call('GET', '/plugins/installations/' . $op['id'] . '/status')['state'] === 'failed' && update::byTypeAndLogicalId('plugin', 'directplugin')->getConfiguration('version') === 'main' && config::byKey('active', 'directplugin') === 1, 'Failed direct installation lost previous settings');
$op = call('POST', '/plugins/installations/repository', 202, $direct, headers: ['Idempotency-Key' => 'direct-request-key-0004']);
config::save('github::enable', 0);
runJob($op);
check(call('GET', '/plugins/installations/' . $op['id'] . '/status')['state'] === 'succeeded', 'Legacy source flag blocked a GitHub job');
foreach (['file:///etc/passwd', 'ftp://example.org/a.zip', 'https://user:secret@example.org/a.zip', "https://example.org/a\n.zip"] as $url) call('POST', '/plugins/installations/repository', 422, ['pluginId' => 'urlplugin', 'source' => 'url', 'configuration' => ['url' => $url]], headers: $directKey);
$op = call('POST', '/plugins/installations/repository', 202, ['pluginId' => 'urlplugin', 'source' => 'url', 'configuration' => ['url' => 'https://example.org/a.zip?a=1&b=2']], headers: $directKey);
runJob($op); check(update::byTypeAndLogicalId('plugin', 'urlplugin')->getSource() === 'url', 'URL installation not delegated');
call('POST', '/plugins/installations/repository', 422, ['pluginId' => 'sambaplugin', 'source' => 'samba', 'configuration' => ['path' => 'plugins/archive.zip']], headers: $directKey);
call('POST', '/plugins/installations/repository', 422, ['pluginId' => 'fileplugin', 'source' => 'file', 'configuration' => ['path' => '/etc/passwd']], headers: $directKey);
function archiveFile($id, $extra = []) {
    $path = jeedom::getTmpFolder('incoming') . '/' . bin2hex(random_bytes(8)) . '.zip';
    $zip = new ZipArchive(); $zip->open($path, ZipArchive::CREATE);
    $zip->addFromString('plugin_info/info.json', json_encode(['id' => $id]));
    foreach ($extra as $name => $value) $zip->addFromString($name, $value);
    $zip->close();
    return new \Slim\Psr7\UploadedFile($path, 'plugin.zip', 'application/zip', filesize($path));
}
call('POST', '/plugins/archives', 403, token: 'member');
call('POST', '/plugins/archives', 422);
call('POST', '/plugins/archives', 422, files: ['file' => archiveFile('fileplugin', ['../outside.php' => 'bad'])]);
$archive = call('POST', '/plugins/archives', 201, files: ['file' => archiveFile('fileplugin')]);
check($archive['pluginId'] === 'fileplugin' && !str_contains(json_encode($archive), $testRoot), 'Upload did not read manifest or leaked local path');
call('POST', '/plugins/installations/repository', 422, ['pluginId' => 'otherplugin', 'source' => 'file', 'configuration' => ['path' => $archive['id']]], headers: $directKey);
$fileRequest = ['pluginId' => 'fileplugin', 'source' => 'file', 'configuration' => ['path' => $archive['id']]];
$op = call('POST', '/plugins/installations/repository', 202, $fileRequest, headers: $directKey);
$archivePath = pluginRepository::archivePath($archive['id'], 'fileplugin');
check((fileperms($archivePath) & 0777) === 0600, 'Uploaded ZIP is not private');
runJob($op);
check(call('GET', '/plugins/installations/' . $op['id'] . '/status')['state'] === 'succeeded' && !is_file($archivePath), 'File installation failed or retained ZIP');
call('POST', '/plugins/installations/repository', 200, $fileRequest, headers: $directKey);
echo "OK $checks REST plugin checks on PHP " . PHP_VERSION . "\n";

