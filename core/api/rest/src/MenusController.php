<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Menus latéraux nommés (classe uiMenu), administrateurs uniquement.
 * Le menu effectif de l'utilisateur connecté est servi par /me/menu.
 */
final class MenusController
{
    public function list(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $items = \uiMenu::all();
        return Json::respond($response, ['items' => $items, 'total' => count($items)]);
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        return Json::respond($response, self::run(static fn () => \uiMenu::byId((string) $args['id'])));
    }

    public function create(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = self::checkBody(Json::body($request), ['name', 'items', 'copyFrom']);
        if (!array_key_exists('name', $body)) {
            throw ApiError::field(422, 'validation_error', 'name', 'Le nom est obligatoire.');
        }
        $copyFrom = $body['copyFrom'] ?? null;
        if ($copyFrom !== null && (!is_string($copyFrom) || array_key_exists('items', $body))) {
            throw ApiError::field(422, 'validation_error', 'copyFrom', 'Identifiant attendu, sans `items`.');
        }
        $menu = self::run(static fn () => \uiMenu::create($body['name'], $body['items'] ?? null, $copyFrom));
        return Json::respond($response, $menu, 201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $body = self::checkBody(Json::body($request), ['name', 'items', 'isDefault']);
        if ($body === []) {
            throw new ApiError(422, 'validation_error', 'Aucune modification.');
        }
        if (array_key_exists('isDefault', $body) && $body['isDefault'] !== true) {
            throw ApiError::field(422, 'validation_error', 'isDefault', 'Seul `true` est accepté : désigner le nouveau menu par défaut.');
        }
        return Json::respond($response, self::run(static fn () => \uiMenu::update((string) $args['id'], $body)));
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        self::run(static fn () => \uiMenu::remove((string) $args['id']));
        return $response->withStatus(204);
    }

    /** Traduit les erreurs de uiMenu en erreurs de l'API. */
    private static function run(callable $action): mixed
    {
        try {
            return $action();
        } catch (\OutOfBoundsException) {
            throw new ApiError(404, 'menu_not_found', 'Menu inconnu.');
        } catch (\LengthException $e) {
            throw new ApiError(409, 'menu_conflict', $e->getMessage());
        } catch (\DomainException $e) {
            throw new ApiError(422, 'menu_default', $e->getMessage());
        } catch (\InvalidArgumentException $e) {
            throw new ApiError(422, 'validation_error', $e->getMessage());
        }
    }

    /** @param list<string> $allowed */
    private static function checkBody(array $body, array $allowed): array
    {
        foreach (array_keys($body) as $key) {
            if (!in_array($key, $allowed, true)) {
                throw ApiError::field(422, 'validation_error', (string) $key, 'Champ inconnu.');
            }
        }
        return $body;
    }
}
