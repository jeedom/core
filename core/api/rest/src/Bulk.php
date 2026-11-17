<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

/** Opérations sur une liste d'identifiants communes aux ressources (actions groupées, réordonnancement). */
final class Bulk
{
    private const MAX = 1000;

    /**
     * `ids` d'une action groupée : 1 à MAX entiers positifs distincts.
     *
     * @return list<int>
     */
    public static function ids(array $body): array
    {
        $ids = $body['ids'] ?? null;
        if (!is_array($ids) || !array_is_list($ids) || $ids === [] || count($ids) > self::MAX
            || array_filter($ids, static fn ($id) => !is_int($id) || $id < 1) !== [] || count(array_unique($ids)) !== count($ids)) {
            throw ApiError::field(422, 'validation_error', 'ids', 'Une liste de 1 à ' . self::MAX . ' identifiants entiers distincts est attendue.');
        }
        return $ids;
    }

    /**
     * Exécute l'action pour chaque identifiant ; un échec n'arrête pas les autres.
     *
     * @param list<int> $ids
     * @param callable(int): void $action
     * @return array{done: list<int>, failed: list<array{id: int, error: array{code: string, message: string}}>}
     */
    public static function each(array $ids, callable $action): array
    {
        $done = [];
        $failed = [];
        foreach ($ids as $id) {
            try {
                $action($id);
                $done[] = $id;
            } catch (ApiError $e) {
                $failed[] = ['id' => $id, 'error' => ['code' => $e->errorCode, 'message' => $e->getMessage()]];
            } catch (\Throwable $e) {
                \log::add('api', 'error', $e->getMessage());
                $failed[] = ['id' => $id, 'error' => ['code' => 'internal_error', 'message' => 'Erreur interne.']];
            }
        }
        return ['done' => $done, 'failed' => $failed];
    }

    /**
     * Valide `ids` (liste d'entiers) et appelle `$place(id, position)` dans l'ordre reçu,
     * positions 1, 2, 3… ; `$place` renvoie faux pour un identifiant inconnu, ignoré sans
     * consommer de position.
     *
     * @param callable(int, int): bool $place
     */
    public static function reorder(array $body, callable $place): void
    {
        $ids = $body['ids'] ?? null;
        if (!is_array($ids) || !array_is_list($ids) || array_filter($ids, static fn ($id) => !is_int($id)) !== []) {
            throw ApiError::field(422, 'validation_error', 'ids', 'Une liste d\'identifiants entiers est attendue.');
        }
        $position = 1;
        foreach ($ids as $id) {
            if ($place($id, $position)) {
                $position++;
            }
        }
    }
}
