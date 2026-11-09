<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class SystemController
{
    public function get(Request $request, Response $response): Response
    {
        self::requireAdmin($request);
        return self::respond($response, [
            'version' => \jeedom::version(),
            'hardware' => \jeedom::getHardwareName(),
            'started' => \jeedom::isStarted(),
        ]);
    }

    public function health(Request $request, Response $response): Response
    {
        self::requireAdmin($request);
        $items = \jeedom::health();
        return self::respond($response, ['items' => $items, 'total' => count($items)]);
    }

    public function reboot(Request $request, Response $response): Response
    {
        self::requireAdmin($request);
        self::requireSudo();
        \jeedom::rebootSystem();
        return $response->withStatus(204);
    }

    public function shutdown(Request $request, Response $response): Response
    {
        self::requireAdmin($request);
        self::requireSudo();
        \jeedom::haltSystem();
        return $response->withStatus(204);
    }

    public function syncTime(Request $request, Response $response): Response
    {
        self::requireAdmin($request);
        \jeedom::forceSyncHour();
        return $response->withStatus(204);
    }

    public function resetTimeReference(Request $request, Response $response): Response
    {
        self::requireAdmin($request);
        \cache::delete('hour');
        return $response->withStatus(204);
    }

    public function consistency(Request $request, Response $response): Response
    {
        self::requireAdmin($request);
        \jeedom::consistency();
        return Json::respond($response, ['log' => 'consistency'], 202);
    }

    public function repairPermissions(Request $request, Response $response): Response
    {
        self::requireAdmin($request);
        self::requireSudo();
        \jeedom::cleanFileSystemRight();
        return $response->withStatus(204);
    }

    public function cleanDatabase(Request $request, Response $response): Response
    {
        self::requireAdmin($request);
        \jeedom::cleanDatabase();
        return Json::respond($response, ['log' => 'cleaningdb'], 202);
    }

    public function databaseSchema(Request $request, Response $response): Response
    {
        self::requireAdmin($request);
        return self::respond($response, ['tables' => (object) \DB::compareDatabase(self::referenceSchema())]);
    }

    public function repairDatabaseSchema(Request $request, Response $response): Response
    {
        self::requireAdmin($request);
        $table = self::textField($request, 'table');
        $schema = self::referenceSchema();
        if ($table !== 'all' && !in_array($table, array_column($schema['tables'], 'name'), true)) {
            throw ApiError::field(422, 'validation_error', 'table', 'Table absente du schéma de référence.');
        }
        \DB::compareAndFix($schema, $table);
        return $response->withStatus(204);
    }

    public function queryDatabase(Request $request, Response $response): Response
    {
        self::requireAdmin($request);
        $sql = self::textField($request, 'sql');
        try {
            $result = \DB::executeConsoleQuery($sql);
        } catch (\InvalidArgumentException $e) {
            throw ApiError::field(422, 'sql_error', 'sql', mb_scrub($e->getMessage(), 'UTF-8'));
        }
        return self::respond($response, $result);
    }

    public function ssh(Request $request, Response $response): Response
    {
        self::requireAdmin($request);
        return self::respond($response, \system::executeConsoleCommand(self::textField($request, 'command')));
    }

    private static function requireAdmin(Request $request): void
    {
        Auth::requireAdmin($request);
        if ((int) $request->getAttribute('user')->getOptions('localOnly', 0) === 1 && \network::getUserLocation() !== 'internal') {
            throw new ApiError(403, 'forbidden', 'Ce compte est limité au réseau local.');
        }
    }

    private static function requireSudo(): void
    {
        if (!\jeedom::isCapable('sudo')) {
            throw new ApiError(409, 'sudo_unavailable', 'Les droits sudo sont indisponibles.');
        }
    }

    private static function textField(Request $request, string $field): string
    {
        $body = Json::body($request);
        $value = $body[$field] ?? null;
        if (array_keys($body) !== [$field] || !is_string($value) || trim($value) === '' || str_contains($value, "\0")) {
            throw ApiError::field(422, 'validation_error', $field, 'Un champ texte non vide est attendu.');
        }
        return $value;
    }

    private static function referenceSchema(): array
    {
        return json_decode(file_get_contents(__DIR__ . '/../../../../install/database.json'), true, 512, JSON_THROW_ON_ERROR);
    }

    private static function respond(Response $response, array $data): Response
    {
        return Json::respond($response->withHeader('Cache-Control', 'private, no-store'), $data, flags: JSON_INVALID_UTF8_SUBSTITUTE);
    }
}
