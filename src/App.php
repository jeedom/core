<?php

declare(strict_types=1);

namespace Jeedom;

use Jeedom\Middleware\ErrorHandlerMiddleware;
use Jeedom\Middleware\InstallationMiddleware;
use Jeedom\Middleware\LegacyMiddleware;
use Jeedom\Middleware\VersionMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

class App implements RequestHandlerInterface
{
    /**
     * @var MiddlewareInterface[]
     */
    private $middlewares;

    public function __construct()
    {
        $this->middlewares = [
            new ErrorHandlerMiddleware(),
            new InstallationMiddleware(),
            new VersionMiddleware(),
            new LegacyMiddleware(),
        ];
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = current($this->middlewares);
        next($this->middlewares);
        if (null === $middleware) {
            throw new RuntimeException('Can not handle request.');
        }

        $response = $middleware->process($request, $this);
        reset($this->middlewares);

        return $response;
    }
}
