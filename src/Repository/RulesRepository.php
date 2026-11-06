<?php

declare(strict_types=1);

namespace Doppel\Repository;

use Doppel\Support\Database;

/** Regras de D&D cadastradas no site: classes, espécies, armas e magias. */
final class RulesRepository
{
    /** tipo => [título, nome no singular, colunas gravadas, coluna de characters que usa o registro] */
    public const TYPES = [
        'classes' => ['Classes', 'classe', ['name', 'hit_die', 'primary_attr', 'secondary_attr', 'spell_slots', 'simple_weapons', 'martial_weapons', 'light_armor', 'medium_armor', 'heavy_armor', 'description'], 'class_id'],
        'especies' => ['Espécies', 'espécie', ['name', 'bonuses', 'description'], 'species_id'],
        'armas' => ['Armas', 'arma', ['name', 'damage', 'category', 'price', 'weight', 'properties'], 'weapon_id'],
        'magias' => ['Magias', 'magia', ['name', 'level', 'school', 'damage', 'description'], null],
    ];
    private const TABLES = ['classes' => 'classes', 'especies' => 'species', 'armas' => 'weapons', 'magias' => 'spells'];
    private const ORDER = ['classes' => 'name', 'especies' => 'name', 'armas' => 'category DESC, name', 'magias' => 'level, name'];

    public function all(string $type): array
    {
        $rows = Database::pdo()->query('SELECT * FROM ' . self::TABLES[$type] . ' ORDER BY ' . self::ORDER[$type])->fetchAll();
        return $type === 'especies' ? array_map(self::decodeSpecies(...), $rows) : $rows;
    }

    public function find(string $type, int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM ' . self::TABLES[$type] . ' WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch() ?: null;
        return $row !== null && $type === 'especies' ? self::decodeSpecies($row) : $row;
    }

    public function nameExists(string $type, string $name): bool
    {
        $stmt = Database::pdo()->prepare('SELECT 1 FROM ' . self::TABLES[$type] . ' WHERE LOWER(name) = LOWER(?)');
        $stmt->execute([$name]);
        return (bool) $stmt->fetchColumn();
    }

    public function create(string $type, array $data): void
    {
        $columns = self::TYPES[$type][2];
        $values = array_map(static fn (string $c) => $data[$c], $columns);
        Database::pdo()->prepare(
            'INSERT INTO ' . self::TABLES[$type] . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', array_fill(0, count($columns), '?')) . ')'
        )->execute($values);
    }

    /** Quantos personagens usam o registro (não dá para apagar regras em uso). */
    public function usage(string $type, int $id): int
    {
        $column = self::TYPES[$type][3];
        if ($column === null) {
            return 0;
        }
        $stmt = Database::pdo()->prepare("SELECT COUNT(*) FROM characters WHERE $column = ?");
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn();
    }

    public function delete(string $type, int $id): void
    {
        Database::pdo()->prepare('DELETE FROM ' . self::TABLES[$type] . ' WHERE id = ?')->execute([$id]);
    }

    private static function decodeSpecies(array $row): array
    {
        $row['bonuses'] = json_decode((string) $row['bonuses'], true) ?: [];
        return $row;
    }
}
