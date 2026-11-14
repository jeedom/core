<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class JeegoController
{
    private const MAX_BODY_BYTES = 32768;

    private const SECTIONS = [
        'hostname' => '/host/hostname',
        'time' => '/host/time',
        'network' => '/network/status',
        'network-pending' => '/network/pending',
        'cellular' => '/cellular/status',
    ];

    // Each action defines its path, input fields, and the kind of task it can start.
    private const ACTIONS = [
        'hostname' => ['/host/hostname', ['hostname'], 'host-hostname-configure'],
        'time' => ['/host/time', ['timezone', 'servers'], 'host-time-configure'],
        'time-setup' => ['/host/time/setup', [], 'host-time-setup'],
        'time-sync' => ['/host/time/sync', [], 'host-time-sync'],
        'network-apply' => ['/network/apply', ['interface', 'mode', 'address', 'mask', 'gateway', 'dns', 'action', 'ssid', 'password', 'security'], 'network-change'],
        'network-confirm' => ['/network/confirm', ['id'], null],
        'network-cancel' => ['/network/cancel', ['id'], null],
        'network-setup' => ['/network/setup', [], 'network-manager-install'],
        'network-setup-cancel' => ['/network/setup/cancel', [], 'network-manager-cancel'],
        'wifi-scan' => ['/wifi/scan', ['interface'], 'network-scan'],
        'hotspot-install' => ['/hotspot/install', [], 'network-hotspot-install'],
        'cellular-configure' => ['/cellular/configure', ['modem', 'primary', 'apn', 'pin', 'username', 'password'], 'cellular-configure'],
        'cellular-install' => ['/cellular/install', [], 'cellular-install'],
        'cellular-power' => ['/cellular/power', [], 'cellular-power'],
        'cellular-disable' => ['/cellular/disable', [], 'cellular-disable'],
    ];

    private const IMAGE_ACTIONS = [
        'check' => ['image-check', []],
        'restart' => ['jeedom-restart', []],
        'update' => ['image-update', ['confirm', 'strategy', 'instance_id', 'version']],
        'revert' => ['image-revert', ['confirm', 'strategy', 'instance_id', 'image']],
    ];

    private const SERVICE_ACTIONS = [
        'install' => ['parameters'],
        'start' => [],
        'restart' => [],
        'stop' => ['confirm'],
        'update' => ['confirm'],
        'policy' => ['enabled', 'time', 'stable_hours'],
    ];

    public function management(Request $request, Response $response): Response
    {
        self::requireJeego($request);
        return self::respond($response, self::call('/management', 200));
    }

    public function imageAction(Request $request, Response $response, array $args): Response
    {
        self::requireJeego($request);
        $action = $args['action'];
        [$kind, $fields] = self::IMAGE_ACTIONS[$action] ?? throw new ApiError(404, 'jeego_action_not_found', 'Action sur l’image Jeedom inconnue.');
        return self::startManagement($request, $response, '/management/image/' . $action, $kind, $fields);
    }

    public function serviceAction(Request $request, Response $response, array $args): Response
    {
        self::requireJeego($request);
        $service = self::serviceId($args['service']);
        $action = $args['action'];
        $fields = self::SERVICE_ACTIONS[$action] ?? throw new ApiError(404, 'jeego_action_not_found', 'Action de service Jeego inconnue.');
        return self::startManagement($request, $response, '/management/services/' . $service . '/' . $action, 'managed-service:' . $service . ':' . $action, $fields);
    }

    public function serviceLogs(Request $request, Response $response, array $args): Response
    {
        self::requireJeego($request);
        $service = self::serviceId($args['service']);
        return self::respond($response, self::call('/services/' . $service . '/logs', 200));
    }

    public function dockerAction(Request $request, Response $response, array $args): Response
    {
        self::requireJeego($request);
        $id = $args['id'];
        if (preg_match('/^(?:project-)?[a-f0-9]{64}$/D', $id) !== 1) {
            throw ApiError::field(422, 'validation_error', 'id', 'Identifiant Docker invalide.');
        }
        $action = $args['action'];
        if (!in_array($action, ['update', 'restart'], true)) {
            throw new ApiError(404, 'jeego_action_not_found', 'Action Docker inconnue.');
        }
        return self::startManagement($request, $response, '/management/docker/' . $id . '/' . $action, 'managed-docker:' . $action, ['revision', 'confirm']);
    }

    public function dockerLogs(Request $request, Response $response, array $args): Response
    {
        self::requireJeego($request);
        $id = $args['id'];
        if (preg_match('/^[a-f0-9]{64}$/D', $id) !== 1) {
            throw ApiError::field(422, 'validation_error', 'id', 'Identifiant Docker invalide.');
        }
        return self::respond($response, self::call('/management/docker/' . $id . '/logs', 200));
    }

    private static function serviceId(string $id): string
    {
        if (preg_match('/^[a-z][a-z0-9-]{0,31}$/D', $id) !== 1) {
            throw ApiError::field(422, 'validation_error', 'service', 'Identifiant de service Jeego invalide.');
        }
        return $id;
    }

    private static function startManagement(Request $request, Response $response, string $path, string $kind, array $fields): Response
    {
        $body = Json::body($request, self::MAX_BODY_BYTES, true);
        if (array_diff(array_keys($body), $fields) !== []) {
            throw new ApiError(422, 'validation_error', 'Le corps contient des champs non autorisés pour cette action.');
        }
        $data = self::call($path, 202, $body);
        if (!self::validTaskId($data->id ?? null) || ($data->kind ?? null) !== $kind) {
            throw self::badResponse();
        }
        return self::respond($response->withHeader('Location', '/api/v1/jeego/tasks/' . $data->id), $data, 202);
    }

    public function status(Request $request, Response $response): Response
    {
        self::requireAdmin($request);
        return self::respond($response, ['enabled' => \jeego::isDetected()]);
    }

    public function updateStatus(Request $request, Response $response): Response
    {
        self::requireJeego($request);
        return self::respond($response, self::call('/update/status', 200));
    }

    public function checkUpdate(Request $request, Response $response): Response
    {
        self::requireJeego($request);
        if (Json::body($request, self::MAX_BODY_BYTES, true) !== []) {
            throw new ApiError(422, 'validation_error', 'La vérification des mises à jour Jeego attend un objet vide.');
        }
        return self::respond($response, self::call('/update/check', 202, []), 202);
    }

    public function startUpdate(Request $request, Response $response): Response
    {
        self::requireJeego($request);
        $body = Json::body($request, self::MAX_BODY_BYTES, true);
        $version = $body['version'] ?? null;
        if (count($body) !== 1 || !is_string($version) || $version === '') {
            throw ApiError::field(422, 'validation_error', 'version', 'Indiquez uniquement la version Jeego à installer.');
        }
        $data = self::call('/update', 202, ['version' => $version]);
        return self::respond($response->withHeader('Location', '/api/v1/jeego/update'), $data, 202);
    }

    public function settings(Request $request, Response $response, array $args): Response
    {
        self::requireJeego($request);
        $section = $args['section'];
        $path = self::SECTIONS[$section] ?? throw new ApiError(404, 'jeego_section_not_found', 'Réglage Jeego inconnu.');
        $query = $request->getQueryParams();
        if (array_key_exists('zones', $query)) {
            if ($section !== 'time' || !in_array($query['zones'], ['0', '1'], true)) {
                throw ApiError::field(422, 'validation_error', 'zones', 'Utilisez 0 ou 1 uniquement pour le réglage de l’heure.');
            }
            if ($query['zones'] === '1') {
                $path .= '?zones=1';
            }
        }
        return self::respond($response, self::call($path, 200));
    }

    public function action(Request $request, Response $response, array $args): Response
    {
        self::requireJeego($request);
        $action = $args['action'];
        [$path, $fields, $kind] = self::ACTIONS[$action] ?? throw new ApiError(404, 'jeego_action_not_found', 'Action Jeego inconnue.');
        $body = Json::body($request, self::MAX_BODY_BYTES, true);
        if (array_diff(array_keys($body), $fields) !== []) {
            throw new ApiError(422, 'validation_error', 'Le corps contient des champs non autorisés pour cette action.');
        }
        if ($action === 'network-confirm') {
            // Apache supplies the local socket address. Host and forwarded headers are client input.
            $localIp = $request->getServerParams()['SERVER_ADDR'] ?? null;
            if (!is_string($localIp) || filter_var($localIp, FILTER_VALIDATE_IP) === false) {
                throw new ApiError(409, 'jeego_network_access_required', 'Ouvrez Jeedom depuis l’adresse réseau à confirmer.');
            }
            $body['local_ip'] = $localIp;
        }

        $status = $action === 'network-confirm' ? 200 : 202;
        $data = self::call($path, $status, $body);
        if ($kind !== null) {
            if (!self::validTaskId($data->id ?? null) || ($data->kind ?? null) !== $kind) {
                throw self::badResponse();
            }
            $response = $response->withHeader('Location', '/api/v1/jeego/tasks/' . $data->id);
        }
        return self::respond($response, $data, $status);
    }

    public function task(Request $request, Response $response, array $args): Response
    {
        self::requireJeego($request);
        $id = $args['id'];
        if (!self::validTaskId($id)) {
            throw ApiError::field(422, 'validation_error', 'id', 'Identifiant de tâche Jeego invalide.');
        }
        $data = self::call('/tasks/' . $id, 200);
        if (($data->id ?? null) !== $id) {
            throw self::badResponse();
        }
        $kind = $data->kind ?? null;
        if (!is_string($kind) || !self::canReadTask($kind)) {
            throw new ApiError(404, 'task_not_found', 'Tâche Jeego inconnue.');
        }
        return self::respond($response, $data);
    }

    private static function canReadTask(string $kind): bool
    {
        if (in_array($kind, array_column(self::ACTIONS, 2), true) || in_array($kind, array_column(self::IMAGE_ACTIONS, 0), true)
            || in_array($kind, ['managed-docker:update', 'managed-docker:restart'], true)) {
            return true;
        }
        return preg_match('/^managed-service:[a-z][a-z0-9-]{0,31}:([a-z]+)$/D', $kind, $matches) === 1
            && isset(self::SERVICE_ACTIONS[$matches[1]]);
    }

    private static function requireAdmin(Request $request): void
    {
        Auth::requireAdmin($request);
        if ((int) $request->getAttribute('user')->getOptions('localOnly', 0) === 1 && \network::getUserLocation() !== 'internal') {
            throw new ApiError(403, 'forbidden', 'Ce compte est limité au réseau local.');
        }
    }

    private static function requireJeego(Request $request): void
    {
        self::requireAdmin($request);
        if (!\jeego::isDetected()) {
            throw new ApiError(404, 'jeego_disabled', 'Ce Jeedom ne fonctionne pas en mode Jeego.');
        }
    }

    private static function validTaskId(mixed $id): bool
    {
        return is_string($id) && preg_match('/^[a-f0-9]{32}$/D', $id) === 1;
    }

    private static function call(string $path, int $expectedStatus, ?array $body = null): \stdClass
    {
        try {
            [$status, $data] = \jeego::requestResponse($path, $body);
        } catch (\Exception) {
            throw new ApiError(503, 'jeego_unavailable', 'La connexion à l’API locale Jeego est indisponible.');
        }

        if ($status === $expectedStatus && $data instanceof \stdClass) {
            return $data;
        }
        $error = $data instanceof \stdClass && ($data->error ?? null) instanceof \stdClass ? $data->error : null;
        $code = $error->code ?? null;
        $message = $error->message ?? null;
        if (is_string($message) && $message !== '') {
            if ($status === 400 && in_array($code, ['invalid_request', 'unsupported_action'], true)) {
                throw new ApiError(422, 'validation_error', $message);
            }
            if ($status === 409 && in_array($code, ['busy', 'operation_conflict', 'operation_unavailable', 'observation_only', 'service_unavailable'], true)) {
                throw new ApiError(409, $code, $message);
            }
            if ($status === 503 && in_array($code, ['settings_unavailable', 'update_unavailable', 'observation_pending'], true)) {
                throw new ApiError(503, $code, $message);
            }
            if ($status === 404 && in_array($code, ['task_not_found', 'unknown_service', 'unknown_action'], true)) {
                throw new ApiError(404, $code, $message);
            }
        }
        if ($status === 404 && $code === 'not_found') {
            throw new ApiError(503, 'jeego_update_required', 'Cette version de Jeego ne fournit pas encore cette fonctionnalité.');
        }
        if (in_array($status, [0, 401, 403], true)) {
            throw new ApiError(503, 'jeego_unavailable', 'La connexion à l’API locale Jeego est indisponible.');
        }
        throw self::badResponse();
    }

    private static function badResponse(): ApiError
    {
        return new ApiError(502, 'jeego_bad_response', 'Réponse inattendue de l’API locale Jeego.');
    }

    private static function respond(Response $response, array|\stdClass $data, int $status = 200): Response
    {
        return Json::respond($response, $data, $status)->withHeader('Cache-Control', 'private, no-store');
    }
}
