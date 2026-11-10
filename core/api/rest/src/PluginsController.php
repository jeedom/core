<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Plugin menu metadata stays readable by connected users. Management and Market
 * installation routes require an administrator and reuse the native classes.
 */
final class PluginsController
{
    public function list(Request $request, Response $response): Response
    {
        $query = $request->getQueryParams();
        $enabled = isset($query['enabled']) && is_string($query['enabled']) ? filter_var($query['enabled'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) : null;
        if (isset($query['enabled']) && $enabled === null) {
            throw ApiError::field(422, 'validation_error', 'enabled', 'Un booléen est attendu.');
        }
        $management = filter_var($query['management'] ?? 'false', FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        if ($management === null) self::invalid('management');
        if ($management) Auth::requireAdmin($request);
        $items = self::items($management, $enabled);
        return self::respond($response, ['items' => $items, 'total' => count($items)]);
    }

    /** Installed inventory is authoritative, including plugins without an update row. */
    public function management(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $items = self::items(true);
        $core = \update::byTypeAndLogicalId('core', 'jeedom');
        return self::respond($response, ['items' => $items, 'total' => count($items),
            'core' => ['name' => 'Core Jeedom', 'version' => \jeedom::version(),
                'source' => is_object($core) ? $core->getSource() : \config::byKey('core::repo::provider'),
                'branch' => (string) \config::byKey('core::branch', 'core', 'master'), 'update' => self::updateSummary($core)],
            'lastCheck' => \config::byKey('update::lastCheck', 'core', '') ?: null, 'operation' => \update::latestOperation()]);
    }

    private static function items(bool $management, ?bool $enabled = null): array
    {
        $items = [];
        foreach ((array) \plugin::listPlugin(false, false, true) as $plugin) {
            if (!is_object($plugin)) {
                continue;
            }
            $active = (int) $plugin->isActive() === 1;
            if ($enabled !== null && $active !== $enabled) {
                continue;
            }
            $update = \update::byTypeAndLogicalId('plugin', $plugin->getId());
            $item = self::toArray($plugin, $active, $update ?: null);
            if ($management) $item['management'] = self::summary($plugin, $update ?: null);
            $items[] = $item;
        }
        usort($items, static fn ($a, $b) => strcasecmp($a['name'], $b['name']));
        return $items;
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $full = $request->getQueryParams()['full'] ?? 'false';
        $full = is_string($full) ? filter_var($full, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) : null;
        if ($full === null) throw ApiError::field(422, 'validation_error', 'full', 'Un booléen est attendu.');
        return self::respond($response, self::details(self::find($args['id'], $full)));
    }

    public function repositories(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        return self::respond($response, ['items' => \pluginRepository::all()]);
    }

    public function getConfiguration(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $plugin = self::find($args['id']);
        return self::respond($response, PluginSettings::read($plugin->getId(), PluginSettings::fields($plugin->getId())));
    }

    public function updateConfiguration(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $plugin = self::find($args['id']);
        $changes = Json::body($request, 16384);
        $values = self::mutate($plugin, static function () use ($plugin, $changes): object {
            $id = $plugin->getId();
            $fields = PluginSettings::fields($id);
            PluginSettings::update($id, $fields, $changes);
            return PluginSettings::read($id, $fields);
        });
        return self::respond($response, $values);
    }

    public function icon(Request $request, Response $response, array $args): Response
    {
        $plugin = self::find($args['id']);
        $prefix = 'plugins/' . $plugin->getId() . '/';
        $icon = $plugin->getPathImgIcon();
        $root = realpath(\plugin::getPluginPath($plugin->getId()));
        $path = $root !== false && str_starts_with($icon, $prefix) ? realpath($root . '/' . substr($icon, strlen($prefix))) : false;
        if ($path === false || !str_starts_with($path, $root . '/')
            || !str_ends_with($path, '.png') || !is_file($path) || ($stream = @fopen($path, 'rb')) === false) {
            throw new ApiError(404, 'plugin_icon_not_found', 'Image du plugin introuvable.');
        }
        return $response->withBody(new \Slim\Psr7\Stream($stream))
            ->withHeader('Content-Type', 'image/png')
            ->withHeader('X-Content-Type-Options', 'nosniff')
            ->withHeader('Cache-Control', 'private, no-cache');
    }

    public function uploadArchive(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $files = $request->getUploadedFiles();
        if (array_keys($files) !== ['file'] || !$files['file'] instanceof \Psr\Http\Message\UploadedFileInterface) self::invalid('file');
        return self::respond($response, self::installationCall(static fn () => \pluginRepository::upload($files['file'])), 201);
    }

    public function installFromRepository(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = self::body($request, ['pluginId', 'source', 'configuration']);
        if (!is_string($body['pluginId'] ?? null) || !is_string($body['source'] ?? null) || !is_array($body['configuration'] ?? null)) self::invalid('pluginId/source/configuration');
        $operation = self::installationCall(fn () => \plugin::startRepositoryInstallation($body['pluginId'], $body['source'], $body['configuration'],
            (int) $request->getAttribute('user')->getId(), $request->getHeaderLine('Idempotency-Key')));
        return self::respond($response, self::operation($operation), in_array($operation['state'], ['queued', 'running'], true) ? 202 : 200);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $plugin = self::find($args['id']);
        $body = self::body($request, ['isEnable', 'functionality', 'logLevel', 'heartbeatDelay', 'heartbeatRestartDaemon', 'updatesBlocked']);
        foreach (['isEnable', 'heartbeatRestartDaemon', 'updatesBlocked'] as $field) {
            if (array_key_exists($field, $body) && !is_bool($body[$field])) self::invalid($field);
        }
        if (array_key_exists('heartbeatDelay', $body) && (!is_int($body['heartbeatDelay']) || $body['heartbeatDelay'] < 0 || $body['heartbeatDelay'] > 2147483647)) self::invalid('heartbeatDelay');
        if (isset($body['logLevel']) && !in_array($body['logLevel'], ['default', 'debug', 'info', 'warning', 'error', 'none'], true)) self::invalid('logLevel');
        if (array_key_exists('logLevel', $body) && !is_string($body['logLevel'])) self::invalid('logLevel');
        if (array_key_exists('functionality', $body)) {
            if (!is_array($body['functionality'])) self::invalid('functionality');
            foreach ($body['functionality'] as $key => $value) {
                $supported = $plugin->getFunctionality()[$key] ?? [];
                if (!is_bool($value) || empty($supported['exists']) || empty($supported['controlable'])) self::invalid('functionality');
            }
        }
        self::mutate($plugin, static function () use ($plugin, $body): void {
            $id = $plugin->getId();
            if (isset($body['updatesBlocked'])) self::setUpdatesBlocked($id, $body['updatesBlocked']);
            if (array_key_exists('isEnable', $body) && (bool) $plugin->isActive() !== $body['isEnable']) $plugin->setIsEnable((int) $body['isEnable']);
            foreach ($body['functionality'] ?? [] as $key => $value) \config::save('functionality::' . $key . '::enable', (int) $value, $id);
            if (array_key_exists('heartbeatDelay', $body)) \config::save('heartbeat::delay::' . $id, $body['heartbeatDelay']);
            if (array_key_exists('heartbeatRestartDaemon', $body)) \config::save('heartbeat::restartDeamon::' . $id, (int) $body['heartbeatRestartDaemon']);
            if (isset($body['logLevel'])) {
                $levels = array_fill_keys(['100', '200', '300', '400', '1000', 'default'], 0);
                $levels[self::LOG_LEVELS[$body['logLevel']]] = 1;
                \config::save('log::level::' . $id, $levels);
            }
        });
        return self::respond($response, self::details($plugin));
    }

    private static function setUpdatesBlocked(string $id, bool $blocked): void
    {
        if (!is_file(\plugin::getPathById($id))) throw new ApiError(404, 'plugin_not_found', 'Plugin inconnu.');
        $update = \update::byTypeAndLogicalId('plugin', $id);
        if (!is_object($update)) {
            if (!$blocked) return;
            $update = new \update();
            $update->setType('plugin');
            $update->setLogicalId($id);
            $update->setLocalVersion(date('Y-m-d H:i:s'));
        } elseif (($update->getConfiguration('doNotUpdate', 0) == 1) === $blocked) {
            return;
        }
        $update->setConfiguration('doNotUpdate', (int) $blocked);
        $update->save();
        \event::add('update::refreshUpdateNumber');
        if ($update->getStatus() === 'update') \update::refreshUpdateMessage();
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $plugin = self::find($args['id']);
        self::mutate($plugin, static function () use ($plugin): void {
            $update = \update::byTypeAndLogicalId('plugin', $plugin->getId());
            if (!is_object($update)) {
                $update = new \update();
                $update->setType('plugin');
                $update->setLogicalId($plugin->getId());
                $update->save();
            }
            $update->deleteObjet();
        });
        return $response->withStatus(204);
    }

    public function dependencies(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        return self::respond($response, self::installationCall(static fn () => self::dependencyInfo(self::find($args['id']))));
    }

    public function installDependencies(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        self::body($request, [], true);
        $plugin = self::find($args['id']);
        self::mutate($plugin, static fn () => $plugin->dependancy_install(true));
        return self::respond($response, self::dependencyInfo($plugin), 202);
    }

    public function updateDependencies(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $body = self::body($request, ['auto']);
        if (!is_bool($body['auto'] ?? null)) self::invalid('auto');
        $plugin = self::find($args['id']);
        self::mutate($plugin, static fn () => $plugin->dependancy_changeAutoMode((int) $body['auto']));
        return self::respond($response, self::dependencyInfo($plugin));
    }

    public function daemon(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        return self::respond($response, self::installationCall(static fn () => self::daemonInfo(self::find($args['id']))));
    }

    public function startDaemon(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $body = self::body($request, ['forceRestart'], true);
        if (array_key_exists('forceRestart', $body) && !is_bool($body['forceRestart'])) self::invalid('forceRestart');
        $plugin = self::find($args['id']);
        self::mutate($plugin, static fn () => $plugin->deamon_start($body['forceRestart'] ?? false));
        return self::respond($response, self::daemonInfo($plugin), 202);
    }

    public function stopDaemon(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        self::body($request, [], true);
        $plugin = self::find($args['id']);
        self::mutate($plugin, static fn () => $plugin->deamon_stop());
        return self::respond($response, self::daemonInfo($plugin));
    }

    public function updateDaemon(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $body = self::body($request, ['auto']);
        if (!is_bool($body['auto'] ?? null)) self::invalid('auto');
        $plugin = self::find($args['id']);
        self::mutate($plugin, static fn () => $plugin->deamon_changeAutoMode((int) $body['auto']));
        return self::respond($response, self::daemonInfo($plugin));
    }

    public function communityPost(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        return self::respond($response, self::installationCall(static fn () => self::find($args['id'])->getCommunityPost()));
    }

    public function prepareInstallation(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $query = $request->getQueryParams();
        $id = filter_var($query['marketId'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 2147483647]]);
        if ($id === false || !is_string($query['version'] ?? null)) self::invalid('marketId/version');
        return self::respond($response, self::installationCall(static fn () => \plugin::prepareMarketInstallation($id, $query['version'])));
    }

    public function install(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = self::body($request, ['marketId', 'version']);
        if (!is_int($body['marketId'] ?? null) || !is_string($body['version'] ?? null)) self::invalid('marketId/version');
        $key = $request->getHeaderLine('Idempotency-Key');
        if (!preg_match('/\A[A-Za-z0-9_-]{16,128}\z/', $key)) self::invalid('Idempotency-Key');
        $operation = self::installationCall(fn () => \plugin::startMarketInstallation($body['marketId'], $body['version'], (int) $request->getAttribute('user')->getId(), $key));
        return self::respond($response, self::operation($operation), in_array($operation['state'], ['queued', 'running'], true) ? 202 : 200);
    }

    public function installation(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $operation = \plugin::installationStatus($args['operationId']);
        if ($operation === null) throw new ApiError(404, 'installation_not_found', 'Installation inconnue.');
        return self::respond($response, self::operation($operation));
    }

    public function latestInstallation(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $last = self::installationCall(static fn () => \plugin::latestInstallation($args['id']));
        $operation = $last === null ? null : \plugin::installationStatus($last['id']);
        return self::respond($response, ['operation' => $operation === null ? null : self::operation($operation)]);
    }

    private const LOG_LEVELS = ['debug' => '100', 'info' => '200', 'warning' => '300', 'error' => '400', 'none' => '1000', 'default' => 'default'];

    private static function details(\plugin $plugin): array
    {
        $id = $plugin->getId();
        $update = \update::byTypeAndLogicalId('plugin', $id);
        $functions = [];
        foreach ($plugin->getFunctionality() as $key => $value) {
            $functions[$key] = ['exists' => !empty($value['exists']), 'controllable' => !empty($value['controlable']),
                'enabled' => \config::byKey('functionality::' . $key . '::enable', $id, 1) == 1];
        }
        $levels = \config::byKey('log::level::' . $id, 'core', []);
        if (is_string($levels)) $levels = json_decode($levels, true);
        $level = 'default';
        foreach (self::LOG_LEVELS as $key => $value) if (!empty($levels[$value])) $level = $key;
        return self::toArray($plugin, (bool) $plugin->isActive(), $update ?: null) + [
            'description' => trim(html_entity_decode(strip_tags((string) $plugin->getDescription()), ENT_QUOTES | ENT_HTML5, 'UTF-8')),
            'author' => (string) $plugin->getAuthor(), 'license' => (string) $plugin->getLicense(),
            'minJeedomVersion' => (string) $plugin->getRequire(), 'minOsVersion' => (string) $plugin->getRequireOsVersion(),
            'compatible' => version_compare(\jeedom::version(), (string) $plugin->getRequire(), '>=')
                && (\system::getDistrib() !== 'debian' || !$plugin->getRequireOsVersion() || version_compare(\system::getOsVersion(), (string) $plugin->getRequireOsVersion(), '>=')),
            'configurationPath' => $plugin->getPathToConfigurationById() ?: null,
            'hasDependency' => (bool) $plugin->getHasDependency() || is_file(\plugin::getPluginPath($id) . '/plugin_info/packages.json'),
            'hasOwnDaemon' => (bool) $plugin->getHasOwnDeamon(), 'usedSpace' => is_numeric($plugin->getUsedSpace()) ? (int) $plugin->getUsedSpace() : null,
            'installation' => trim(html_entity_decode(strip_tags((string) $plugin->getInstallation()), ENT_QUOTES | ENT_HTML5, 'UTF-8')),
            'links' => ['documentation' => self::webUrl($plugin->getDocumentation()), 'changelog' => self::webUrl($plugin->getChangelog()),
                'issue' => self::webUrl($plugin->getIssue()),
                'market' => is_object($update) && $update->getSource() === 'market' ? self::webUrl($plugin->getInfo('display')) : null],
            'functionality' => (object) $functions, 'logs' => array_values(array_filter($plugin->getLogList(), self::validLog(...))),
            'logLevel' => $level, 'heartbeatDelay' => (int) \config::byKey('heartbeat::delay::' . $id, 'core', 0),
            'heartbeatRestartDaemon' => \config::byKey('heartbeat::restartDeamon::' . $id, 'core', 0) == 1,
            'update' => is_object($update) ? ['id' => (int) $update->getId(), 'source' => (string) $update->getSource(),
                'branch' => (string) $update->getConfiguration('version', 'stable'), 'status' => (string) $update->getStatus(),
                'localVersion' => $update->getLocalVersion() ?: null, 'remoteVersion' => $update->getRemoteVersion() ?: null,
                'updatedAt' => $update->getUpdateDate() ?: null] : null,
        ];
    }

    private static function summary(\plugin $plugin, ?\update $update): array
    {
        $hasDaemon = (bool) $plugin->getHasOwnDeamon();
        $state = null;
        if ($hasDaemon && $plugin->isActive()) {
            try { $state = (string) ($plugin->deamon_info()['state'] ?? 'unknown'); }
            catch (\Throwable) { $state = 'unknown'; }
        }
        return ['source' => is_object($update) ? (string) $update->getSource() : null,
            'branch' => is_object($update) ? ($update->getConfiguration('version') ?: null) : null,
            'updatedAt' => is_object($update) ? ($update->getUpdateDate() ?: null) : null,
            'updatesBlocked' => is_object($update) && $update->getConfiguration('doNotUpdate', 0) == 1,
            'hasOwnDaemon' => $hasDaemon, 'daemonState' => $state, 'update' => self::updateSummary($update)];
    }

    private static function updateSummary($update): ?array
    {
        if (!is_object($update)) return null;
        $enabled = $update->getType() === 'core' ? \config::byKey('update::allowCore', 'core', 1) == 1 : $update->getConfiguration('doNotUpdate', 0) != 1;
        return ['id' => (int) $update->getId(), 'status' => (string) $update->getStatus(),
            'localVersion' => $update->getLocalVersion() ?: null, 'remoteVersion' => $update->getRemoteVersion() ?: null,
            'updatable' => $enabled && $update->getStatus() === 'update',
            'reinstallable' => \update::canReinstall($update)];
    }

    private static function dependencyInfo(\plugin $plugin): array
    {
        $info = $plugin->dependancy_info();
        return ['state' => (string) ($info['state'] ?? 'nok'), 'auto' => !empty($info['auto']),
            'log' => self::validLog($info['log'] ?? null) ? $info['log'] : null,
            'progression' => isset($info['progression']) && is_numeric($info['progression']) ? (float) $info['progression'] : null,
            'duration' => isset($info['duration']) && is_numeric($info['duration']) ? (float) $info['duration'] : null,
            'lastLaunch' => isset($info['last_launch']) ? (string) $info['last_launch'] : null];
    }

    private static function daemonInfo(\plugin $plugin): array
    {
        $info = $plugin->deamon_info();
        return ['state' => (string) ($info['state'] ?? 'nok'), 'launchable' => (string) ($info['launchable'] ?? 'nok'),
            'launchableMessage' => (string) ($info['launchable_message'] ?? ''), 'auto' => !empty($info['auto']),
            'log' => self::validLog($info['log'] ?? null) ? $info['log'] : null,
            'lastLaunch' => isset($info['last_launch']) ? (string) $info['last_launch'] : null];
    }

    private static function validLog(mixed $value): bool
    {
        return is_string($value) && $value !== 'nok' && $value !== '..' && preg_match('/\A[A-Za-z0-9_.-]+\z/', $value) === 1;
    }

    private static function respond(Response $response, mixed $data, int $status = 200): Response
    {
        return Json::respond($response->withHeader('Cache-Control', 'no-store'), $data, $status);
    }

    private static function webUrl(mixed $value): ?string
    {
        return is_string($value) && filter_var($value, FILTER_VALIDATE_URL)
            && in_array(strtolower((string) parse_url($value, PHP_URL_SCHEME)), ['http', 'https'], true)
            && parse_url($value, PHP_URL_USER) === null && parse_url($value, PHP_URL_PASS) === null ? $value : null;
    }

    private static function find(string $id, bool $full = false): \plugin
    {
        if (!preg_match('/\A[A-Za-z0-9_][A-Za-z0-9_-]{0,126}\z/', $id) || !is_file(\plugin::getPathById($id))) {
            throw new ApiError(404, 'plugin_not_found', 'Plugin inconnu.');
        }
        try {
            $plugin = \plugin::byId($id, $full);
            if ($plugin->getId() !== $id) throw new \RuntimeException('Manifeste incohérent.');
            return $plugin;
        }
        catch (\Throwable) { throw new ApiError(404, 'plugin_not_found', 'Plugin inconnu ou invalide.'); }
    }

    private static function body(Request $request, array $fields, bool $empty = false): array
    {
        $body = Json::body($request);
        if ((!$empty && $body === []) || array_diff(array_keys($body), $fields)) throw new ApiError(422, 'validation_error', 'Champs absents, inconnus ou non modifiables.');
        return $body;
    }

    private static function invalid(string $field): never
    {
        throw ApiError::field(422, 'validation_error', $field, 'Valeur invalide.');
    }

    private static function mutate(\plugin $plugin, callable $action): mixed
    {
        return self::installationCall(static fn () => \plugin::withInstallationLock($plugin->getId(), static function () use ($plugin, $action) {
            \plugin::assertInstallationIdle($plugin->getId());
            return $action();
        }));
    }

    private static function installationCall(callable $action): mixed
    {
        try { return $action(); }
        catch (ApiError $e) { throw $e; }
        catch (\Throwable $e) {
            $status = in_array($e->getCode(), [401, 403, 404, 409, 422, 502, 503], true) ? (int) $e->getCode() : 409;
            throw new ApiError($status, 'plugin_operation_failed', \update::operationErrorMessage($e));
        }
    }

    private static function operation(array $operation): array
    {
        return array_intersect_key($operation, array_flip(['id', 'pluginId', 'marketId', 'source', 'version', 'state', 'createdAt', 'startedAt', 'finishedAt', 'error']));
    }

    private static function toArray(\plugin $plugin, bool $active, ?\update $update): array
    {
        $id = (string) $plugin->getId();
        $icon = (string) $plugin->getPathImgIcon();
        // Module de la nouvelle UI, à part des pages desktop et mobile (décision du 05/11).
        $module = 'plugins/' . $id . '/ui/ui.js';
        $version = is_object($update) ? (string) $update->getLocalVersion() : '';
        return [
            'id' => $id,
            'name' => (string) $plugin->getName(),
            'category' => (string) $plugin->getCategory(),
            'isEnable' => $active,
            'hasEqLogic' => class_exists($id) && is_subclass_of($id, \eqLogic::class),
            'icon' => $icon !== '' && $icon !== 'core/img/no-image-plugin.png' ? $icon : null,
            'uiModule' => $active && is_file(__DIR__ . '/../../../../' . $module) ? $module : null,
            'version' => $version !== '' ? $version : null,
        ];
    }
}

