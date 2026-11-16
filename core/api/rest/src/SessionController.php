<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class SessionController
{
    private const ACCESS_COOKIE = '__Host-JeedomAccess';
    private const REFRESH_COOKIE = '__Secure-JeedomRefresh';

    public function open(Request $request, Response $response): Response
    {
        RequestSecurity::requireHttps($request);
        $body = Json::body($request, 8192);
        $mode = $body['mode'] ?? 'web';
        if (!in_array($mode, ['web', 'api'], true)) {
            throw ApiError::field(422, 'validation_error', 'mode', 'Choisissez web ou api.');
        }
        if ($request->hasHeader('Authorization') || $mode === 'api' && (self::cookie($request) !== null || self::refreshCookie($request) !== null)) {
            self::mixed();
        }
        if ($mode === 'web') {
            RequestSecurity::requireOrigin($request);
        }
        foreach (['login', 'password'] as $field) {
            if (!is_string($body[$field] ?? null) || $body[$field] === '') {
                throw ApiError::field(422, 'validation_error', $field, 'Ce champ est obligatoire.');
            }
        }
        $remember = $body['rememberDevice'] ?? ($mode === 'web');
        if (!is_bool($remember) || $mode === 'api' && $remember) {
            throw ApiError::field(422, 'validation_error', 'rememberDevice', 'Option réservée à une session Web.');
        }
        $name = self::clientName($body['clientName'] ?? ($mode === 'web' ? 'Core UI' : 'Client API'));
        $user = self::authenticate($body['login'], $body['password'], $body['twoFactorCode'] ?? null, $mode);
        try {
            $connection = \session::create($user, $remember, \getClientIp(), self::refreshCookie($request),
                mode: $mode, name: $name, userAgent: $request->getHeaderLine('User-Agent'));
        } catch (\DomainException $error) {
            throw new ApiError(401, 'invalid_credentials', $error->getMessage());
        }
        \jeedom::event('user_connect', false, ['trigger_value' => $user->getLogin()]);
        return self::respond($response, $connection, $mode);
    }

    public function inspect(Request $request, Response $response): Response
    {
        RequestSecurity::requireHttps($request);
        RequestSecurity::requireOrigin($request, false);
        if ($request->hasHeader('Authorization')) {
            self::mixed();
        }
        $access = Auth::identity(self::cookie($request), 'web');
        $refresh = Auth::identity(self::refreshCookie($request), 'web', true);
        // A lost rotation response can leave the old cookie (and the previously read CSRF).
        // The client retries with the same request key. The endpoint never rotates on GET.
        $identity = $refresh ?? $access;
        if ($identity === null) {
            $identity = \session::retryContext(self::refreshCookie($request), $request->getHeaderLine('Idempotency-Key'));
            if ($identity !== null && !Auth::accountAllowed($identity['user'], 'web')) {
                $identity = null;
            }
        }
        if ($identity === null) {
            throw self::expired();
        }
        return Json::respond($response->withHeader('Cache-Control', 'no-store'), [
            'csrfToken' => $identity['session']['csrf_token'],
            'sessionId' => $identity['session']['id'],
            'accessValid' => $access !== null && $access['session']['id'] === $identity['session']['id'],
        ]);
    }

    public function refresh(Request $request, Response $response): Response
    {
        RequestSecurity::requireHttps($request);
        $body = Json::body($request, 2048);
        $web = !array_key_exists('refreshToken', $body);
        if ($request->hasHeader('Authorization') || !$web && (self::cookie($request) !== null || self::refreshCookie($request) !== null)) {
            self::mixed();
        }
        if ($web) {
            RequestSecurity::requireOrigin($request);
        }
        $token = $web ? self::refreshCookie($request) : $body['refreshToken'];
        if (!is_string($token)) {
            throw self::expired();
        }
        $attempt = $request->getHeaderLine('Idempotency-Key');
        if (!preg_match('/\A[a-zA-Z0-9_-]{16,128}\z/', $attempt)) {
            throw ApiError::field(422, 'validation_error', 'Idempotency-Key', 'Clé de renouvellement invalide.');
        }
        $mode = $web ? 'web' : 'api';
        $connection = \session::refresh($token, $mode, $attempt, static function (\user $user, array $row) use ($web, $request): void {
            if (!Auth::accountAllowed($user, $row['mode'], $row['scope'])) {
                throw self::expired();
            }
            if ($web) {
                self::requireCsrf($request, $row);
            }
        }, \getClientIp(), $request->getHeaderLine('User-Agent'));
        if ($connection === null) {
            throw self::expired();
        }
        return self::respond($response, $connection, $mode);
    }

    public function openSupport(Request $request, Response $response): Response
    {
        RequestSecurity::requireHttps($request);
        RequestSecurity::requireOrigin($request);
        if ($request->hasHeader('Authorization') || !str_starts_with(strtolower($request->getHeaderLine('Content-Type')), 'application/json')) {
            throw new ApiError(403, 'support_access_unavailable', 'Accès support indisponible.');
        }
        $body = Json::body($request, 1024, true);
        if (!is_string($body['grant'] ?? null) || !is_string($body['code'] ?? null) || count($body) !== 2) {
            throw new ApiError(422, 'validation_error', 'Demande de connexion support invalide.');
        }
        $previous = self::refreshCookie($request);
        $current = Auth::identity(self::cookie($request), 'web');
        if ($current !== null && $current['user']->getLogin() !== \supportAccess::LOGIN) {
            throw new ApiError(409, 'support_session_conflict', 'Ouvrez l’accès support dans un navigateur sans session Jeedom active.');
        }
        try {
            $connection = \supportAccess::connect($body['grant'], $body['code'], $previous, \getClientIp(), $request->getHeaderLine('User-Agent'));
        } catch (\Exception) {
            throw new ApiError(403, 'support_access_unavailable', 'Accès support indisponible. Relancez la connexion depuis le Market.');
        }
        return self::withCookies($response, $connection)->withStatus(204);
    }

    public function close(Request $request, Response $response): Response
    {
        RequestSecurity::requireHttps($request);
        [$transport, $access] = Auth::access($request);
        $identity = Auth::identity($access, $transport);
        if ($transport === 'web') {
            RequestSecurity::requireOrigin($request);
            $identity = Auth::identity(self::refreshCookie($request), 'web', true) ?? $identity;
            if ($identity === null) {
                $retry = \session::retryContext(self::refreshCookie($request), $request->getHeaderLine('Idempotency-Key'));
                if ($retry !== null && Auth::accountAllowed($retry['user'], 'web')) {
                    $identity = $retry;
                }
            }
            if ($identity !== null) {
                self::requireCsrf($request, $identity['session']);
            }
            $response = self::clearCookie($request, $response);
        }
        if ($identity !== null) {
            \session::revoke($identity['user'], $identity['session']['id']);
        }
        return $response->withStatus(204)->withHeader('Cache-Control', 'no-store');
    }

    public static function authenticate(string $login, string $password, mixed $twoFactor, string $mode): \user
    {
        if (\user::isBan()) {
            throw new ApiError(403, 'ip_banned', 'Adresse IP bannie après trop d’échecs de connexion.');
        }
        if ($twoFactor !== null && !is_string($twoFactor)) {
            throw ApiError::field(422, 'validation_error', 'twoFactorCode', 'Un texte est attendu.');
        }
        $user = \user::connect($login, $password);
        if (!$user instanceof \user || !$user->isValidAndEnabled() || $user->getLogin() === \supportAccess::LOGIN
            || !Auth::accountAllowed($user, $mode)) {
            self::refuse($login, 'invalid_credentials', 'Identifiant ou mot de passe incorrect.');
        }
        if ((int) $user->getOptions('twoFactorAuthentification', 0) === 1) {
            if ($twoFactor === null || trim($twoFactor) === '') {
                throw new ApiError(401, 'two_factor_required', 'Double authentification requise.');
            }
            if (!preg_match('/\A[0-9]{6}\z/', $twoFactor) || $user->getOptions('twoFactorAuthentificationSecret') === '' || !$user->validateTwoFactorCode($twoFactor)) {
                self::refuse($login, 'two_factor_invalid', 'Code de double authentification invalide.');
            }
        }
        return $user;
    }

    public static function clientName(mixed $name): string
    {
        if (!is_string($name) || trim($name) === '' || mb_strlen(trim($name)) > 100 || preg_match('/[\x00-\x1F\x7F]/', $name)) {
            throw ApiError::field(422, 'validation_error', 'clientName', 'Un nom de 1 à 100 caractères est attendu.');
        }
        return trim($name);
    }

    public static function cookie(Request $request): ?string
    {
        return self::readCookie($request, self::ACCESS_COOKIE);
    }

    public static function refreshCookie(Request $request): ?string
    {
        return self::readCookie($request, self::REFRESH_COOKIE);
    }

    public static function clearCookie(Request $request, Response $response): Response
    {
        return self::withCookies($response, ['token' => '', 'refreshToken' => '', 'expires' => 1, 'refreshExpires' => 1, 'remembered' => true]);
    }

    public static function refreshPayload(array $connection): array
    {
        return ['refreshToken' => $connection['refreshToken'], 'refreshExpiresAt' => gmdate(DATE_ATOM, $connection['refreshExpires'])];
    }

    private static function readCookie(Request $request, string $name): ?string
    {
        $token = $request->getCookieParams()[$name] ?? null;
        return is_string($token) && $token !== '' ? $token : null;
    }

    private static function respond(Response $response, array $connection, string $mode): Response
    {
        if ($mode === 'api') {
            return Json::respond($response->withHeader('Cache-Control', 'no-store'), [
                'accessToken' => $connection['token'], 'accessExpiresAt' => gmdate(DATE_ATOM, $connection['expires']),
            ] + self::refreshPayload($connection));
        }
        $csrf = $connection['csrfToken'];
        return Json::respond(self::withCookies($response, $connection), ['user' => UsersController::meArray($connection['user'], $csrf), 'csrfToken' => $csrf]);
    }

    private static function withCookies(Response $response, array $connection): Response
    {
        $expires = static fn (int $time): string => '; Expires=' . gmdate('D, d M Y H:i:s \G\M\T', $time);
        $flags = '; HttpOnly; Secure; SameSite=Strict';
        return $response->withHeader('Cache-Control', 'no-store')
            ->withAddedHeader('Set-Cookie', self::ACCESS_COOKIE . '=' . $connection['token'] . '; Path=/' . $flags . ($connection['remembered'] ? $expires($connection['expires']) : ''))
            ->withAddedHeader('Set-Cookie', self::REFRESH_COOKIE . '=' . $connection['refreshToken'] . '; Path=/api/v1/session' . $flags
                . ($connection['remembered'] ? $expires($connection['refreshExpires']) : ''));
    }

    private static function requireCsrf(Request $request, array $row): void
    {
        if (!hash_equals($row['csrf_token'], $request->getHeaderLine('X-CSRF-Token'))) {
            throw new ApiError(403, 'csrf_invalid', 'Jeton CSRF absent ou invalide.');
        }
    }

    private static function expired(): ApiError
    {
        return new ApiError(401, 'session_expired', 'Session expirée, reconnectez-vous.');
    }

    private static function mixed(): never
    {
        throw new ApiError(403, 'mixed_credentials', 'Les justificatifs Web et API doivent être utilisés séparément.');
    }

    private static function refuse(string $login, string $code, string $reason): never
    {
        \user::failedLogin(['login' => $login, 'reason' => $reason]);
        sleep(5);
        throw new ApiError(401, $code, $reason);
    }
}
