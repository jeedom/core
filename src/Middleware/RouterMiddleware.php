<?php

declare(strict_types=1);

namespace Jeedom\Middleware;

use config;
use Exception;
use GuzzleHttp\Psr7\Response;
use function in_array;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;
use translate;

class RouterMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        require_once dirname(__DIR__, 2) . '/core/php/core.inc.php';

        $params = $request->getQueryParams();
        if (!isset($params['v']) || !in_array($params['v'], ['d', 'm'], true)) {
            throw new RuntimeException('Erreur : veuillez contacter l\'administrateur');
        }

        if ($params['v'] === 'd') {
            if (isset($params['modal'])) {
                try {
                    include_file('core', 'authentification', 'php');
                    if (!isConnect()) {
                        throw new Exception('{{401 - Accès non autorisé}}');
                    }
                    include_file('desktop', init('modal'), 'modal', init('plugin'));
                } catch (Exception $e) {
                    ob_end_clean();
                    echo '<div class="alert alert-danger div_alert">';
                    echo translate::exec(displayException($e), 'desktop/' . init('p') . '.php');
                    echo '</div>';
                }
            } elseif (isset($params['configure'])) {
                include_file('core', 'authentification', 'php');
                include_file('plugin_info', 'configuration', 'configuration', init('plugin'));
            } elseif (isset($params['ajax']) && $params['ajax'] === '1') {
                try {
                    include_file('core', 'authentification', 'php');
                    include_file('desktop', init('p'), 'php', init('m'));
                } catch (Exception $e) {
                    ob_end_clean();
                    echo '<div class="alert alert-danger div_alert">';
                    echo translate::exec(displayException($e), 'desktop/' . init('p') . '.php');
                    echo '</div>';
                }
            } else {
                include_file('desktop', 'index', 'php');
            }

            //page title:
            try {
                if ( !isset($params['modal']) && !isset($params['configure']) && init('p') !== 'message') {
                    $title = pageTitle(init('p')) . ' - ' . config::byKey('product_name');
                    echo '<script>';
                    echo 'document.title = "' . $title . '"';
                    echo '</script>';
                }
            } catch (Exception $e) {
            }

        } elseif ($params['v'] === 'm') {
            $_fn = 'index';
            $_type = 'html';
            $_plugin = '';
            if (isset($params['modal'])) {
                $_fn = init('modal');
                $_type = 'modalhtml';
                $_plugin = init('plugin');
            } elseif (isset($params['p'], $params['ajax'])) {
                $_fn = $params['p'];
                $_plugin = $params['m'] ?? $_plugin;
            }
            include_file('mobile', $_fn, $_type, $_plugin);
        }

        return new Response();
    }
}
