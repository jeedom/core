<?php

declare(strict_types=1);

namespace Jeedom\Middleware;

use Exception;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Exception $exception) {
            // TODO: changer pour un status 400 ou 500 (selon les cas)
            // FIXME: requètes en boucle à cause du manifest si status != 2xx
            return new Response(200, [], $exception->getMessage());
        }
    }
}
