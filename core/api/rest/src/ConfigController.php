<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Configuration globale du core, par onglet de l'administration.
 * Lecture : tout utilisateur connecté, sans les clés chiffrées pour un non-admin.
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
        'cache' => [
            'cache::engine' => 'string',
            'cache::redisaddr' => 'string',
            'cache::redisport' => 'number',
            'event::waitPollingTime' => 'number',
        ],
        'api' => [
            'api::core::mode' => 'string',
            'api::apitts::mode' => 'string',
            'api::apipro::mode' => 'string',
            'api::apimarket::mode' => 'string',
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

    public static function sectionPattern(): string
    {
        return implode('|', array_keys(self::SECTIONS));
    }

    public function all(Request $request, Response $response): Response
    {
        $result = ['summaries' => self::readSummaries()];
        foreach (array_keys(self::SECTIONS) as $section) {
            $result[$section] = self::read($section, self::isAdmin($request));
        }
        return Json::respond($response, $result);
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        return Json::respond($response, self::read($args['section'], self::isAdmin($request)));
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        self::requireWrite($request);
        $section = $args['section'];
        $body = Json::body($request);
        if ($body === []) {
            throw new ApiError(422, 'validation_error', 'Aucune clé à modifier.');
        }
        $values = [];
        foreach ($body as $key => $value) {
            $values[self::storedKey((string) $key)] = self::validate($section, (string) $key, $value);
        }
        foreach ($values as $key => $value) {
            try {
                \config::save($key, $value);
            } catch (\Exception $e) {
                throw new ApiError(422, 'validation_error', $e->getMessage());
            }
        }
        return Json::respond($response, self::read($section, true));
    }

    public function summaries(Request $request, Response $response): Response
    {
        return Json::respond($response, self::readSummaries());
    }

    public function replaceSummaries(Request $request, Response $response): Response
    {
        self::requireWrite($request);
        $body = Json::body($request);
        $summaries = [];
        foreach ($body as $key => $definition) {
            $key = (string) $key;
            if (!is_array($definition) || ($definition['key'] ?? null) !== $key || !preg_match('/^[A-Za-z0-9_]+$/', $key)) {
                throw ApiError::field(422, 'validation_error', $key, 'Chaque entrée doit être un objet dont le champ « key » est égal à sa clé (lettres, chiffres, _).');
            }
            $summaries[$key] = self::validateSummary($key, $definition);
        }
        \config::save('object:summary', $summaries);
        return Json::respond($response, self::readSummaries());
    }

    /** @return array<string, mixed> */
    private static function read(string $section, bool $admin): array
    {
        $types = self::SECTIONS[$section];
        $stored = \config::byKeys(array_map(self::storedKey(...), array_keys($types)));
        $result = [];
        foreach ($types as $key => $type) {
            $storedKey = self::storedKey($key);
            if (!$admin && \config::shouldBeEncrypted($storedKey)) {
                continue;
            }
            $result[$key] = self::toApi($type, $stored[$storedKey] ?? '');
        }
        if (isset(self::REPOSITORY_KEYS[$section])) {
            foreach (self::repositoryKeys($section) as $key) {
                $result[$key] = self::toApi('boolean', \config::byKey($key));
            }
        }
        return $result;
    }

    private static function toApi(string $type, mixed $value): mixed
    {
        return match ($type) {
            'secret' => ['isSet' => $value !== '' && $value !== null],
            'boolean' => (string) $value === '1',
            'number' => is_numeric($value) ? $value + 0 : null,
            default => is_scalar($value) ? (string) $value : json_encode($value, JSON_UNESCAPED_UNICODE),
        };
    }

    private static function validate(string $section, string $key, mixed $value): mixed
    {
        $type = self::SECTIONS[$section][$key] ?? (in_array($key, self::repositoryKeys($section), true) ? 'boolean' : null);
        $ok = match ($type) {
            null => throw ApiError::field(422, 'validation_error', $key, 'Clé inconnue pour cet onglet.'),
            'boolean' => is_bool($value),
            'number' => $value === null || is_int($value) || is_float($value),
            default => is_string($value),
        };
        if (!$ok) {
            $expected = ['boolean' => 'un booléen', 'number' => 'un nombre ou null'][$type] ?? 'un texte';
            throw ApiError::field(422, 'validation_error', $key, "Valeur invalide : $expected est attendu.");
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
            $keys[] = $id . '::' . $suffix;
        }
        return $keys;
    }

    /** @return array<string, array<string, mixed>> */
    private static function readSummaries(): array
    {
        $result = [];
        foreach ((array) \config::byKey('object:summary') as $key => $definition) {
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
                throw ApiError::field(422, 'validation_error', "$key.$field", 'Champ inconnu ou de mauvais type.');
            }
            $stored[$field] = $type === 'boolean' ? ($value ? '1' : '0') : $value;
        }
        if (trim((string) ($stored['name'] ?? '')) === '') {
            throw ApiError::field(422, 'validation_error', "$key.name", 'Le nom est obligatoire.');
        }
        if (isset($stored['calcul']) && !in_array($stored['calcul'], ['sum', 'avg', 'text'], true)) {
            throw ApiError::field(422, 'validation_error', "$key.calcul", 'Calcul attendu : sum, avg ou text.');
        }
        if (isset($stored['count']) && !in_array($stored['count'], ['', 'binary'], true)) {
            throw ApiError::field(422, 'validation_error', "$key.count", 'Comptage attendu : vide ou binary.');
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

    private static function requireWrite(Request $request): void
    {
        Auth::requireAdmin($request);
        try {
            \unautorizedInDemo();
        } catch (\Exception $e) {
            throw new ApiError(403, 'demo_forbidden', $e->getMessage());
        }
    }
}
