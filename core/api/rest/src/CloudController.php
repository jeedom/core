<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class CloudController
{
    public function services(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        try {
            $services = \repo_market::cloudServices();
        } catch (\Exception $exception) {
            throw match ($exception->getCode()) {
                401 => new ApiError(409, 'cloud_authentication_required', 'Reconnectez ce Jeedom au Market pour consulter les quotas Cloud.'),
                429 => new ApiError(429, 'cloud_rate_limited', 'Le Cloud a limité les requêtes. Réessayez dans quelques instants.'),
                default => new ApiError(502, 'cloud_services_unavailable', 'Impossible de charger les services et quotas Cloud.'),
            };
        }
        return Json::respond($response->withHeader('Cache-Control', 'private, no-store'), ['services' => $services]);
    }

    public function monitoring(Request $request, Response $response): Response
    {
        self::requireMonitoring($request);
        $enabled = null;
        if ($request->getMethod() === 'PUT') {
            $body = Json::body($request, 1024, true);
            if (array_keys($body) !== ['enabled'] || !is_bool($body['enabled'])) {
                throw ApiError::field(422, 'validation_error', 'enabled', 'Indiquez si cette box doit être surveillée.');
            }
            $enabled = $body['enabled'];
        }
        try {
            $status = \repo_market::monitoring($enabled);
        } catch (\Exception $error) {
            throw self::monitoringError($error);
        }
        return Json::respond($response->withHeader('Cache-Control', 'private, no-store'), $status);
    }

    public function testMonitoring(Request $request, Response $response): Response
    {
        self::requireMonitoring($request);
        $body = Json::body($request, 1024, true, true);
        if ($body !== [] && (array_keys($body) !== ['channel'] || !in_array($body['channel'], \repo_market::MONITORING_CHANNELS, true))) {
            throw ApiError::field(422, 'validation_error', 'channel', 'Canal de notification inconnu.');
        }
        try {
            $result = \repo_market::testMonitoring($body['channel'] ?? null);
        } catch (\Exception $error) {
            throw self::monitoringError($error);
        }
        return Json::respond($response->withHeader('Cache-Control', 'private, no-store'), $result);
    }

    private static function requireMonitoring(Request $request): void
    {
        Auth::requireAdmin($request);
        if (!\repo_market::isConnected()) throw new ApiError(409, 'market_not_connected', 'Reconnectez le compte Market.');
        if (!\config::byKey('service::monitoring::enable')) throw new ApiError(403, 'monitoring_not_available', 'Le monitoring est indisponible pour ce compte.');
    }

    private static function monitoringError(\Exception $error): ApiError
    {
        return match ($error->getCode()) {
            401 => new ApiError(409, 'cloud_authentication_required', 'Reconnectez le compte Market.'),
            403 => new ApiError(403, 'monitoring_not_available', 'Le monitoring est indisponible pour ce compte.'),
            404 => new ApiError(502, 'monitoring_not_configured', 'Le Cloud ne trouve pas le monitoring de cette box.'),
            409 => new ApiError(409, 'no_configured_contact', 'Aucun destinataire configuré pour ce canal.'),
            429 => new ApiError(429, 'cloud_rate_limited', 'Patientez une minute avant de relancer le test.'),
            default => new ApiError(502, 'monitoring_unavailable', 'Impossible de joindre le service de monitoring.'),
        };
    }
}
