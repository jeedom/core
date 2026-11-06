<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App as SlimApp;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

/** Assemble l'API v1. Le core doit être chargé (core.inc.php) avant l'appel. */
final class App
{
    public static function create(): SlimApp
    {
        $app = AppFactory::create();
        $app->addRoutingMiddleware();
        $app->group('/api/v1', static function (RouteCollectorProxy $api): void {
            $objects = new ObjectsController();
            $api->get('/objects', $objects->list(...));
            $api->post('/objects', $objects->create(...));
            $api->put('/objects/order', $objects->reorder(...));
            $api->get('/objects/summary', $objects->globalSummary(...));
            $api->post('/objects/summary/{key}/virtual', self::notImplemented(...));
            $api->get('/objects/{id}', $objects->get(...));
            $api->patch('/objects/{id}', $objects->update(...));
            $api->delete('/objects/{id}', $objects->delete(...));
            $api->get('/objects/{id}/children', $objects->children(...));
            $api->post('/objects/{id}/eqLogics/order-by-usage', $objects->orderEqLogicsByUsage(...));
            $api->get('/objects/{id}/summary', $objects->summary(...));
            $api->get('/objects/{id}/summary/{key}/eqLogics', $objects->summaryEqLogics(...));
            $api->put('/objects/{id}/image', self::notImplemented(...));
            $api->delete('/objects/{id}/image', self::notImplemented(...));

            $eqLogics = new EqLogicsController();
            $api->get('/eqLogics', $eqLogics->list(...));
            $api->post('/eqLogics', $eqLogics->create(...));
            $api->patch('/eqLogics', $eqLogics->updateMany(...));
            $api->post('/eqLogics/delete', $eqLogics->deleteMany(...));
            $api->put('/eqLogics/order', $eqLogics->reorder(...));
            $api->get('/eqLogics/{id}', $eqLogics->get(...));
            $api->patch('/eqLogics/{id}', $eqLogics->update(...));
            $api->delete('/eqLogics/{id}', $eqLogics->delete(...));
            $api->post('/eqLogics/{id}/copy', $eqLogics->copy(...));
            $api->get('/eqLogics/{id}/usages', $eqLogics->usages(...));
            $api->put('/eqLogics/{id}/image', self::notImplemented(...));
            $api->delete('/eqLogics/{id}/image', self::notImplemented(...));

            $cmds = new CmdsController();
            $api->get('/cmds', $cmds->list(...));
            $api->post('/cmds', $cmds->create(...));
            $api->patch('/cmds', $cmds->updateMany(...));
            $api->put('/cmds/order', $cmds->reorder(...));
            $api->get('/cmds/{id}', $cmds->get(...));
            $api->patch('/cmds/{id}', $cmds->update(...));
            $api->delete('/cmds/{id}', $cmds->delete(...));
            $api->post('/cmds/{id}/exec', $cmds->exec(...));
            $api->get('/cmds/{id}/value', $cmds->value(...));
            $api->get('/cmds/{id}/history', $cmds->history(...));
            $api->get('/cmds/{id}/statistics', $cmds->statistics(...));
            $api->get('/cmds/{id}/usages', $cmds->usages(...));

            $api->get('/scenarios/{id}/log', (new ScenariosController())->log(...));
            $api->get('/events', (new EventsController())->stream(...));

            $api->get('/plugins', (new PluginsController())->list(...));
            $api->get('/me', self::getMe(...));
            $api->delete('/session', (new SessionController())->close(...));
        })->add(new Auth());
        // Seule route sans authentification : l'ouverture de session.
        $app->post('/api/v1/session', (new SessionController())->open(...));

        // Toutes les erreurs sortent au format `Error` de la spec, y compris 404/405 de routage.
        $errors = $app->addErrorMiddleware(false, false, false);
        $errors->setDefaultErrorHandler(static function (Request $request, \Throwable $e) use ($app): Response {
            $error = match (true) {
                $e instanceof ApiError => $e,
                $e instanceof HttpNotFoundException => new ApiError(404, 'route_not_found', 'Route inconnue.'),
                $e instanceof HttpMethodNotAllowedException => new ApiError(405, 'method_not_allowed', 'Méthode non autorisée sur cette route.'),
                default => new ApiError(500, 'internal_error', 'Erreur interne.'),
            };
            if ($error->status === 500) {
                \log::add('api', 'error', $e->getMessage());
            }
            return Json::error($app->getResponseFactory()->createResponse(), $error);
        });
        return $app;
    }

    /** Utilisateur connecté, pour que l'UI adapte l'affichage (équivalent d'isConnect('admin')). */
    private static function getMe(Request $request, Response $response): Response
    {
        return Json::respond($response, self::me($request->getAttribute('user'), $request->getAttribute('csrfToken')));
    }

    /** Schéma `Me` de la spec. */
    public static function me(\user $user, ?string $csrfToken): array
    {
        return [
            'id' => (int) $user->getId(),
            'login' => (string) $user->getLogin(),
            'isAdmin' => $user->getProfils() === 'admin',
            'csrfToken' => $csrfToken,
        ];
    }

    private static function notImplemented(): never
    {
        throw new ApiError(501, 'not_implemented', 'Route prévue par la spec, pas encore implémentée dans la passerelle.');
    }
}
