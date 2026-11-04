<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

/** Erreur métier traduite en réponse au format `Error` de la spec. */
final class ApiError extends \RuntimeException
{
    /** @param list<array{field: string, message: string}> $details */
    public function __construct(
        public readonly int $status,
        public readonly string $errorCode,
        string $message,
        public readonly array $details = [],
    ) {
        parent::__construct($message);
    }

    public static function field(int $status, string $code, string $field, string $message): self
    {
        return new self($status, $code, $message, [['field' => $field, 'message' => $message]]);
    }
}
