<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Ressource /plugins (core/api/rest/openapi.yaml) : plugins installés, lisibles par tout
 * utilisateur connecté (menu de la nouvelle UI, chargement des modules d'interface).
 */
final class PluginsController
{
    public function list(Request $request, Response $response): Response
    {
        $query = $request->getQueryParams();
        $enabled = isset($query['enabled']) ? filter_var($query['enabled'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) : null;
        $items = [];
        foreach ((array) \plugin::listPlugin(false, false, true) as $plugin) {
            if (!is_object($plugin)) {
                continue;
            }
            $active = (int) $plugin->isActive() === 1;
            if ($enabled !== null && $active !== $enabled) {
                continue;
            }
            $items[] = self::toArray($plugin, $active);
        }
        usort($items, static fn ($a, $b) => strcasecmp($a['name'], $b['name']));
        return Json::respond($response, ['items' => $items, 'total' => count($items)]);
    }

    private static function toArray(\plugin $plugin, bool $active): array
    {
        $id = (string) $plugin->getId();
        $icon = (string) $plugin->getPathImgIcon();
        // Module de la nouvelle UI, à part des pages desktop et mobile (décision du 05/11).
        $module = 'plugins/' . $id . '/ui/ui.js';
        $update = \update::byTypeAndLogicalId('plugin', $id);
        $version = is_object($update) ? (string) $update->getLocalVersion() : '';
        return [
            'id' => $id,
            'name' => (string) $plugin->getName(),
            'category' => (string) $plugin->getCategory(),
            'isEnable' => $active,
            'hasEqLogic' => class_exists($id) && is_subclass_of($id, \eqLogic::class),
            'icon' => $icon !== '' && $icon !== 'core/img/no-image-plugin.png' ? $icon : null,
            'uiModule' => $active && is_file(__DIR__ . '/../../../../' . $module) ? $module : null,
            'version' => $version !== '' ? $version : null,
        ];
    }
}
