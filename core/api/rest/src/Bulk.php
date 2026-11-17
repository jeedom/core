<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

/** Opérations sur une liste d'identifiants communes aux ressources (réordonnancement). */
final class Bulk
{
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
