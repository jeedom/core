<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Json
{
    public static function respond(ResponseInterface $response, mixed $data, int $status = 200, int $flags = 0): ResponseInterface
    {
        $response->getBody()->write(json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION | $flags));
        return $response->withStatus($status)->withHeader('Content-Type', 'application/json; charset=utf-8');
    }

    public static function error(ResponseInterface $response, ApiError $error): ResponseInterface
    {
        $body = ['code' => $error->errorCode, 'message' => $error->getMessage()];
        if ($error->details !== []) {
            $body['details'] = $error->details;
        }
        return self::respond($response, ['error' => $body], $error->status);
    }

    /** @return array<string, mixed> */
    public static function body(ServerRequestInterface $request, ?int $maxBytes = null): array
    {
        $stream = $request->getBody();
        if ($maxBytes === null) {
            $raw = (string) $stream;
        } else {
            if ($stream->isSeekable()) {
                $stream->rewind();
            }
            $raw = '';
            while (strlen($raw) <= $maxBytes && !$stream->eof()) {
                $chunk = $stream->read(min(65536, $maxBytes + 1 - strlen($raw)));
                if ($chunk === '') {
                    break;
                }
                $raw .= $chunk;
            }
            if (strlen($raw) > $maxBytes) {
                throw new ApiError(413, 'request_too_large', 'Le corps de la requête dépasse la taille maximale.');
            }
        }
        try {
            $data = json_decode($raw === '' ? 'null' : $raw, true, 64, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw new ApiError(400, 'invalid_json', 'Le corps de la requête n\'est pas du JSON valide.');
        }
        if (!is_array($data) || array_is_list($data) && $data !== []) {
            throw new ApiError(400, 'invalid_json', 'Le corps de la requête doit être un objet JSON.');
        }
        return $data;
    }
}
