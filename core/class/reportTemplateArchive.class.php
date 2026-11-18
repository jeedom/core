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

/** PDF générés pour un modèle, conservés dans son dossier de données. */
class reportTemplateArchive {
    private const ID_PATTERN = '/\A[0-9]{8}T[0-9]{6}Z-[0-9a-f]{16}\z/';

    public static function list(int $templateId): array {
        $directory = self::directory($templateId);
        if (!self::exists($directory)) {
            return [];
        }
        $archives = [];
        foreach (new FilesystemIterator($directory, FilesystemIterator::SKIP_DOTS) as $file) {
            $id = $file->getBasename('.pdf');
            if ($file->getExtension() === 'pdf' && preg_match(self::ID_PATTERN, $id) && !$file->isLink() && $file->isFile()) {
                $archives[$id] = self::metadata($id, $file->getSize());
            }
        }
        krsort($archives, SORT_STRING);
        return array_values($archives);
    }

    /** @param resource $pdf */
    public static function save(int $templateId, $pdf): array {
        return reportTemplate::withLock($templateId, static function () use ($templateId, $pdf): array {
            $directory = self::directory($templateId);
            if (!self::exists($directory) && !mkdir($directory, 0750, true)) {
                throw new RuntimeException('Impossible de créer le dossier des rapports.');
            }
            $id = gmdate('Ymd\THis\Z') . '-' . bin2hex(random_bytes(8));
            $temporary = $directory . '/.pending-' . $id;
            $output = fopen($temporary, 'xb');
            if ($output === false) {
                throw new RuntimeException('Impossible de préparer l’archive du rapport.');
            }
            try {
                $size = fstat($pdf)['size'] ?? 0;
                if ($size <= 0 || stream_copy_to_stream($pdf, $output) !== $size || !fflush($output)) {
                    throw new RuntimeException('Impossible d’écrire le PDF du rapport.');
                }
                if (!rename($temporary, $directory . '/' . self::filename($id))) {
                    throw new RuntimeException('Impossible de publier le PDF du rapport.');
                }
                return self::metadata($id, $size);
            } finally {
                fclose($output);
                if (is_file($temporary)) {
                    unlink($temporary);
                }
            }
        });
    }

    /** @return resource */
    public static function open(int $templateId, string $id) {
        $file = @fopen(self::find($templateId, $id), 'rb');
        if ($file === false) {
            throw new OutOfBoundsException('Rapport introuvable ou illisible.');
        }
        return $file;
    }

    public static function remove(int $templateId, string $id): void {
        $file = self::find($templateId, $id);
        if (!@unlink($file)) {
            clearstatcache(true, $file);
            if (!is_file($file)) {
                throw new OutOfBoundsException('Rapport introuvable.');
            }
            throw new RuntimeException('Impossible de supprimer le PDF du rapport.');
        }
    }

    public static function filename(string $id): string {
        return $id . '.pdf';
    }

    public static function removeTemplate(reportTemplate $template, int $templateId): void {
        $directory = reportTemplate::dataDirectory($templateId);
        $discarded = null;
        try {
            reportTemplate::withLock($templateId, static function () use ($template, $directory, &$discarded): void {
                if (self::exists($directory)) {
                    $target = $directory . '-deleted-' . bin2hex(random_bytes(8));
                    if (!rename($directory, $target)) {
                        throw new RuntimeException('Impossible de retirer les fichiers du modèle.');
                    }
                    $discarded = $target;
                }
                DB::remove($template);
            });
        } catch (Throwable $error) {
            if ($discarded !== null && !rename($discarded, $directory)) {
                log::add('api', 'error', 'Impossible de remettre en place les fichiers du modèle ' . $templateId);
            }
            throw $error;
        }

        if ($discarded !== null) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($discarded, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $file) {
                if ($file->isLink() || !$file->isDir()) {
                    @unlink($file->getPathname());
                } else {
                    @rmdir($file->getPathname());
                }
            }
            if (!@rmdir($discarded)) {
                log::add('api', 'warning', 'Nettoyage incomplet des fichiers du modèle ' . $templateId);
            }
        }
    }

    private static function directory(int $templateId): string {
        return reportTemplate::dataDirectory($templateId) . '/reports';
    }

    private static function exists(string $directory): bool {
        if (is_dir($directory) && realpath($directory) === $directory) {
            return true;
        }
        if (file_exists($directory) || is_link($directory) || is_link(dirname($directory))) {
            throw new RuntimeException('Dossier des rapports invalide.');
        }
        return false;
    }

    private static function find(int $templateId, string $id): string {
        $file = self::directory($templateId) . '/' . self::filename($id);
        if (!preg_match(self::ID_PATTERN, $id) || realpath($file) !== $file || !is_file($file)) {
            throw new OutOfBoundsException('Rapport introuvable.');
        }
        return $file;
    }

    private static function metadata(string $id, int $size): array {
        $date = DateTimeImmutable::createFromFormat('!Ymd\THis\Z', substr($id, 0, 16), new DateTimeZone('UTC'));
        if ($date === false) {
            throw new RuntimeException('Date du rapport illisible.');
        }
        return ['id' => $id, 'created_at' => $date->format('Y-m-d\TH:i:s\Z'), 'size' => $size];
    }

}
