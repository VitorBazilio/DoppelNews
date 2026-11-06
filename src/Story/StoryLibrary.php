<?php

declare(strict_types=1);

namespace Doppel\Story;

/** Carrega as aventuras (arquivos JSON em /stories) e valida sua estrutura. */
final class StoryLibrary
{
    /** @var array<string, array>|null */
    private static ?array $stories = null;

    /** @return array<string, array> */
    public static function all(): array
    {
        if (self::$stories === null) {
            self::$stories = [];
            foreach (glob(ROOT . '/stories/*.json') ?: [] as $file) {
                $story = json_decode((string) file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
                $story['id'] = basename($file, '.json');
                self::$stories[$story['id']] = $story;
            }
            uasort(self::$stories, static fn (array $a, array $b) => $a['year'] <=> $b['year']);
        }
        return self::$stories;
    }

    public static function find(string $id): ?array
    {
        return self::all()[$id] ?? null;
    }

    /** @return string[] lista de problemas encontrados (vazia se a aventura está íntegra) */
    public static function validate(array $story): array
    {
        $errors = [];
        $nodes = $story['nodes'] ?? [];
        foreach (['start', 'deathNode'] as $key) {
            if (!isset($nodes[$story[$key] ?? ''])) {
                $errors[] = "'$key' aponta para um nó inexistente";
            }
        }
        $reachable = [];
        foreach ($nodes as $id => $node) {
            $isEnding = isset($node['ending']);
            if ($isEnding && !empty($node['choices'])) {
                $errors[] = "$id: nó de final não deve ter escolhas";
            }
            if (!$isEnding && empty($node['choices'])) {
                $errors[] = "$id: nó sem escolhas e sem final (beco sem saída)";
            }
            foreach ($node['choices'] ?? [] as $i => $choice) {
                foreach (array_filter([$choice['to'] ?? null, $choice['check']['fail'] ?? null]) as $target) {
                    $reachable[$target] = true;
                    if (!isset($nodes[$target])) {
                        $errors[] = "$id/escolha $i: destino '$target' não existe";
                    }
                }
            }
        }
        $reachable[$story['start'] ?? ''] = true;
        $reachable[$story['deathNode'] ?? ''] = true;
        foreach (array_diff(array_keys($nodes), array_keys($reachable)) as $orphan) {
            $errors[] = "$orphan: nó inalcançável";
        }
        return $errors;
    }
}
