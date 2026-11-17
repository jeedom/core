<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

/** Résolution d'un fichier ordinaire à l'intérieur du dossier d'un plugin. */
final class PluginFiles
{
    /**
     * Chemin réel du fichier, null s'il n'existe pas ou sort du plugin. Sans `$followLinks`,
     * aucun lien symbolique n'est admis sous la racine du plugin ; avec, seule la cible
     * finale doit rester dans le plugin.
     */
    public static function path(string $id, string $relative, bool $followLinks = false): ?string
    {
        $root = realpath(\plugin::getPluginPath($id));
        if ($root === false) {
            return null;
        }
        $path = $root . '/' . $relative;
        $real = realpath($path);
        if ($real === false || !is_file($real)) {
            return null;
        }
        if ($followLinks) {
            return str_starts_with($real, $root . '/') ? $real : null;
        }
        return $real === $path ? $path : null;
    }
}
