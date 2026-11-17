<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

require_once __DIR__ . '/progressLog.class.php';

/**
 * Suivi d'une restauration lancée par jeedom::runBackupInBackground, indépendant de la base
 * et des sessions : un fichier par opération (en-tête avec l'empreinte du jeton et
 * l'expiration, puis le résultat écrit par le lanceur, qui garde le verrou jusqu'à la fin).
 *
 * Ce fichier ne charge pas core.inc.php : l'API REST le lit avant le bootstrap du Core,
 * pendant que la base est remplacée.
 */
final class restoreTracking {
	/** Fichiers conservés pendant une restauration suivie (la lecture du suivi en dépend). */
	public const FILES = array(
		'api/v1/.htaccess',
		'core/api/rest/index.php',
		'core/api/rest/src/Router.php',
		'core/api/rest/src/RestoreStatus.php',
		'core/api/rest/src/Json.php',
		'core/api/rest/src/ApiError.php',
		'core/class/restoreTracking.class.php',
		'core/class/progressLog.class.php',
	);
	private const LIFETIME = 86400;

	/** @return array{id: string, token: string, expires: int, result: resource} */
	public static function start(): array {
		$directory = self::directory(true);
		self::clean($directory);
		$id = bin2hex(random_bytes(16));
		$token = bin2hex(random_bytes(32));
		$expires = time() + self::LIFETIME;
		$header = json_encode(['hash' => hash('sha256', $token), 'expires' => $expires], JSON_THROW_ON_ERROR) . "\n";
		$path = $directory . '/' . $id;
		$result = @fopen($path, 'x+');
		if ($result === false) {
			throw new RuntimeException('Impossible de créer le suivi de restauration.');
		}
		if (!flock($result, LOCK_EX | LOCK_NB) || @fwrite($result, $header) !== strlen($header) || !@fflush($result)) {
			fclose($result);
			@unlink($path);
			throw new RuntimeException('Impossible d’enregistrer le suivi de restauration.');
		}
		// Le lanceur hérite du verrou et écrit le résultat à la suite de cet en-tête.
		return ['id' => $id, 'token' => $token, 'expires' => $expires, 'result' => $result];
	}

	public static function discard(string $id): void {
		$directory = self::directory();
		if ($directory !== null && preg_match('/\A[0-9a-f]{32}\z/', $id)) {
			@unlink($directory . '/' . $id);
		}
	}

	/**
	 * État d'une opération, ou null si l'identifiant, le jeton ou le suivi ne conviennent pas.
	 * @return array{state: string, progress?: ?int}|null
	 */
	public static function status(mixed $id, mixed $token): ?array {
		if (!is_string($id) || !preg_match('/\A[0-9a-f]{32}\z/', $id)
			|| !is_string($token) || !preg_match('/\A[0-9a-f]{64}\z/', $token)) {
			return null;
		}
		$directory = self::directory();
		if ($directory === null) {
			return null;
		}
		$result = @fopen($directory . '/' . $id, 'r');
		if ($result === false) {
			return null;
		}
		try {
			$header = fgets($result, 256);
			$metadata = $header === false ? null : json_decode($header, true);
			if (!is_int($metadata['expires'] ?? null) || $metadata['expires'] <= time()
				|| !is_string($metadata['hash'] ?? null)
				|| !hash_equals($metadata['hash'], hash('sha256', $token))) {
				return null;
			}
			if (!flock($result, LOCK_SH | LOCK_NB)) {
				return ['state' => 'running', 'progress' => self::progress($id)];
			}
			return ['state' => match (fread($result, 8)) {
				'success' => 'success',
				'failed' => 'failed',
				default => 'unknown',
			}];
		} finally {
			fclose($result);
		}
	}

	private static function directory(bool $create = false): ?string {
		if (function_exists('posix_geteuid')) {
			// Un lancement en CLI peut utiliser un autre compte que le serveur web.
			$uid = posix_geteuid();
			$installation = substr(hash('sha256', dirname(__DIR__, 2)), 0, 24);
			$path = sys_get_temp_dir() . '/jeedom-restore-' . $installation . '-' . $uid;
			if ($create && !is_dir($path)) {
				@mkdir($path, 0700);
			}
			$stat = @lstat($path);
			if ($stat !== false && ($stat['mode'] & 0177777) === 0040700 && $stat['uid'] === $uid) {
				return $path;
			}
		}
		if ($create) {
			throw new RuntimeException('Répertoire de suivi de restauration indisponible.');
		}
		return null;
	}

	private static function clean(string $directory): void {
		$cutoff = time() - self::LIFETIME;
		foreach (glob($directory . '/*') ?: [] as $path) {
			$modified = @filemtime($path);
			if ($modified !== false && $modified <= $cutoff) {
				@unlink($path);
			}
		}
	}

	/** Avancement lu dans log/restore, borné à 99 : la fin se lit dans le résultat du lanceur. */
	private static function progress(string $id): ?int {
		$log = @fopen(dirname(__DIR__, 2) . '/log/restore', 'rb');
		if ($log === false) {
			return null;
		}
		try {
			// Le journal est partagé. Pour cette opération, le lanceur marque sa première ligne.
			if (fgets($log, 64) !== "[RESTORE $id]\n") {
				return null;
			}
			$percent = progressLog::lastPercent(progressLog::tail($log));
			return $percent === null ? null : min(99, $percent);
		} finally {
			fclose($log);
		}
	}
}
