<?php

declare(strict_types=1);

namespace Doppel\Rpg;

/**
 * Regras de criação de personagem de D&D 5ª edição (nível 1):
 * compra de pontos, modificadores, perícias e pontos de vida.
 */
final class CharacterRules
{
    public const BASE = 8;
    public const MAX_BUY = 15;
    public const BUDGET = 27;
    /** Custo acumulado de cada valor na compra de pontos (Livro do Jogador). */
    public const COSTS = [8 => 0, 9 => 1, 10 => 2, 11 => 3, 12 => 4, 13 => 5, 14 => 7, 15 => 9];

    /** Perícias e o atributo de cada uma. */
    public const SKILLS = [
        'Acrobacia' => 'des', 'Arcanismo' => 'int', 'Atletismo' => 'for', 'Atuação' => 'car',
        'Enganação' => 'car', 'Furtividade' => 'des', 'História' => 'int', 'Intimidação' => 'car',
        'Intuição' => 'sab', 'Investigação' => 'int', 'Lidar com Animais' => 'sab', 'Medicina' => 'sab',
        'Natureza' => 'int', 'Percepção' => 'sab', 'Persuasão' => 'car', 'Prestidigitação' => 'des',
        'Religião' => 'int', 'Sobrevivência' => 'sab',
    ];

    public static function modifier(int $score): int
    {
        return (int) floor(($score - 10) / 2);
    }

    /**
     * Valida a compra de pontos.
     * @param array<string,int> $base valores comprados (antes dos bônus da espécie)
     * @return ?string mensagem de erro, ou null se estiver tudo certo
     */
    public static function validatePointBuy(array $base): ?string
    {
        $spent = 0;
        foreach (array_keys(ATTRIBUTES) as $attr) {
            $value = $base[$attr] ?? null;
            if (!is_int($value) || !isset(self::COSTS[$value])) {
                return 'Cada atributo deve ficar entre ' . self::BASE . ' e ' . self::MAX_BUY . ' antes dos bônus da espécie.';
            }
            $spent += self::COSTS[$value];
        }
        return $spent > self::BUDGET ? "Você gastou $spent pontos, mas o limite é " . self::BUDGET . '.' : null;
    }

    public static function pointsSpent(array $base): int
    {
        return array_sum(array_map(static fn (int $v): int => self::COSTS[$v] ?? 0, $base));
    }

    /** Soma os bônus da espécie aos valores comprados. */
    public static function applyBonuses(array $base, array $bonuses): array
    {
        $final = [];
        foreach (array_keys(ATTRIBUTES) as $attr) {
            $final[$attr] = $base[$attr] + (int) ($bonuses[$attr] ?? 0);
        }
        return $final;
    }

    /** No nível 1: valor máximo do dado de vida + modificador de Constituição (mínimo 1). */
    public static function hitPoints(int $hitDie, int $constitution): int
    {
        return max(1, $hitDie + self::modifier($constitution));
    }

    /** Bônus das perícias de uma ficha, a partir dos atributos finais. */
    public static function skills(array $attributes): array
    {
        $skills = [];
        foreach (self::SKILLS as $name => $attr) {
            $skills[$name] = ['attr' => $attr, 'bonus' => self::modifier($attributes[$attr])];
        }
        return $skills;
    }
}
