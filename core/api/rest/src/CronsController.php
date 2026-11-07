<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/** Gestion des tâches du moteur cron, réservée aux administrateurs. */
final class CronsController
{
    private const FIELDS = ['class', 'function', 'schedule', 'enable', 'deamon', 'once', 'timeout', 'deamonSleepTime'];

    public function list(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $query = ListQuery::from($request);
        $items = [];
        foreach (\cron::all(true) as $cron) {
            if ($query->matches([$cron->getId(), $cron->getClass(), $cron->getFunction(), $cron->getSchedule()])) {
                $items[] = self::toArray($cron);
            }
        }
        return Json::respond($response, $query->page($items));
    }

    public function create(Request $request, Response $response): Response
    {
        self::requireWrite($request);
        $body = Json::body($request);
        foreach (['function', 'schedule'] as $field) {
            if (!array_key_exists($field, $body)) {
                throw ApiError::field(422, 'validation_error', $field, 'Ce champ est obligatoire.');
            }
        }
        $cron = new \cron();
        self::apply($cron, $body);
        return Json::respond($response, self::toArray($cron), 201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        self::requireWrite($request);
        $cron = self::find($args['id']);
        $body = Json::body($request);
        if ($body === []) {
            throw new ApiError(422, 'validation_error', 'Aucun champ à modifier.');
        }
        self::apply($cron, $body);
        return Json::respond($response, self::toArray($cron));
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        self::requireWrite($request);
        self::find($args['id'])->remove();
        return $response->withStatus(204);
    }

    public function start(Request $request, Response $response, array $args): Response
    {
        self::requireWrite($request);
        $cron = self::find($args['id']);
        // run() relance de force une tâche déjà active : l'API refuse ce cas explicitement.
        if ($cron->running() || in_array($cron->getState(), ['starting', 'stoping', 'stopping'], true)) {
            throw new ApiError(409, 'cron_running', 'La tâche est déjà en cours de lancement ou d’exécution.');
        }
        $cron->run();
        return Json::respond($response, self::toArray($cron), 202);
    }

    public function stop(Request $request, Response $response, array $args): Response
    {
        self::requireWrite($request);
        $cron = self::find($args['id']);
        $cron->halt();
        return Json::respond($response, self::toArray($cron));
    }

    private static function requireWrite(Request $request): void
    {
        Auth::requireAdmin($request);
        try {
            \unautorizedInDemo();
        } catch (\Exception $e) {
            throw new ApiError(403, 'demo_forbidden', $e->getMessage());
        }
    }

    private static function find(string $id): \cron
    {
        $cron = ctype_digit($id) ? \cron::byId((int) $id) : null;
        if (!is_object($cron)) {
            throw new ApiError(404, 'cron_not_found', 'Tâche inconnue.');
        }
        return $cron;
    }

    private static function apply(\cron $cron, array $body): void
    {
        if ($unknown = array_diff(array_keys($body), self::FIELDS)) {
            throw ApiError::field(422, 'validation_error', (string) reset($unknown), 'Champ inconnu ou non modifiable.');
        }
        foreach (['class', 'function', 'schedule'] as $field) {
            if (!array_key_exists($field, $body)) {
                continue;
            }
            if (!is_string($body[$field]) || mb_strlen($body[$field]) > 127 || ($field !== 'class' && trim($body[$field]) === '')) {
                throw ApiError::field(422, 'validation_error', $field, 'Un texte de 127 caractères au plus est attendu (non vide sauf pour la classe).');
            }
            $body[$field] = trim($body[$field]);
        }
        if (isset($body['schedule']) && $body['schedule'] !== $cron->getSchedule()) {
            try {
                $valid = \Cron\CronExpression::isValidExpression(\checkAndFixCron($body['schedule']));
            } catch (\Throwable) {
                $valid = false;
            }
            if (!$valid) {
                throw ApiError::field(422, 'validation_error', 'schedule', 'Expression cron invalide.');
            }
        }
        foreach (['enable', 'deamon', 'once'] as $field) {
            if (array_key_exists($field, $body)) {
                if (!is_bool($body[$field])) {
                    throw ApiError::field(422, 'validation_error', $field, 'Un booléen est attendu.');
                }
                $body[$field] = (int) $body[$field];
            }
        }
        foreach (['timeout', 'deamonSleepTime'] as $field) {
            if (array_key_exists($field, $body) && (!is_int($body[$field]) || $body[$field] < 0 || $body[$field] > 2147483647)) {
                throw ApiError::field(422, 'validation_error', $field, 'Un entier de 0 à 2147483647 est attendu.');
            }
        }
        // preSave() peut remplacer l'id par celui d'une autre tâche avec la même cible.
        // Refuser le doublon avant save() évite d'écraser silencieusement cette autre tâche.
        if (empty($cron->getOption())) {
            $class = $body['class'] ?? $cron->getClass();
            $function = $body['function'] ?? $cron->getFunction();
            foreach (\cron::all() as $other) {
                if ((string) $other->getClass() === (string) $class && $other->getFunction() === $function && (int) $other->getId() !== (int) $cron->getId()) {
                    throw ApiError::field(409, 'cron_target_taken', 'function', 'Une tâche utilise déjà cette classe et cette fonction.');
                }
            }
        }
        try {
            \utils::a2o($cron, $body);
            $cron->save();
        } catch (\Exception $e) {
            throw new ApiError(422, 'validation_error', $e->getMessage());
        }
    }

    private static function toArray(\cron $cron): array
    {
        $cron->refresh();
        // Les getters de durée remplacent 0 par la configuration globale. Garder ici
        // la valeur enregistrée pour que modifier un autre champ conserve cet héritage.
        $timeout = new \ReflectionProperty(\cron::class, 'timeout');
        $sleep = new \ReflectionProperty(\cron::class, 'deamonSleepTime');
        $runtime = $cron->getCache('runtime');
        return [
            'id' => (int) $cron->getId(),
            'class' => (string) $cron->getClass(),
            'function' => (string) $cron->getFunction(),
            'schedule' => (string) $cron->getSchedule(),
            'enable' => (bool) (int) $cron->getEnable(),
            'deamon' => (bool) (int) $cron->getDeamon(),
            'once' => (bool) (int) $cron->getOnce(),
            'timeout' => (int) $timeout->getValue($cron),
            'deamonSleepTime' => (int) $sleep->getValue($cron),
            'state' => (string) $cron->getState(),
            'lastRun' => $cron->getLastRun() ?: null,
            'pid' => (int) $cron->getPID() ?: null,
            'runtime' => is_numeric($runtime) ? (float) $runtime : null,
            'option' => $cron->getOption(),
        ];
    }
}
