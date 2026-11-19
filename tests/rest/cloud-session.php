<?php
declare(strict_types=1);

require dirname(__DIR__, 2) . '/vendor/autoload.php';
require dirname(__DIR__, 2) . '/core/class/session.class.php';

use Jeedom\Api\Rest\ApiError;
use Jeedom\Api\Rest\Auth;
use Jeedom\Api\Rest\CloudService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Response;

final class user {
    public static function byId($id): self { return new self(); }
    public static function isBan(): bool { return false; }
    public function isValidAndEnabled(): bool { return true; }
    public function getLogin(): string { return CloudService::LOGIN; }
    public function getPassword(): string { return 'fixture'; }
    public function getOptions($key, $default = '') { return $key === 'cloudService::technical' ? 1 : $default; }
}

final class DB {
    public static array $session;
    public static array $updates = [];

    public static function Prepare(string $sql, array $params): ?array {
        if (str_starts_with($sql, 'SELECT')) {
            return hash_equals(self::$session['token_hash'], $params['hash']) ? self::$session : null;
        }
        self::$updates[] = $params;
        if (isset($params['userAgent'])) {
            self::$session['user_agent'] = $params['userAgent'];
        }
        return null;
    }
}

$token = str_repeat('a', 64);
DB::$session = [
    'id' => str_repeat('b', 64), 'user_id' => 1, 'mode' => 'api', 'scope' => CloudService::SCOPE,
    'token_hash' => hash('sha256', $token), 'credential_hash' => hash('sha256', implode("\0", ['fixture', 0, ''])),
    'ip' => '192.0.2.1', 'user_agent' => 'axios/1.19.0', 'last_activity_at' => gmdate('Y-m-d H:i:s'),
];
$handler = new class implements RequestHandlerInterface {
    public function handle(ServerRequestInterface $request): ResponseInterface {
        CloudService::requireSession($request);
        return new Response(204);
    }
};
$request = (new ServerRequestFactory())->createServerRequest('GET', 'https://box.example.test/api/v1/plugins/gsh/api/cloud-service')
    ->withHeader('Authorization', 'Bearer ' . $token)->withHeader('User-Agent', 'Jeedom cloud service');
$auth = new Auth();
$auth->process($request, $handler);
$auth->process($request, $handler);
$auth->process($request->withoutHeader('User-Agent'), $handler);
try {
    $auth->process($request->withUri($request->getUri()->withPath('/api/v1/objects')), $handler);
    throw new RuntimeException('Cloud session reached a route outside its scope.');
} catch (ApiError $error) {
    if ($error->errorCode !== 'scope_forbidden') {
        throw $error;
    }
}
if (DB::$updates !== [['id' => DB::$session['id'], 'userAgent' => 'Jeedom cloud service', 'previousUserAgent' => 'axios/1.19.0']]) {
    throw new RuntimeException('Cloud callback must update the stored User-Agent once, while preserving the session IP.');
}
echo "OK Cloud session User-Agent follows the authenticated callback\n";
