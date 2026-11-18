<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Configuration globale du core, par onglet de l'administration.
 * Lecture : tout utilisateur connecté ; un non-admin ne reçoit que les clés de USER_READABLE.
 * Écriture : administrateurs. Les clés chiffrées ne sortent jamais en clair.
 */
final class ConfigController
{
    /** Type de chaque clé exposée, par onglet (source : docs/inventaire-config.md de jeevolution). */
    private const SECTIONS = [
        'general' => [
            'name' => 'string',
            'display_name_login' => 'boolean',
            'language' => 'string',
            'tts::engine' => 'string',
            'timezone' => 'string',
            'ntp::optionalServer' => 'string',
            'ignoreHourCheck' => 'boolean',
            'core::jqueryless' => 'boolean',
            'info::latitude' => 'number',
            'info::longitude' => 'number',
            'info::altitude' => 'number',
        ],
        'interface' => [
            'jeedom_theme_main' => 'string',
            'jeedom_theme_alternate' => 'string',
            'mobile_theme_color' => 'string',
            'mobile_theme_color_night' => 'string',
            'theme_changeAccordingTime' => 'boolean',
            'theme_start_day_hour' => 'string',
            'theme_end_day_hour' => 'string',
            'mobile_theme_useAmbientLight' => 'boolean',
            'theme_displayAsTable' => 'boolean',
            'interface::advance::coloredIcons' => 'boolean',
            'scenario::disableAutocomplete' => 'boolean',
            'interface::toast::position' => 'string',
            'interface::toast::duration' => 'number',
            'interface::advance::enable' => 'boolean',
            'css::background-opacity' => 'number',
            'css::border-radius' => 'number',
            'widget::shadow' => 'boolean',
            'enableCustomCss' => 'boolean',
            'widget::step::height' => 'number',
            'widget::step::width' => 'number',
            'widget::margin' => 'number',
            'interface::advance::vertCentering' => 'boolean',
            'interface::advance::coloredcats' => 'boolean',
            'interface::mobile::onecolumn' => 'boolean',
            'autoreorder::weight_human_action' => 'number',
            'autoreorder::weight_automation_action' => 'number',
            'autoreorder::weight_history' => 'number',
            'showBackgroundImg' => 'boolean',
            'interface::background::opacitylight' => 'number',
            'interface::background::opacitydark' => 'number',
            'css::objectBackgroundBlur' => 'number',
        ],
        'network' => [
            'network::disableInternalAuto' => 'boolean',
            'internalProtocol' => 'string',
            'internalAddr' => 'string',
            'internalComplement' => 'string',
            'network::disableMangement' => 'boolean',
            'externalProtocol' => 'string',
            'externalAddr' => 'string',
            'externalComplement' => 'string',
            'dns::mode' => 'string',
            'network::localip' => 'string',
            'network::trustedProxies' => 'string',
            'proxyAddress' => 'string',
            'proxyPort' => 'number',
            'proxyLogin' => 'string',
            'proxyPassword' => 'secret',
        ],
        'logs' => [
            'timeline::maxevent' => 'number',
            'addMessageForErrorLog' => 'boolean',
            'maxLineLog' => 'number',
            'maxSizeLog' => 'number',
            'log::level' => 'number',
            LogLevels::KEY => 'logLevels',
            'alert::addMessageOnTimeout' => 'boolean',
            'alert::addMessageOnBatterywarning' => 'boolean',
            'alert::addMessageOnBatterydanger' => 'boolean',
            'alert::addMessageOnWarning' => 'boolean',
            'alert::addMessageOnDanger' => 'boolean',
        ],
        'notifications' => [
            'notifications::settings' => 'notifications',
        ],
        'eqLogics' => [
            'numberOfTryBeforeEqLogicDisable' => 'number',
            'battery::warning' => 'number',
            'battery::danger' => 'number',
            'displayStatsWidget' => 'boolean',
            'historyCalculPeriod' => 'number',
            'historyCalculTendance' => 'number',
            'historyArchiveTime' => 'number',
            'historyArchivePackage' => 'number',
            'history::smooth' => 'string',
            'history::defaultShowPeriod' => 'string',
            'historyPurge' => 'string',
            'history::allowFuture' => 'boolean',
            'cmdPushUrl' => 'string',
            'expression::autoQuote' => 'boolean',
            'cmd::allowCheckState' => 'boolean',
        ],
        'reports' => [
            'report::delay' => 'number',
            'report::maxdays' => 'number',
        ],
        'links' => [
            'graphlink::scenario::drill' => 'number',
            'graphlink::jeeObject::drill' => 'number',
            'graphlink::eqLogic::drill' => 'number',
            'graphlink::cmd::drill' => 'number',
            'graphlink::dataStore::drill' => 'number',
            'graphlink::prerender' => 'number',
            'graphlink::render' => 'number',
        ],
        'interactions' => [
            'interact::confidence1' => 'number',
            'interact::confidence2' => 'number',
            'interact::confidence3' => 'number',
            'interact::confidence' => 'number',
            'interact::weigh1' => 'number',
            'interact::weigh2' => 'number',
            'interact::weigh3' => 'number',
            'interact::weigh4' => 'number',
            'interact::noResponseIfEmpty' => 'boolean',
            'interact::regexpExcludGlobal' => 'string',
            'interact::autoreply::enable' => 'boolean',
            'interact::contextual::enable' => 'boolean',
            'interact::contextual::startpriority' => 'string',
            'interact::contextual::splitword' => 'string',
            'interact::warnme::enable' => 'boolean',
            'interact::warnme::start' => 'string',
            'interact::warnme::defaultreturncmd' => 'string',
            'interact::autoreply::object::synonym' => 'string',
            'interact::autoreply::eqLogic::synonym' => 'string',
            'interact::autoreply::cmd::synonym' => 'string',
            'interact::autoreply::summary::synonym' => 'string',
            'interact::autoreply::cmd::slider::max' => 'string',
            'interact::autoreply::cmd::slider::min' => 'string',
        ],
        'security' => [
            'session_lifetime' => 'number',
            'security::registerDeviceLifetime' => 'number',
            'security::maxFailedLogin' => 'number',
            'security::timeLoginFailed' => 'number',
            'security::bantime' => 'number',
            'security::whiteips' => 'string',
            'ldap:enable' => 'boolean',
            'ldap:samba4' => 'boolean',
            'ldap:tls' => 'boolean',
            'ldap:host' => 'secret',
            'ldap:port' => 'string',
            'ldap:domain' => 'string',
            'ldap:basedn' => 'string',
            'ldap:username' => 'secret',
            'ldap:password' => 'secret',
            'ldap::usersearch' => 'string',
            'ldap:filter:admin' => 'string',
            'ldap:filter:user' => 'string',
            'ldap:filter:restrict' => 'string',
            'dontProtectTooFastLaunchDependancy' => 'boolean',
            'dontProtectTooFastLaunchDaemon' => 'boolean',
        ],
        'updates' => [
            'core::repo::provider' => 'string',
            'core::branch' => 'string',
            'update::autocheck' => 'boolean',
            'github::core::user' => 'string',
            'github::core::repository' => 'string',
            'github::core::branch' => 'string',
            'github::token' => 'secret',
            'url::core::url' => 'string',
            'url::core::version' => 'string',
        ],
        'backups' => [
            'backup::path' => 'string',
            'backup::keepDays' => 'number',
            'backup::maxSize' => 'number',
            'market::cloudUpload' => 'boolean',
            'samba::cloudUpload' => 'boolean',
            'samba::keepDays' => 'number',
            'samba::backup::ip' => 'string',
            'samba::backup::share' => 'string',
            'samba::backup::folder' => 'string',
            'samba::backup::username' => 'string',
            'samba::backup::password' => 'secret',
            'market::cloud::backup::name' => 'string',
            'market::cloud::backup::password' => 'secret',
            'market::cloud::backup::password_confirmation' => 'secret',
        ],
        'cache' => [
            'cache::engine' => 'string',
            'cache::redisaddr' => 'string',
            'cache::redisport' => 'number',
            'event::waitPollingTime' => 'number',
        ],
        'api' => [
            'api::forbidden::method' => 'string',
            'api::allow::method' => 'string',
        ],
    ];

    /**
     * Clés lisibles par un non-administrateur : ce dont une interface utilisateur a besoin
     * (identité et localisation de la box, thème, résumés, menu, affichage des historiques).
     * Tout le reste (réseau, sécurité, sauvegardes, cache, mises à jour…) est réservé aux admins.
     */
    private const USER_READABLE = [
        'object:summary', 'ui::menu',
        'name', 'display_name_login', 'language', 'timezone', 'info::latitude', 'info::longitude', 'info::altitude',
        'battery::warning', 'battery::danger', 'displayStatsWidget', 'historyCalculPeriod', 'historyCalculTendance',
        'history::smooth', 'history::defaultShowPeriod',
    ];

    /**
     * Nom exposé => nom encore stocké en base, tant que le script de migration
     * du core n'a pas renommé ces clés.
     */
    private const STORED_AS = [
        'history::defaultShowPeriod' => 'history::defautShowPeriod',
        'dontProtectTooFastLaunchDaemon' => 'dontProtectTooFastLaunchDeamony',
    ];

    private const SUMMARY_FIELDS = [
        'key' => 'string', 'name' => 'string', 'calcul' => 'string', 'icon' => 'string',
        'iconnul' => 'string', 'unit' => 'string', 'count' => 'string', 'allowDisplayZero' => 'boolean',
        'ignoreIfCmdOlderThan' => 'raw', 'hidenumber' => 'boolean', 'hidenulnumber' => 'boolean',
    ];

    public function get(Request $request, Response $response): Response
    {
        $requested = trim((string) ($request->getQueryParams()['keys'] ?? ''));
        if ($requested === '') {
            $keys = array_keys(self::exposedKeys());
        } else {
            $keys = array_values(array_unique(array_map('trim', explode(',', $requested))));
            foreach ($keys as $key) {
                if (!isset(self::exposedKeys()[$key])) {
                    throw ApiError::field(422, 'validation_error', $key, 'Clé inconnue ou non exposée.');
                }
            }
        }
        return Json::respond($response, (object) self::read($keys, self::isAdmin($request)));
    }

    public function update(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = Json::body($request);
        if ($body === []) {
            throw new ApiError(422, 'validation_error', 'Aucune clé à modifier.');
        }
        $values = [];
        foreach ($body as $key => $value) {
            if ($key === LogLevels::KEY) {
                $values += LogLevels::changes($value);
                continue;
            }
            $values[self::storedKey((string) $key)] = self::validate((string) $key, $value);
        }
        $cloudPasswordChanged = array_key_exists('market::cloud::backup::password', $values)
            || array_key_exists('market::cloud::backup::password_confirmation', $values);
        if ($cloudPasswordChanged && ($values['market::cloud::backup::password'] ?? null) !== ($values['market::cloud::backup::password_confirmation'] ?? null)) {
            throw ApiError::field(422, 'validation_error', 'market::cloud::backup::password_confirmation', 'Les mots de passe de chiffrement diffèrent.');
        }
        if ((array_key_exists('core::repo::provider', $values) || array_key_exists('url::core::url', $values))
            && ($values['core::repo::provider'] ?? \config::byKey('core::repo::provider')) === 'url'
            && !self::isCoreUpdateUrl((string) ($values['url::core::url'] ?? \config::byKey('url::core::url')))) {
            throw ApiError::field(422, 'validation_error', 'url::core::url', 'Renseignez l’URL de l’archive du Core.');
        }
        // Toujours en transaction : un hook preConfig_*/postConfig_* qui lève ne doit pas laisser
        // les clés précédentes écrites. Les effets des hooks hors base (démarrage ou arrêt du
        // service DNS, caches des résumés d'objets) ne sont pas annulés.
        $sensitive = array_intersect_key($values, self::SECTIONS['backups'] + self::SECTIONS['updates']) !== [];
        \DB::beginTransaction();
        try {
            foreach ($values as $key => $value) {
                \config::save($key, $value);
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new ApiError(422, 'validation_error', $sensitive ? 'Impossible d’enregistrer la configuration.' : $e->getMessage());
        }
        return Json::respond($response, (object) self::read(array_map('strval', array_keys($body)), true));
    }

    /** @return array<string, string> toutes les clés exposées et leur type */
    private static function exposedKeys(): array
    {
        static $keys = null;
        if ($keys === null) {
            $keys = ['object:summary' => 'summary', 'ui::menu' => 'menu', 'calculHistory' => 'calculHistory'] + array_merge(...array_values(self::SECTIONS));
            foreach (\update::listRepo() as $id => $repo) {
                if (!empty($repo['scope']['proxy'])) {
                    $keys[$id . '::allowDNS'] = 'boolean';
                }
            }
        }
        return $keys;
    }

    /**
     * @param list<string> $keys
     * @return array<string, mixed>
     */
    private static function read(array $keys, bool $admin): array
    {
        $types = self::exposedKeys();
        $stored = \config::byKeys(array_map(self::storedKey(...), array_diff($keys, [LogLevels::KEY])));
        $result = [];
        foreach ($keys as $key) {
            $storedKey = self::storedKey($key);
            if (!$admin && !self::userReadable($key, $storedKey)) {
                continue;
            }
            if ($key === LogLevels::KEY) {
                $result[$key] = LogLevels::read();
                continue;
            }
            $value = self::toApi($types[$key], $stored[$storedKey] ?? '');
            // Sans rétention Samba configurée, la modale doit pouvoir afficher un champ vide.
            $result[$key] = $key === 'samba::keepDays' && $value === null ? '' : $value;
        }
        return $result;
    }

    private static function toApi(string $type, mixed $value): mixed
    {
        return match ($type) {
            'secret' => ['isSet' => $value !== '' && $value !== null],
            'boolean' => (string) $value === '1',
            'number' => is_numeric($value) ? $value + 0 : null,
            'summary' => (object) self::summariesToApi(is_array($value) ? $value : []),
            'menu' => self::menuToApi($value),
            'calculHistory' => self::calculationsToApi($value),
            'notifications' => is_array($value) ? $value : ['destinations' => [], 'types' => []],
            default => is_scalar($value) ? (string) $value : json_encode($value, JSON_UNESCAPED_UNICODE),
        };
    }

    private static function validate(string $key, mixed $value): mixed
    {
        $type = self::exposedKeys()[$key] ?? null;
        if ($type === 'summary') {
            return self::validateSummaries($value);
        }
        if ($type === 'menu') {
            return $value === null ? '' : self::validateMenu($value);
        }
        if ($type === 'calculHistory') {
            return self::validateCalculations($value);
        }
        if ($type === 'notifications') {
            try {
                return \config::validateNotificationSettings($value);
            } catch (\InvalidArgumentException $e) {
                throw ApiError::field(422, 'validation_error', $key, $e->getMessage());
            }
        }
        $ok = match ($type) {
            null => throw ApiError::field(422, 'validation_error', $key, 'Clé inconnue ou non exposée.'),
            'boolean' => is_bool($value),
            'number' => $value === null || is_int($value) || is_float($value),
            default => is_string($value),
        };
        if (!$ok) {
            $expected = ['boolean' => 'un booléen', 'number' => 'un nombre ou null'][$type] ?? 'un texte';
            throw ApiError::field(422, 'validation_error', $key, "Valeur invalide : $expected est attendu.");
        }
        $invalidUpdateSetting = match ($key) {
            'core::repo::provider' => $value !== 'default' && empty(\update::listRepo()[$value]['scope']['core'])
                ? 'Source de mise à jour du Core inconnue.' : null,
            'core::branch', 'github::core::branch' => !\update::isCoreBranchName($value) ? 'Nom de branche Git invalide.' : null,
            'github::core::user', 'github::core::repository' => !preg_match('/\A(?!\.{1,2}\z)[A-Za-z0-9_.-]{1,100}\z/', $value)
                ? 'Nom de propriétaire ou de dépôt GitHub invalide.' : null,
            'github::token' => strlen($value) > 1024 || preg_match('/[\x00-\x20\x7f]/', $value) ? 'Jeton GitHub invalide.' : null,
            'url::core::url', 'url::core::version' => $value !== '' && !self::isCoreUpdateUrl($value) ? 'Une URL HTTP ou HTTPS est attendue.' : null,
            default => null,
        };
        if ($invalidUpdateSetting !== null) {
            throw ApiError::field(422, 'validation_error', $key, $invalidUpdateSetting);
        }
        if (str_starts_with($key, 'samba::backup::') && !\repo_samba::validBackupSetting(substr($key, strlen('samba::backup::')), $value)) {
            throw ApiError::field(422, 'validation_error', $key, 'Paramètre Samba invalide.');
        }
        if ($key === 'network::trustedProxies') {
            try {
                $value = RequestSecurity::normalizeTrustedProxies($value);
            } catch (\InvalidArgumentException $e) {
                throw ApiError::field(422, 'validation_error', $key, 'Adresse IP ou plage CIDR invalide : ' . mb_substr($e->getMessage(), 0, 64) . '.');
            }
            if ($value === '') {
                throw ApiError::field(422, 'validation_error', $key, 'Indiquez au moins une adresse IP ou plage CIDR (127.0.0.1;::1 pour n’accepter que le relais local).');
            }
        }
        if ($key === 'market::cloud::backup::name' && $value !== '' && !\repo_market::isBackupFolderName($value)) {
            throw ApiError::field(422, 'validation_error', $key, 'Nom du dossier Cloud invalide.');
        }
        // Le script de sauvegarde utilise le chemin dans des arguments shell entre guillemets doubles.
        if ($key === 'backup::path' && (trim($value) === '' || preg_match('/["\\\\$\x60\x00-\x1f\x7f]/', $value))) {
            throw ApiError::field(422, 'validation_error', $key, 'Chemin de sauvegarde invalide.');
        }
        return match ($type) {
            'boolean' => $value ? '1' : '0',
            'number' => $value ?? '',
            default => $value,
        };
    }

    private static function isCoreUpdateUrl(string $value): bool
    {
        return strlen($value) <= 4096 && filter_var($value, FILTER_VALIDATE_URL) !== false
            && in_array(strtolower((string) parse_url($value, PHP_URL_SCHEME)), ['http', 'https'], true);
    }

    private const CALCULATION_GROUPING = '/\A((avg|max|high|min|low|sum)::(minute|hour|day|week|month|year))?\z/';

    /**
     * Calculs d'historique enregistrés, au format de l'ancienne interface : formules en noms
     * lisibles (comme `convertToHumanReadable`), `graphStep` en booléen. Les entrées illisibles sont ignorées.
     *
     * @return list<array<string, mixed>>
     */
    private static function calculationsToApi(mixed $value): array
    {
        $result = [];
        foreach (is_array($value) ? $value : [] as $item) {
            if (!is_array($item) || !is_string($item['calcul'] ?? null) || trim($item['calcul']) === '') {
                continue;
            }
            $graphType = (string) ($item['graphType'] ?? '');
            $groupingType = (string) ($item['groupingType'] ?? '');
            $result[] = [
                'name' => (string) ($item['name'] ?? ''),
                'calcul' => (string) \jeedom::toHumanReadable($item['calcul']),
                'graphType' => in_array($graphType, ['', 'line', 'area', 'column'], true) ? $graphType : '',
                'groupingType' => preg_match(self::CALCULATION_GROUPING, $groupingType) ? $groupingType : '',
                'graphStep' => in_array($item['graphStep'] ?? '0', ['1', 1, true], true),
            ];
        }
        return $result;
    }

    /**
     * Valide la liste des calculs d'historique et la renvoie au format stocké par l'ancienne
     * interface : formules avec identifiants (comme `fromHumanReadable`), `graphStep` en '0' / '1'.
     *
     * @return list<array<string, string>>
     */
    private static function validateCalculations(mixed $items): array
    {
        if (!is_array($items) || !array_is_list($items) || count($items) > 100) {
            throw ApiError::field(422, 'validation_error', 'calculHistory', 'Une liste de 100 calculs au plus est attendue.');
        }
        $result = [];
        foreach ($items as $i => $item) {
            $path = "calculHistory.$i";
            if (!is_array($item) || array_is_list($item) && $item !== []) {
                throw ApiError::field(422, 'validation_error', $path, 'Chaque calcul doit être un objet.');
            }
            if ($unknown = array_diff(array_keys($item), ['name', 'calcul', 'graphType', 'groupingType', 'graphStep'])) {
                throw ApiError::field(422, 'validation_error', "$path." . reset($unknown), 'Champ inconnu.');
            }
            $calcul = $item['calcul'] ?? null;
            if (!is_string($calcul) || trim($calcul) === '') {
                throw ApiError::field(422, 'validation_error', "$path.calcul", 'La formule est obligatoire.');
            }
            $name = $item['name'] ?? '';
            if (!is_string($name) || mb_strlen($name) > 127) {
                throw ApiError::field(422, 'validation_error', "$path.name", 'Un texte de 127 caractères au plus est attendu.');
            }
            $graphType = $item['graphType'] ?? '';
            if (!in_array($graphType, ['', 'line', 'area', 'column'], true)) {
                throw ApiError::field(422, 'validation_error', "$path.graphType", 'Type attendu : line, area, column ou vide.');
            }
            $groupingType = $item['groupingType'] ?? '';
            if (!is_string($groupingType) || !preg_match(self::CALCULATION_GROUPING, $groupingType)) {
                throw ApiError::field(422, 'validation_error', "$path.groupingType", 'Format attendu : fonction::période (ex. avg::hour) ou vide.');
            }
            $graphStep = $item['graphStep'] ?? false;
            if (!is_bool($graphStep)) {
                throw ApiError::field(422, 'validation_error', "$path.graphStep", 'Un booléen est attendu.');
            }
            $result[] = [
                'name' => $name,
                'calcul' => (string) \jeedom::fromHumanReadable($calcul),
                'graphType' => $graphType,
                'groupingType' => $groupingType,
                'graphStep' => $graphStep ? '1' : '0',
            ];
        }
        return $result;
    }

    /** Menu enregistré, ou null (menu par défaut) s'il est absent ou illisible. */
    private static function menuToApi(mixed $value): ?array
    {
        if (!is_array($value)) {
            return null;
        }
        try {
            return self::validateMenu($value);
        } catch (ApiError) {
            return null;
        }
    }

    /** Valide une liste d'entrées du menu latéral (`ui::menu`) et la renvoie normalisée. */
    private static function validateMenu(mixed $items): array
    {
        try {
            return \uiMenu::validateItems($items, true);
        } catch (\InvalidArgumentException $e) {
            throw ApiError::field(422, 'validation_error', 'ui::menu', $e->getMessage());
        }
    }

    /** @return array<string, array<string, mixed>> */
    private static function summariesToApi(array $summaries): array
    {
        $result = [];
        foreach ($summaries as $key => $definition) {
            if (!is_array($definition)) {
                continue;
            }
            $item = [];
            foreach (self::SUMMARY_FIELDS as $field => $type) {
                if (array_key_exists($field, $definition)) {
                    $item[$field] = match ($type) {
                        'boolean' => (string) $definition[$field] === '1' || $definition[$field] === true,
                        'raw' => $definition[$field],
                        default => (string) $definition[$field],
                    };
                }
            }
            $item['key'] = (string) $key;
            $result[(string) $key] = $item;
        }
        return $result;
    }

    /** @return array<string, array<string, mixed>> définitions au format stocké par l'administration */
    private static function validateSummaries(mixed $value): array
    {
        if (!is_array($value) || array_is_list($value) && $value !== []) {
            throw ApiError::field(422, 'validation_error', 'object:summary', 'Un objet indexé par clé de résumé est attendu.');
        }
        $summaries = [];
        foreach ($value as $key => $definition) {
            $key = (string) $key;
            if (!is_array($definition) || ($definition['key'] ?? null) !== $key || !preg_match('/^[A-Za-z0-9_]+$/', $key)) {
                throw ApiError::field(422, 'validation_error', "object:summary.$key", 'Chaque entrée doit être un objet dont le champ « key » est égal à sa clé (lettres, chiffres, _).');
            }
            $summaries[$key] = self::validateSummary($key, $definition);
        }
        return $summaries;
    }

    /** @return array<string, mixed> définition au format stocké par l'administration */
    private static function validateSummary(string $key, array $definition): array
    {
        $stored = [];
        foreach ($definition as $field => $value) {
            $type = self::SUMMARY_FIELDS[$field] ?? null;
            $ok = match ($type) {
                null => false,
                'boolean' => is_bool($value),
                'raw' => is_string($value) || is_int($value),
                default => is_string($value),
            };
            if (!$ok) {
                throw ApiError::field(422, 'validation_error', "object:summary.$key.$field", 'Champ inconnu ou de mauvais type.');
            }
            $stored[$field] = $type === 'boolean' ? ($value ? '1' : '0') : $value;
        }
        if (trim((string) ($stored['name'] ?? '')) === '') {
            throw ApiError::field(422, 'validation_error', "object:summary.$key.name", 'Le nom est obligatoire.');
        }
        if (isset($stored['calcul']) && !in_array($stored['calcul'], ['sum', 'avg', 'text'], true)) {
            throw ApiError::field(422, 'validation_error', "object:summary.$key.calcul", 'Calcul attendu : sum, avg ou text.');
        }
        if (isset($stored['count']) && !in_array($stored['count'], ['', 'binary'], true)) {
            throw ApiError::field(422, 'validation_error', "object:summary.$key.count", 'Comptage attendu : vide ou binary.');
        }
        return $stored;
    }

    private static function userReadable(string $key, string $storedKey): bool
    {
        return (in_array($key, self::USER_READABLE, true) || array_key_exists($key, self::SECTIONS['interface']))
            && !\config::shouldBeEncrypted($storedKey);
    }

    private static function storedKey(string $key): string
    {
        return self::STORED_AS[$key] ?? $key;
    }

    private static function isAdmin(Request $request): bool
    {
        return $request->getAttribute('user')?->getProfils() === 'admin';
    }
}
