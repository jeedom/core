<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\Node\BinaryNode;
use Symfony\Component\ExpressionLanguage\Node\ConstantNode;
use Symfony\Component\ExpressionLanguage\Node\NameNode;
use Symfony\Component\ExpressionLanguage\Node\Node;
use Symfony\Component\ExpressionLanguage\Node\UnaryNode;
use Symfony\Component\ExpressionLanguage\SyntaxError;

final class DiagnosticExpression
{
    public static function test(string $source): array
    {
        $bindings = [];
        $names = [];
        // Les littéraux entre guillemets sont ignorés. Les valeurs sont liées au moteur
        // comme paramètres typés : le contenu d’une commande n’est jamais interprété.
        $pattern = <<<'REGEX'
~"(?:\\.|[^"\\])*"|'(?:\\.|[^'\\])*'|(?<function>\b(?:average|min|max|stateDuration|eqEnable|variable))\s*\((?<args>(?:#(?:\[[^\]\r\n]*\]){2,3}#|#[\w]+#|[^()#]++)*)\)|(?<reference>#[\w]+#|#(?:\[[^\]\r\n]*\]){2,3}#)|(?<equal>(?<![=!<>])=(?!=))|(?<logic>\b(?:et|ou)\b)~iu
REGEX;
        $normalized = preg_replace_callback($pattern, static function (array $match) use (&$bindings, &$names): string {
            if (($match['equal'] ?? '') === '=') {
                return '==';
            }
            if (($match['logic'] ?? '') !== '') {
                return strtolower($match['logic']) === 'et' ? '&&' : '||';
            }
            $function = $match['function'] ?? '';
            $reference = $match['reference'] ?? '';
            if ($function === '' && $reference === '') {
                return $match[0];
            }
            return $names[$match[0]] ??= self::bind($bindings, $function, $function !== '' ? $match['args'] : $reference);
        }, $source);

        $language = new ExpressionLanguage();
        try {
            $parsed = $language->parse($normalized ?? '', array_keys($bindings));
        } catch (SyntaxError) {
            throw self::invalid('Expression invalide ou fonction non prise en charge.');
        }
        self::validate($parsed->getNodes());
        $values = [];
        $details = [];
        foreach ($bindings as $name => [$label, $read]) {
            $value = $read();
            $values[$name] = self::scalar($value);
            $details[] = ['label' => $label, 'value' => $values[$name]];
        }
        try {
            $result = $language->evaluate($parsed, $values);
        } catch (\RuntimeException | \ArithmeticError | \TypeError | \ValueError) {
            throw self::invalid('Le calcul de cette expression a échoué.');
        }
        return [
            'expression' => \cmd::cmdToHumanReadable($source),
            'result' => self::scalar($result),
            'values' => $details,
        ];
    }

    private static function bind(array &$bindings, string $function, string $input): string
    {
        if (count($bindings) >= 32) {
            throw self::invalid('Une expression peut référencer au maximum 32 valeurs.');
        }
        $name = 'value' . count($bindings);
        $label = $input;
        if ($function !== '') {
            [$label, $read] = self::functionValue($function, trim($input));
        } else {
            $reference = \cmd::humanReadableToCmd($input);
            if (preg_match('/^#\d+#$/D', $reference)) {
                $cmd = self::infoCommand($reference);
                $label = $cmd->getHumanName(true);
                $read = static function () use ($cmd): mixed {
                    $value = $cmd->getCache('value', null);
                    if ($value === null) {
                        throw self::invalid('Aucune valeur disponible pour ' . $cmd->getHumanName(true) . '.');
                    }
                    return in_array($cmd->getSubType(), ['numeric', 'binary'], true) && is_numeric($value) ? $value + 0 : $value;
                };
            } else {
                $tags = \scenarioExpression::getRequestTags($input);
                if (!array_key_exists($input, $tags)) {
                    throw self::invalid('Commande ou tag introuvable : ' . $input);
                }
                $read = static fn () => $tags[$input];
            }
        }
        $bindings[$name] = [$label, $read];
        return $name;
    }

    private static function functionValue(string $function, string $input): array
    {
        $label = $function . '(' . \cmd::cmdToHumanReadable(\eqLogic::toHumanReadable($input)) . ')';
        switch (strtolower($function)) {
            case 'variable':
                $key = trim($input, "\"' ");
                if (!preg_match('/^[\p{L}\p{N}_ .-]+$/uD', $key)) {
                    throw self::invalid('Nom de variable invalide.');
                }
                return [$label, static fn () => \scenarioExpression::variable($key)];
            case 'eqenable':
                $reference = \eqLogic::fromHumanReadable($input);
                if (!preg_match('/^(?:#eqLogic([1-9]\d*)#|([1-9]\d*))$/D', $reference, $match)) {
                    throw self::invalid('Équipement attendu dans eqEnable.');
                }
                $equipment = \eqLogic::byId($match[2] ?? $match[1]);
                if (!is_object($equipment)) {
                    throw self::invalid('Équipement introuvable.');
                }
                return [$label, static fn () => (int) $equipment->getIsEnable()];
            case 'stateduration':
                $cmd = self::infoCommand(\cmd::humanReadableToCmd($input));
                return [$label, static fn () => \scenarioExpression::stateDuration('#' . $cmd->getId() . '#')];
        }
        // La période du constructeur graphique est un littéral, pas une expression.
        if (!preg_match('/^(#\d+#|#(?:\[[^\]\r\n]*\]){3}#)(?:\s*,\s*([\w ]+))?$/uD', $input, $match)) {
            throw self::invalid('Une commande et une période sont attendues pour ' . $function . '.');
        }
        $cmd = self::infoCommand(\cmd::humanReadableToCmd($match[1]));
        $period = strtolower(trim($match[2] ?? '1 hour'));
        if (!preg_match('/^(?:([1-9]\d{0,2}) )?(min|hour|day|week|month)s?$/D', $period, $interval)
            || (int) ($interval[1] ?: 1) * ['min' => 1, 'hour' => 60, 'day' => 1440, 'week' => 10080, 'month' => 44640][$interval[2]] > 527040) {
            throw self::invalid('La période doit être comprise entre une minute et un an.');
        }
        $period = ($interval[1] ?: '1') . ' ' . $interval[2];
        $reference = '#' . $cmd->getId() . '#';
        $read = match (strtolower($function)) {
            'average' => static fn () => \scenarioExpression::average($reference, $period),
            'min' => static fn () => \scenarioExpression::min($reference, $period),
            'max' => static fn () => \scenarioExpression::max($reference, $period),
        };
        return [$label, $read];
    }

    private static function infoCommand(string $reference): \cmd
    {
        $cmd = preg_match('/^#([1-9]\d*)#$/D', $reference, $match) ? \cmd::byId($match[1]) : null;
        if (!is_object($cmd)) {
            throw self::invalid('Commande introuvable : ' . $reference);
        }
        if ($cmd->getType() !== 'info') {
            throw self::invalid('Seules les commandes info peuvent être testées.');
        }
        return $cmd;
    }

    private static function validate(Node $node, int $depth = 0): void
    {
        $binary = ['+', '-', '*', '/', '%', '**', '~', '==', '===', '!=', '!==', '<', '<=', '>', '>=', 'and', 'or', '&&', '||', 'matches'];
        if ($depth > 64 || !($node instanceof ConstantNode || $node instanceof NameNode || $node instanceof UnaryNode
            || ($node instanceof BinaryNode && in_array($node->attributes['operator'], $binary, true)))) {
            throw self::invalid('Opération non prise en charge par le testeur.');
        }
        foreach ($node->nodes as $child) {
            self::validate($child, $depth + 1);
        }
    }

    private static function scalar(mixed $value): string|int|float|bool|null
    {
        return is_scalar($value) && (!is_float($value) || is_finite($value)) ? $value : null;
    }

    private static function invalid(string $message): ApiError
    {
        return ApiError::field(422, 'validation_error', 'expression', $message);
    }
}
