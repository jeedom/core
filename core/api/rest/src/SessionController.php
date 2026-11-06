<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Ressource /session : connexion et déconnexion de la nouvelle UI. Mêmes étapes que
 * login() de core/php/authentification.php et l'ajax user::login, détaillées ici pour
 * renvoyer une erreur précise à l'UI (login() ne dit pas pourquoi il refuse).
 */
final class SessionController
{
    public function open(Request $request, Response $response): Response
    {
        if (\user::isBan()) {
            throw new ApiError(403, 'ip_banned', 'Adresse IP bannie après trop d\'échecs de connexion.');
        }
        $body = Json::body($request);
        foreach (['login', 'password'] as $field) {
            if (!is_string($body[$field] ?? null) || $body[$field] === '') {
                throw ApiError::field(422, 'validation_error', $field, 'Ce champ est obligatoire.');
            }
        }
        $twoFactor = $body['twoFactorCode'] ?? null;
        if ($twoFactor !== null && !is_string($twoFactor)) {
            throw ApiError::field(422, 'validation_error', 'twoFactorCode', 'Un texte est attendu.');
        }
        $remember = $body['rememberDevice'] ?? false;
        if (!is_bool($remember)) {
            throw ApiError::field(422, 'validation_error', 'rememberDevice', 'Un booléen est attendu.');
        }
        $login = $body['login'];
        $user = \user::connect($login, $body['password']);
        if (!is_object($user) || (int) $user->getEnable() === 0) {
            self::refuse($login, 'invalid_credentials', 'Nom d\'utilisateur ou mot de passe invalide ou utilisateur désactivé');
        }
        if ((int) $user->getOptions('localOnly', 0) === 1 && \network::getUserLocation() !== 'internal') {
            self::refuse($login, 'invalid_credentials', 'Utilisateur local uniquement');
        }
        if (\network::getUserLocation() !== 'internal' && (int) $user->getOptions('twoFactorAuthentification', 0) === 1
            && $user->getOptions('twoFactorAuthentificationSecret') != '') {
            // Comme l'ajax : un code absent n'est pas compté comme un échec.
            if ($twoFactor === null || trim($twoFactor) === '') {
                throw new ApiError(401, 'two_factor_required', 'Double authentification requise.');
            }
            if (!$user->validateTwoFactorCode($twoFactor)) {
                self::refuse($login, 'two_factor_invalid', 'Code d\'authentification à deux facteurs invalide ou manquant');
            }
        }
        Session::open($request);
        Session::login($user);
        if ($remember) {
            self::registerDevice($user);
        }
        $token = Session::csrfToken();
        Session::close();
        \jeedom::event('user_connect', false, ['trigger_value' => $login]);
        \log::audit('User login', ['login' => $login, 'ip' => getClientIp()]);
        return Json::respond($response, ['user' => App::me($user, $token), 'csrfToken' => $token]);
    }

    public function close(Request $request, Response $response): Response
    {
        $forget = filter_var($request->getQueryParams()['forgetDevice'] ?? false, FILTER_VALIDATE_BOOL);
        if ($request->getAttribute('csrfToken') !== null) {
            Session::open($request);
            Session::destroy($forget);
        }
        return $response->withStatus(204);
    }

    /** Échec compté pour le bannissement, avec le même délai que login(). */
    private static function refuse(string $login, string $code, string $reason): never
    {
        \user::failedLogin(['login' => $login, 'reason' => __($reason, 'core/php/authentification.php')]);
        sleep(5);
        throw new ApiError(401, $code, $code === 'two_factor_invalid' ? 'Code de double authentification invalide.' : 'Identifiant ou mot de passe incorrect.');
    }

    /** « Rester connecté », comme l'ajax user::login avec storeConnection. */
    private static function registerDevice(\user $user): void
    {
        $key = \config::genKey();
        $devices = $user->getOptions('registerDevice', []);
        if (!is_array($devices)) {
            $devices = [];
        }
        $devices[sha512($key)] = ['datetime' => date('Y-m-d H:i:s'), 'ip' => getClientIp(), 'session_id' => session_id()];
        $user->setOptions('registerDevice', $devices);
        $user->save();
        @setcookie('registerDevice', sha512($user->getHash()) . '-' . $key, [
            'expires' => time() + \user::registerDeviceLifetime(), 'path' => '/', 'httponly' => true, 'samesite' => 'Strict',
            'secure' => ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https',
        ]);
    }
}
