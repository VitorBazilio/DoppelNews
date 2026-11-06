<?php

declare(strict_types=1);

namespace Doppel\Support;

use PDO;

/** Cria o esquema do banco e popula notícias e regras de exemplo (só em tabelas vazias). */
final class Installer
{
    public static function run(PDO $pdo): void
    {
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $schema = file_get_contents(ROOT . "/database/schema.$driver.sql");
        foreach (array_filter(array_map('trim', explode(';', $schema))) as $statement) {
            $pdo->exec($statement);
        }

        if (self::isEmpty($pdo, 'news')) {
            self::seedNews($pdo);
        }
        if (self::isEmpty($pdo, 'classes')) {
            self::seedRules($pdo);
        }
    }

    private static function isEmpty(PDO $pdo, string $table): bool
    {
        return (int) $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn() === 0;
    }

    private static function seedNews(PDO $pdo): void
    {
        $insert = $pdo->prepare(
            'INSERT INTO news (title, summary, body, category, image, author, featured, published_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        foreach (require ROOT . '/database/seed/news.php' as $row) {
            $insert->execute([
                $row['title'], $row['summary'], $row['body'], $row['category'], $row['image'], $row['author'],
                $row['featured'] ? 1 : 0, date('Y-m-d H:i:s', strtotime('-' . $row['hours_ago'] . ' hours')),
            ]);
        }
    }

    private static function seedRules(PDO $pdo): void
    {
        $rules = require ROOT . '/database/seed/rules.php';

        $class = $pdo->prepare(
            'INSERT INTO classes (name, hit_die, primary_attr, secondary_attr, spell_slots, simple_weapons,
             martial_weapons, light_armor, medium_armor, heavy_armor, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        foreach ($rules['classes'] as $row) {
            $class->execute($row);
        }

        $species = $pdo->prepare('INSERT INTO species (name, bonuses, description) VALUES (?, ?, ?)');
        foreach ($rules['species'] as [$name, $bonuses, $description]) {
            $species->execute([$name, json_encode($bonuses), $description]);
        }

        $weapon = $pdo->prepare('INSERT INTO weapons (name, damage, category, price, weight, properties) VALUES (?, ?, ?, ?, ?, ?)');
        foreach ($rules['weapons'] as $row) {
            $weapon->execute($row);
        }

        $spell = $pdo->prepare('INSERT INTO spells (name, level, school, damage, description) VALUES (?, ?, ?, ?, ?)');
        foreach ($rules['spells'] as $row) {
            $spell->execute($row);
        }
    }
}
