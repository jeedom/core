<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Session PHP de la nouvelle UI, avec les mêmes réglages de cookie que
 * core/php/authentification.php (HttpOnly, SameSite=Strict, __Host- en HTTPS), et le
 * jeton CSRF exigé sur les écritures.
 */
final class Session
{
    private const CSRF_KEY = 'apiCsrfToken';

    public static function name(): string
    {
        return self::isHttps() ? '__Host-PHPSESSID' : 'PHPSESSID';
    }

    /** Vrai si la requête porte un cookie de session. */
    public static function present(ServerRequestInterface $request): bool
    {
        return ($request->getCookieParams()[self::name()] ?? '') !== '';
    }

    /** Ouvre la session PHP (celle du cookie s'il y en a un) en écriture. */
    public static function open(ServerRequestInterface $request): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        $lifetime = \config::byKey('session_lifetime');
        $lifetime = is_numeric($lifetime) ? (int) $lifetime : 24;
        ini_set('session.gc_maxlifetime', (string) ($lifetime * 3600));
        ini_set('session.use_cookies', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_strict_mode', '1');
        ini_set('session.sid_length', '64');
        session_name(self::name());
        session_set_cookie_params(['lifetime' => $lifetime * 3600, 'path' => '/', 'secure' => self::isHttps(), 'httponly' => true, 'samesite' => 'Strict']);
        $id = (string) ($request->getCookieParams()[self::name()] ?? '');
        if ($id !== '' && preg_match('/^[A-Za-z0-9,-]{22,256}$/', $id)) {
            session_id($id);
        }
        @session_start();
    }

    public static function close(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            @session_write_close();
        }
    }

    /** Utilisateur de la session, avec les mêmes contrôles qu'isConnect(). */
    public static function user(): ?\user
    {
        $stored = $_SESSION['user'] ?? null;
        if (!is_object($stored)) {
            return null;
        }
        $user = \user::byId($stored->getId());
        if (!is_object($user) || !$user->isValidAndEnabled()) {
            return null;
        }
        if ((int) $user->getOptions('localOnly', 0) === 1 && \network::getUserLocation() !== 'internal') {
            return null;
        }
        return $user;
    }

    public static function login(\user $user): void
    {
        $_SESSION['user'] = $user;
        @session_regenerate_id(true);
        $_SESSION[self::CSRF_KEY] = bin2hex(random_bytes(32));
    }

    public static function csrfToken(): ?string
    {
        $token = $_SESSION[self::CSRF_KEY] ?? null;
        if (!is_string($token) || $token === '') {
            if (!isset($_SESSION['user'])) {
                return null;
            }
            // Session ouverte par l'ancienne interface : le jeton est créé à la première lecture.
            $token = $_SESSION[self::CSRF_KEY] = bin2hex(random_bytes(32));
        }
        return $token;
    }

    public static function checkCsrf(ServerRequestInterface $request): bool
    {
        $token = self::csrfToken();
        return $token !== null && hash_equals($token, $request->getHeaderLine('X-CSRF-Token'));
    }

    public static function destroy(bool $forgetDevice): void
    {
        $user = $_SESSION['user'] ?? null;
        if (is_object($user)) {
            \log::audit('User logout', ['login' => $user->getLogin(), 'ip' => getClientIp()]);
        }
        $_SESSION = [];
        @session_destroy();
        $expired = ['expires' => time() - 3600, 'path' => '/', 'secure' => self::isHttps(), 'httponly' => true, 'samesite' => 'Strict'];
        @setcookie(self::name(), '', $expired);
        if ($forgetDevice) {
            @setcookie('registerDevice', '', $expired);
        }
    }

    private static function isHttps(): bool
    {
        return ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https' || (($_SERVER['HTTPS'] ?? '') !== '' && $_SERVER['HTTPS'] !== 'off');
    }
}
