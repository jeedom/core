<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Ressource /scenarios (core/api/rest/openapi.yaml), pour l'instant limitée au journal.
 * Le journal se suit en direct en rappelant la route avec l'offset reçu, tant que
 * l'écran est ouvert, que le scénario soit vu comme actif ou non.
 */
final class ScenariosController
{
    /** Lecture maximale par appel ; le reste vient à l'appel suivant. */
    private const MAX_READ = 1_048_576;
    private const DEFAULT_TAIL = 200;
    private const MAX_TAIL = 5000;

    public function log(Request $request, Response $response, array $args): Response
    {
        $scenario = self::findReadable($args['id'], $request->getAttribute('user'));
        $query = $request->getQueryParams();
        $offset = null;
        if (isset($query['offset'])) {
            if (!ctype_digit((string) $query['offset'])) {
                throw ApiError::field(422, 'validation_error', 'offset', 'Un entier positif ou nul est attendu.');
            }
            $offset = (int) $query['offset'];
        }
        $tail = self::DEFAULT_TAIL;
        if (isset($query['tail'])) {
            if (!ctype_digit((string) $query['tail']) || (int) $query['tail'] < 1 || (int) $query['tail'] > self::MAX_TAIL) {
                throw ApiError::field(422, 'validation_error', 'tail', 'Un entier entre 1 et 5000 est attendu.');
            }
            $tail = (int) $query['tail'];
        }

        $path = __DIR__ . '/../../../../log/scenarioLog/scenario' . $scenario->getId() . '.log';
        clearstatcache(true, $path);
        $size = is_file($path) ? (int) filesize($path) : 0;
        $reset = false;
        if ($offset !== null && $offset > $size) {
            // Journal vidé ou recréé depuis le dernier appel : on repart du début.
            $offset = 0;
            $reset = true;
        }
        $start = $offset ?? max(0, $size - self::MAX_READ);
        $content = $size > $start ? (string) file_get_contents($path, false, null, $start, self::MAX_READ) : '';
        // Seules les lignes complètes sont renvoyées ; une ligne en cours d'écriture viendra au prochain appel.
        $end = strrpos($content, "\n");
        $content = $end === false ? '' : substr($content, 0, $end);
        $next = $start + ($end === false ? 0 : $end + 1);
        $lines = $content === '' ? [] : explode("\n", $content);
        if ($offset === null) {
            if ($start > 0) {
                array_shift($lines); // première ligne probablement coupée
            }
            $lines = array_slice($lines, -$tail);
        }
        return Json::respond($response, [
            'lines' => array_values(array_map(static fn (string $line) => mb_convert_encoding($line, 'UTF-8', 'UTF-8'), $lines)),
            'offset' => $next,
            'reset' => $reset,
        ]);
    }

    private static function findReadable(string $id, \user $user): \scenario
    {
        $scenario = ctype_digit($id) ? \scenario::byId((int) $id) : null;
        if (!is_object($scenario)) {
            throw new ApiError(404, 'scenario_not_found', 'Scénario inconnu.');
        }
        if (!$scenario->hasRight('r', $user)) {
            throw new ApiError(403, 'forbidden', 'Droits insuffisants sur ce scénario.');
        }
        return $scenario;
    }
}
