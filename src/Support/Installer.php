<?php

declare(strict_types=1);

namespace Doppel\Support;

use PDO;

/** Cria o esquema do banco e popula notícias e regras de exemplo (só em tabelas vazias). */
final class Installer
{
    /** Verdadeiro se o banco ainda não tem o esquema atual (banco novo ou de uma versão anterior). */
    public static function needsUpgrade(PDO $pdo): bool
    {
        try {
            $pdo->query('SELECT featured, author FROM news WHERE 1 = 0');
            $pdo->query('SELECT 1 FROM characters WHERE 1 = 0');
            return false;
        } catch (\PDOException) {
            return true;
        }
    }

    public static function run(PDO $pdo): void
    {
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $schema = file_get_contents(ROOT . "/database/schema.$driver.sql");
        foreach (array_filter(array_map('trim', explode(';', $schema))) as $statement) {
            $pdo->exec($statement);
        }
        self::upgradeNewsTable($pdo);

        if (self::isEmpty($pdo, 'news')) {
            self::seedNews($pdo);
        }
        if (self::isEmpty($pdo, 'classes')) {
            self::seedRules($pdo);
        }
    }

    /** Bancos da versão anterior do DoppelNews não têm assinatura nem destaque nas notícias. */
    private static function upgradeNewsTable(PDO $pdo): void
    {
        $columns = [
            'author' => "VARCHAR(80) NOT NULL DEFAULT 'Equipe Doppel'",
            'featured' => 'INTEGER NOT NULL DEFAULT 0',
        ];
        foreach ($columns as $column => $definition) {
            try {
                $pdo->query("SELECT $column FROM news WHERE 1 = 0");
            } catch (\PDOException) {
                $pdo->exec("ALTER TABLE news ADD COLUMN $column $definition");
            }
        }

        // As imagens de exemplo foram renomeadas e convertidas para WebP.
        $renamed = [
            'cp.jpg' => 'banner-cyberpunk.webp', 'god2.jpg' => 'banner-god-of-war.webp', 'zel.jpg' => 'banner-zelda.webp',
            'as2.png' => 'banner-anime.webp', 'xs2.png' => 'banner-games.webp', 'wl2.jpg' => 'banner-dnd.webp',
            '152ecb7bdd94d569c149aa487200a9ab.jpg' => 'boku-no-hero.webp', 'c8464905084d0b5bde4a220925f12e0f.jpg' => 'coringa.webp',
            '718183fb6bc0537329c6092c61d32459.jpg' => 'cyberpunk.webp', '82542ee1637228abc089e7f352c5831e.jpg' => 'god-of-war.webp',
            '66c59bc1f8c087df626ded700b9e0c6e.png' => 'liga-da-justica.webp', '370126da9e4b5cc40c8ca1aa01584012.jpg' => 'shenmue.webp',
        ];
        $update = $pdo->prepare('UPDATE news SET image = ? WHERE image = ?');
        foreach ($renamed as $old => $new) {
            $update->execute([$new, $old]);
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
