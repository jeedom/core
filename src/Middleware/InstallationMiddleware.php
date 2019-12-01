<?php

declare(strict_types=1);

namespace Jeedom\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class InstallationMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        //no config, install Jeedom!
        if (!file_exists(dirname(__DIR__, 2) . '/core/config/common.config.php')) {
            return new Response(302, [
                'Location' => '/install/setup.php',
            ]);
        }

        return $handler->handle($request);
    }
}
