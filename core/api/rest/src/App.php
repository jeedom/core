<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App as SlimApp;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

/** Assemble l'API v1. Le core doit être chargé (core.inc.php) avant l'appel. */
final class App
{
    public static function create(): SlimApp
    {
        $app = AppFactory::create();
        $app->addRoutingMiddleware();
        $app->group('/api/v1', static function (RouteCollectorProxy $api): void {
            $objects = new ObjectsController();
            $api->get('/objects', $objects->list(...));
            $api->post('/objects', $objects->create(...));
            $api->put('/objects/order', $objects->reorder(...));
            $api->get('/objects/summary', $objects->globalSummary(...));
            $api->post('/objects/summary/{key}/virtual', $objects->createSummaryVirtual(...));
            $api->get('/objects/{id}', $objects->get(...));
            $api->patch('/objects/{id}', $objects->update(...));
            $api->delete('/objects/{id}', $objects->delete(...));
            $api->get('/objects/{id}/children', $objects->children(...));
            $api->post('/objects/{id}/eqLogics/order-by-usage', $objects->orderEqLogicsByUsage(...));
            $api->get('/objects/{id}/summary', $objects->summary(...));
            $api->get('/objects/{id}/summary/{key}/eqLogics', $objects->summaryEqLogics(...));
            $api->put('/objects/{id}/image', $objects->uploadImage(...));
            $api->delete('/objects/{id}/image', $objects->deleteImage(...));

            $eqLogics = new EqLogicsController();
            $api->get('/eqLogics', $eqLogics->list(...));
            $api->post('/eqLogics', $eqLogics->create(...));
            $api->patch('/eqLogics', $eqLogics->updateMany(...));
            $api->post('/eqLogics/delete', $eqLogics->deleteMany(...));
            $api->put('/eqLogics/order', $eqLogics->reorder(...));
            $api->get('/eqLogics/{id}', $eqLogics->get(...));
            $api->patch('/eqLogics/{id}', $eqLogics->update(...));
            $api->delete('/eqLogics/{id}', $eqLogics->delete(...));
            $api->post('/eqLogics/{id}/copy', $eqLogics->copy(...));
            $api->get('/eqLogics/{id}/usages', $eqLogics->usages(...));
            $api->put('/eqLogics/{id}/image', $eqLogics->uploadImage(...));
            $api->delete('/eqLogics/{id}/image', $eqLogics->deleteImage(...));

            $display = new DisplayController();
            $api->get('/display', $display->list(...));
            $api->put('/display/eqLogics/{id}', $display->replace(...));
            $api->delete('/display/eqLogics/{id}', $display->reset(...));

            $cmds = new CmdsController();
            $api->get('/cmds', $cmds->list(...));
            $api->post('/cmds', $cmds->create(...));
            $api->patch('/cmds', $cmds->updateMany(...));
            $api->put('/cmds/order', $cmds->reorder(...));
            $api->get('/cmds/generic-types', $cmds->genericTypes(...));
            $api->get('/cmds/{id}', $cmds->get(...));
            $api->patch('/cmds/{id}', $cmds->update(...));
            $api->delete('/cmds/{id}', $cmds->delete(...));
            $api->post('/cmds/{id}/exec', $cmds->exec(...));
            $api->get('/cmds/{id}/value', $cmds->value(...));
            $api->get('/cmds/{id}/history', $cmds->history(...));
            $api->get('/cmds/{id}/statistics', $cmds->statistics(...));
            $api->put('/cmds/{id}/history/point', $cmds->updateHistoryPoint(...));
            $api->post('/cmds/{id}/history/empty', $cmds->emptyHistory(...));
            $api->post('/cmds/{id}/history/copy', $cmds->copyHistory(...));
            $api->delete('/history/future', $cmds->removeFutureHistory(...));
            $api->get('/history/calculation', $cmds->calculatedHistory(...));
            $api->get('/cmds/{id}/usages', $cmds->usages(...));

            $scenarios = new ScenariosController();
            $api->get('/scenarios', $scenarios->list(...));
            $api->post('/scenarios', $scenarios->create(...));
            $api->patch('/scenarios', $scenarios->updateMany(...));
            $api->post('/scenarios/delete', $scenarios->deleteMany(...));
            $api->put('/scenarios/order', $scenarios->reorder(...));
            $api->get('/scenarios/groups', $scenarios->groups(...));
            $api->get('/scenarios/{id}', $scenarios->get(...));
            $api->patch('/scenarios/{id}', $scenarios->update(...));
            $api->delete('/scenarios/{id}', $scenarios->delete(...));
            $api->post('/scenarios/{id}/copy', $scenarios->copy(...));
            $api->post('/scenarios/{id}/start', $scenarios->start(...));
            $api->post('/scenarios/{id}/stop', $scenarios->stop(...));
            $api->get('/scenarios/{id}/log', $scenarios->log(...));
            $api->delete('/scenarios/{id}/log', $scenarios->emptyLog(...));
            $api->get('/scenarios/{id}/usages', $scenarios->usages(...));

            $variables = new VariablesController();
            $api->get('/variables', $variables->list(...));
            $api->post('/variables', $variables->create(...));
            $api->get('/variables/{id}', $variables->get(...));
            $api->patch('/variables/{id}', $variables->update(...));
            $api->delete('/variables/{id}', $variables->delete(...));
            $api->get('/variables/{id}/usages', $variables->usages(...));

            $notes = new NotesController();
            $api->get('/notes', $notes->list(...));
            $api->post('/notes', $notes->create(...));
            $api->get('/notes/{id}', $notes->get(...));
            $api->patch('/notes/{id}', $notes->update(...));
            $api->delete('/notes/{id}', $notes->delete(...));

            $crons = new CronsController();
            $api->get('/crons', $crons->list(...));
            $api->post('/crons', $crons->create(...));
            $api->patch('/crons/{id}', $crons->update(...));
            $api->delete('/crons/{id}', $crons->delete(...));
            $api->post('/crons/{id}/start', $crons->start(...));
            $api->post('/crons/{id}/stop', $crons->stop(...));

            $config = new ConfigController();
            $api->get('/config', $config->get(...));
            $api->patch('/config', $config->update(...));

            $networkAccess = new NetworkAccessController();
            $api->get('/network/access', $networkAccess->get(...));
            $api->patch('/network/access', $networkAccess->update(...));
            $api->post('/network/dns/restart', $networkAccess->restartDns(...));

            $market = new MarketController();
            $api->get('/market/connection', $market->status(...));
            $api->post('/market/connection', $market->connect(...));
            $api->delete('/market/connection', $market->disconnect(...));
            $api->post('/market/connection/synchronize', $market->synchronize(...));

            $jeego = new JeegoController();
            $api->get('/jeego', $jeego->status(...));
            $api->get('/jeego/settings/{section}', $jeego->settings(...));
            $api->post('/jeego/actions/{action}', $jeego->action(...));
            $api->get('/jeego/tasks/{id}', $jeego->task(...));
            $api->get('/jeego/update', $jeego->updateStatus(...));
            $api->post('/jeego/update/check', $jeego->checkUpdate(...));
            $api->post('/jeego/update', $jeego->startUpdate(...));

            $system = new SystemController();
            $api->get('/system', $system->get(...));
            $api->get('/system/health', $system->health(...));
            $api->post('/system/health/benchmark', $system->benchmark(...));
            $api->post('/system/reboot', $system->reboot(...));
            $api->post('/system/shutdown', $system->shutdown(...));
            $api->delete('/system/time/reference', $system->resetTimeReference(...));
            $api->post('/system/consistency', $system->consistency(...));
            $api->post('/system/permissions/repair', $system->repairPermissions(...));
            $api->post('/system/database/cleanup', $system->cleanDatabase(...));
            $api->get('/system/database/schema', $system->databaseSchema(...));
            $api->post('/system/database/schema/repair', $system->repairDatabaseSchema(...));
            $api->post('/system/database/queries', $system->queryDatabase(...));
            $api->post('/system/ssh', $system->ssh(...));

            $files = new FilesController();
            $api->get('/system/files/roots', $files->roots(...));
            $api->get('/system/files/entries', $files->entries(...));
            $api->post('/system/files/entries', $files->create(...));
            $api->delete('/system/files/entries', $files->delete(...));
            $api->get('/system/files/content', $files->read(...));
            $api->put('/system/files/content', $files->write(...));
            $api->post('/system/files/upload', $files->upload(...));
            $api->get('/system/files/download', $files->download(...));

            $messages = new MessagesController();
            $api->get('/messages', $messages->list(...));
            $api->delete('/messages', $messages->clear(...));
            $api->get('/messages/count', $messages->count(...));
            $api->delete('/messages/{id}', $messages->delete(...));

            $logs = new LogsController();
            $api->get('/logs', $logs->list(...));
            $api->delete('/logs', $logs->deleteAll(...));
            $api->get('/logs/{name}', $logs->get(...));
            $api->get('/logs/{name}/download', $logs->download(...));
            $api->delete('/logs/{name}', $logs->delete(...));
            $api->delete('/logs/{name}/contents', $logs->clear(...));

            $backups = new BackupsController();
            $api->get('/backups', $backups->list(...));
            $api->post('/backups', $backups->create(...));
            $api->post('/backups/archives', $backups->upload(...));
            $api->get('/backups/repositories', $backups->repositories(...));
            $api->get('/backups/repositories/market/status', $backups->cloudStatus(...));
            $api->post('/backups/repositories/samba/test', $backups->testSamba(...));
            $api->get('/backups/repositories/{repositoryId}/archives', $backups->remoteArchives(...));
            $api->post('/backups/repositories/{repositoryId}/archives/{id}/import', $backups->import(...));
            $api->get('/backups/{id}/archive', $backups->download(...));
            $api->delete('/backups/{id}', $backups->delete(...));
            $api->post('/backups/{id}/restore', $backups->restore(...));
            $api->post('/backups/{id}/exports', $backups->export(...));

            $api->get('/events', (new EventsController())->stream(...));

            $plugins = new PluginsController();
            $api->get('/plugins', $plugins->list(...));
            $api->get('/plugins/management', $plugins->management(...));
            $api->get('/plugins/repositories', $plugins->repositories(...));
            $api->post('/plugins/archives', $plugins->uploadArchive(...));
            $api->post('/plugins/installations/repository', $plugins->installFromRepository(...));
            $api->get('/plugins/installations/preview', $plugins->prepareInstallation(...));
            $api->post('/plugins/installations', $plugins->install(...));
            $api->get('/plugins/installations/{operationId}/status', $plugins->installation(...));
            $api->get('/plugins/{id}', $plugins->get(...));
            $api->get('/plugins/{id}/icon', $plugins->icon(...));
            $api->get('/plugins/{id}/ui/{path:.+}', $plugins->uiAsset(...));
            $api->any('/plugins/{id}/api[/{path:.+}]', $plugins->dispatchApi(...));
            $api->patch('/plugins/{id}', $plugins->update(...));
            $api->delete('/plugins/{id}', $plugins->delete(...));
            $api->get('/plugins/{id}/configuration', $plugins->getConfiguration(...));
            $api->patch('/plugins/{id}/configuration', $plugins->updateConfiguration(...));
            $api->get('/plugins/{id}/configuration/form', $plugins->getConfigurationForm(...));
            $api->get('/plugins/{id}/configuration/schema', $plugins->getConfigurationSchema(...));
            $api->get('/plugins/{id}/dependencies', $plugins->dependencies(...));
            $api->patch('/plugins/{id}/dependencies', $plugins->updateDependencies(...));
            $api->post('/plugins/{id}/dependencies/install', $plugins->installDependencies(...));
            $api->get('/plugins/{id}/daemon', $plugins->daemon(...));
            $api->patch('/plugins/{id}/daemon', $plugins->updateDaemon(...));
            $api->post('/plugins/{id}/daemon/start', $plugins->startDaemon(...));
            $api->post('/plugins/{id}/daemon/stop', $plugins->stopDaemon(...));
            $api->get('/plugins/{id}/community-post', $plugins->communityPost(...));
            $api->get('/plugins/{id}/installations/latest', $plugins->latestInstallation(...));

            $updates = new UpdatesController();
            $api->get('/updates/core/branches', $updates->coreBranches(...));
            $api->get('/updates/core/maintenance', $updates->coreMaintenance(...));
            $api->post('/updates/core/maintenance', $updates->startCoreMaintenance(...));
            $api->post('/updates/check', $updates->check(...));
            $api->get('/updates/operations/latest', $updates->latest(...));
            $api->post('/updates/operations', $updates->start(...));
            $users = new UsersController();
            $api->get('/users', $users->list(...));
            $api->post('/users', $users->create(...));
            $api->get('/users/{id}', $users->get(...));
            $api->patch('/users/{id}', $users->update(...));
            $api->delete('/users/{id}', $users->delete(...));
            $api->get('/users/{id}/devices', $users->devices(...));
            $api->delete('/users/{id}/devices', $users->forgetDevices(...));
            $api->delete('/users/{id}/devices/{key}', $users->forgetDevice(...));
            $api->get('/users/{id}/sessions', $users->sessions(...));
            $api->delete('/users/{id}/sessions', $users->closeSessions(...));
            $api->delete('/users/{id}/sessions/{sessionId}', $users->closeSession(...));
            $api->get('/users/{id}/rights', $users->rights(...));
            $api->put('/users/{id}/rights', $users->replaceRights(...));
            $api->post('/users/{id}/rights/copy', $users->copyRights(...));

            $api->get('/me', $users->me(...));
            $api->patch('/me', $users->updateMe(...));
            $api->get('/me/token', $users->myToken(...));
            $api->post('/me/token', $users->regenerateMyToken(...));
            $api->get('/me/devices', $users->myDevices(...));
            $api->delete('/me/devices', $users->forgetMyDevices(...));
            $api->delete('/me/devices/{key}', $users->forgetMyDevice(...));
            $api->get('/me/sessions', $users->mySessions(...));
            $api->delete('/me/sessions/{sessionId}', $users->closeMySession(...));
            $api->delete('/session', (new SessionController())->close(...));
        })->add(new Auth());
        // Seule route sans authentification : l'ouverture de session.
        $app->post('/api/v1/session', (new SessionController())->open(...));

        // Toutes les erreurs sortent au format `Error` de la spec, y compris 404/405 de routage.
        $errors = $app->addErrorMiddleware(false, false, false);
        $errors->setDefaultErrorHandler(static function (Request $request, \Throwable $e) use ($app): Response {
            $error = match (true) {
                $e instanceof ApiError => $e,
                $e instanceof HttpNotFoundException => new ApiError(404, 'route_not_found', 'Route inconnue.'),
                $e instanceof HttpMethodNotAllowedException => new ApiError(405, 'method_not_allowed', 'Méthode non autorisée sur cette route.'),
                default => new ApiError(500, 'internal_error', 'Erreur interne.'),
            };
            if ($error->status === 500) {
                \log::add('api', 'error', $e->getMessage());
            }
            return Json::error($app->getResponseFactory()->createResponse(), $error);
        });
        return $app;
    }
}


