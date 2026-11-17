<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

/** Lecture des paramètres de requête typés communs aux contrôleurs. */
final class QueryParam
{
    /**
     * Booléen facultatif : null si absent ou vide, sinon true/false, 1/0, yes/no, on/off ;
     * toute autre valeur est refusée (422).
     */
    public static function bool(array $query, string $name): ?bool
    {
        $value = $query[$name] ?? null;
        if ($value === null || $value === '') {
            return null;
        }
        $bool = filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        if ($bool === null) {
            throw ApiError::field(422, 'validation_error', $name, 'true ou false est attendu.');
        }
        return $bool;
    }
}
