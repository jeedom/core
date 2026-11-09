<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\StreamInterface;

/**
 * Corps de la réponse SSE de GET /events. Slim le lit morceau par morceau et envoie
 * chaque morceau au client ; la lecture attend les nouveaux événements en relisant la
 * table `event` toutes les secondes, jusqu'à la durée maximale du flux.
 *
 * Format : `id` = curseur (`datetime` de l'événement), `event` = type, `data` = JSON
 * `{type, data}`. Un commentaire `: ping` part toutes les 15 s sans événement.
 */
final class EventStream implements StreamInterface
{
    public const MAX_DURATION = 55;
    private const POLL_INTERVAL_US = 1_000_000;
    private const PING_INTERVAL = 15;
    private const RETRY_MS = 1000;

    private string $buffer;
    private float $deadline;
    private float $lastSent;
    private bool $finished = false;

    public function __construct(
        private readonly EventFilter $filter,
        private ?float $cursor,
        int $maxDuration,
        private ?string $slot,
        private readonly ?\user $slotUser = null,
    ) {
        $now = microtime(true);
        $this->deadline = $now + $maxDuration;
        $this->lastSent = $now;
        $this->buffer = 'retry: ' . self::RETRY_MS . "\n\n";
        if ($this->cursor === null || $this->cursor > $now) {
            // Sans curseur, le flux commence à l'instant de la connexion.
            $this->cursor = $now;
        } elseif ($this->isTooOld($this->cursor)) {
            $this->buffer .= self::message(null, 'resync', ['reason' => 'cursor_too_old']);
        }
        $this->buffer .= $this->checkpoint();
        if (PHP_SAPI !== 'cli') {
            // Chaque morceau part tout de suite chez le client (pas de tampon PHP).
            while (ob_get_level() > 0) {
                ob_end_flush();
            }
            ob_implicit_flush(true);
            set_time_limit($maxDuration + 30);
        }
    }

    public function read(int $length): string
    {
        while ($this->buffer === '' && !$this->finished) {
            $this->fill();
        }
        $chunk = substr($this->buffer, 0, $length);
        $this->buffer = (string) substr($this->buffer, strlen($chunk));
        if ($this->buffer === '' && $this->finished) {
            $this->close();
        }
        return $chunk;
    }

    public function eof(): bool
    {
        return $this->finished && $this->buffer === '';
    }

    /** Un tour d'attente : nouveaux événements, ping, ou fin du flux. */
    private function fill(): void
    {
        if (microtime(true) >= $this->deadline || connection_aborted()) {
            $this->finished = true;
            return;
        }
        $this->pollEvents();
        $now = microtime(true);
        if ($this->buffer !== '') {
            $this->lastSent = $now;
            return;
        }
        if ($now - $this->lastSent >= self::PING_INTERVAL) {
            $this->buffer = ": ping\n" . $this->checkpoint();
            $this->lastSent = $now;
            return;
        }
        usleep((int) min(self::POLL_INTERVAL_US, max(0, ($this->deadline - $now) * 1_000_000)));
    }

    private function pollEvents(): void
    {
        $rows = \DB::Prepare(
            'SELECT `datetime`, `name`, `option` FROM `event` WHERE `datetime` > :datetime ORDER BY `datetime`',
            ['datetime' => self::formatCursor($this->cursor)],
            \DB::FETCH_TYPE_ALL
        );
        foreach ((array) $rows as $row) {
            $this->cursor = (float) $row['datetime'];
            $data = self::decode($row['option']);
            if ($this->filter->accepts((string) $row['name'], $data)) {
                $this->buffer .= self::message(self::formatCursor($this->cursor), (string) $row['name'], $data);
            }
        }
    }

    /** Vrai si des événements postérieurs au curseur ont pu être supprimés de la table. */
    private function isTooOld(float $cursor): bool
    {
        $row = \DB::Prepare('SELECT MIN(`datetime`) AS oldest FROM `event`', [], \DB::FETCH_TYPE_ROW);
        return isset($row['oldest']) && $row['oldest'] !== null && (float) $row['oldest'] > $cursor;
    }

    private static function decode(mixed $option): mixed
    {
        if ($option === null || $option === '') {
            return null;
        }
        try {
            $data = json_decode((string) $option, true, 64, JSON_THROW_ON_ERROR);
            // event::add sans option enregistre `[]` : la spec annonce null pour un événement sans contenu.
            return $data === [] ? null : $data;
        } catch (\JsonException) {
            return (string) $option;
        }
    }

    /** Met à jour le curseur du client, y compris quand le flux est silencieux. */
    private function checkpoint(): string
    {
        return 'id: ' . self::formatCursor($this->cursor) . "\n\n";
    }

    private static function formatCursor(float $cursor): string
    {
        return sprintf('%.6F', $cursor);
    }

    private static function message(?string $id, string $type, mixed $data): string
    {
        $json = json_encode(['type' => $type, 'data' => $data], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);
        return ($id !== null ? 'id: ' . $id . "\n" : '') . 'event: ' . $type . "\n" . 'data: ' . $json . "\n\n";
    }

    public function close(): void
    {
        $this->finished = true;
        if ($this->slot !== null && $this->slotUser !== null) {
            StreamSlots::release($this->slotUser, $this->slot);
        }
        $this->slot = null;
    }

    public function __destruct()
    {
        $this->close();
    }

    public function __toString(): string
    {
        $content = '';
        while (!$this->eof()) {
            $content .= $this->read(8192);
        }
        return $content;
    }

    public function detach()
    {
        $this->close();
        return null;
    }

    public function getSize(): ?int
    {
        return null;
    }

    public function tell(): int
    {
        throw new \RuntimeException('Flux non positionnable.');
    }

    public function isSeekable(): bool
    {
        return false;
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        throw new \RuntimeException('Flux non positionnable.');
    }

    public function rewind(): void
    {
        throw new \RuntimeException('Flux non positionnable.');
    }

    public function isWritable(): bool
    {
        return false;
    }

    public function write(string $string): int
    {
        throw new \RuntimeException('Flux en lecture seule.');
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function getContents(): string
    {
        return $this->__toString();
    }

    public function getMetadata(?string $key = null)
    {
        return $key === null ? [] : null;
    }
}
