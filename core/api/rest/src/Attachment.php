<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface;

/** En-têtes d'un fichier téléchargé : nom UTF-8 (RFC 6266) avec un repli ASCII. */
final class Attachment
{
    public static function headers(ResponseInterface $response, string $name, string $contentType): ResponseInterface
    {
        $fallback = preg_replace('/[^A-Za-z0-9_.-]/', '_', $name);
        return $response
            ->withHeader('Content-Type', $contentType)
            ->withHeader('Content-Disposition', "attachment; filename=\"$fallback\"; filename*=UTF-8''" . rawurlencode($name))
            ->withHeader('X-Content-Type-Options', 'nosniff');
    }
}
