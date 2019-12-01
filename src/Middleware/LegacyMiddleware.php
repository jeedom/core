<?php

declare(strict_types=1);

namespace Jeedom\Middleware;

use config;
use Exception;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use translate;

class LegacyMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        //dunno desktop or mobile:
        if (!isset($_GET['v'])) {
            $useragent = $_SERVER["HTTP_USER_AGENT"] ?? 'none';
            $getParams = (
                false !== stripos($useragent, 'Android')
                || strpos($useragent, 'iPod')
                || strpos($useragent, 'iPhone')
                || strpos($useragent, 'Mobile')
                || strpos($useragent, 'WebOS')
                || strpos($useragent, 'mobile')
                || strpos($useragent, 'hp-tablet')
            ) ? 'm' : 'd';
            foreach ($_GET AS $var => $value) {
                if(is_array($value)){
                    continue;
                }
                $getParams .= '&' . $var . '=' . $value;
            }
            $url = 'index.php?v=' . trim($getParams, '&');
            if (headers_sent()) {
                echo '<script type="text/javascript">';
                echo "window.location.href='$url';";
                echo '</script>';
            } else {
                header('Location: ' . $url);
            }
            die();
        }

        require_once dirname(__DIR__, 2) . '/core/php/core.inc.php';
        if (isset($_GET['v']) && $_GET['v'] === 'd') {
            if (isset($_GET['modal'])) {
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
            } elseif (isset($_GET['configure'])) {
                include_file('core', 'authentification', 'php');
                include_file('plugin_info', 'configuration', 'configuration', init('plugin'));
            } elseif (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
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
                if ( init('p') != 'message' && !isset($_GET['configure']) && !isset($_GET['modal']) ) {
                    $title = pageTitle(init('p')) . ' - ' . config::byKey('product_name');
                    echo '<script>';
                    echo 'document.title = "' . $title . '"';
                    echo '</script>';
                }
            } catch (Exception $e) {
            }

        } elseif (isset($_GET['v']) && $_GET['v'] == 'm') {
            $_fn = 'index';
            $_type = 'html';
            $_plugin = '';
            if (isset($_GET['modal'])) {
                $_fn = init('modal');
                $_type = 'modalhtml';
                $_plugin = init('plugin');
            } elseif (isset($_GET['p']) && isset($_GET['ajax'])) {
                $_fn = $_GET['p'];
                $_plugin = $_GET['m'] ?? $_plugin;
            }
            include_file('mobile', $_fn, $_type, $_plugin);
        } else {
            echo "Erreur : veuillez contacter l'administrateur";
        }

        return new Response();
    }
}
