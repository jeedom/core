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

require_once __DIR__ . '/../php/core.inc.php';

class reportTemplateImage {
    private const TYPES = ['image/png', 'image/jpeg', 'image/webp', 'image/gif'];
    private const MAX_PIXELS = 16000000;

    public static function save(int $templateId, string $source): string {
        $bytes = file_get_contents($source, false, null, 0, utils::IMAGE_MAX_SIZE + 1);
        $info = $bytes === false ? false : @getimagesizefromstring($bytes);
        if ($info === false || !in_array($info['mime'], self::TYPES, true)) {
            throw new InvalidArgumentException('Une image PNG, JPEG, WebP ou GIF est attendue.');
        }
        if ($info[0] * $info[1] > self::MAX_PIXELS) {
            throw new InvalidArgumentException('L’image dépasse 16 mégapixels.');
        }
        if (strlen($bytes) > utils::IMAGE_MAX_SIZE || @imagecreatefromstring($bytes) === false) {
            throw new InvalidArgumentException('L’image est invalide.');
        }

        $id = substr(hash('sha256', $bytes), 0, 32);
        return reportTemplate::withLock($templateId, static function () use ($templateId, $id, $bytes): string {
            $directory = self::directory($templateId);
            if ((!is_dir($directory) && !mkdir($directory, 0750, true)) || realpath($directory) !== $directory) {
                throw new RuntimeException('Impossible de créer le dossier des images du rapport.');
            }
            if (self::path($templateId, $id) !== null) {
                return $id;
            }
            $temporary = $directory . '/.pending-' . bin2hex(random_bytes(8));
            try {
                if (file_put_contents($temporary, $bytes) !== strlen($bytes) || !rename($temporary, $directory . '/' . $id)) {
                    throw new RuntimeException('Impossible d’enregistrer l’image du rapport.');
                }
            } finally {
                if (is_file($temporary)) {
                    unlink($temporary);
                }
            }
            return $id;
        });
    }

    public static function path(int $templateId, string $id): ?string {
        if (!preg_match('/\A[a-f0-9]{32}\z/', $id)) {
            return null;
        }
        $path = self::directory($templateId) . '/' . $id;
        return realpath($path) === $path && is_file($path) && filesize($path) <= utils::IMAGE_MAX_SIZE ? $path : null;
    }

    private static function directory(int $templateId): string {
        return reportTemplate::dataDirectory($templateId) . '/images';
    }
}
