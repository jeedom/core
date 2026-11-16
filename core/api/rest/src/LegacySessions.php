<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

/** Accès en administration aux sessions PHP locales, sans exposer leurs identifiants de cookie. */
final class LegacySessions
{
    public static function active(): ?array
    {
        $paths = self::files();
        return $paths === null ? null : iterator_to_array(self::scan($paths), false);
    }

    public static function revoke(\user $user, string $id): bool
    {
        foreach (self::scan(self::files() ?? [], $id) as $row) {
            if ($row['user_id'] !== (int) $user->getId()) {
                continue;
            }
            $phpId = substr(basename($row['path']), 5);
            $devices = $user->getOptions('registerDevice', []);
            if (is_array($devices)) {
                $remaining = array_filter($devices, static fn ($device) => ($device['session_id'] ?? null) !== $phpId);
                if ($remaining !== $devices) {
                    $user->setOptions('registerDevice', $remaining)->save();
                }
            }
            // Les requêtes qui ont déjà ouvert ce fichier liront une session vide après le déverrouillage.
            if (!ftruncate($row['handle'], 0)) {
                throw new \RuntimeException('Impossible de fermer la session PHP.');
            }
            @unlink($row['path']);
            \log::audit('Legacy PHP session revoked from REST', ['sessionId' => $id, 'userId' => $row['user_id']]);
            return true;
        }
        return false;
    }

    private static function files(): ?array
    {
        if (session_module_name() !== 'files' || !in_array(ini_get('session.serialize_handler'), ['php', 'php_serialize'], true)) {
            return null;
        }
        $parts = explode(';', session_save_path(), 3);
        $directory = realpath((string) (end($parts) ?: sys_get_temp_dir()));
        if ($directory === false || !is_dir($directory) || !is_readable($directory)) {
            return null;
        }
        $depth = count($parts) > 1 ? (int) $parts[0] : 0;
        $pattern = addcslashes($directory, '\\*?[]') . str_repeat('/[a-zA-Z0-9,-]', max(0, $depth)) . '/sess_*';
        $files = glob($pattern, GLOB_NOSORT);
        return $files === false ? null : $files;
    }

    /** Garde le verrou pendant la lecture et, en cas de révocation, jusqu'à la sortie de la boucle. */
    private static function scan(array $paths, ?string $target = null): \Generator
    {
        $lifetime = \config::preConfig_session_lifetime(\config::byKey('session_lifetime', 'core', 24)) * 3600;
        foreach ($paths as $path) {
            if (!preg_match('/\Asess_([a-zA-Z0-9,-]{1,256})\z/', basename($path), $match)) {
                continue;
            }
            $id = hash('sha256', 'legacy:' . $match[1]);
            if (($target !== null && $id !== $target) || is_link($path) || !is_file($path)) {
                continue;
            }
            $handle = @fopen($path, $target === null ? 'rb' : 'r+b');
            if ($handle === false) {
                continue;
            }
            try {
                if (!flock($handle, $target === null ? LOCK_SH : LOCK_EX)) {
                    continue;
                }
                clearstatcache(true, $path);
                $entry = @lstat($path);
                $stat = fstat($handle);
                if ($entry === false || $stat === false || ($entry['mode'] & 0170000) !== 0100000
                    || $entry['dev'] !== $stat['dev'] || $entry['ino'] !== $stat['ino']
                    || (function_exists('posix_geteuid') && $stat['uid'] !== posix_geteuid())) {
                    continue;
                }
                $expires = $stat['mtime'] + $lifetime;
                if ($expires <= time()) {
                    continue;
                }
                $raw = stream_get_contents($handle);
                if ($raw === false || $raw === '') {
                    continue;
                }
                $options = ['allowed_classes' => [\user::class]];
                try {
                    $data = ini_get('session.serialize_handler') === 'php_serialize'
                        ? @unserialize($raw, $options) : @\decodeSessionData($raw, $options);
                } catch (\Throwable) {
                    continue;
                }
                if (!(($data['user'] ?? null) instanceof \user)) {
                    continue;
                }
                $row = [
                    'id' => $id, 'user_id' => (int) $data['user']->getId(),
                    'ip' => filter_var($data['ip'] ?? '', FILTER_VALIDATE_IP) ?: '', 'user_agent' => null,
                    'last_activity_at' => gmdate('Y-m-d H:i:s', $stat['mtime']), 'expires_at' => gmdate('Y-m-d H:i:s', $expires),
                    'client_name' => '', 'mode' => 'legacy', 'scope' => '',
                ];
                yield $target === null ? $row : $row + ['path' => $path, 'handle' => $handle];
            } finally {
                fclose($handle);
            }
        }
    }
}
