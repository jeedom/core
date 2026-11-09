<?php
/** Real Slim/Auth/JSON routes and operation service; native updater is isolated. */
if (PHP_SAPI !== 'cli') throw new RuntimeException('CLI only.');
require dirname(__DIR__, 2) . '/vendor/autoload.php';
require __DIR__ . '/operations-fixtures.php';
use Jeedom\Api\Rest\App;
use Slim\Psr7\Factory\ServerRequestFactory;

$root = sys_get_temp_dir() . '/jeedom-rest-updates-' . bin2hex(random_bytes(8));
mkdir($root);
register_shutdown_function(static function () use ($root) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $file) $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
    rmdir($root);
});
class config {
    public static array $values = [];
    public static function byKey($key, $plugin = 'core', $default = '', $refresh = false) { return self::$values[$plugin][$key] ?? $default; }
    public static function save($key, $value, $plugin = 'core') { self::$values[$plugin][$key] = $value; }
}
class user {
    public static bool $enabled = true;
    public function __construct(private int $id) {}
    public static function byHash($key) { return match ($key) { 'admin' => new self(1), 'member' => new self(2), default => null }; }
    public static function byId($id) { return new self($id); }
    public function getId() { return $this->id; }
    public function getEnable() { return 1; }
    public function getProfils() { return $this->id === 1 ? 'admin' : 'user'; }
    public function isValidAndEnabled() { return self::$enabled; }
}
class jeedom {
    public static function version() { return '5.0.0'; }
    public static function getTmpFolder($name) { global $root; $path = $root . '/' . $name; if (!is_dir($path)) mkdir($path); return $path; }
    public static function getDocUrl(...$args) { return 'https://doc.jeedom.com/changelog'; }
}
class system {
    public static bool $nativeBusy = false;
    public static bool $coreFails = false;
    public static bool $relaunch = false;
    public static bool $pendingRelaunch = false;
    public static array $commands = [];
    public static function ps(...$args) {
        if (self::$pendingRelaunch) {
            self::$pendingRelaunch = false;
            file_put_contents(log::getPathToLog('update'), "[END UPDATE SUCCESS]\n", FILE_APPEND);
            return [['pid' => 2]];
        }
        return self::$nativeBusy ? [['pid' => 1]] : [];
    }
    public static function php($arguments) {
        self::$commands[] = $arguments;
        if (str_contains($arguments, 'install/update.php')) { update::$executed[] = 'core'; }
        if (str_contains($arguments, 'install/update.php')) {
            self::$pendingRelaunch = self::$relaunch;
            file_put_contents(log::getPathToLog('update'), self::$coreFails ? "[END UPDATE ERROR]\n" : (self::$relaunch ? "[PROGRESS][100]\n" : "[PROGRESS][100]\n[END UPDATE SUCCESS]\n"), FILE_APPEND);
        }
    }
}
class log {
    public static function getPathToLog($name) { return jeedom::getTmpFolder('logs') . '/' . $name; }
    public static function clear($name) { file_put_contents(self::getPathToLog($name), ''); }
    public static function add($name, $level, $text) { file_put_contents(self::getPathToLog($name), $text . "\n", FILE_APPEND); }
}
defineRestOperationsFixture('plugin', <<<'PHP'
    public static function getPathById($id) { return jeedom::getTmpFolder('plugins') . '/' . $id . '.json'; }
    public static function listPlugin(...$args) { return array_map(static fn ($id) => new self($id), ['hue', 'weather', 'manual']); }
    public function getId() { return $this->id; }
    public function getCategory() { return 'communication'; }
    public function isActive() { return 1; }
    public function getHasOwnDeamon() { return false; }
    public function __construct(private string $id) {}
    public static function byId($id) { return new self($id); }
    public function getName() { return strtoupper($this->id); }
    public function getPathImgIcon() { return 'https://outside.example/image.png'; }
    public function getChangelog() { return 'javascript:alert(1)'; }
PHP);
defineRestOperationsFixture('update', <<<'PHP'
    public static array $items = [];
    public static array $executed = [];
    public static array $checked = [];
    public static ?int $failure = null;
    public function __construct(private int $id, private string $type, private string $logicalId, private string $status = 'update', private bool $disabled = false) {}
    public static function all() { return self::$items; }
    public static function byTypeAndLogicalId($type, $id) { foreach (self::$items as $item) if ($item->getType() === $type && $item->getLogicalId() === $id) return $item; return null; }
    public static function byId($id) { foreach (self::$items as $item) if ($item->getId() === $id) return $item; return null; }
    public static function checkAllUpdate($filter, $findNew = true, $saveLastCheck = true) {
        self::$checked[] = [$filter, $saveLastCheck];
        if ($saveLastCheck) config::save('update::lastCheck', '2026-11-09 08:30:00');
    }
    public static function refreshUpdateMessage() {}
    public function getId() { return $this->id; }
    public function getType() { return $this->type; }
    public function getLogicalId() { return $this->logicalId; }
    public function getName() { return $this->logicalId; }
    public function getSource() { return 'market'; }
    public function getConfiguration($key, $default = '') { return $key === 'doNotUpdate' ? (int) $this->disabled : 'beta'; }
    public function getLocalVersion() { return '1'; }
    public function getRemoteVersion() { return '2'; }
    public function getUpdateDate() { return null; }
    public function getStatus() { return $this->status; }
    public function doUpdate() { self::$executed[] = $this->id; if (self::$failure === $this->id) throw new RuntimeException('https://user:secret@example.org/?token=secret'); }
PHP);
class queue {
    public static array $items = [];
    public static function all() { return self::$items; }
}
class repo_market { public static function downloadObject($update) {} }
$demo = false;
function unautorizedInDemo($actor = null) { global $demo; if (!$actor instanceof user || $demo) throw new RuntimeException('Mode démo'); }
update::$items = [new update(1, 'core', 'jeedom'), new update(2, 'plugin', 'hue'), new update(3, 'plugin', 'weather'),
    new update(4, 'plugin', 'held', 'hold'), new update(5, 'plugin', 'blocked', 'update', true), new update(6, 'os', 'debian')];
foreach (['hue', 'weather', 'held', 'blocked', 'manual'] as $id) file_put_contents(plugin::getPathById($id), '{}');
$app = App::create(); $checks = 0;
function check($condition, $message) { global $checks; $checks++; if (!$condition) throw new RuntimeException($message); }
function call($method, $path, $status = 200, $body = null, $token = 'admin', $key = 'request-key-00000001') {
    global $app;
    $request = (new ServerRequestFactory())->createServerRequest($method, 'http://localhost/api/v1' . $path);
    if ($body !== null) { $request->getBody()->write(json_encode($body)); $request = $request->withHeader('Content-Type', 'application/json'); }
    if ($token !== null) $request = $request->withHeader('Authorization', 'Bearer ' . $token);
    if ($key !== null) $request = $request->withHeader('Idempotency-Key', $key);
    $response = $app->handle($request);
    $raw = (string) $response->getBody();
    check($response->getStatusCode() === $status, "$method $path expected $status got " . $response->getStatusCode() . ': ' . $raw);
    return json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
}
function resetOperation() { config::$values['core']['rest::updateOperation'] = null; }

foreach (['/plugins/management', '/updates/operations/latest'] as $path) { call('GET', $path, 401, token: null); call('GET', $path, 403, token: 'member'); }
foreach (['/updates/check', '/updates/operations'] as $path) call('POST', $path, 403, ['ids' => [2]], 'member');
$catalog = call('GET', '/plugins/management');
check($catalog['total'] === 3 && $catalog['core']['update']['id'] === 1, 'Installed inventory or Core missing');
$manual = array_values(array_filter($catalog['items'], fn ($item) => $item['id'] === 'manual'))[0];
check($manual['management']['update'] === null, 'Manual plugin without update must remain visible');
$job = call('POST', '/updates/check', 202); update::executeOperation($job['id']);
check(update::$checked === [['core', false], ['plugin', true]], 'Check must include only Core and plugins and record completion once');
check(call('GET', '/plugins/management')['lastCheck'] === '2026-11-09 08:30:00', 'Last check date not visible in inventory');
resetOperation(); system::$commands = [];
foreach ([[], ['ids' => []], ['ids' => [1, 1]], ['ids' => ['1']], ['ids' => [true]], ['ids' => [[1]]], ['ids' => [0]], ['ids' => [2], 'os' => true]] as $body) call('POST', '/updates/operations', 422, $body);
call('POST', '/updates/operations', 422, ['ids' => [2]], key: null);
foreach ([4, 5, 6, 999] as $id) call('POST', '/updates/operations', 409, ['ids' => [$id]]);
$demo = true; call('POST', '/updates/operations', 403, ['ids' => [2]]); call('POST', '/updates/check', 403); $demo = false;
system::$nativeBusy = true; call('POST', '/updates/operations', 409, ['ids' => [2]]); system::$nativeBusy = false;
$job = call('POST', '/updates/operations', 202, ['ids' => [1, 2, 3]]);
check(array_column($job['items'], 'id') === [1, 2, 3], 'Core must run before plugins');
check(!isset($job['userId'], $job['requestKey']), 'Private job metadata exposed');
$repeat = call('POST', '/updates/operations', 202, ['ids' => [3, 2, 1]]);
check($job['id'] === $repeat['id'] && count(system::$commands) === 1, 'Retry launched twice');
call('POST', '/updates/operations', 409, ['ids' => [2]]);
call('POST', '/updates/operations', 409, ['ids' => [2]], key: 'request-key-00000002');
call('POST', '/updates/check', 409);
try { update::withPluginMutation(static fn () => null); check(false, 'Plugin mutation accepted during a queued update'); } catch (RuntimeException $e) { check($e->getCode() === 409, 'Wrong mutation conflict'); }
update::executeOperation($job['id']);
$finished = call('GET', '/updates/operations/latest')['operation'];
check($finished['state'] === 'succeeded' && count(array_filter($finished['items'], fn ($item) => $item['state'] === 'succeeded')) === 3, 'Batch did not finish');
check(update::$executed === ['core', 2, 3], 'Core did not execute before the selected plugins');
$command = end(system::$commands);
check(str_contains($command, 'core=1 plugins=0') && str_contains($command, 'backup::before=1'), 'Unsafe Core installer options');
check(str_ends_with($command, ' 2>&1 &'), 'Core installer must run without a waiting shell');
call('POST', '/updates/operations', 200, ['ids' => [1, 2, 3]]);
resetOperation(); update::$failure = 2;
$job = call('POST', '/updates/operations', 202, ['ids' => [2, 3]]);
update::executeOperation($job['id']);
$failed = call('GET', '/updates/operations/latest')['operation'];
check($failed['state'] === 'failed' && array_column($failed['items'], 'state') === ['failed', 'skipped'], 'Failure did not stop remaining updates');
check(!str_contains($failed['error'], 'secret'), 'Error exposed credentials');
resetOperation(); update::$failure = null; system::$coreFails = true;
$job = call('POST', '/updates/operations', 202, ['ids' => [1, 2]]); update::executeOperation($job['id']);
$failedCore = call('GET', '/updates/operations/latest')['operation'];
check($failedCore['state'] === 'failed' && array_column($failedCore['items'], 'state') === ['failed', 'skipped'], 'Plugins must not start after Core failure');
resetOperation(); system::$coreFails = false; system::$relaunch = true;
$job = call('POST', '/updates/operations', 202, ['ids' => [1, 2]]); update::executeOperation($job['id']);
check(call('GET', '/updates/operations/latest')['operation']['state'] === 'succeeded' && !system::$pendingRelaunch, 'Native Core relaunch was not followed to completion');
system::$relaunch = false;
resetOperation(); user::$enabled = false;
$job = call('POST', '/updates/operations', 202, ['ids' => [2]]); update::executeOperation($job['id']);
check(call('GET', '/updates/operations/latest')['operation']['state'] === 'failed', 'Revoked worker actor was accepted'); user::$enabled = true;
resetOperation();
$job = call('POST', '/updates/operations', 202, ['ids' => [2]]);
config::$values['core']['rest::updateOperation']['createdAt'] = date('c', time() - 130);
check(call('GET', '/updates/operations/latest')['operation']['state'] === 'failed', 'Unstarted worker remained queued forever');
resetOperation();
$job = call('POST', '/updates/operations', 202, ['ids' => [1]]);
config::$values['core']['rest::updateOperation']['state'] = 'running';
config::$values['core']['rest::updateOperation']['items'][0]['state'] = 'running';
file_put_contents(log::getPathToLog('update'), "[PROGRESS][35]\n");
$lock = fopen(jeedom::getTmpFolder('rest-updates') . '/runner.lock', 'c'); flock($lock, LOCK_EX);
check(call('GET', '/updates/operations/latest')['operation']['progress'] === 35, 'Native Core progress not exposed');
flock($lock, LOCK_UN); fclose($lock);
check(call('GET', '/updates/operations/latest')['operation']['state'] === 'failed', 'Lost running worker not detected');
resetOperation();
update::$items[0] = new update(1, 'core', 'jeedom', 'ok');
update::$items[1] = new update(2, 'plugin', 'hue', 'ok');
call('POST', '/updates/operations', 422, ['ids' => [2], 'force' => 'true']);
call('POST', '/updates/operations', 409, ['ids' => [2]]);
call('POST', '/updates/operations', 409, ['ids' => [2], 'force' => true]); // Disabled repository.
config::save('market::enable', 1);
foreach ([4, 5, 6] as $id) call('POST', '/updates/operations', 409, ['ids' => [$id], 'force' => true]);
$job = call('POST', '/updates/operations', 202, ['ids' => [2, 1], 'force' => true]);
call('POST', '/updates/operations', 409, ['ids' => [1, 2]]);
check(call('POST', '/updates/operations', 202, ['ids' => [1, 2], 'force' => true])['id'] === $job['id'], 'Forced update replay created a new job');
update::$executed = [];
update::executeOperation($job['id']);
check(call('GET', '/updates/operations/latest')['operation']['state'] === 'succeeded' && update::$executed === ['core', 2], 'Reinstall did not use the native Core and plugin update paths');
echo "OK $checks REST update checks on PHP " . PHP_VERSION . "\n";
