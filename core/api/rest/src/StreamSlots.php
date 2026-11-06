<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

/**
 * Compte les flux SSE ouverts par utilisateur (table cache du core), pour qu'un client
 * ne prenne pas tous les processus Apache. Chaque place expire d'elle-même peu après
 * la durée maximale du flux, même si le processus s'est arrêté sans la libérer.
 */
final class StreamSlots
{
    private const MARGIN = 15;

    /** @return string|null identifiant de la place, null si l'utilisateur a atteint la limite */
    public static function acquire(\user $user, int $duration): ?string
    {
        $key = self::key($user);
        $now = time();
        $slots = array_filter((array) \cache::byKey($key)->getValue([]), static fn ($expires) => (int) $expires > $now);
        if (count($slots) >= EventsController::MAX_STREAMS_PER_USER) {
            return null;
        }
        $id = bin2hex(random_bytes(8));
        $slots[$id] = $now + $duration + self::MARGIN;
        \cache::set($key, $slots, $duration + self::MARGIN);
        return $id;
    }

    public static function release(\user $user, string $id): void
    {
        $key = self::key($user);
        $slots = (array) \cache::byKey($key)->getValue([]);
        unset($slots[$id]);
        if ($slots === []) {
            \cache::delete($key);
            return;
        }
        \cache::set($key, $slots, max(1, max($slots) - time()));
    }

    private static function key(\user $user): string
    {
        return 'api::events::streams::' . $user->getId();
    }
}
