<?php

/** Direct plugin installation sources; never accepts a client-supplied local path. */
class pluginRepository {

	public static function all(): array {
		$items = array();
		foreach (update::listRepo() as $id => $repo) {
			if (!in_array($id, array('market', 'github', 'url', 'file', 'samba'), true) || empty($repo['enable']) || empty($repo['scope']['plugin'])) continue;
			if ($id === 'market') {
				$items[] = array('id' => $id, 'name' => (string) $repo['name'], 'fields' => array());
				continue;
			}
			$fields = array();
			foreach ($repo['configuration']['parameters_for_add'] ?? array() as $key => $field) {
				$fields[] = array('id' => $key, 'name' => (string) $field['name'],
					'type' => $key === 'token' ? 'password' : ($field['type'] === 'file' ? 'file' : 'text'),
					'required' => $key !== 'token', 'default' => (string) ($field['default'] ?? ''));
			}
			if ($fields) $items[] = array('id' => $id, 'name' => (string) $repo['name'], 'fields' => $fields);
		}
		return $items;
	}

	public static function validate(string $id, string $source, array $configuration, bool $checkArchive = true): array {
		plugin::validatePluginId($id);
		if ($source === 'market') throw new InvalidArgumentException('Utilisez la route d’installation Market.', 422);
		$repo = null;
		foreach (self::all() as $item) if ($item['id'] === $source) $repo = $item;
		if ($repo === null) throw new InvalidArgumentException('Source d’installation absente ou désactivée.', 422);
		$allowed = array_column($repo['fields'], 'id');
		if (array_diff(array_keys($configuration), $allowed)) throw new InvalidArgumentException('Paramètre de dépôt inconnu.', 422);
		$result = array();
		foreach ($repo['fields'] as $field) {
			$value = $configuration[$field['id']] ?? $field['default'];
			if (!is_string($value) || strlen($value) > 4096 || ($field['required'] && trim($value) === '')) throw new InvalidArgumentException('Champ de dépôt invalide : ' . $field['name'], 422);
			$result[$field['id']] = trim($value);
		}
		if ($source === 'github') {
			foreach (array('user' => '/\A[A-Za-z0-9][A-Za-z0-9_-]{0,100}\z/', 'repository' => '/\A[A-Za-z0-9_][A-Za-z0-9_.-]{0,100}\z/',
				'version' => '/\A[A-Za-z0-9_][A-Za-z0-9_\/.+-]{0,200}\z/', 'token' => '/\A[A-Za-z0-9_-]*\z/') as $key => $pattern) {
				if (!preg_match($pattern, $result[$key]) || str_contains($result[$key], '..')) throw new InvalidArgumentException('Paramètre GitHub invalide : ' . $key, 422);
			}
		} elseif ($source === 'url') {
			$url = $result['url'];
			if (!filter_var($url, FILTER_VALIDATE_URL) || !in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), array('http', 'https'), true)
				|| parse_url($url, PHP_URL_USER) !== null || parse_url($url, PHP_URL_PASS) !== null || preg_match('/[\x00-\x20\x7f]/', $url)) {
				throw new InvalidArgumentException('URL HTTP(S) du fichier ZIP invalide.', 422);
			}
		} elseif ($source === 'samba') {
			if (!preg_match('/\A[A-Za-z0-9_][A-Za-z0-9_\/.-]*\.zip\z/i', $result['path']) || str_contains($result['path'], '..')) throw new InvalidArgumentException('Chemin ZIP Samba invalide.', 422);
		} else {
			if (!preg_match('/\A[a-f0-9]{32}\z/', $result['path'])) throw new InvalidArgumentException('Archive téléversée invalide.', 422);
			if ($checkArchive) self::archivePath($result['path'], $id);
		}
		$update = update::byTypeAndLogicalId('plugin', $id);
		if (is_object($update) && $update->getConfiguration('doNotUpdate', 0) == 1) throw new RuntimeException('La réinstallation de ce plugin est désactivée.', 409);
		ksort($result);
		return $result;
	}

	public static function upload(\Psr\Http\Message\UploadedFileInterface $file): array {
		if ($file->getError() !== UPLOAD_ERR_OK || $file->getSize() === null || $file->getSize() < 100 || $file->getSize() > 100000000
			|| strtolower(pathinfo($file->getClientFilename() ?? '', PATHINFO_EXTENSION)) !== 'zip') throw new InvalidArgumentException('Un fichier ZIP de 100 Mo maximum est attendu.', 422);
		$folder = self::archiveFolder();
		foreach (glob($folder . '/*.zip') ?: array() as $old) if (filemtime($old) < time() - 3600) unlink($old);
		$token = bin2hex(random_bytes(16));
		$path = $folder . '/' . $token . '.zip';
		try {
			$file->moveTo($path);
			chmod($path, 0600);
			$id = self::archivePluginId($path);
			return array('id' => $token, 'pluginId' => $id, 'name' => basename($file->getClientFilename()), 'size' => filesize($path));
		} catch (Throwable $error) {
			if (is_file($path)) unlink($path);
			throw $error;
		}
	}

	public static function archivePath(string $token, string $id): string {
		if (!preg_match('/\A[a-f0-9]{32}\z/', $token)) throw new InvalidArgumentException('Archive invalide.', 422);
		$path = self::archiveFolder() . '/' . $token . '.zip';
		if (!is_file($path) || filemtime($path) < time() - 3600) throw new RuntimeException('Archive absente ou expirée. Téléverser à nouveau le fichier.', 409);
		if (self::archivePluginId($path) !== $id) throw new InvalidArgumentException('L’identifiant saisi ne correspond pas au plugin de l’archive.', 422);
		return $path;
	}

	public static function removeArchive(string $token): void {
		if (preg_match('/\A[a-f0-9]{32}\z/', $token)) {
			$path = self::archiveFolder() . '/' . $token . '.zip';
			if (is_file($path)) unlink($path);
		}
	}

	private static function archiveFolder(): string {
		$folder = jeedom::getTmpFolder('rest-plugin-archives');
		chmod($folder, 0700);
		return $folder;
	}

	private static function archivePluginId(string $path): string {
		$zip = new ZipArchive();
		if ($zip->open($path) !== true) throw new InvalidArgumentException('Archive ZIP illisible.', 422);
		try {
			$manifest = null;
			$size = 0;
			if ($zip->numFiles > 10000) throw new InvalidArgumentException('Archive ZIP trop volumineuse.', 422);
			for ($i = 0; $i < $zip->numFiles; $i++) {
				$entry = $zip->statIndex($i);
				$name = $entry['name'];
				$size += $entry['size'];
				$opsys = $attributes = 0;
				$zip->getExternalAttributesIndex($i, $opsys, $attributes);
				if (str_starts_with($name, '/') || str_contains($name, '\\') || preg_match('~(^|/)\.\.(/|$)|^[A-Za-z]:~', $name)
					|| (($attributes >> 16) & 0170000) === 0120000 || $size > 1000000000) throw new InvalidArgumentException('Contenu de l’archive ZIP invalide.', 422);
				if (preg_match('~^(?:[^/]+/)?plugin_info/info\.json$~', $name)) {
					if ($manifest !== null || $entry['size'] > 1000000) throw new InvalidArgumentException('Manifeste du plugin invalide.', 422);
					$manifest = json_decode($zip->getFromIndex($i), true);
				}
			}
			$id = is_array($manifest) ? ($manifest['id'] ?? null) : null;
			if (!is_string($id)) throw new InvalidArgumentException('Manifeste du plugin absent ou invalide.', 422);
			plugin::validatePluginId($id);
			return $id;
		} finally { $zip->close(); }
	}
}
