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
}
