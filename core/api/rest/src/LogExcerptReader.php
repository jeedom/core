<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/** Lecture bornée d'un journal ouvert, avec positions en octets stables lors des ajouts. */
final class LogExcerptReader
{
    private const BLOCK_BYTES = 16384;
    private const MAX_LINE_BYTES = 65536;
    private const MAX_PAGE_BYTES = 1048576;
    private const CURSOR_SAMPLE_BYTES = 128;

    /** @param resource $file */
    public function __construct(private $file) {}

    public static function respond(Request $request, Response $response, string $path, bool $allowMissing = false): Response
    {
        $query = $request->getQueryParams();
        $limit = $query['limit'] ?? '300';
        if ((!is_string($limit) && !is_int($limit)) || !ctype_digit((string) $limit) || (int) $limit < 1 || (int) $limit > 1000) {
            throw ApiError::field(422, 'validation_error', 'limit', 'Un entier de 1 à 1000 est attendu.');
        }
        if (array_key_exists('before', $query) && array_key_exists('after', $query)) {
            throw ApiError::field(422, 'validation_error', 'before', 'Utiliser un seul curseur à la fois.');
        }
        $direction = array_key_exists('before', $query) ? 'before' : 'after';
        $cursor = $query[$direction] ?? null;
        if ($cursor !== null && (!is_string($cursor) || preg_match('/\A(?:0|[1-9][0-9]{0,18})\.[a-f0-9]{64}\z/D', $cursor) !== 1)) {
            throw ApiError::field(422, 'validation_error', $direction, 'Curseur de journal invalide.');
        }
        $response = $response->withHeader('Cache-Control', 'private, no-store');
        $file = @fopen($path, 'rb');
        if ($file !== false) {
            try {
                return Json::respond($response, (new self($file))->read((int) $limit, $direction, $cursor));
            } finally {
                fclose($file);
            }
        }
        if ($allowMissing && !is_file($path)) {
            // Le journal d'un scénario peut n'exister qu'après son premier lancement.
            $emptyCursor = '0.' . str_repeat('0', 64);
            return Json::respond($response, [
                'lines' => [], 'fragment' => null, 'previousCursor' => null, 'nextCursor' => $emptyCursor,
                'hasMore' => false, 'reset' => $cursor !== null && $cursor !== $emptyCursor,
            ]);
        }
        throw new ApiError(403, 'log_not_readable', 'Le journal ne peut pas être lu.');
    }

    public function read(int $limit, string $direction, ?string $cursor): array
    {
        $stat = $this->stat();
        $size = $stat['size'];
        $offset = $cursor === null ? $size : (int) strstr($cursor, '.', true);
        $reset = $cursor !== null && ($offset > $size || $cursor !== $this->cursor($stat, $offset));

        if ($cursor !== null && !$reset && $direction === 'after') {
            $page = $this->after($offset, $size, $limit);
        } else {
            $page = $this->before($reset ? $size : $offset, $limit);
        }

        if ($this->stat()['size'] < $size
            || ($cursor !== null && !$reset && $cursor !== $this->cursor($stat, $offset))) {
            throw self::changed();
        }

        return [
            'lines' => array_map(self::decode(...), $page['lines']),
            'fragment' => $page['fragment'] === null ? null : self::decode($page['fragment']),
            'previousCursor' => $page['start'] > 0 ? $this->cursor($stat, $page['start']) : null,
            'nextCursor' => $this->cursor($stat, $page['next']),
            'hasMore' => $page['end'] < $size,
            'reset' => $reset,
        ];
    }

    private function before(int $end, int $limit): array
    {
        if ($end === 0) {
            return ['start' => 0, 'next' => 0, 'end' => 0, 'lines' => [], 'fragment' => null];
        }

        $start = $end;
        $blocks = [];
        $breaks = 0;
        $floor = max(0, $end - self::MAX_PAGE_BYTES - self::MAX_LINE_BYTES - 1);
        while ($start > $floor && $breaks <= $limit) {
            $length = min(self::BLOCK_BYTES, $start - $floor);
            $start -= $length;
            $block = $this->bytesAt($start, $length);
            $blocks[] = $block;
            $breaks += substr_count($block, "\n");
        }
        $parts = explode("\n", implode('', array_reverse($blocks)));
        $last = array_pop($parts);
        $fragment = $last === '' ? null : $last;
        $bytes = strlen($last);
        self::checkLineLength($bytes);
        if ($start > 0 && $this->bytesAt($start - 1, 1) !== "\n") {
            // Le premier bloc peut commencer au milieu d'une ligne située avant cet extrait.
            $cut = array_shift($parts);
            if (!$parts && $fragment === null && $cut !== null && strlen($cut) >= self::MAX_LINE_BYTES) {
                self::checkLineLength(strlen($cut) + 1);
            }
        }

        $lines = [];
        $lineLimit = $fragment === null ? $limit : $limit - 1;
        for ($index = count($parts) - 1; $index >= 0 && count($lines) < $lineLimit; $index--) {
            $line = $parts[$index];
            $length = strlen($line) + 1;
            if ($bytes + $length > self::MAX_PAGE_BYTES) {
                break;
            }
            self::checkLineLength($length);
            $lines[] = $line;
            $bytes += $length;
        }
        return [
            'start' => $end - $bytes,
            'next' => $end - strlen($last),
            'end' => $end,
            'lines' => array_reverse($lines),
            'fragment' => $fragment,
        ];
    }

    private function after(int $start, int $size, int $limit): array
    {
        $position = $start;
        $lines = [];
        $fragment = null;
        if (fseek($this->file, $start) !== 0) {
            throw self::changed();
        }
        while ($position < $size && count($lines) < $limit) {
            $line = fgets($this->file, min(self::MAX_LINE_BYTES + 1, $size - $position) + 1);
            if ($line === false) {
                throw self::changed();
            }
            $length = strlen($line);
            if ($position - $start + $length > self::MAX_PAGE_BYTES) {
                break;
            }
            self::checkLineLength($length);
            if (!str_ends_with($line, "\n")) {
                // Ne pas avancer le curseur tant qu'une écriture n'a pas terminé cette ligne.
                $fragment = $line;
                break;
            }
            $lines[] = substr($line, 0, -1);
            $position += $length;
        }
        return [
            'start' => $start,
            'next' => $position,
            'end' => $position + strlen($fragment ?? ''),
            'lines' => $lines,
            'fragment' => $fragment,
        ];
    }

    private function cursor(array $stat, int $offset): string
    {
        $length = min(self::CURSOR_SAMPLE_BYTES, $offset);
        // chunkLog() réécrit le fichier en place. Ces empreintes restent stables lors d'un ajout,
        // y compris lorsque le fichier était plus court que la taille d'un échantillon.
        $head = $this->bytesAt(0, $length);
        $anchor = $offset <= $length ? $head : $this->bytesAt($offset - $length, $length);
        return $offset . '.' . hash('sha256', serialize([$stat['dev'], $stat['ino'], $offset, $head, $anchor]));
    }

    private function bytesAt(int $start, int $length): string
    {
        if ($length === 0) {
            return '';
        }
        $bytes = fseek($this->file, $start) === 0 ? stream_get_contents($this->file, $length) : false;
        if ($bytes === false || strlen($bytes) !== $length) {
            throw self::changed();
        }
        return $bytes;
    }

    private function stat(): array
    {
        $stat = fstat($this->file);
        if ($stat === false) {
            throw self::changed();
        }
        return $stat;
    }

    private static function decode(string $line): string
    {
        $line = str_ends_with($line, "\r") ? substr($line, 0, -1) : $line;
        return mb_convert_encoding($line, 'UTF-8', 'UTF-8');
    }

    private static function checkLineLength(int $length): void
    {
        if ($length > self::MAX_LINE_BYTES) {
            throw new ApiError(413, 'log_line_too_long', 'Une ligne du journal dépasse 64 Kio.');
        }
    }

    private static function changed(): ApiError
    {
        return new ApiError(409, 'log_changed', 'Le journal a changé pendant sa lecture. Réessayer.');
    }
}
