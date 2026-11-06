<?php

declare(strict_types=1);

namespace Doppel\Rpg;

/**
 * Rolagem de dados na notação de RPG: "d20", "2d6+3", "1d8-1".
 * No chat da sala, uma mensagem que seja só a expressão (ou "/r expressão") vira rolagem.
 */
final class Dice
{
    private const PATTERN = '/^(?:\/r(?:oll)?\s+)?(\d{0,2})d(\d{1,3})\s*([+-]\s*\d{1,3})?$/i';
    public const MAX_DICE = 20;
    public const ALLOWED_SIDES = [2, 4, 6, 8, 10, 12, 20, 100];

    /** @return ?array{expression: string, rolls: int[], modifier: int, total: int} null se não for rolagem */
    public static function roll(string $text, ?callable $random = null): ?array
    {
        if (!preg_match(self::PATTERN, trim($text), $m)) {
            return null;
        }
        $count = $m[1] === '' ? 1 : (int) $m[1];
        $sides = (int) $m[2];
        $modifier = isset($m[3]) ? (int) str_replace(' ', '', $m[3]) : 0;
        if ($count < 1 || $count > self::MAX_DICE || !in_array($sides, self::ALLOWED_SIDES, true)) {
            return null;
        }

        $random ??= static fn (int $max): int => random_int(1, $max);
        $rolls = [];
        for ($i = 0; $i < $count; $i++) {
            $rolls[] = $random($sides);
        }
        $expression = $count . 'd' . $sides . ($modifier !== 0 ? ($modifier > 0 ? '+' : '') . $modifier : '');

        return ['expression' => $expression, 'rolls' => $rolls, 'modifier' => $modifier, 'total' => array_sum($rolls) + $modifier];
    }

    /** "rolou 2d6+3: [4, 5] + 3 = 12" */
    public static function describe(array $roll): string
    {
        $text = 'rolou ' . $roll['expression'] . ': [' . implode(', ', $roll['rolls']) . ']';
        if ($roll['modifier'] !== 0) {
            $text .= ($roll['modifier'] > 0 ? ' + ' : ' − ') . abs($roll['modifier']);
        }
        return $text . ' = ' . $roll['total'];
    }
}
