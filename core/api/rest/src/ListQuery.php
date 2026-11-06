<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Recherche libre (`q`) et découpage (`limit`, `offset`) des listes de l'API. Le filtrage
 * se fait en PHP, après le contrôle des droits par élément (décision : droits vérifiés
 * dans les routes), pour que `total` n'inclue jamais un élément non lisible.
 */
final class ListQuery
{
    private const MAX_LIMIT = 500;

    /** @param list<string> $words */
    private function __construct(private readonly array $words, private readonly ?int $limit, private readonly int $offset)
    {
    }

    public static function from(ServerRequestInterface $request): self
    {
        $query = $request->getQueryParams();
        $q = $query['q'] ?? '';
        if (!is_string($q) || mb_strlen($q) > 200) {
            throw ApiError::field(422, 'validation_error', 'q', 'Un texte de 200 caractères au plus est attendu.');
        }
        $limit = null;
        if (isset($query['limit'])) {
            if (!ctype_digit((string) $query['limit']) || (int) $query['limit'] < 1 || (int) $query['limit'] > self::MAX_LIMIT) {
                throw ApiError::field(422, 'validation_error', 'limit', 'Un entier de 1 à ' . self::MAX_LIMIT . ' est attendu.');
            }
            $limit = (int) $query['limit'];
        }
        $offset = $query['offset'] ?? '0';
        if (!ctype_digit((string) $offset)) {
            throw ApiError::field(422, 'validation_error', 'offset', 'Un entier positif ou nul est attendu.');
        }
        $words = array_values(array_filter(preg_split('/\s+/u', self::fold($q)) ?: [], static fn ($word) => $word !== ''));
        return new self($words, $limit, (int) $offset);
    }

    /** Vrai si chaque mot de `q` se trouve dans au moins un des champs. */
    public function matches(array $fields): bool
    {
        if ($this->words === []) {
            return true;
        }
        $haystack = array_map(static fn ($field) => self::fold((string) $field), $fields);
        foreach ($this->words as $word) {
            $found = false;
            foreach ($haystack as $field) {
                if (str_contains($field, $word)) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                return false;
            }
        }
        return true;
    }

    /** @return array{items: list<mixed>, total: int} */
    public function page(array $items): array
    {
        $items = array_values($items);
        return ['items' => array_slice($items, $this->offset, $this->limit), 'total' => count($items)];
    }

    /** Minuscules sans accents, pour comparer « Écran » et « ecran ». */
    private static function fold(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        if (class_exists(\Normalizer::class)) {
            $text = (string) preg_replace('/\p{Mn}+/u', '', (string) \Normalizer::normalize($text, \Normalizer::FORM_D));
        } else {
            $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
            $text = $ascii !== false ? $ascii : $text;
        }
        return $text;
    }
}
