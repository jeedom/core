<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Configuration globale du core, par onglet de l'administration.
 * Lecture : tout utilisateur connecté, avec les secrets et réglages de destination réservés aux admins.
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
            'alert::addMessageOnTimeout' => 'boolean',
            'alert::timeoutCmd' => 'string',
            'alert::addMessageOnBatterywarning' => 'boolean',
            'alert::batterywarningCmd' => 'string',
            'alert::addMessageOnBatterydanger' => 'boolean',
            'alert::batterydangerCmd' => 'string',
            'alert::addMessageOnWarning' => 'boolean',
            'alert::warningCmd' => 'string',
            'alert::addMessageOnDanger' => 'boolean',
            'alert::dangerCmd' => 'string',
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
            'sso:allowRemoteUser' => 'boolean',
            'sso:remoteUserHeader' => 'string',
            'dontProtectTooFastLaunchDependancy' => 'boolean',
            'dontProtectTooFastLaunchDaemon' => 'boolean',
        ],
        'updates' => [
            'core::repo::provider' => 'string',
            'core::branch' => 'string',
            'update::autocheck' => 'boolean',
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
     * Nom exposé => nom encore stocké en base, tant que le script de migration
     * du core n'a pas renommé ces clés.
     */
    private const STORED_AS = [
        'history::defaultShowPeriod' => 'history::defautShowPeriod',
        'dontProtectTooFastLaunchDaemon' => 'dontProtectTooFastLaunchDeamony',
    ];

    /** Clés dynamiques par dépôt : onglet => suffixe. */
    private const REPOSITORY_KEYS = ['network' => 'allowDNS', 'updates' => 'enable'];

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
            $values[self::storedKey((string) $key)] = self::validate((string) $key, $value);
        }
        $sambaChanged = array_key_exists('samba::cloudUpload', $values);
        $cloudPasswordChanged = array_key_exists('market::cloud::backup::password', $values)
            || array_key_exists('market::cloud::backup::password_confirmation', $values);
        if ($cloudPasswordChanged && ($values['market::cloud::backup::password'] ?? null) !== ($values['market::cloud::backup::password_confirmation'] ?? null)) {
            throw ApiError::field(422, 'validation_error', 'market::cloud::backup::password_confirmation', 'Les mots de passe de chiffrement diffèrent.');
        }
        if ($sambaChanged) {
            // Les anciennes installations et le moteur de sauvegarde utilisent ces deux clés.
            $values['samba::enable'] = $values['samba::cloudUpload'];
        }
        $atomic = array_intersect_key($values, self::SECTIONS['backups']) !== [];
        if ($atomic) {
            \DB::beginTransaction();
        }
        try {
            foreach ($values as $key => $value) {
                \config::save($key, $value);
            }
            if ($atomic) {
                \DB::commit();
            }
        } catch (\Exception $e) {
            if ($atomic) {
                \DB::rollBack();
                throw new ApiError(422, 'validation_error', 'Impossible d’enregistrer les paramètres de sauvegarde.');
            }
            throw new ApiError(422, 'validation_error', $e->getMessage());
        }
        return Json::respond($response, (object) self::read(array_map('strval', array_keys($body)), true));
    }

    /** @return array<string, string> toutes les clés exposées et leur type */
    private static function exposedKeys(): array
    {
        static $keys = null;
        if ($keys === null) {
            $keys = ['object:summary' => 'summary'] + array_merge(...array_values(self::SECTIONS));
            foreach (self::REPOSITORY_KEYS as $section => $suffix) {
                foreach (self::repositoryKeys($section) as $key) {
                    $keys[$key] = 'boolean';
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
        $storedKeys = array_map(self::storedKey(...), $keys);
        if (in_array('samba::cloudUpload', $keys, true)) {
            $storedKeys[] = 'samba::enable';
        }
        $stored = \config::byKeys($storedKeys);
        $result = [];
        foreach ($keys as $key) {
            $storedKey = self::storedKey($key);
            if (!$admin && (\config::shouldBeEncrypted($storedKey) || str_starts_with($key, 'samba::backup::')
                || str_starts_with($key, 'market::cloud::backup::'))) {
                continue;
            }
            $value = self::toApi($types[$key], $stored[$storedKey] ?? '');
            if ($key === 'samba::cloudUpload') {
                $value = $value && (string) ($stored['samba::enable'] ?? '') === '1';
            }
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
            default => is_scalar($value) ? (string) $value : json_encode($value, JSON_UNESCAPED_UNICODE),
        };
    }

    private static function validate(string $key, mixed $value): mixed
    {
        $type = self::exposedKeys()[$key] ?? null;
        if ($type === 'summary') {
            return self::validateSummaries($value);
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
        if (str_starts_with($key, 'samba::backup::') && !\repo_samba::validBackupSetting(substr($key, strlen('samba::backup::')), $value)) {
            throw ApiError::field(422, 'validation_error', $key, 'Paramètre Samba invalide.');
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

    /** @return list<string> clés `{dépôt}::{suffixe}` de l'onglet */
    private static function repositoryKeys(string $section): array
    {
        $suffix = self::REPOSITORY_KEYS[$section] ?? null;
        if ($suffix === null) {
            return [];
        }
        $keys = [];
        foreach (\update::listRepo() as $id => $repo) {
            // Comme l'administration : le DNS n'est proposé que pour les dépôts qui le gèrent.
            if ($suffix === 'allowDNS' && empty($repo['scope']['proxy'])) {
                continue;
            }
            if ($suffix === 'enable' && $id === 'samba') {
                continue;
            }
            $keys[] = $id . '::' . $suffix;
        }
        return $keys;
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

    private static function storedKey(string $key): string
    {
        return self::STORED_AS[$key] ?? $key;
    }

    private static function isAdmin(Request $request): bool
    {
        return $request->getAttribute('user')?->getProfils() === 'admin';
    }
}
