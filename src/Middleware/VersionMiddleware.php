<?php

declare(strict_types=1);

namespace Jeedom\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class VersionMiddleware implements MiddlewareInterface
{
    private const VERSION_PARAMETER = 'v';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $params = $request->getQueryParams();
        if (isset($params[self::VERSION_PARAMETER])) {
            return $handler->handle($request);
        }

        //dunno desktop or mobile:
        $serverParams = $request->getServerParams();
        $useragent = $serverParams['HTTP_USER_AGENT'] ?? 'none';
        $version = (
            false !== stripos($useragent, 'Android')
            || strpos($useragent, 'iPod')
            || strpos($useragent, 'iPhone')
            || strpos($useragent, 'Mobile')
            || strpos($useragent, 'WebOS')
            || strpos($useragent, 'mobile')
            || strpos($useragent, 'hp-tablet')
        ) ? 'm' : 'd';
        $uri = $request->getUri();
        $query = rtrim(sprintf('%s=%s&%s', self::VERSION_PARAMETER, $version, $uri->getQuery()), '&');

        return new Response(302, [
            'Location' => (string) $uri->withQuery($query),
        ]);
    }
}
