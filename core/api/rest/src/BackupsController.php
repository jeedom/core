<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;
use Slim\Psr7\Stream;

/** Sauvegardes du core et transferts vers les dépôts activés. */
final class BackupsController
{
    public function list(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $items = [];
        foreach (\jeedom::listBackupFiles() as $path) {
            $items[] = self::archive($path);
        }
        return self::items($response, $items);
    }

    public function create(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        self::start(static fn () => \jeedom::backup(true));
        return self::accepted($response, 'backup');
    }

    public function upload(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $files = $request->getUploadedFiles();
        if (($request->getParsedBody() ?? []) !== [] || array_keys($files) !== ['file'] || !$files['file'] instanceof UploadedFileInterface) {
            throw ApiError::field(422, 'validation_error', 'file', 'Un fichier .tar.gz est attendu.');
        }
        try {
            $path = \jeedom::uploadBackup($files['file']);
        } catch (\InvalidArgumentException $e) {
            if ($e->getCode() === 413) {
                throw new ApiError(413, 'backup_too_large', $e->getMessage());
            }
            throw ApiError::field(422, 'validation_error', 'file', $e->getMessage());
        } catch (\RuntimeException $e) {
            if ($e->getCode() === 409) {
                throw new ApiError(409, 'backup_already_exists', $e->getMessage());
            }
            throw $e;
        }
        return Json::respond($response, self::archive($path), 201);
    }

    public function download(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $path = self::find($args['id']);
        $handle = @fopen($path, 'rb');
        if ($handle === false) {
            throw new ApiError(404, 'backup_not_found', 'Sauvegarde introuvable ou illisible.');
        }
        $name = basename($path);
        $fallback = preg_replace('/[^A-Za-z0-9_.-]/', '_', $name);
        return $response->withBody(new Stream($handle))
            ->withHeader('Content-Type', 'application/gzip')
            ->withHeader('Content-Disposition', "attachment; filename=\"$fallback\"; filename*=UTF-8''" . rawurlencode($name))
            ->withHeader('X-Content-Type-Options', 'nosniff')
            ->withHeader('Cache-Control', 'private, no-store');
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        if (!\jeedom::removeBackup(self::find($args['id']))) {
            throw new ApiError(409, 'backup_not_removable', 'Impossible de supprimer cette sauvegarde.');
        }
        return $response->withStatus(204);
    }

    public function restore(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $path = self::find($args['id']);
        $tracking = self::start(static fn () => \jeedom::restore($path, true));
        // Seule une session Web reçoit le cookie de suivi.
        if ($tracking === null || $request->getAttribute('webSessionId') === null) {
            return self::accepted($response, 'restore');
        }
        $cookie = RestoreStatus::cookie($tracking, $request->getServerParams(), $request->getUri()->getScheme());
        return Json::respond($response->withAddedHeader('Set-Cookie', $cookie)->withHeader('Cache-Control', 'no-store'),
            ['log' => 'restore', 'operationId' => $tracking['id']], 202);
    }

    public function repositories(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $items = [];
        foreach (self::backupRepositories() as $id => $repo) {
            $items[] = ['id' => $id, 'name' => $repo['name']];
        }
        return self::items($response, $items);
    }

    public function cloudStatus(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $status = \repo_market::connectionStatus();
        return Json::respond($response->withHeader('Cache-Control', 'private, no-store'), [
            'connected' => $status['connected'],
            'subscriptionActive' => (string) \config::byKey('service::backup::enable') === '1',
        ]);
    }

    public function testSamba(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = Json::body($request, 16 * 1024, true);
        try {
            \repo_samba::test($body);
        } catch (\InvalidArgumentException $e) {
            throw new ApiError(422, 'validation_error', $e->getMessage());
        } catch (\Exception $e) {
            throw new ApiError(502, 'backup_repository_failed', 'Impossible d’accéder au partage Samba.');
        }
        return $response->withStatus(204);
    }

    public function remoteArchives(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $repo = self::repository($args['repositoryId']);
        $items = [];
        foreach (self::transfer(static fn () => $repo::backup_list_details()) as $archive) {
            $name = $archive['name'];
            if (self::validRemoteName($repo, $name)) {
                $items[] = ['id' => self::id($name), 'name' => $name, 'size' => $archive['size']];
            }
        }
        return self::items($response, $items);
    }

    public function import(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $repo = self::repository($args['repositoryId']);
        $name = self::name($args['id']);
        if (!self::validRemoteName($repo, $name)) {
            throw new ApiError(404, 'backup_not_found', 'Sauvegarde inconnue.');
        }
        self::transfer(static fn () => $repo::backup_restore($name));
        return $response->withStatus(204);
    }

    public function export(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $repo = self::repository('market');
        $path = self::find($args['id']);
        self::transfer(static fn () => $repo::backup_send($path));
        return $response->withStatus(204);
    }

    private static function archive(string $path): array
    {
        $name = basename($path);
        $stat = @stat($path);
        return ['id' => self::id($name), 'name' => $name, 'size' => $stat === false ? null : $stat['size'],
            'modifiedAt' => $stat === false ? null : gmdate(DATE_ATOM, $stat['mtime'])];
    }

    private static function items(Response $response, array $items): Response
    {
        return Json::respond($response, ['items' => $items, 'total' => count($items)]);
    }

    private static function accepted(Response $response, string $log): Response
    {
        return Json::respond($response, ['log' => $log], 202);
    }

    private static function start(callable $action): ?array
    {
        try {
            return $action();
        } catch (\RuntimeException $e) {
            if ($e->getCode() === 409) {
                throw new ApiError(409, 'backup_operation_in_progress', $e->getMessage());
            }
            throw $e;
        }
    }

    // L'identifiant ne dépend que du nom. L'alphabet URL évite le double décodage
    // par le serveur et le routeur des noms contenant notamment un signe %.
    private static function id(string $name): string
    {
        return rtrim(strtr(base64_encode($name), '+/', '-_'), '=');
    }

    private static function name(string $id): string
    {
        if ($id === '' || strlen($id) > 340) {
            throw new ApiError(404, 'backup_not_found', 'Sauvegarde inconnue.');
        }
        $name = base64_decode(strtr($id, '-_', '+/'), true);
        if ($name === false || self::id($name) !== $id) {
            throw new ApiError(404, 'backup_not_found', 'Sauvegarde inconnue.');
        }
        return $name;
    }

    private static function find(string $id): string
    {
        $path = \jeedom::getBackupPath(self::name($id));
        if ($path === null) {
            throw new ApiError(404, 'backup_not_found', 'Sauvegarde inconnue.');
        }
        return $path;
    }

    private static function backupRepositories(): array
    {
        return array_intersect_key(\update::listBackupRepos(), ['market' => true, 'samba' => true]);
    }

    private static function repository(string $id): string
    {
        $repo = self::backupRepositories()[$id] ?? null;
        if ($repo === null) {
            throw new ApiError(404, 'backup_repository_not_found', 'Dépôt de sauvegardes inconnu ou désactivé.');
        }
        return $repo['class'];
    }

    private static function validRemoteName(string $repo, mixed $name): bool
    {
        if (!is_string($name) || strlen($name) > 255) {
            return false;
        }
        if ($repo === \repo_market::class) {
            return str_ends_with($name, '.tar.gz.gpg') && \jeedom::isBackupImportName('cloud-' . substr($name, 0, -4))
                && \jeedom::isBackupFileName(substr($name, 0, -4));
        }
        return $repo === \repo_samba::class && \repo_samba::isBackupRestoreName($name);
    }

    private static function transfer(callable $action): mixed
    {
        try {
            return $action();
        } catch (\Exception $e) {
            if ($e->getCode() === 404) {
                throw new ApiError(404, 'backup_not_found', 'Sauvegarde distante introuvable.');
            }
            // Certaines erreurs des dépôts contiennent la commande ou l'URL utilisée.
            throw new ApiError(502, 'backup_repository_failed', 'Le transfert ou la lecture du dépôt de sauvegardes a échoué.');
        }
    }
}
