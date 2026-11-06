<?php

declare(strict_types=1);

namespace Doppel\Story;

/**
 * Motor do livro-jogo. É puro: recebe a aventura e o estado, devolve o novo estado.
 * Toda regra (requisitos, testes de dado, efeitos, morte) é aplicada aqui no servidor.
 */
final class Gamebook
{
    public static function newState(array $story): array
    {
        $stats = [];
        foreach ($story['stats'] as $key => $def) {
            $stats[$key] = $def['start'];
        }
        $state = ['node' => $story['start'], 'stats' => $stats, 'items' => [], 'steps' => 0, 'notice' => null];
        return self::enter($story, $state, $story['start']);
    }

    public static function node(array $story, array $state): array
    {
        return $story['nodes'][$state['node']];
    }

    /** Escolhas do nó atual, já marcadas como disponíveis ou bloqueadas (com o motivo). */
    public static function choices(array $story, array $state): array
    {
        $result = [];
        foreach (self::node($story, $state)['choices'] ?? [] as $index => $choice) {
            $reason = self::unmetRequirement($story, $state, $choice['requires'] ?? []);
            $result[] = [
                'index' => $index,
                'text' => $choice['text'],
                'available' => $reason === null,
                'reason' => $reason,
                'risky' => isset($choice['check']),
            ];
        }
        return $result;
    }

    public static function ending(array $story, array $state): ?string
    {
        return self::node($story, $state)['ending'] ?? null;
    }

    /** Aplica a escolha $index e devolve o novo estado, ou null se a escolha for inválida. */
    public static function choose(array $story, array $state, int $index): ?array
    {
        $choices = self::choices($story, $state);
        if (!isset($choices[$index]) || !$choices[$index]['available'] || self::ending($story, $state) !== null) {
            return null;
        }
        $choice = self::node($story, $state)['choices'][$index];
        $state['notice'] = null;
        $state['steps']++;
        $target = $choice['to'];
        $effects = $choice['effects'] ?? [];

        if (isset($choice['check'])) {
            [$success, $state['notice']] = self::rollCheck($story, $state, $choice['check']);
            if (!$success) {
                $target = $choice['check']['fail'];
                $effects = $choice['check']['failEffects'] ?? [];
            }
        }
        $state = self::applyEffects($story, $state, $effects);

        return self::enter($story, $state, $target);
    }

    private static function enter(array $story, array $state, string $nodeId): array
    {
        $state['node'] = $nodeId;
        $state = self::applyEffects($story, $state, $story['nodes'][$nodeId]['effects'] ?? []);

        foreach ($story['stats'] as $key => $def) {
            if (!empty($def['lethal']) && $state['stats'][$key] <= 0 && $nodeId !== $story['deathNode']) {
                $state['node'] = $story['deathNode'];
                break;
            }
        }
        return $state;
    }

    private static function rollCheck(array $story, array $state, array $check): array
    {
        $dice = [random_int(1, 6), random_int(1, 6)];
        $bonus = $state['stats'][$check['stat']];
        $total = array_sum($dice) + $bonus;
        $success = $total >= $check['target'];
        $label = $story['stats'][$check['stat']]['label'];

        $notice = sprintf(
            'Teste de %s: 2d6 (%d + %d) + %d = %d contra %d — %s.',
            $label, $dice[0], $dice[1], $bonus, $total, $check['target'], $success ? 'sucesso' : 'falha'
        );
        return [$success, $notice];
    }

    private static function applyEffects(array $story, array $state, array $effects): array
    {
        foreach ($effects['stats'] ?? [] as $key => $delta) {
            $max = $story['stats'][$key]['max'] ?? PHP_INT_MAX;
            $state['stats'][$key] = max(0, min($max, $state['stats'][$key] + $delta));
        }
        foreach ($effects['items'] ?? [] as $item) {
            if (!in_array($item, $state['items'], true)) {
                $state['items'][] = $item;
            }
        }
        if (!empty($effects['remove'])) {
            $state['items'] = array_values(array_diff($state['items'], $effects['remove']));
        }
        return $state;
    }

    private static function unmetRequirement(array $story, array $state, array $requires): ?string
    {
        foreach ($requires['stats'] ?? [] as $key => $min) {
            if ($state['stats'][$key] < $min) {
                return sprintf('Requer %s %d', $story['stats'][$key]['label'], $min);
            }
        }
        foreach ($requires['items'] ?? [] as $item) {
            if (!in_array($item, $state['items'], true)) {
                return "Requer: $item";
            }
        }
        return null;
    }
}
