<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use PragmaRX\Google2FA\Google2FA as Totp;
use PragmaRX\Google2FAQRCode\Google2FA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Ressources /users (administrateurs) et /me (son propre compte). Reprend les
 * garde-fous de core/ajax/user.ajax.php : liste blanche de champs, comptes système
 * intouchables, pas d'auto-désactivation, création et suppression refusées si LDAP est
 * actif (le changement de mot de passe reste permis, comme l'ajax `save`/`saveProfils`).
 * Le mot de passe, le `hash` et la double authentification ne sortent jamais de /users.
 */
final class UsersController
{
    public const SYSTEM_LOGINS = ['internal_report', 'jeedom_support', \supportAccess::LOGIN];
    private const PROFILES = ['admin', 'user', 'restrict'];
    private const KINDS = ['human', 'system', 'plugin'];
    private const API_MODES = ['enable', 'whiteip', 'localhost', 'disable'];
    private const RIGHT_KEY = '/^((eqLogic|jeeObject|scenario|view|plan|plan3d)[0-9]+|menu::[A-Za-z0-9_]+)$/';
    private const RIGHT_VALUES = ['n', 'r', 'rx'];
    /** Préférences de `MePreferences` : true = entier ou null, sinon type particulier. */
    private const PREFERENCES = [
        'homePage' => 'string', 'homePageMobile' => 'string',
        'defaultDashboardObject' => 'id', 'defaultMobileObject' => 'id', 'defaultDesktopView' => 'id', 'defaultMobileView' => 'id',
        'defaultDashboardPlan' => 'id', 'defaultMobilePlan' => 'id', 'defaultDashboardPlan3d' => 'id', 'defaultMobilePlan3d' => 'id',
        'defaultPlanFullScreen' => 'flag', 'defaultPlan3dFullScreen' => 'flag', 'displayViewByDefault' => 'flag',
        'notification::cmd' => 'cmd',
    ];

    // ---------------------------------------------------------------- /users

    public function list(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $params = $request->getQueryParams();
        $profils = self::enumParam($params, 'profils', self::PROFILES);
        $kind = self::enumParam($params, 'kind', self::KINDS);
        $enable = null;
        if (isset($params['enable'])) {
            $enable = filter_var($params['enable'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
            if ($enable === null) {
                throw ApiError::field(422, 'validation_error', 'enable', 'Un booléen est attendu (true ou false).');
            }
        }
        $items = [];
        $accounts = [];
        foreach (\user::all() as $user) {
            $item = self::toArray($user);
            if (($profils === null || $item['profils'] === $profils) && ($kind === null || $item['kind'] === $kind)
                && ($enable === null || $item['enable'] === $enable)) {
                $accounts[$item['id']] = $user;
                $items[$item['id']] = $item + ['lastActivity' => null];
            }
        }
        foreach (\session::activeConnections($accounts) as $session) {
            $id = $session['user_id'];
            $activity = self::sessionDate($session['last_activity_at']);
            if ($items[$id]['lastActivity'] === null || $activity > $items[$id]['lastActivity']) {
                $items[$id]['lastActivity'] = $activity;
            }
        }
        return Json::respond($response->withHeader('Cache-Control', 'no-store'), [
            'items' => array_values($items), 'total' => count($items),
        ]);
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        return Json::respond($response, self::toArray(self::find($args['id'])));
    }

    public function create(Request $request, Response $response): Response
    {
        Auth::requireAccountAdmin($request);
        $me = $request->getAttribute('user');
        $body = Json::body($request);
        self::rejectUnknown($body, ['login', 'password']);
        foreach (['login', 'password'] as $field) {
            if (!array_key_exists($field, $body)) {
                throw ApiError::field(422, 'validation_error', $field, 'Ce champ est obligatoire.');
            }
        }
        self::requireNoLdap('Création impossible tant que l\'authentification LDAP est active.');
        $login = self::validLogin($body['login'], null);
        $password = self::validPassword($body['password'], 'password', $login);
        $user = new \user();
        $user->setLogin($login);
        $user->setPassword($password);
        $user->setProfils('user');
        $user->setEnable(1);
        $user->setOptions('api::mode', 'disable');
        self::save($user);
        \log::audit('User created from API', ['login' => $login, 'by' => $me->getLogin()]);
        return Json::respond($response, self::toArray($user), 201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        Auth::requireAccountAdmin($request);
        $me = $request->getAttribute('user');
        $user = self::findWritable($args['id']);
        $body = Json::body($request);
        self::rejectUnknown($body, ['login', 'password', 'profils', 'enable', 'options']);
        if ($body === []) {
            throw new ApiError(422, 'validation_error', 'Aucun champ à modifier.');
        }
        if (array_key_exists('profils', $body) && !in_array($body['profils'], self::PROFILES, true)) {
            throw ApiError::field(422, 'validation_error', 'profils', 'Valeur attendue : admin, user ou restrict.');
        }
        if (array_key_exists('enable', $body) && !is_bool($body['enable'])) {
            throw ApiError::field(422, 'validation_error', 'enable', 'Un booléen est attendu.');
        }
        $options = array_key_exists('options', $body) ? self::validAdminOptions($body['options']) : [];
        $login = array_key_exists('login', $body) ? self::validLogin($body['login'], $user) : null;
        $password = array_key_exists('password', $body) ? self::validPassword($body['password'], 'password', $login ?? (string) $user->getLogin()) : null;
        $disable = ($body['enable'] ?? null) === false;
        $demote = isset($body['profils']) && $body['profils'] !== 'admin';
        if ((int) $user->getId() === (int) $me->getId() && $password !== null) {
            throw ApiError::field(422, 'user_self_password', 'password',
                'Pour changer votre propre mot de passe, utilisez PATCH /me avec le mot de passe actuel.');
        }
        if ((int) $user->getId() === (int) $me->getId() && ($disable || $demote)) {
            throw new ApiError(422, 'user_self_lockout', $disable
                ? 'Vous ne pouvez pas désactiver le compte avec lequel vous êtes connecté.'
                : 'Vous ne pouvez pas changer le profil du compte avec lequel vous êtes connecté.');
        }
        if (($disable || $demote) && self::isLastAdmin($user)) {
            throw new ApiError(422, 'last_admin', 'Le dernier administrateur actif ne peut être ni désactivé ni rétrogradé.');
        }
        if ($login !== null) {
            $user->setLogin($login);
        }
        if ($password !== null) {
            $user->setPassword($password);
        }
        if (isset($body['profils'])) {
            $user->setProfils($body['profils']);
        }
        if (array_key_exists('enable', $body)) {
            $user->setEnable((int) $body['enable']);
        }
        foreach ($options as $key => $value) {
            $user->setOptions($key, $value);
        }
        self::save($user);
        return Json::respond($response, self::toArray($user));
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        Auth::requireAccountAdmin($request);
        $me = $request->getAttribute('user');
        $user = self::find($args['id']);
        self::requireNoLdap('Suppression impossible tant que l\'authentification LDAP est active.');
        if ((int) $user->getId() === (int) $me->getId()) {
            throw new ApiError(422, 'user_self_delete', 'Vous ne pouvez pas supprimer le compte avec lequel vous êtes connecté.');
        }
        try {
            $user->remove();
        } catch (\Exception $e) {
            throw new ApiError(422, 'last_admin', $e->getMessage());
        }
        return $response->withStatus(204);
    }

    public function devices(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        return Json::respond($response, self::sessionList($request, self::find($args['id']), true));
    }

    public function forgetDevices(Request $request, Response $response, array $args): Response
    {
        Auth::requireAccountAdmin($request);
        return self::forget($request, $response, self::find($args['id']), null);
    }

    public function forgetDevice(Request $request, Response $response, array $args): Response
    {
        Auth::requireAccountAdmin($request);
        return self::forget($request, $response, self::find($args['id']), $args['key']);
    }

    public function closeSessions(Request $request, Response $response, array $args): Response
    {
        Auth::requireAccountAdmin($request);
        $actor = $request->getAttribute('user');
        $user = self::find($args['id']);
        \session::revokeAll((int) $user->getId());
        if ($request->getAttribute('webSessionId') !== null && (int) $actor->getId() === (int) $user->getId()) {
            $response = SessionController::clearCookie($request, $response);
        }
        return $response->withStatus(204);
    }

    public function sessions(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        return Json::respond($response, self::sessionList($request, self::find($args['id'])));
    }

    public function activeSessions(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $accounts = [];
        foreach (\user::all() as $user) {
            if ($user->isValidAndEnabled()) {
                $accounts[(int) $user->getId()] = $user;
            }
        }
        $legacy = LegacySessions::active();
        $current = $request->getAttribute('restSessionId');
        $items = [];
        foreach (array_merge(\session::activeConnections($accounts), $legacy ?? []) as $session) {
            $user = $accounts[$session['user_id']] ?? null;
            if ($user === null) {
                continue;
            }
            $items[] = self::sessionInfo($session, $current) + [
                'userId' => (int) $user->getId(), 'login' => $user->getLogin(), 'kind' => self::kind($user),
                'userAgent' => $session['user_agent'],
            ];
        }
        usort($items, static fn (array $a, array $b): int => [$b['current'], $b['datetime']] <=> [$a['current'], $a['datetime']]);
        return Json::respond($response->withHeader('Cache-Control', 'no-store'), [
            'items' => $items, 'total' => count($items), 'legacyAvailable' => $legacy !== null,
        ]);
    }

    public function closeSession(Request $request, Response $response, array $args): Response
    {
        Auth::requireAccountAdmin($request);
        self::closeOne($request, self::find($args['id']), $args['sessionId']);
        return $response->withStatus(204);
    }

    public function closeLegacySession(Request $request, Response $response, array $args): Response
    {
        Auth::requireAccountAdmin($request);
        $user = self::find($args['id']);
        if (!preg_match('/\A[0-9a-f]{64}\z/', $args['sessionId']) || !LegacySessions::revoke($user, $args['sessionId'])) {
            throw new ApiError(404, 'session_not_found', 'Session inconnue.');
        }
        return $response->withStatus(204);
    }

    public function rights(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        return Json::respond($response, self::rightsOf(self::find($args['id'])));
    }

    public function replaceRights(Request $request, Response $response, array $args): Response
    {
        Auth::requireAccountAdmin($request);
        $user = self::findWritable($args['id']);
        $body = Json::body($request);
        foreach ($body as $key => $value) {
            if (!preg_match(self::RIGHT_KEY, (string) $key)) {
                throw ApiError::field(422, 'validation_error', (string) $key, 'Élément inconnu (ex. eqLogic12, jeeObject3, menu::dashboard).');
            }
            if (!in_array($value, self::RIGHT_VALUES, true)) {
                throw ApiError::field(422, 'validation_error', (string) $key, 'Valeur attendue : n, r ou rx.');
            }
        }
        $current = $user->getRights();
        foreach (is_array($current) ? array_keys($current) : [] as $key) {
            if (!array_key_exists($key, $body)) {
                $user->setRights($key, null);
            }
        }
        foreach ($body as $key => $value) {
            $user->setRights((string) $key, $value);
        }
        self::save($user);
        return Json::respond($response, self::rightsOf($user));
    }

    public function copyRights(Request $request, Response $response, array $args): Response
    {
        Auth::requireAccountAdmin($request);
        $from = self::find($args['id']);
        $body = Json::body($request);
        self::rejectUnknown($body, ['to']);
        $toId = $body['to'] ?? null;
        if (!is_int($toId) || $toId < 1) {
            throw ApiError::field(422, 'validation_error', 'to', 'Un identifiant d\'utilisateur est attendu.');
        }
        $to = \user::byId($toId);
        if (!is_object($to) || $toId === (int) $from->getId()) {
            throw ApiError::field(422, 'validation_error', 'to', 'Utilisateur cible inconnu ou identique à la source.');
        }
        if (self::isSystem($to)) {
            throw new ApiError(403, 'user_system', 'Les comptes système ne sont pas modifiables.');
        }
        $from->copyRightsTo($to);
        return Json::respond($response, self::rightsOf($to));
    }

    // ---------------------------------------------------------------- /me

    public function me(Request $request, Response $response): Response
    {
        return Json::respond($response->withHeader('Cache-Control', 'no-store'),
            self::meArray($request->getAttribute('user'), $request->getAttribute('csrfToken')));
    }

    public function myMenu(Request $request, Response $response): Response
    {
        return Json::respond($response->withHeader('Cache-Control', 'no-store'), \uiMenu::effective($request->getAttribute('user')));
    }

    public function updateMe(Request $request, Response $response): Response
    {
        $user = self::currentWritable($request);
        $body = Json::body($request);
        self::rejectUnknown($body, ['currentPassword', 'password', 'preferences']);
        if ($body === []) {
            throw new ApiError(422, 'validation_error', 'Aucun champ à modifier.');
        }
        $preferences = array_key_exists('preferences', $body) ? self::validPreferences($body['preferences'], $user) : [];
        $password = null;
        if (array_key_exists('password', $body)) {
            self::requireNotBanned();
            $password = self::validPassword($body['password'], 'password', (string) $user->getLogin());
            $current = $body['currentPassword'] ?? null;
            if (!is_string($current) || $current === '') {
                throw ApiError::field(422, 'validation_error', 'currentPassword', 'Le mot de passe actuel est obligatoire pour en changer.');
            }
            if (!$user->checkPassword($current)) {
                self::rejectCredential($user, ApiError::field(422, 'invalid_current_password', 'currentPassword', 'Mot de passe actuel incorrect.'));
            }
        } elseif (array_key_exists('currentPassword', $body)) {
            throw ApiError::field(422, 'validation_error', 'currentPassword', 'Utile seulement avec password.');
        }
        $change = static function (\user $account) use ($password, $preferences): void {
            if ($password !== null) {
                $account->setPassword($password);
            }
            foreach ($preferences as $key => $value) {
                $account->setOptions($key, $value);
            }
            self::save($account);
        };
        if ($password === null) {
            $change($user);
        } else {
            try {
                $user = \session::changeCredentials($user, $request->getAttribute('restSessionId'), $change);
            } catch (\DomainException $error) {
                throw new ApiError(401, 'session_expired', $error->getMessage());
            }
        }
        return Json::respond($response, self::meArray($user, $request->getAttribute('csrfToken')));
    }

    public function setupMyTwoFactor(Request $request, Response $response): Response
    {
        $user = self::currentWritable($request);
        $sessionId = self::twoFactorSession($request, $user);
        if ($user->hasTwoFactor()) {
            throw new ApiError(409, 'two_factor_already_enabled', 'La double authentification est déjà activée.');
        }

        $google2fa = new Google2FA(null, new SvgImageBackEnd());
        $cacheKey = 'rest::twoFactorSetup::' . $sessionId;
        $secret = \cache::byKey($cacheKey)->getValue();
        if ($secret === '') {
            $secret = $google2fa->generateSecretKey(32);
            \cache::set($cacheKey, $secret, 600);
        }
        $qrCode = $google2fa->getQRCodeInline('Jeedom ' . \config::byKey('name'), $user->getLogin(), $secret);
        if (!str_starts_with($qrCode, 'data:')) {
            $qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrCode);
        }
        return Json::respond($response->withHeader('Cache-Control', 'no-store'), ['secret' => $secret, 'qrCode' => $qrCode]);
    }

    public function updateMyTwoFactor(Request $request, Response $response): Response
    {
        $user = self::currentWritable($request);
        $sessionId = self::twoFactorSession($request, $user);
        self::requireNotBanned();
        $body = Json::body($request, 4096);
        self::rejectUnknown($body, ['enabled', 'currentPassword', 'twoFactorCode']);
        $enabled = $body['enabled'] ?? null;
        $password = $body['currentPassword'] ?? null;
        $code = $body['twoFactorCode'] ?? null;
        if (!is_bool($enabled)) {
            throw ApiError::field(422, 'validation_error', 'enabled', 'Un booléen est attendu.');
        }
        if (!is_string($password) || $password === '') {
            throw ApiError::field(422, 'validation_error', 'currentPassword', 'Le mot de passe actuel est obligatoire.');
        }
        if (!is_string($code) || !preg_match('/\A[0-9]{6}\z/', $code)) {
            throw ApiError::field(422, 'validation_error', 'twoFactorCode', 'Le code de double authentification doit contenir six chiffres.');
        }

        $cacheKey = 'rest::twoFactorSetup::' . $sessionId;
        try {
            $user = \session::changeCredentials($user, $sessionId, static function (\user $account) use ($enabled, $password, $code, $cacheKey): void {
                if ($enabled === $account->hasTwoFactor()) {
                    throw new ApiError(409, 'two_factor_state_changed', 'L’état de la double authentification a changé. Rechargez la page.');
                }
                if (!$account->checkPassword($password)) {
                    throw ApiError::field(422, 'invalid_current_password', 'currentPassword', 'Mot de passe actuel incorrect.');
                }
                $secret = $enabled ? \cache::byKey($cacheKey)->getValue() : $account->getOptions('twoFactorAuthentificationSecret');
                if ($enabled && $secret === '') {
                    throw new ApiError(409, 'two_factor_setup_expired', 'La configuration a expiré. Relancez l’activation.');
                }
                if (!(new Totp())->verifyKey($secret, $code)) {
                    throw ApiError::field(422, 'two_factor_invalid', 'twoFactorCode', 'Code de double authentification invalide.');
                }
                $account->setOptions('twoFactorAuthentification', (int) $enabled, false);
                $account->setOptions('twoFactorAuthentificationSecret', $enabled ? $secret : '', false);
                self::save($account);
            });
        } catch (\DomainException $error) {
            throw new ApiError(401, 'session_expired', $error->getMessage());
        } catch (ApiError $error) {
            self::rejectCredential($user, $error);
        }
        \cache::delete($cacheKey);
        \log::audit($enabled ? 'Two-factor authentication enabled' : 'Two-factor authentication disabled', ['login' => $user->getLogin()]);
        return Json::respond($response->withHeader('Cache-Control', 'no-store'), ['enabled' => $enabled]);
    }

    public function createScriptSession(Request $request, Response $response, array $args = []): Response
    {
        return $this->createLongLivedSession($request, $response, $args['id'] ?? null, false);
    }

    public function createMcpSession(Request $request, Response $response, array $args): Response
    {
        return $this->createLongLivedSession($request, $response, $args['id'], true);
    }

    private function createLongLivedSession(Request $request, Response $response, ?string $target, bool $mcp): Response
    {
        if ($target !== null) {
            Auth::requireAccountAdmin($request);
        }
        if ($request->getAttribute('webSessionId') === null) {
            throw new ApiError(403, 'forbidden', 'La création d’un accès longue durée nécessite une session Web.');
        }
        if ($mcp && !\config::mcpEnabled()) {
            throw self::mcpDisabled();
        }
        $actor = self::currentWritable($request);
        $body = Json::body($request, 8192);
        $fields = ['clientName', 'durationDays', 'password', 'twoFactorCode'];
        self::rejectUnknown($body, $mcp ? $fields : [...$fields, 'scope', 'tokenType']);
        $tokenType = array_key_exists('tokenType', $body) ? $body['tokenType'] : 'access';
        if (!in_array($tokenType, ['access', 'refresh'], true)) {
            throw ApiError::field(422, 'validation_error', 'tokenType', 'Choisissez un jeton d’accès ou de renouvellement.');
        }
        $name = SessionController::clientName($body['clientName'] ?? null);
        $scope = $mcp ? \session::MCP_SCOPE : ($body['scope'] ?? null);
        if (!$mcp && !in_array($scope, \session::SCRIPT_SCOPES, true)) {
            throw ApiError::field(422, 'validation_error', 'scope', 'Choisissez lecture ou contrôle.');
        }
        $days = $body['durationDays'] ?? null;
        if (!is_int($days) || $days < 1 || $days > \session::SCRIPT_MAX_DURATION_DAYS) {
            throw ApiError::field(422, 'validation_error', 'durationDays', 'Choisissez une durée de 1 à 365 jours.');
        }
        $password = $body['password'] ?? null;
        if (!is_string($password) || $password === '') {
            throw ApiError::field(422, 'validation_error', 'password', 'Le mot de passe est obligatoire.');
        }
        $confirmed = SessionController::authenticate($actor->getLogin(), $password, $body['twoFactorCode'] ?? null, 'web');
        if ((int) $confirmed->getId() !== (int) $actor->getId()) {
            throw new ApiError(401, 'invalid_credentials', 'Le compte a changé. Reconnectez-vous.');
        }
        $user = $confirmed;
        if ($target !== null) {
            Auth::requireAccountAdmin($request->withAttribute('user', $confirmed));
            $user = self::findWritable($target);
        }
        if (!$user->isValidAndEnabled()) {
            throw new ApiError(403, $mcp ? 'mcp_access_forbidden' : 'script_access_forbidden', 'Le compte doit être actif.');
        }
        try {
            $connection = \session::create($user, false, '', null, time() + $days * 86400, $mcp ? 'api' : 'script', $name, $scope, $tokenType === 'refresh');
        } catch (\DomainException $error) {
            if ($mcp && $error->getCode() === \session::MCP_DISABLED_ERROR) {
                throw self::mcpDisabled();
            }
            throw new ApiError(401, 'invalid_credentials', $error->getMessage());
        }
        \log::audit($mcp ? 'REST MCP access created' : 'REST script access created', ['sessionId' => $connection['id'], 'by' => $confirmed->getLogin(), 'login' => $user->getLogin()]);
        $token = $tokenType === 'refresh' ? SessionController::refreshPayload($connection) : [
            'accessToken' => $connection['token'], 'accessExpiresAt' => gmdate(DATE_ATOM, $connection['expires']),
        ];
        return Json::respond($response->withHeader('Cache-Control', 'no-store'), ['id' => $connection['id']] + $token, 201);
    }

    private static function mcpDisabled(): ApiError
    {
        return new ApiError(403, 'mcp_disabled', 'Le serveur MCP est désactivé.');
    }

    public function myDevices(Request $request, Response $response): Response
    {
        return Json::respond($response, self::sessionList($request, $request->getAttribute('user'), true));
    }

    public function forgetMyDevices(Request $request, Response $response): Response
    {
        return self::forget($request, $response, $request->getAttribute('user'), null);
    }

    public function forgetMyDevice(Request $request, Response $response, array $args): Response
    {
        return self::forget($request, $response, $request->getAttribute('user'), $args['key']);
    }

    public function mySessions(Request $request, Response $response): Response
    {
        return Json::respond($response, self::sessionList($request, $request->getAttribute('user')));
    }

    public function closeMySession(Request $request, Response $response, array $args): Response
    {
        $user = self::reload($request->getAttribute('user'));
        self::closeOne($request, $user, $args['sessionId']);
        return $response->withStatus(204);
    }

    /** Schéma `Me` de la spec. */
    public static function meArray(\user $user, ?string $csrfToken): array
    {
        $preferences = [];
        foreach (self::PREFERENCES as $key => $type) {
            $value = $user->getOptions($key, null);
            if ($value === null || $value === '') {
                if ($type === 'id') {
                    $preferences[$key] = null;
                }
                continue;
            }
            $preferences[$key] = match ($type) {
                'id' => is_numeric($value) ? (int) $value : null,
                'flag' => (int) $value === 1 ? 1 : 0,
                'cmd' => (string) \jeedom::toHumanReadable($value),
                default => (string) $value,
            };
        }
        $me = [
            'id' => (int) $user->getId(),
            'login' => (string) $user->getLogin(),
            'isAdmin' => $user->getProfils() === 'admin',
            'profils' => (string) $user->getProfils(),
            'preferences' => (object) $preferences,
            'csrfToken' => $csrfToken,
        ];
        if (!self::isSystem($user)) {
            $me['twoFactorEnabled'] = $user->hasTwoFactor();
        }
        return $me;
    }

    // ---------------------------------------------------------------- outils

    /** Schéma `User` : jamais de mot de passe, de `hash`, de secret 2FA ni de `session_id`. */
    private static function toArray(\user $user): array
    {
        $last = $user->getOptions('lastConnection', '');
        $options = [
            'localOnly' => (int) $user->getOptions('localOnly', 0) === 1 ? 1 : 0,
            'doNotRotateHash' => (int) $user->getOptions('doNotRotateHash', 0) === 1 ? 1 : 0,
        ];
        $menu = (string) $user->getOptions('menu', '');
        $options['menu'] = $menu === '' ? null : $menu;
        $mode = $user->getOptions('api::mode', '');
        if (in_array($mode, self::API_MODES, true)) {
            $options['api::mode'] = $mode;
        }
        $kind = self::kind($user);
        return [
            'id' => (int) $user->getId(),
            'login' => (string) $user->getLogin(),
            'profils' => (string) $user->getProfils(),
            'enable' => (int) $user->getEnable() === 1,
            'kind' => $kind,
            'twoFactorEnabled' => $user->hasTwoFactor(),
            'lastConnection' => is_string($last) && $last !== '' && strtotime($last) !== false ? date('c', strtotime($last)) : null,
            'options' => $options,
        ] + ($kind === 'plugin' ? ['daemonPluginName' => \plugin::daemonAccountPluginName($user->getLogin())] : []);
    }

    private static function rightsOf(\user $user): object
    {
        $rights = $user->getRights();
        return (object) (is_array($rights) ? $rights : []);
    }

    private static function kind(\user $user): string
    {
        return \plugin::isDaemonAccount($user->getLogin()) ? 'plugin' : (self::isSystem($user) ? 'system' : 'human');
    }

    private static function sessionList(Request $request, \user $user, bool $remembered = false): array
    {
        $current = $request->getAttribute('restSessionId');
        $items = [];
        foreach (\session::connections($user, $remembered) as $session) {
            $items[] = self::sessionInfo($session, $current, $remembered);
        }
        return ['items' => $items, 'total' => count($items)];
    }

    private static function sessionInfo(array $session, ?string $current, bool $remembered = false): array
    {
        return [
            ($remembered ? 'key' : 'id') => $session['id'],
            'ip' => $session['ip'],
            'datetime' => self::sessionDate($session['last_activity_at']),
            'expiresAt' => self::sessionDate($session['expires_at']),
            'current' => $session['id'] === $current,
        ] + ($remembered ? [] : [
            'name' => $session['client_name'],
            'type' => $session['mode'],
            'scope' => $session['scope'] === '' ? null : $session['scope'],
        ]);
    }

    private static function sessionDate(string $datetime): string
    {
        return str_replace(' ', 'T', $datetime) . 'Z';
    }

    private static function closeOne(Request $request, \user $user, string $id): void
    {
        if ($id === $request->getAttribute('restSessionId') && (int) $user->getId() === (int) $request->getAttribute('user')->getId()) {
            throw new ApiError(409, 'current_session', 'Pour fermer la session courante, utiliser DELETE /session.');
        }
        if (!\session::revoke($user, $id)) {
            throw new ApiError(404, 'session_not_found', 'Session inconnue.');
        }
    }

    private static function forget(Request $request, Response $response, \user $user, ?string $key): Response
    {
        if ($key === null) {
            \session::revokeAll((int) $user->getId(), true);
        } elseif (!\session::revoke($user, $key, true)) {
            throw new ApiError(404, 'device_not_found', 'Appareil inconnu.');
        }
        if ($request->getAttribute('webSessionRemembered') && (int) $user->getId() === (int) $request->getAttribute('user')->getId()
            && ($key === null || $key === $request->getAttribute('restSessionId'))) {
            $response = SessionController::clearCookie($request, $response);
        }
        return $response->withStatus(204);
    }

    private static function find(string $id): \user
    {
        $user = ctype_digit($id) ? \user::byId((int) $id) : null;
        if (!is_object($user)) {
            throw new ApiError(404, 'user_not_found', 'Utilisateur inconnu.');
        }
        return $user;
    }

    private static function findWritable(string $id): \user
    {
        $user = self::find($id);
        if (self::isSystem($user)) {
            throw new ApiError(403, 'user_system', 'Les comptes système ne sont pas modifiables.');
        }
        return $user;
    }

    /** Relit le compte du jeton en base avant de le modifier. */
    private static function reload(\user $user): \user
    {
        $fresh = \user::byId($user->getId());
        if (!is_object($fresh)) {
            throw new ApiError(401, 'unauthorized', 'Compte introuvable.');
        }
        return $fresh;
    }

    private static function currentWritable(Request $request): \user
    {
        $user = self::reload($request->getAttribute('user'));
        if (self::isSystem($user)) {
            throw new ApiError(403, 'user_system', 'Les comptes système ne sont pas modifiables.');
        }
        return $user;
    }

    private static function twoFactorSession(Request $request, \user $user): string
    {
        $sessionId = $request->getAttribute('restSessionId');
        if ($sessionId === null) {
            throw new ApiError(403, 'forbidden', 'La gestion de la double authentification nécessite une session utilisateur.');
        }
        return $sessionId;
    }

    private static function requireNotBanned(): void
    {
        if (\user::isBan()) {
            throw new ApiError(403, 'ip_banned', 'Adresse IP bannie après trop d\'échecs de connexion.');
        }
    }

    /** Un mot de passe ou un code actuel faux compte comme un échec de connexion (bannissement, délai). */
    private static function rejectCredential(\user $user, ApiError $error): never
    {
        if (in_array($error->errorCode, ['invalid_current_password', 'two_factor_invalid'], true)) {
            \user::failedLogin(['login' => $user->getLogin(), 'reason' => $error->getMessage()]);
            sleep(5);
        }
        throw $error;
    }

    public static function isSystem(\user $user): bool
    {
        return in_array($user->getLogin(), self::SYSTEM_LOGINS, true) || \plugin::isDaemonAccount($user->getLogin()) || CloudService::isLogin($user->getLogin());
    }

    /**
     * L'ajax teste `ldap::enable`, user::connect() teste `ldap:enable` : on refuse si
     * l'une ou l'autre est active, pour ne jamais créer un compte local à côté de l'annuaire.
     */
    private static function requireNoLdap(string $message): void
    {
        if (\config::byKey('ldap::enable') == '1' || \config::byKey('ldap:enable') == '1') {
            throw new ApiError(422, 'ldap_enabled', $message);
        }
    }

    private static function isLastAdmin(\user $user): bool
    {
        $admins = \user::byProfils('admin', true);
        return count($admins) === 1 && (int) $admins[0]->getId() === (int) $user->getId();
    }

    private static function save(\user $user): void
    {
        try {
            $user->save();
        } catch (\Exception $e) {
            throw new ApiError(422, 'validation_error', $e->getMessage());
        }
    }

    private static function rejectUnknown(array $body, array $allowed): void
    {
        if ($unknown = array_diff(array_keys($body), $allowed)) {
            throw ApiError::field(422, 'validation_error', (string) reset($unknown), 'Champ inconnu ou non modifiable.');
        }
    }

    private static function enumParam(array $params, string $name, array $values): ?string
    {
        if (!isset($params[$name])) {
            return null;
        }
        if (!in_array($params[$name], $values, true)) {
            throw ApiError::field(422, 'validation_error', $name, 'Valeur attendue : ' . implode(', ', $values) . '.');
        }
        return $params[$name];
    }

    private static function validLogin(mixed $login, ?\user $user): string
    {
        if (!is_string($login) || trim($login) === '' || mb_strlen($login) > 255) {
            throw ApiError::field(422, 'validation_error', 'login', 'Un texte non vide de 255 caractères au plus est attendu.');
        }
        if (in_array($login, self::SYSTEM_LOGINS, true) || CloudService::isLogin($login)) {
            throw ApiError::field(422, 'validation_error', 'login', 'Ce login est réservé.');
        }
        $other = \user::byLogin($login);
        if (is_object($other) && ($user === null || (int) $other->getId() !== (int) $user->getId())) {
            throw ApiError::field(409, 'user_login_taken', 'login', 'Un utilisateur porte déjà ce login.');
        }
        return $login;
    }

    /** Règle `NewPassword` : 8 caractères, une lettre, un chiffre, différent de l'identifiant. */
    private static function validPassword(mixed $password, string $field, string $login): string
    {
        if (!is_string($password) || $password === '') {
            throw ApiError::field(422, 'validation_error', $field, 'Un mot de passe non vide est attendu.');
        }
        if (mb_strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)
            || mb_strtolower($password) === mb_strtolower($login)) {
            throw ApiError::field(422, 'weak_password', $field,
                'Le mot de passe doit faire au moins 8 caractères, avec au moins une lettre et un chiffre, et être différent de l\'identifiant.');
        }
        return $password;
    }

    private static function validAdminOptions(mixed $options): array
    {
        if (!is_array($options) || (array_is_list($options) && $options !== [])) {
            throw ApiError::field(422, 'validation_error', 'options', 'Un objet est attendu.');
        }
        foreach ($options as $key => $value) {
            $ok = match ($key) {
                'localOnly', 'doNotRotateHash' => $value === 0 || $value === 1,
                'api::mode' => in_array($value, self::API_MODES, true),
                'menu' => $value === null || (is_string($value) && \uiMenu::exists($value)),
                default => throw ApiError::field(422, 'validation_error', 'options.' . $key, 'Option inconnue ou non modifiable.'),
            };
            if (!$ok) {
                throw ApiError::field(422, 'validation_error', 'options.' . $key, 'Valeur invalide.');
            }
        }
        if (array_key_exists('menu', $options)) {
            $options['menu'] ??= '';
        }
        return $options;
    }

    private static function validPreferences(mixed $preferences, \user $user): array
    {
        if (!is_array($preferences) || (array_is_list($preferences) && $preferences !== [])) {
            throw ApiError::field(422, 'validation_error', 'preferences', 'Un objet est attendu.');
        }
        $result = [];
        foreach ($preferences as $key => $value) {
            $field = 'preferences.' . $key;
            $type = self::PREFERENCES[$key] ?? throw ApiError::field(422, 'validation_error', $field, 'Préférence inconnue.');
            $ok = match ($type) {
                'id' => $value === null || is_int($value),
                'flag' => $value === 0 || $value === 1,
                default => is_string($value),
            };
            if (!$ok) {
                throw ApiError::field(422, 'validation_error', $field, 'Valeur invalide.');
            }
            $result[$key] = match ($type) {
                'id' => $value ?? '',
                'cmd' => self::validNotificationCmd($value, $user, $field),
                default => $value,
            };
        }
        return $result;
    }

    /**
     * La commande de notification est exécutée par la réinitialisation du mot de passe,
     * sans session : seule une commande message que le compte peut lui-même exécuter
     * (droit, code d'accès, confirmation) est acceptée. Chaîne vide : aucune commande.
     */
    private static function validNotificationCmd(string $value, \user $user, string $field): string
    {
        $value = trim((string) \jeedom::fromHumanReadable($value));
        if ($value === '') {
            return '';
        }
        $cmd = preg_match('/\A#?([1-9][0-9]*)#?\z/', $value, $match) ? \cmd::byId((int) $match[1]) : null;
        if (!is_object($cmd) || !is_object($cmd->getEqLogic())) {
            throw ApiError::field(422, 'validation_error', $field, 'Commande inconnue.');
        }
        if ($cmd->getType() !== 'action' || $cmd->getSubType() !== 'message') {
            throw ApiError::field(422, 'validation_error', $field, 'Une commande action de type message est attendue.');
        }
        if (!$cmd->hasRight($user) || !$cmd->checkAccessCode('') || (int) $cmd->getConfiguration('actionConfirm') === 1) {
            throw ApiError::field(403, 'forbidden', $field, 'Ce compte ne peut pas exécuter cette commande (droit, code d\'accès ou confirmation requis).');
        }
        return '#' . $cmd->getId() . '#';
    }
}
