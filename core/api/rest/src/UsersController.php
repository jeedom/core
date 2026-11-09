<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Ressources /users (administrateurs) et /me (son propre compte). Reprend les
 * garde-fous de core/ajax/user.ajax.php : liste blanche de champs, comptes système
 * intouchables, pas d'auto-désactivation, refus si LDAP actif. Le mot de passe, le
 * `hash` et la double authentification ne sortent jamais de /users.
 */
final class UsersController
{
    public const SYSTEM_LOGINS = ['internal_report', 'jeedom_support'];
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
        foreach (\user::all() as $user) {
            $item = self::toArray($user);
            if (($profils === null || $item['profils'] === $profils) && ($kind === null || $item['kind'] === $kind)
                && ($enable === null || $item['enable'] === $enable)) {
                $items[] = $item;
            }
        }
        return Json::respond($response, ['items' => $items, 'total' => count($items)]);
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        return Json::respond($response, self::toArray(self::find($args['id'])));
    }

    public function create(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
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
        Auth::requireAdmin($request);
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
        Auth::requireAdmin($request);
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
        Auth::requireAdmin($request);
        return self::forget($request, $response, self::find($args['id']), null);
    }

    public function forgetDevice(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        return self::forget($request, $response, self::find($args['id']), $args['key']);
    }

    public function closeSessions(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
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

    public function closeSession(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        self::closeOne($request, self::find($args['id']), $args['sessionId']);
        return $response->withStatus(204);
    }

    public function rights(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        return Json::respond($response, self::rightsOf(self::find($args['id'])));
    }

    public function replaceRights(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
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
        Auth::requireAdmin($request);
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

    public function updateMe(Request $request, Response $response): Response
    {
        $user = self::currentWritable($request);
        $body = Json::body($request);
        self::rejectUnknown($body, ['currentPassword', 'password', 'preferences']);
        if ($body === []) {
            throw new ApiError(422, 'validation_error', 'Aucun champ à modifier.');
        }
        $preferences = array_key_exists('preferences', $body) ? self::validPreferences($body['preferences']) : [];
        $password = null;
        if (array_key_exists('password', $body)) {
            $password = self::validPassword($body['password'], 'password', (string) $user->getLogin());
            $current = $body['currentPassword'] ?? null;
            if (!is_string($current) || $current === '') {
                throw ApiError::field(422, 'validation_error', 'currentPassword', 'Le mot de passe actuel est obligatoire pour en changer.');
            }
            $stored = $user->getPassword();
            if (!password_verify($current, $stored) && !hash_equals($stored, \sha512($current))) {
                throw ApiError::field(422, 'invalid_current_password', 'currentPassword', 'Mot de passe actuel incorrect.');
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
                $user = \session::changeCredentials($user, $request->getAttribute('webSessionId'), $change);
            } catch (\DomainException $error) {
                throw new ApiError(401, 'session_expired', $error->getMessage());
            }
        }
        return Json::respond($response, self::meArray($user, $request->getAttribute('csrfToken')));
    }

    public function myToken(Request $request, Response $response): Response
    {
        $user = $request->getAttribute('user');
        if (self::isSystem($user)) {
            throw new ApiError(403, 'user_system', 'Les comptes système n\'ont pas de jeton consultable.');
        }
        return Json::respond($response, self::token($user));
    }

    public function regenerateMyToken(Request $request, Response $response): Response
    {
        $user = self::currentWritable($request);
        $user->setHash('');
        self::save($user);
        return Json::respond($response, self::token($user), 201);
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
        return [
            'id' => (int) $user->getId(),
            'login' => (string) $user->getLogin(),
            'isAdmin' => $user->getProfils() === 'admin',
            'profils' => (string) $user->getProfils(),
            'preferences' => (object) $preferences,
            'csrfToken' => $csrfToken,
        ];
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
        $mode = $user->getOptions('api::mode', '');
        if (in_array($mode, self::API_MODES, true)) {
            $options['api::mode'] = $mode;
        }
        return [
            'id' => (int) $user->getId(),
            'login' => (string) $user->getLogin(),
            'profils' => (string) $user->getProfils(),
            'enable' => (int) $user->getEnable() === 1,
            'kind' => self::isSystem($user) ? 'system' : 'human',
            'twoFactorEnabled' => (int) $user->getOptions('twoFactorAuthentification', 0) === 1,
            'lastConnection' => is_string($last) && $last !== '' && strtotime($last) !== false ? date('c', strtotime($last)) : null,
            'options' => $options,
        ];
    }

    private static function rightsOf(\user $user): object
    {
        $rights = $user->getRights();
        return (object) (is_array($rights) ? $rights : []);
    }

    private static function token(\user $user): array
    {
        $generated = $user->getOptions('hashGenerated', '');
        return [
            'token' => $user->getHash(),
            'generatedAt' => is_string($generated) && $generated !== '' && strtotime($generated) !== false ? date('c', strtotime($generated)) : null,
        ];
    }

    private static function sessionList(Request $request, \user $user, bool $remembered = false): array
    {
        $current = $request->getAttribute('webSessionId');
        $items = [];
        foreach (\session::connections($user, $remembered) as $session) {
            $items[] = [
                ($remembered ? 'key' : 'id') => $session['id'],
                'ip' => $session['ip'],
                'datetime' => str_replace(' ', 'T', $session['last_activity_at']) . 'Z',
                'expiresAt' => str_replace(' ', 'T', $session['expires_at']) . 'Z',
                'current' => $session['id'] === $current,
            ];
        }
        return ['items' => $items, 'total' => count($items)];
    }

    private static function closeOne(Request $request, \user $user, string $id): void
    {
        if ($id === $request->getAttribute('webSessionId') && (int) $user->getId() === (int) $request->getAttribute('user')->getId()) {
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
            && ($key === null || $key === $request->getAttribute('webSessionId'))) {
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

    public static function isSystem(\user $user): bool
    {
        return in_array($user->getLogin(), self::SYSTEM_LOGINS, true);
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
        if (in_array($login, self::SYSTEM_LOGINS, true)) {
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
                default => throw ApiError::field(422, 'validation_error', 'options.' . $key, 'Option inconnue ou non modifiable.'),
            };
            if (!$ok) {
                throw ApiError::field(422, 'validation_error', 'options.' . $key, 'Valeur invalide.');
            }
        }
        return $options;
    }

    private static function validPreferences(mixed $preferences): array
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
                'cmd' => \jeedom::fromHumanReadable($value),
                default => $value,
            };
        }
        return $result;
    }
}
