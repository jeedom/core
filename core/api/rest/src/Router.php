<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;

/** Routeur REST et réponses d’erreur, utilisables avant le bootstrap du Core. */
final class Router
{
    // Ces fichiers restent disponibles pendant une restauration suivie depuis l’interface.
    public const RESTORE_FILES = [
        'api/v1/.htaccess',
        'core/api/rest/index.php',
        'core/api/rest/src/Router.php',
        'core/api/rest/src/RestoreStatus.php',
        'core/api/rest/src/Json.php',
        'core/api/rest/src/ApiError.php',
    ];

    public static function create(): App
    {
        $app = AppFactory::create();
        $app->addRoutingMiddleware();
        $app->get(RestoreStatus::PATH, RestoreStatus::show(...));

        $errors = $app->addErrorMiddleware(false, false, false);
        $errors->setDefaultErrorHandler(static function (Request $request, \Throwable $e) use ($app): Response {
            $error = match (true) {
                $e instanceof ApiError => $e,
                $e instanceof HttpNotFoundException => new ApiError(404, 'route_not_found', 'Route inconnue.'),
                $e instanceof HttpMethodNotAllowedException => new ApiError(405, 'method_not_allowed', 'Méthode non autorisée sur cette route.'),
                default => new ApiError(500, 'internal_error', 'Erreur interne.'),
            };
            if ($error->status === 500) {
                if (class_exists(\log::class, false)) {
                    \log::add('api', 'error', $e->getMessage());
                } else {
                    error_log($e->getMessage());
                }
            }
            return Json::error($app->getResponseFactory()->createResponse(), $error);
        });

        return $app;
    }
}
