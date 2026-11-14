<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

use Jeedom\Api\Rest\App;
use Jeedom\Api\Rest\RestoreStatus;
use Jeedom\Api\Rest\Router;
use Slim\Psr7\Factory\ServerRequestFactory;

// Point d'entrée de l'API REST v1 (/api/v1/…, voir api/v1/.htaccess).
// Contrat : core/api/rest/openapi.yaml.
require_once __DIR__ . '/../../../vendor/autoload.php';

$request = ServerRequestFactory::createFromGlobals();
// Le suivi doit répondre même quand la base et les sessions sont en cours de remplacement.
if ($request->getUri()->getPath() === RestoreStatus::PATH) {
    Router::create()->run($request);
    return;
}

require_once __DIR__ . '/../../php/core.inc.php';
App::create()->run($request);
