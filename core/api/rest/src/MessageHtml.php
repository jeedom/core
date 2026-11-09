<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

/**
 * Assainit le texte d'un message rendu par message::toArray() : le core y garde
 * <i> et <a> avec tous leurs attributs (onclick, href="javascript:…"). Ne restent
 * que <i> sans attribut et <a> avec un href http(s) ou relatif ; toute autre
 * balise est remplacée par son texte.
 */
final class MessageHtml
{
    public static function sanitize(string $html): string
    {
        if ($html === '') {
            return '';
        }
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8"><div>' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
        $root = $doc->getElementsByTagName('div')->item(0);
        if ($root === null) {
            return htmlspecialchars($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        self::clean($root);
        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }
        return $out;
    }

    private static function clean(\DOMNode $node): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child instanceof \DOMText) {
                continue;
            }
            if (!$child instanceof \DOMElement) {
                $node->removeChild($child);
                continue;
            }
            $tag = strtolower($child->tagName);
            if ($tag !== 'a' && $tag !== 'i') {
                $node->replaceChild($node->ownerDocument->createTextNode($child->textContent), $child);
                continue;
            }
            $href = $tag === 'a' ? $child->getAttribute('href') : '';
            foreach (iterator_to_array($child->attributes) as $attribute) {
                $child->removeAttribute($attribute->name);
            }
            if ($href !== '' && self::safeHref($href)) {
                $child->setAttribute('href', trim($href));
            }
            self::clean($child);
        }
    }

    /** http(s), ou lien relatif sans schéma (pas de « xxx: » avant le premier /, ? ou #). */
    private static function safeHref(string $href): bool
    {
        $compact = (string) preg_replace('/[\x00-\x20\x7f]+/', '', $href);
        return preg_match('#^https?://#i', $compact) === 1 || preg_match('#^[^/?\#]*:#', $compact) !== 1;
    }
}
