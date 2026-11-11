<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/** HTTP validation only; the native service owns updates and background execution. */
final class UpdatesController
{
    public function coreBranches(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        try {
            $branches = \update::listCoreBranches();
        } catch (\Exception $e) {
            throw new ApiError(502, 'core_branches_unavailable', 'Impossible de récupérer les branches du Core Jeedom.');
        }
        return self::respond($response, ['branches' => $branches]);
    }

    public function check(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = trim((string) $request->getBody()) === '' ? [] : Json::body($request);
        if (array_diff(array_keys($body), ['target'])) {
            throw new ApiError(422, 'validation_error', 'Paramètre de vérification inconnu.');
        }
        $options = [];
        if (array_key_exists('target', $body)) {
            $target = $body['target'];
            $pluginId = is_array($target) ? ($target['pluginId'] ?? null) : null;
            if ($target === ['type' => 'core']) {
                $options['target'] = $target;
            } elseif (is_array($target) && count($target) === 2 && ($target['type'] ?? null) === 'plugin'
                && is_string($pluginId) && preg_match('/\A[A-Za-z0-9_][A-Za-z0-9_-]{0,126}\z/', $pluginId)) {
                $options['target'] = ['type' => 'plugin', 'pluginId' => $pluginId];
            } else {
                throw ApiError::field(422, 'validation_error', 'target', 'Sélectionnez le Core ou un plugin installé.');
            }
        }
        return self::schedule($request, $response, [], 'check', false, $options);
    }

    public function latest(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        return self::respond($response, ['operation' => \update::latestOperation()]);
    }

    public function start(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = Json::body($request);
        $ids = $body['ids'] ?? null;
        $force = $body['force'] ?? false;
        if (array_key_exists('force', $body) && !is_bool($body['force'])) throw ApiError::field(422, 'validation_error', 'force', 'Un booléen est attendu.');
        if (array_diff(array_keys($body), ['ids', 'force', 'backupBefore']) || !is_array($ids) || !array_is_list($ids)
            || count($ids) < 1 || count($ids) > 200 || count(array_unique($ids, SORT_REGULAR)) !== count($ids)) {
            throw ApiError::field(422, 'validation_error', 'ids', 'Sélection invalide (1 à 200 identifiants distincts).');
        }
        foreach ($ids as $id) if (!is_int($id) || $id < 1) throw ApiError::field(422, 'validation_error', 'ids', 'Identifiant invalide.');
        $options = [];
        if (array_key_exists('backupBefore', $body)) {
            if (!is_bool($body['backupBefore'])) {
                throw ApiError::field(422, 'validation_error', 'backupBefore', 'Un booléen est attendu.');
            }
            $options['backupBefore'] = $body['backupBefore'];
        }
        return self::schedule($request, $response, $ids, 'update', $force, $options);
    }

    public function coreMaintenance(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        return self::respond($response, [
            'enabled' => \config::byKey('update::allowCore', 'core', 1) == 1,
            'migrations' => \update::coreMigrations(),
        ]);
    }

    public function startCoreMaintenance(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = Json::body($request);
        $action = $body['action'] ?? null;
        $version = $body['version'] ?? null;
        if (in_array($action, ['force', 'preUpdate'], true) && count($body) === 1) {
            $options = ['action' => $action];
        } elseif ($action === 'reapply' && count($body) === 2 && is_string($version)
            && preg_match('/\A\d{1,8}\.\d{1,8}\.\d{1,8}\z/', $version)) {
            $options = ['action' => 'reapply', 'version' => $version];
        } else {
            throw new ApiError(422, 'validation_error', 'Action de maintenance ou version de migration invalide.');
        }
        return self::schedule($request, $response, [], 'maintenance', false, $options);
    }

    private static function schedule(Request $request, Response $response, array $ids, string $kind, bool $force, array $options): Response
    {
        $operation = self::call(fn () => \update::startOperation($ids, (int) $request->getAttribute('user')->getId(),
            self::key($request), $kind, $force, $options));
        return self::respond($response, $operation, in_array($operation['state'], ['queued', 'running'], true) ? 202 : 200);
    }

    private static function key(Request $request): string
    {
        $key = $request->getHeaderLine('Idempotency-Key');
        if (!preg_match('/\A[A-Za-z0-9_-]{16,128}\z/', $key)) throw ApiError::field(422, 'validation_error', 'Idempotency-Key', 'Clé de requête invalide.');
        return $key;
    }

    private static function call(callable $action): mixed
    {
        try { return $action(); }
        catch (ApiError $e) { throw $e; }
        catch (\Throwable $e) {
            $status = in_array($e->getCode(), [403, 404, 409, 422, 503], true) ? (int) $e->getCode() : 503;
            throw new ApiError($status, 'update_operation_failed', \update::operationErrorMessage($e));
        }
    }

    private static function respond(Response $response, mixed $data, int $status = 200): Response
    {
        return Json::respond($response->withHeader('Cache-Control', 'no-store'), $data, $status);
    }
}
