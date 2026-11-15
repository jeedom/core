<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class SupportController
{
    public function create(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        if ($request->getAttribute('user')->getLogin() === 'demo') {
            throw new ApiError(403, 'forbidden', 'Action indisponible en mode démo.');
        }
        $body = Json::body($request, 40 * 1024, true);
        if (array_diff(array_keys($body), ['category', 'title', 'message', 'openSupport']) !== []) {
            throw new ApiError(422, 'validation_error', 'Paramètre de demande de support inconnu.');
        }
        $title = self::text($body, 'title', 1, 120);
        $message = self::text($body, 'message', 25, 6000);
        $category = $body['category'] ?? null;
        if (!is_string($category) || strlen($category) > 96
            || (!in_array($category, ['core', 'core::scenario'], true)
                && (preg_match('/\Aplugin::([A-Za-z0-9_-][A-Za-z0-9_.-]*)\z/', $category, $match) !== 1
                    || !in_array($match[1], \plugin::listPlugin(false, false, false, true), true)))) {
            throw ApiError::field(422, 'validation_error', 'category', 'Catégorie de support invalide.');
        }
        if (array_key_exists('openSupport', $body) && !is_bool($body['openSupport'])) {
            throw ApiError::field(422, 'validation_error', 'openSupport', 'Un booléen est attendu.');
        }
        if (!\repo_market::isConnected()) {
            throw new ApiError(409, 'market_not_connected', 'Connectez ce Jeedom au Market avant d’envoyer une demande.');
        }

        try {
            \repo_market::saveTicket([
                'type' => 'support',
                'category' => $category,
                'title' => $title,
                'message' => $message,
                'openSupport' => $body['openSupport'] ?? false,
            ]);
        } catch (\Exception $exception) {
            throw match ($exception->getCode()) {
                401 => new ApiError(409, 'market_not_connected', 'Reconnectez ce Jeedom au Market.'),
                403 => new ApiError(403, 'ticket_forbidden', 'Le Market a refusé la demande pour ce compte ou cette catégorie.'),
                409 => new ApiError(409, 'ticket_creation_in_progress', 'Une demande est déjà en cours d’envoi.'),
                422 => new ApiError(422, 'ticket_rejected', 'Le Market a refusé le contenu de la demande. Vérifiez les champs.'),
                429 => new ApiError(429, 'ticket_quota_reached', 'Le Market a limité l’envoi de demandes pour ce compte.'),
                default => new ApiError(502, 'market_unavailable', 'Le Market n’a pas confirmé l’ouverture du ticket.'),
            };
        }
        return $response->withStatus(204)->withHeader('Cache-Control', 'no-store');
    }

    private static function text(array $body, string $field, int $min, int $max): string
    {
        $value = is_string($body[$field] ?? null) ? trim($body[$field]) : '';
        if (strlen($value) < $min || strlen($value) > $max) {
            throw ApiError::field(422, 'validation_error', $field, "Saisissez entre {$min} et {$max} octets.");
        }
        return $value;
    }
}
