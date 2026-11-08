<?php
/** Standalone REST integration suite: real Slim routes/Auth/JSON, isolated native services. */
if (PHP_SAPI !== 'cli') throw new RuntimeException('Run directly with php tests/rest/plugins.php.');
require dirname(__DIR__, 2) . '/vendor/autoload.php';
error_reporting(E_ALL & ~E_DEPRECATED); // Existing session.sid_length setting is deprecated on PHP 8.4.

use Jeedom\Api\Rest\App;
use Jeedom\Api\Rest\Session;
use Slim\Psr7\Factory\ServerRequestFactory;

$testRoot = sys_get_temp_dir() . '/jeedom-plugin-rest-' . bin2hex(random_bytes(8));
mkdir($testRoot);
ini_set('session.save_path', $testRoot);
register_shutdown_function(static function () use ($testRoot): void {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($testRoot, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($iterator as $file) $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
    rmdir($testRoot);
});
class config {
    public static array $values = ['core' => ['market::enable' => 1, 'name' => 'Maison', 'enableQueue' => 1]];
    public static function byKey($key, $plugin = 'core', $default = '', $refresh = false) { return self::$values[$plugin][$key] ?? $default; }
    public static function save($key, $value, $plugin = 'core') { self::$values[$plugin][$key] = $value; }
}
class user {
    public static bool $enabled = true;
    public function __construct(private int $id) {}
    public static function byHash($key) { return match ($key) { 'admin' => new self(1), 'member' => new self(2), default => null }; }
    public static function byId($id) { return in_array($id, [1, 2], true) ? new self($id) : null; }
    public function getId() { return $this->id; }
    public function getProfils() { return $this->id === 1 ? 'admin' : 'user'; }
    public function getEnable() { return self::$enabled ? 1 : 0; }
    public function isValidAndEnabled() { return self::$enabled; }
    public function getOptions($key, $default = '') { return $default; }
}
class jeedom {
    public static function version() { return trim(file_get_contents(dirname(__DIR__, 2) . '/core/config/version')); }
    public static function getHardwareName() { return 'docker'; }
    public static function getTmpFolder($name) { global $testRoot; $path = $testRoot . '/' . $name; if (!is_dir($path)) mkdir($path); return $path; }
}
class system {
    public static function getOsVersion() { return '13'; }
    public static function getDistrib() { return 'debian'; }
}
class log { public static function add(...$args) {} public static function audit(...$args) {} }
class network { public static function getUserLocation() { return 'internal'; } }
class eqLogic {}
class fixture extends eqLogic {}
class plugin {
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
    public function getPathImgIcon() { return 'core/img/no-image-plugin.png'; }
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
}
class update {
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
    public function deleteObjet() { unlink(plugin::getPathById($this->values['logicalId'])); $this->remove(); }
    public function doUpdate() {
        self::$installs++;
        if (self::$duringInstall) (self::$duringInstall)();
        if (self::$fail) throw new RuntimeException('Téléchargement refusé https://market.example.org/?password=secret&username=fixture&other=ok');
        manifest($this->values['logicalId']);
        config::save('active', 1, $this->values['logicalId']);
        $this->values['localVersion'] = '2026-11-08';
    }
}
class repo_market {
    public static int $reads = 0;
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
    private int $id = 0;
    public string $state = 'stop';
    public bool $running = false;
    public array $arguments = [];
    public function __call($method, $args) {}
    public function setArguments($args) { $this->arguments = $args; }
    public function save() { $this->id = count(self::$items) + 1; self::$items[$this->id] = $this; }
    public function start() { $this->state = 'starting'; }
    public function getId() { return $this->id; }
    public static function byId($id) { return self::$items[$id] ?? null; }
    public function getState() { return $this->state; }
    public function running() { return $this->running; }
}
$demo = false;
function unautorizedInDemo($actor = null) { global $demo; if (!$actor instanceof user) throw new RuntimeException('Acteur de la requête absent'); if ($demo) throw new RuntimeException('Mode démo'); }
function manifest($id) { $path = plugin::getPathById($id); if (!is_dir(dirname($path))) mkdir(dirname($path), 0775, true); file_put_contents($path, json_encode(['id' => $id])); }
require dirname(__DIR__, 2) . '/core/class/pluginInstallation.class.php';
manifest('fixture');
$app = App::create();
$checks = 0;
function check($condition, $message) { global $checks; $checks++; if (!$condition) throw new RuntimeException($message); }
function call($method, $path, $status = 200, $body = null, $token = 'admin', $headers = [], $cookies = []) {
    global $app;
    $request = (new ServerRequestFactory())->createServerRequest($method, 'http://localhost/api/v1' . $path);
    $query = parse_url($path, PHP_URL_QUERY); if ($query) { parse_str($query, $params); $request = $request->withQueryParams($params); }
    if ($body !== null) { $request->getBody()->write(json_encode($body)); $request = $request->withHeader('Content-Type', 'application/json'); }
    if ($token !== null) $request = $request->withHeader('Authorization', 'Bearer ' . $token);
    foreach ($headers as $name => $value) $request = $request->withHeader($name, $value);
    $response = $app->handle($request->withCookieParams($cookies));
    $raw = (string) $response->getBody();
    check($response->getStatusCode() === $status, "$method $path: expected $status, got " . $response->getStatusCode() . ': ' . $raw);
    check(!isset($GLOBALS['_USER_GLOBAL']), 'REST user leaked between requests');
    return $raw !== '' ? json_decode($raw, true, 512, JSON_THROW_ON_ERROR) : null;
}
function runJob($operation) {
    $queue = queue::byId(pluginInstallation::latest($operation['pluginId'])['queueId']);
    $queue->state = 'run'; $queue->running = true;
    pluginInstallation::execute($operation['id']);
    $queue->running = false; unset(queue::$items[$queue->getId()]);
}
$key = ['Idempotency-Key' => 'request-key-00000001'];
check(jeedom::version() === '5.0.0', 'Wrong Core version');
call('GET', '/plugins', 401, token: null);
call('GET', '/plugins', 200, token: 'member');
call('GET', '/plugins?enabled=nonsense', 422);
$detail = call('GET', '/plugins/fixture?full=true');
check($detail['usedSpace'] === 1234 && $detail['description'] === 'Description', 'Full plugin metadata incorrect');
check($detail['logs'] === ['fixture'] && $detail['links']['changelog'] === null && $detail['links']['issue'] === null, 'Unsafe plugin metadata exposed');
check($detail['update'] === null && $detail['compatible'], 'Local plugin without update not supported');
foreach (['/plugins/fixture', '/plugins/fixture/dependencies', '/plugins/fixture/daemon', '/plugins/fixture/community-post', '/plugins/installation?marketId=123&version=stable', '/plugins/fixture/installation'] as $path) call('GET', $path, 403, token: 'member');
foreach (['/plugins/absent', '/plugins/..%2Fpasswd'] as $path) call('GET', $path, 404);
call('GET', '/plugins/fixture?full=invalid', 422);
call('PATCH', '/plugins/fixture', body: ['isEnable' => true, 'functionality' => ['cron' => false], 'heartbeatDelay' => 5, 'heartbeatRestartDaemon' => true, 'logLevel' => 'debug']);
check(config::byKey('active', 'fixture') === 1 && config::byKey('functionality::cron::enable', 'fixture') === 0, 'Native activation/options not saved');
foreach ([['isEnable' => 1], ['heartbeatDelay' => -1], ['heartbeatDelay' => '5'], ['logLevel' => null], ['apiKey' => 'secret'], ['functionality' => ['health' => false]], ['functionality' => ['unknown' => true]], ['isEnable' => false, 'logLevel' => 'invalid']] as $invalid) call('PATCH', '/plugins/fixture', 422, $invalid);
check(config::byKey('active', 'fixture') === 1, 'Invalid request partly applied');
$demo = true;
call('PATCH', '/plugins/fixture', 403, ['isEnable' => false]);
call('POST', '/plugins/fixture/dependencies/install', 403, (object) []);
call('POST', '/plugins/installations', 403, ['marketId' => 123, 'version' => 'stable'], headers: $key);
$demo = false;
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
call('GET', '/plugins/installation?marketId=123&version=beta');
check(update::$installs === 0 && queue::$items === [], 'GET prepared an installation with side effects');
foreach (['', '?marketId=123', '?marketId=123&version=pro', '?marketId=abc&version=stable', '?marketId=2147483648&version=stable'] as $query) call('GET', '/plugins/installation' . $query, 422);
repo_market::$purchase = false; call('GET', '/plugins/installation?marketId=123&version=stable', 403); repo_market::$purchase = true;
repo_market::$allowed = ['stable']; call('GET', '/plugins/installation?marketId=123&version=beta', 403); repo_market::$allowed[] = 'beta';
repo_market::$published = 0; call('GET', '/plugins/installation?marketId=123&version=stable', 409); repo_market::$published = 1;
repo_market::$minimum = '6.0'; call('GET', '/plugins/installation?marketId=123&version=stable', 409); repo_market::$minimum = '4.4';
repo_market::$parameters = ['os::stable::min' => '14']; call('GET', '/plugins/installation?marketId=123&version=stable', 409); repo_market::$parameters = [];
repo_market::$hardware = ['docker' => 0]; call('GET', '/plugins/installation?marketId=123&version=stable', 409); repo_market::$hardware = [];
repo_market::$type = 'service'; call('GET', '/plugins/installation?marketId=123&version=stable', 404); repo_market::$type = 'plugin';
repo_market::$logicalId = '../bad'; call('GET', '/plugins/installation?marketId=123&version=stable', 422); repo_market::$logicalId = 'newplugin';
call('POST', '/plugins/installations', 422, ['marketId' => 123, 'version' => 'stable']);
call('POST', '/plugins/installations', 422, ['marketId' => '123', 'version' => 'stable'], headers: $key);
call('POST', '/plugins/installations', 422, ['marketId' => 123, 'version' => 'stable', 'url' => 'https://attacker.invalid'], headers: $key);
$operation = call('POST', '/plugins/installations', 202, ['marketId' => 123, 'version' => 'beta'], headers: $key);
check($operation['version'] === 'beta' && array_intersect_key($operation, array_flip(['queueId', 'userId', 'requestKey'])) === [], 'Wrong public operation projection');
check(update::$installs === 0, 'POST did not queue the installation');
check(call('POST', '/plugins/installations', 202, ['marketId' => 123, 'version' => 'beta'], headers: $key)['id'] === $operation['id'] && count(queue::$items) === 1, 'Duplicate request created another task');
call('POST', '/plugins/installations', 409, ['marketId' => 123, 'version' => 'stable'], headers: $key);
call('POST', '/plugins/installations', 409, ['marketId' => 123, 'version' => 'beta'], headers: ['Idempotency-Key' => 'request-key-00000002']);
call('GET', '/plugins/installations/' . $operation['id']);
call('GET', '/plugins/newplugin/installation');
update::$duringInstall = static function () use ($key, $operation): void {
    check(call('GET', '/plugins/installations/' . $operation['id'])['state'] === 'running', 'Running state not visible');
    call('POST', '/plugins/installations', 202, ['marketId' => 123, 'version' => 'beta'], headers: $key);
    call('POST', '/plugins/installations', 409, ['marketId' => 123, 'version' => 'beta'], headers: ['Idempotency-Key' => 'request-key-00000003']);
};
runJob($operation); update::$duringInstall = null;
check(call('GET', '/plugins/installations/' . $operation['id'])['state'] === 'succeeded', 'Successful installation not visible');
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
$failed = call('GET', '/plugins/installations/' . $operation['id']);
check($failed['state'] === 'failed' && !str_contains(json_encode($failed), 'secret'), 'Failure hidden or credentials exposed');
check(update::byTypeAndLogicalId('plugin', 'newplugin')->getConfiguration('version') === 'stable', 'Failed install changed installed branch');
check(config::byKey('active', 'newplugin') === 1, 'Failed install changed active state');
$operation = call('POST', '/plugins/installations', 202, ['marketId' => 123, 'version' => 'stable'], headers: ['Idempotency-Key' => 'request-key-00000006']);
repo_market::$purchase = false; runJob($operation); repo_market::$purchase = true;
check(call('GET', '/plugins/installations/' . $operation['id'])['state'] === 'failed', 'Worker did not revalidate Market rights');
$operation = call('POST', '/plugins/installations', 202, ['marketId' => 123, 'version' => 'stable'], headers: ['Idempotency-Key' => 'request-key-00000007']);
user::$enabled = false; runJob($operation); user::$enabled = true;
check(call('GET', '/plugins/installations/' . $operation['id'])['state'] === 'failed', 'Worker accepted revoked administrator');
$operation = call('POST', '/plugins/installations', 202, ['marketId' => 123, 'version' => 'stable'], headers: ['Idempotency-Key' => 'request-key-00000008']);
queue::$items = [];
check(call('GET', '/plugins/installations/' . $operation['id'])['state'] === 'failed', 'Lost task reported as successful');
call('GET', '/plugins/installations/invalid', 404);
Session::open((new ServerRequestFactory())->createServerRequest('GET', 'http://localhost/'));
Session::login(user::byId(1)); $csrf = Session::csrfToken(); $cookies = [Session::name() => session_id()]; Session::close();
call('PATCH', '/plugins/fixture', 403, ['isEnable' => false], token: null, cookies: $cookies);
call('PATCH', '/plugins/fixture', body: ['isEnable' => false], token: null, cookies: $cookies, headers: ['X-CSRF-Token' => $csrf]);
call('DELETE', '/plugins/fixture', 403, token: 'member');
call('DELETE', '/plugins/fixture', 204);
check(!is_file(plugin::getPathById('fixture')), 'Native plugin removal not used');
echo "OK $checks REST plugin checks on PHP " . PHP_VERSION . "\n";
