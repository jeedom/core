<?php
declare(strict_types=1);

use Jeedom\Api\Rest\ApiError;
use Jeedom\Api\Rest\LogExcerptReader;
use Jeedom\Api\Rest\LogsController;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

final class log
{
    public static string $directory;

    public static function getPathToLog(string $name): string
    {
        return self::$directory . '/' . $name;
    }

    public static function liste(): array
    {
        return array_values(array_diff(scandir(self::$directory), ['.', '..']));
    }

    public static function remove(string $name, bool $clearProtected = true): ?bool
    {
        $path = self::getPathToLog($name);
        if ($name === 'http.error') {
            if ($clearProtected) {
                file_put_contents($path, '');
            }
            return null;
        }
        return unlink($path);
    }
}

function expectSame(mixed $expected, mixed $actual, string $message): void
{
    if ($expected !== $actual) {
        throw new RuntimeException($message . ': ' . var_export($actual, true));
    }
}

function excerpt(array $query = [], string $name = 'test', string $profile = 'admin'): array
{
    $user = new class($profile) {
        public function __construct(private string $profile) {}
        public function getProfils(): string { return $this->profile; }
    };
    $request = (new ServerRequestFactory())->createServerRequest('GET', '/api/v1/logs/' . $name . '/excerpt')
        ->withAttribute('user', $user)
        ->withQueryParams($query);
    $response = (new LogsController())->excerpt($request, (new ResponseFactory())->createResponse(), ['name' => $name]);
    expectSame('private, no-store', $response->getHeaderLine('Cache-Control'), 'Aucun cache des extraits');
    return json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
}

function expectError(int $status, string $code, callable $read): void
{
    try {
        $read();
    } catch (ApiError $error) {
        expectSame($status, $error->status, 'Statut HTTP');
        expectSame($code, $error->errorCode, 'Code de l’erreur');
        return;
    }
    throw new RuntimeException('Erreur attendue : ' . $code);
}

log::$directory = sys_get_temp_dir() . '/jeedom-rest-log-' . bin2hex(random_bytes(8));
mkdir(log::$directory);
$path = log::getPathToLog('test');
$protectedPath = log::getPathToLog('http.error');

try {
    file_put_contents($path, "Un\nDeux\n  Trois\r\n\nQuatre");
    $tail = excerpt(['limit' => '3']);
    expectSame(['  Trois', ''], $tail['lines'], 'Fin du journal, CRLF, indentation et ligne vide');
    expectSame('Quatre', $tail['fragment'], 'Fragment final');
    expectSame(false, $tail['hasMore'], 'La fin tient dans un seul extrait');

    $older = excerpt(['before' => $tail['previousCursor'], 'limit' => '3']);
    expectSame(['Un', 'Deux'], $older['lines'], 'Extrait précédent');
    expectSame(null, $older['previousCursor'], 'Début du fichier');
    expectSame(true, $older['hasMore'], 'Pagination vers l’avant disponible');
    expectSame($tail, excerpt(['after' => $older['nextCursor'], 'limit' => '3']), 'Navigation dans les deux sens');

    file_put_contents($path, " suite\nCinq\n", FILE_APPEND);
    $delta = excerpt(['after' => $tail['nextCursor']]);
    expectSame(['Quatre suite', 'Cinq'], $delta['lines'], 'La ligne complète remplace son fragment');
    expectSame(null, $delta['fragment'], 'Aucun fragment restant');
    expectSame(false, $delta['reset'], 'Un ajout ne change pas le curseur');
    expectSame([], excerpt(['after' => $delta['nextCursor']])['lines'], 'Aucune répétition');

    // Même inode après troncature et repousse, comme avec log::chunkLog().
    $inode = fileinode($path);
    file_put_contents($path, str_repeat("Journal après réécriture\n", 100) . "Nouvelle fin\n");
    clearstatcache(true, $path);
    expectSame($inode, fileinode($path), 'Réécriture en place');
    $reset = excerpt(['after' => $delta['nextCursor'], 'limit' => '2']);
    expectSame(true, $reset['reset'], 'Détecte la réécriture malgré la nouvelle taille');
    expectSame(['Journal après réécriture', 'Nouvelle fin'], $reset['lines'], 'Renvoie la fin actuelle');

    file_put_contents($path, '');
    $cleared = excerpt(['after' => $reset['nextCursor']]);
    expectSame(true, $cleared['reset'], 'Détecte un journal vidé');
    expectSame([], $cleared['lines'], 'Journal vide');

    $file = fopen($path, 'wb');
    ftruncate($file, 24 * 1024 * 1024);
    fseek($file, 0, SEEK_END);
    fwrite($file, "\nPremier\nDernier\n");
    fclose($file);
    clearstatcache(true, $path);
    expectSame(['Premier', 'Dernier'], excerpt(['limit' => '2'])['lines'], 'Lecture depuis la fin d’un gros fichier');

    $line = str_repeat('a', 65535) . "\n";
    file_put_contents($path, str_repeat($line, 20));
    $large = excerpt(['limit' => '1000']);
    expectSame(16, count($large['lines']), 'Extrait de fin borné à 1 Mio');
    $back = excerpt(['before' => $large['previousCursor'], 'limit' => '1000']);
    expectSame(4, count($back['lines']), 'La limite de taille conserve les lignes précédentes');
    expectSame($large, excerpt(['after' => $back['nextCursor'], 'limit' => '1000']), 'La pagination respecte la limite en octets');

    file_put_contents($path, str_repeat('x', 65536) . "\n");
    expectError(413, 'log_line_too_long', fn () => excerpt());
    expectError(422, 'validation_error', fn () => excerpt(['after' => ['invalide']]));
    expectError(422, 'validation_error', fn () => excerpt(['before' => $delta['nextCursor'], 'after' => $delta['nextCursor']]));
    expectError(404, 'log_not_found', fn () => excerpt([], 'inconnu'));
    expectError(403, 'forbidden', fn () => excerpt([], 'test', 'user'));

    $scenarioPath = log::getPathToLog('scenario');
    $request = (new ServerRequestFactory())->createServerRequest('GET', '/api/v1/scenarios/1/log/excerpt');
    $responseFactory = new ResponseFactory();
    $response = LogExcerptReader::respond($request, $responseFactory->createResponse(), $scenarioPath, allowMissing: true);
    $empty = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    expectSame([], $empty['lines'], 'Journal de scénario avant le premier lancement');
    file_put_contents($scenarioPath, "Démarrage\n");
    try {
        $response = LogExcerptReader::respond($request->withQueryParams(['after' => $empty['nextCursor']]),
            $responseFactory->createResponse(), $scenarioPath, allowMissing: true);
        $started = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        expectSame(['Démarrage'], $started['lines'], 'Suivi du premier lancement');
        expectSame(true, $started['reset'], 'Le journal du scénario a été créé');
    } finally {
        unlink($scenarioPath);
    }

    file_put_contents($protectedPath, "Journal Apache\n");
    $request = (new ServerRequestFactory())->createServerRequest('DELETE', '/api/v1/logs')
        ->withAttribute('user', new class {
            public function getProfils(): string { return 'admin'; }
        });
    $controller = new LogsController();
    $append = fn (string $name) => $controller->add($request->withMethod('POST'), $responseFactory->createResponse(), ['name' => $name]);
    expectError(422, 'validation_error', fn () => $append('.htaccess'));
    expectError(422, 'validation_error', fn () => $append('../outside'));
    expectError(422, 'validation_error', fn () => $append('new.php'));
    $link = log::getPathToLog('alias');
    symlink($path, $link);
    try {
        expectError(422, 'validation_error', fn () => $append('alias'));
    } finally {
        unlink($link);
    }

    $delete = fn (string $name) => json_decode((string) $controller->delete(
        $request, $responseFactory->createResponse(), ['name' => $name]
    )->getBody(), true, 512, JSON_THROW_ON_ERROR);
    expectSame(['removed' => false], $delete('http.error'), 'Le journal protégé ne peut pas être supprimé');
    expectSame("Journal Apache\n", file_get_contents($protectedPath), 'La suppression ne vide pas le journal protégé');
    expectSame(['removed' => true], $delete('test'), 'Le journal ordinaire est supprimé');
    expectSame(false, file_exists($path), 'Le fichier ordinaire est retiré');
    expectError(404, 'log_not_found', fn () => excerpt());

    file_put_contents($path, "Autre journal\n");
    $response = $controller->deleteAll($request, $responseFactory->createResponse());
    $result = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    expectSame(['removed' => ['test'], 'retained' => ['http.error']], $result, 'Suppression groupée des journaux autorisés');
    expectSame(false, file_exists($path), 'La suppression groupée retire le fichier ordinaire');
    expectSame("Journal Apache\n", file_get_contents($protectedPath), 'La suppression groupée conserve le contenu protégé');
} finally {
    foreach ([$path, $protectedPath] as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    rmdir(log::$directory);
}

echo "Journaux REST : OK\n";
