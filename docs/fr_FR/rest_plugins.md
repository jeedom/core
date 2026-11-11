# Gestion REST des plugins (Core 5)

Cette partie est implémentée sur `eval-api-rest`, dont `core/config/version` vaut `5.0.0`. Les chemins sont relatifs à `/api/v1`. Le contrat complet et les formats des réponses sont dans `core/api/rest/openapi.yaml`.

Les routes passent par le middleware existant : utilisateur connecté par Bearer ou session Core UI, `X-CSRF-Token` obligatoire sur les écritures par session. La liste reste accessible aux utilisateurs connectés ; les informations de gestion, journaux, réglages, assistance et installations sont réservés aux administrateurs. Les écritures refusent le compte démo avec l’utilisateur réel de la requête, y compris par Bearer. Les réponses de gestion ne sont pas mises en cache.

| Ancienne action | Route REST |
| --- | --- |
| `plugin.ajax.php all` | `GET /plugins?enabled=true|false` |
| `getConf` | `GET /plugins/{id}?full=true|false` |
| `toggle` | `PATCH /plugins/{id}` avec `isEnable` booléen |
| Réglages généraux de la fiche | Même PATCH : `functionality`, `logLevel`, `heartbeatDelay`, `heartbeatRestartDaemon` |
| `getDependancyInfo` | `GET /plugins/{id}/dependencies` |
| `dependancyInstall` | `POST /plugins/{id}/dependencies/install` avec `{}` |
| `dependancyChangeAutoMode` | `PATCH /plugins/{id}/dependencies` avec `auto` booléen |
| `getDeamonInfo` | `GET /plugins/{id}/daemon` |
| `deamonStart` | `POST /plugins/{id}/daemon/start` avec `forceRestart` booléen facultatif |
| `deamonStop` | `POST /plugins/{id}/daemon/stop` avec `{}` |
| `deamonChangeAutoMode` | `PATCH /plugins/{id}/daemon` avec `auto` booléen |
| `createCommunityPost` | `GET /plugins/{id}/community-post`, prépare seulement le lien du brouillon |
| Suppression native (`update.ajax.php remove`) | `DELETE /plugins/{id}`, supprime aussi les équipements comme le Core existant |

La fiche renvoie les métadonnées, la compatibilité Core/OS, le module Core UI disponible, les fonctionnalités contrôlables, les journaux et l’état de la mise à jour. Les paramètres privés du dépôt et la configuration arbitraire du plugin ne sont pas exposés. Les fonctionnalités absentes ou non contrôlables ne peuvent pas être modifiées. Les réglages propres à chaque plugin restent à déclarer dans son interface et ses contrats ; le chemin historique `configurationPath` est une métadonnée, pas une nouvelle route d’exécution PHP.

## Déclarer des routes propres à un plugin

Dans le dossier d'un plugin, `core/api/rest/routes.php` retourne une fonction de déclaration. Le Core charge ce fichier pour une requête à l'API du plugin si celui-ci est installé et actif. Les chemins déclarés sont relatifs à `/api/v1/plugins/{id}/api`.

```php
<?php
use Jeedom\Api\Rest\Auth;
use Jeedom\Api\Rest\Json;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

return static function (RouteCollectorProxy $routes): void {
    $routes->get('/status', static function (Request $request, Response $response): Response {
        Auth::requireAdmin($request);
        return Json::respond($response, ['status' => 'ok']);
    });
};
```

La route de cet exemple répond à `GET /api/v1/plugins/exemple/api/status` pour le plugin `exemple`. On peut utiliser les méthodes habituelles de Slim (`get`, `post`, `patch`, etc.) et les paramètres de chemin comme `/equipements/{id}`. Le fichier est évalué pour chaque requête vers ce préfixe. Il doit uniquement déclarer les routes, et les effets métier doivent rester dans les contrôleurs.

L'utilisateur authentifié est disponible via `$request->getAttribute('user')`. Une écriture par session passe par le contrôle CSRF du Core. Le plugin applique les droits adaptés à chaque opération, par exemple `Auth::requireAdmin()` et les droits sur l'équipement demandé. Les contrôleurs peuvent utiliser `Json::body()`, `Json::respond()` et `ApiError` pour le même format que le Core. Les routes de plugins sont isolées par identifiant de plugin et les routes standard du Core gardent leurs propres chemins.

## Installation depuis le Market

Le lien Market ouvre Core UI sous `/ui/#/plugins/install?marketId=1234&version=beta`. Le navigateur transmet uniquement l’identifiant numérique et la branche, jamais un secret ou une archive.

1. Core UI appelle `GET /plugins/installations/preview?marketId=1234&version=beta`. Le serveur résout le plugin auprès du Market configuré sur la box. Il contrôle l’achat/licence, la publication et l’accès à la branche, les compatibilités connues et une éventuelle autre source d’installation. La réponse contient le vrai nom du plugin, la branche, l’état installé/actif, la branche actuellement installée et le nom/version de la box. Cette lecture ne crée aucune installation.
2. Après confirmation explicite, Core UI appelle `POST /plugins/installations` avec `{"marketId":1234,"version":"beta"}`, `X-CSRF-Token` en session et un en-tête `Idempotency-Key` aléatoire de 16 à 128 caractères (`A-Z`, `a-z`, chiffres, `_`, `-`). La même clé pour la dernière opération retrouve celle-ci, même terminée ; une autre demande concurrente du même plugin donne 409. Une clé utilisée pour une autre branche donne aussi 409.
3. Une réponse 202 signifie que l’installation est planifiée. La queue native sérialise ces installations, et `plugin::executeInstallation` appelle `update::doUpdate()` avec la source Market et la branche explicitement choisie. Le compte administrateur, le mode démo, le compte Market et les compatibilités sont revérifiés dans le worker. Les erreurs sont conservées sans relance automatique.
4. `GET /plugins/installations/{operationId}/status` expose `queued`, `running`, `succeeded` ou `failed`, les dates et l’erreur exploitable. `GET /plugins/{id}/installations/latest` retrouve la dernière opération après rechargement ou reconnexion. Le dernier état par plugin est conservé dans la configuration existante, sans nouvelle table. Une référence remplacée renvoie 404. Une tâche native perdue ou interrompue ne devient jamais un succès.
5. Après `succeeded`, `GET /plugins/{id}` donne accès à la fiche. Une première installation reste inactive ; une réinstallation ou un changement de branche conserve les réglages et l’état actif/inactif par les mécanismes existants. Les dépendances, le démon et l’activation ont leurs propres routes et états : des fichiers installés ne signifient pas que le plugin est prêt à l’emploi.

Les actions REST de gestion et l’installation d’un même plugin partagent un verrou. Les anciennes interfaces ne sont pas modifiées et les autres chemins historiques de mise à jour continuent de suivre leurs mécanismes natifs. Le téléchargement passe par le client `repo_market` déjà présent sur la branche ; l’API de la box ne réimplémente aucun transport Market et n’accepte pas de dépôt, d’URL ou de commande fournis par le navigateur. Le client REST Core–Market en cours de développement pourra prendre la place de ce transport sans changer les routes Core UI. Les URL authentifiées de téléchargement ne sont plus inscrites dans le journal natif par `repo_market::install`.

## Liste de gestion et installation directe

`GET /plugins?management=true`, réservé aux administrateurs, ajoute à chaque plugin un résumé `management` : source, branche, date de mise à jour, présence et état du démon. La liste simple utilisée par les menus reste inchangée. Un démon absent ou un plugin inactif n’est pas présenté comme une panne ; une erreur de lecture d’un démon donne `unknown` sans empêcher les autres cartes de s’afficher.

- `GET /plugins/repositories` expose uniquement les sources actives compatibles avec les plugins (GitHub, URL, Fichier, Samba) et les métadonnées de leurs champs natifs, sans la configuration privée de la box. Le jeton GitHub est un champ masqué facultatif.
- `POST /plugins/archives` reçoit un multipart `file` (ZIP, 100 Mo maximum), valide le manifeste et les chemins de l’archive sans l’extraire, puis retourne `{id, pluginId, name, size}`. Le serveur choisit le nom du fichier, conservé dans un dossier privé pendant une heure maximum. Aucun chemin local fourni par le navigateur n’est accepté.
- `POST /plugins/installations/repository`, avec `Idempotency-Key`, reçoit `{pluginId, source, configuration}`. Les seuls paramètres acceptés sont ceux déclarés par la source. Pour Fichier, `configuration.path` contient la référence opaque du téléversement. Le manifeste doit correspondre à l’identifiant saisi. GitHub et Samba refusent les paramètres susceptibles de devenir des commandes ; les URL acceptent HTTP(S) sans identifiants et sont protégées par `escapeshellarg` dans le téléchargement natif.
- Les installations directes partagent le verrou, la queue et les routes de suivi du Market. Les paramètres temporaires sont chiffrés avec la clé existante de Jeedom, puis supprimés en fin de tâche ; aucune réponse de suivi n’expose les paramètres privés. Le worker revérifie l’administrateur, le mode démo et la source active. Les archives sont supprimées après la tâche. Les doublons et erreurs suivent les mêmes règles que le Market. Le champ `marketId` vaut `null` pour ces installations ; `source` est renseigné et `version` contient la branche GitHub ou `null`.

La page Core UI correspondante présente les cartes et une popup d’installation dont les champs dépendent de ces métadonnées. Les cartes sont rafraîchies après succès ; les plugins nouvellement installés restent inactifs et une réinstallation conserve les réglages et l’état actif.

## Vérification

`php tests/rest/plugins.php` exerce le vrai routeur Slim, l’authentification Bearer/session, le CSRF et le JSON, avec les services natifs et le Market simulés. Il vérifie les droits, le mode démo, les champs refusés, les états dépendances/démon, les prérequis Market, les doubles demandes, les états de queue, les refus différés, la conservation des réglages et les échecs. Aucun plugin réel n’est téléchargé ou installé par ce test. Le workflow `REST plugin management` exécute syntaxe, PHPStan et ce parcours sur PHP 8.2 et 8.4, à chaque modification de cette branche.

Le parcours de confirmation d’un lien d’installation provenant du Market reste à implémenter dans `jeedom/core-ui`. La validation d’une installation complète avec un Market et une box de recette reste à effectuer sur ces environnements.

