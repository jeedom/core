<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use stdClass;

final class McpController
{
    private const MODERN = '2026-07-28';
    private const LEGACY = ['2025-11-25', '2025-06-18'];
    private const VERSION_KEY = 'io.modelcontextprotocol/protocolVersion';
    private const SERVER_KEY = 'io.modelcontextprotocol/serverInfo';
    private const MAX_REQUEST_BYTES = 1048576;
    private const CALLS_PER_MINUTE = 120;

    public function handle(Request $request, Response $response): Response
    {
        $response = $response->withHeader('Cache-Control', 'no-store');
        $this->requireOrigin($request);
        if (!$this->acceptsMcp($request)) {
            return self::error($response, 406, -32600, 'Accept doit autoriser application/json et text/event-stream.');
        }
        $retry = $this->retryAfter($request->getAttribute('restSessionId'));
        if ($retry > 0) {
            return self::error($response->withHeader('Retry-After', (string) $retry), 429, -32000, 'Limite de débit MCP atteinte.');
        }
        try {
            $message = Json::body($request, self::MAX_REQUEST_BYTES, true);
        } catch (ApiError $error) {
            $code = $error->getPrevious() instanceof \JsonException ? -32700 : -32600;
            return self::error($response, $error->status, $code, $error->getMessage());
        }
        $id = $message['id'] ?? null;
        $method = $message['method'] ?? null;
        $params = array_key_exists('params', $message) ? $message['params'] : new stdClass();
        $notification = !array_key_exists('id', $message);
        if (($message['jsonrpc'] ?? null) !== '2.0' || !is_string($method) || $method === ''
            || (!$notification && !is_string($id) && !is_int($id))
            || !$params instanceof stdClass || array_key_exists('result', $message) || array_key_exists('error', $message)) {
            return self::error($response, 400, -32600, 'Requête JSON-RPC invalide.');
        }

        $meta = $params->_meta ?? null;
        $bodyVersion = $meta instanceof stdClass ? ($meta->{self::VERSION_KEY} ?? null) : null;
        $headerVersion = $request->getHeaderLine('MCP-Protocol-Version');
        if ($bodyVersion !== null || $headerVersion === self::MODERN
            || $request->hasHeader('Mcp-Method') || $request->hasHeader('Mcp-Name')) {
            if (!is_string($bodyVersion) || $bodyVersion === '' || $bodyVersion !== $headerVersion
                || $request->getHeaderLine('Mcp-Method') !== $method
                || ($method === 'tools/call' && !$this->nameMatches($request, $params->name ?? null))) {
                return self::error($response, 400, -32020, 'En-têtes MCP manquants ou incohérents.', $id);
            }
            if ($headerVersion !== self::MODERN) {
                return self::unsupported($response, $id, $headerVersion);
            }
            $modern = true;
        } else {
            $modern = false;
            if ($headerVersion !== '' && !in_array($headerVersion, self::LEGACY, true)) {
                return self::unsupported($response, $id, $headerVersion);
            }
            if ($method === 'server/discover' || ($headerVersion === '' && !in_array($method, ['initialize', 'notifications/initialized'], true))) {
                return self::error($response, 400, -32020, 'En-tête MCP-Protocol-Version manquant.', $id);
            }
        }

        if ($notification) {
            return !$modern && $method === 'notifications/initialized'
                ? $response->withStatus(202)
                : self::error($response, 400, -32600, 'Notification MCP non prise en charge.');
        }

        $server = ['name' => 'Jeedom', 'version' => (string) \jeedom::version()];
        $capabilities = ['tools' => new stdClass()];
        switch ($method) {
            case 'server/discover':
                $result = [
                    'supportedVersions' => [self::MODERN, ...self::LEGACY],
                    'capabilities' => $capabilities,
                    '_meta' => [self::SERVER_KEY => $server],
                ];
                break;
            case 'initialize':
                if ($modern) {
                    return self::error($response, 404, -32601, 'Méthode MCP inconnue.', $id);
                }
                $requested = $params->protocolVersion ?? null;
                if (!is_string($requested)) {
                    return self::error($response, 400, -32602, 'Version MCP manquante.', $id);
                }
                $result = [
                    'protocolVersion' => in_array($requested, self::LEGACY, true) ? $requested : self::LEGACY[0],
                    'serverInfo' => $server,
                    'capabilities' => $capabilities,
                ];
                break;
            case 'ping':
                $result = [];
                break;
            case 'tools/list':
                $result = ['tools' => McpTools::list()];
                break;
            case 'tools/call':
                $name = $params->name ?? null;
                $arguments = property_exists($params, 'arguments') ? $params->arguments : new stdClass();
                if (!is_string($name) || !McpTools::has($name) || !$arguments instanceof stdClass) {
                    return self::error($response, 400, -32602, 'Outil MCP inconnu ou arguments invalides.', $id);
                }
                $result = McpTools::call($request, $name, (array) $arguments);
                break;
            default:
                return self::error($response, 404, -32601, 'Méthode MCP inconnue.', $id);
        }
        if ($modern) {
            $result['resultType'] = 'complete';
        }
        return Json::respond($response, ['jsonrpc' => '2.0', 'id' => $id, 'result' => (object) $result]);
    }

    private function acceptsMcp(Request $request): bool
    {
        $accepted = [];
        foreach (explode(',', strtolower($request->getHeaderLine('Accept'))) as $entry) {
            [$mime, $options] = array_pad(explode(';', trim($entry), 2), 2, '');
            if (!preg_match('/(?:^|;)\s*q\s*=\s*0(?:\.0*)?\s*(?:;|$)/', $options)) {
                $accepted[] = trim($mime);
            }
        }
        return in_array('application/json', $accepted, true) && in_array('text/event-stream', $accepted, true);
    }

    private function requireOrigin(Request $request): void
    {
        if (!$request->hasHeader('Origin')) {
            return;
        }
        $origin = $request->getHeaderLine('Origin');
        foreach (['internal', 'external'] as $location) {
            $url = parse_url(\network::getNetworkAccess($location));
            if (!isset($url['scheme'], $url['host']) || !in_array($url['scheme'], ['http', 'https'], true)) {
                continue;
            }
            $defaultPort = $url['scheme'] === 'https' ? 443 : 80;
            $port = $url['port'] ?? $defaultPort;
            $allowed = $url['scheme'] . '://' . strtolower($url['host']) . ($port === $defaultPort ? '' : ':' . $port);
            if ($origin === $allowed) {
                return;
            }
        }
        throw new ApiError(403, 'origin_invalid', 'Origine de la requête non autorisée.');
    }

    private function nameMatches(Request $request, mixed $name): bool
    {
        $header = $request->getHeaderLine('Mcp-Name');
        if (str_starts_with($header, '=?base64?') && str_ends_with($header, '?=')) {
            $header = base64_decode(substr($header, 9, -2), true);
        }
        return is_string($name) && $name !== '' && $header === $name;
    }

    private function retryAfter(string $sessionId): int
    {
        $key = 'api::mcp::rate::' . $sessionId;
        $file = \jeedom::getTmpFolder('rest-mcp') . '/' . substr(hash('sha256', $sessionId), 0, 2) . '.lock';
        $lock = @fopen($file, 'c');
        if ($lock === false) {
            throw new ApiError(503, 'mcp_unavailable', 'Limiteur MCP indisponible.');
        }
        try {
            if (!flock($lock, LOCK_EX)) {
                throw new ApiError(503, 'mcp_unavailable', 'Limiteur MCP indisponible.');
            }
            $now = microtime(true);
            $calls = array_values(array_filter((array) \cache::byKey($key)->getValue([]), static fn ($time) => $time > $now - 60));
            if (count($calls) >= self::CALLS_PER_MINUTE) {
                return max(1, (int) ceil($calls[0] + 60 - $now));
            }
            $calls[] = $now;
            \cache::set($key, $calls, 61);
            return 0;
        } finally {
            fclose($lock);
        }
    }

    private static function unsupported(Response $response, string|int|null $id, string $version): Response
    {
        return self::error($response, 400, -32022, 'Version MCP non prise en charge.', $id, [
            'supported' => [self::MODERN, ...self::LEGACY], 'requested' => $version,
        ]);
    }

    private static function error(Response $response, int $status, int $code, string $message, string|int|null $id = null, array $data = []): Response
    {
        $error = ['code' => $code, 'message' => $message];
        if ($data !== []) {
            $error['data'] = $data;
        }
        return Json::respond($response, ['jsonrpc' => '2.0', 'id' => $id, 'error' => $error], $status);
    }
}
