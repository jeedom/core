<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/** HTTP validation only; the native service owns updates and background execution. */
final class UpdatesController
{
    public function check(Request $request, Response $response): Response
    {
        self::requireWrite($request);
        if (trim((string) $request->getBody()) !== '' && Json::body($request) !== []) throw new ApiError(422, 'validation_error', 'Aucun paramètre attendu.');
        $operation = self::call(fn () => \updateOperation::start([], (int) $request->getAttribute('user')->getId(), self::key($request), 'check'));
        return self::respond($response, $operation, in_array($operation['state'], ['queued', 'running'], true) ? 202 : 200);
    }

    public function latest(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        return self::respond($response, ['operation' => \updateOperation::latest()]);
    }

    public function start(Request $request, Response $response): Response
    {
        self::requireWrite($request);
        $body = Json::body($request);
        $ids = $body['ids'] ?? null;
        if (array_diff(array_keys($body), ['ids']) || !is_array($ids) || !array_is_list($ids)
            || count($ids) < 1 || count($ids) > 200 || count(array_unique($ids, SORT_REGULAR)) !== count($ids)) {
            throw ApiError::field(422, 'validation_error', 'ids', 'Sélection invalide (1 à 200 identifiants distincts).');
        }
        foreach ($ids as $id) if (!is_int($id) || $id < 1) throw ApiError::field(422, 'validation_error', 'ids', 'Identifiant invalide.');
        $operation = self::call(fn () => \updateOperation::start($ids, (int) $request->getAttribute('user')->getId(), self::key($request)));
        return self::respond($response, $operation, in_array($operation['state'], ['queued', 'running'], true) ? 202 : 200);
    }

    private static function requireWrite(Request $request): void
    {
        Auth::requireAdmin($request);
        try { \unautorizedInDemo($request->getAttribute('user')); }
        catch (\Exception $e) { throw new ApiError(403, 'demo_forbidden', $e->getMessage()); }
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
            throw new ApiError($status, 'update_operation_failed', \pluginInstallation::errorMessage($e));
        }
    }

    private static function respond(Response $response, mixed $data, int $status = 200): Response
    {
        return Json::respond($response->withHeader('Cache-Control', 'no-store'), $data, $status);
    }
}
