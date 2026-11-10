<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/** Connexion de ce Jeedom au Market, via le client utilisé par le Core. */
final class MarketController
{
    public function status(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        return self::respond($response, \repo_market::connectionStatus());
    }

    public function connect(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = Json::body($request, 16 * 1024, true);
        if (array_diff(array_keys($body), ['login', 'password', 'twoFactorCode']) !== []) {
            throw new ApiError(422, 'validation_error', 'Paramètre de connexion Market inconnu.');
        }
        $login = $body['login'] ?? null;
        $password = $body['password'] ?? null;
        if (!is_string($login) || trim($login) === '') {
            throw ApiError::field(422, 'validation_error', 'login', 'Renseignez votre identifiant Market.');
        }
        if (!is_string($password) || $password === '') {
            throw ApiError::field(422, 'validation_error', 'password', 'Renseignez votre mot de passe Market.');
        }
        $twoFactorCode = $body['twoFactorCode'] ?? '';
        if (array_key_exists('twoFactorCode', $body) && (!is_string($twoFactorCode) || !preg_match('/\A[0-9]{6}\z/', $twoFactorCode))) {
            throw ApiError::field(422, 'validation_error', 'twoFactorCode', 'Le code de double authentification doit contenir six chiffres.');
        }

        try {
            $status = \repo_market::connect(trim($login), $password, $twoFactorCode);
        } catch (\Exception $exception) {
            // Une erreur du Market ne doit ni invalider la session Core ni renvoyer les détails de la requête distante.
            throw match ($exception->getCode()) {
                400, 401, 403, 422 => new ApiError(422, 'market_credentials_rejected', 'Le Market a refusé la connexion. Vérifiez l’identifiant, le mot de passe et le code de double authentification.'),
                429 => new ApiError(429, 'market_rate_limited', 'Le Market a limité les tentatives. Réessayez dans quelques instants.'),
                default => new ApiError(502, 'market_unavailable', 'Impossible de joindre le Market ou de terminer la connexion.'),
            };
        }
        return self::respond($response, $status);
    }

    private static function respond(Response $response, array $status): Response
    {
        $data = ['connected' => $status['connected'], 'login' => $status['login']];
        if (isset($status['warning'])) {
            $data['warning'] = 'Compte connecté. La synchronisation avec le Market a échoué.';
        }
        return Json::respond($response->withHeader('Cache-Control', 'private, no-store'), $data);
    }
}
