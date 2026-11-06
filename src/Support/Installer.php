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
        return !self::canQuery($pdo, 'SELECT featured, author FROM news WHERE 1 = 0')
            || !self::canQuery($pdo, 'SELECT active FROM users WHERE 1 = 0')
            || !self::canQuery($pdo, 'SELECT 1 FROM room_members WHERE 1 = 0')
            || self::canQuery($pdo, 'SELECT room_id FROM characters WHERE 1 = 0');
    }

    public static function run(PDO $pdo): void
    {
        $oldCharacters = self::takeOldCharacters($pdo);
        self::runSchema($pdo);
        self::addMissingColumns($pdo);
        self::restoreOldCharacters($pdo, $oldCharacters);
        self::renameSampleImages($pdo);

        if (self::isEmpty($pdo, 'news')) {
            self::seedNews($pdo);
        }
        if (self::isEmpty($pdo, 'classes')) {
            self::seedRules($pdo);
        }
    }

    private static function runSchema(PDO $pdo): void
    {
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $schema = file_get_contents(ROOT . "/database/schema.$driver.sql");
        foreach (array_filter(array_map('trim', explode(';', $schema))) as $statement) {
            $pdo->exec($statement);
        }
    }

    private static function canQuery(PDO $pdo, string $sql): bool
    {
        try {
            $pdo->query($sql);
            return true;
        } catch (\PDOException) {
            return false;
        }
    }

    /** Colunas que bancos de versões anteriores não têm. */
    private static function addMissingColumns(PDO $pdo): void
    {
        $columns = [
            ['news', 'author', "VARCHAR(80) NOT NULL DEFAULT 'Equipe Doppel'"],
            ['news', 'featured', 'INTEGER NOT NULL DEFAULT 0'],
            ['users', 'active', 'INTEGER NOT NULL DEFAULT 1'],
        ];
        foreach ($columns as [$table, $column, $definition]) {
            if (!self::canQuery($pdo, "SELECT $column FROM $table WHERE 1 = 0")) {
                $pdo->exec("ALTER TABLE $table ADD COLUMN $column $definition");
            }
        }
    }

    /**
     * Na versão anterior cada ficha pertencia a uma sala (characters.room_id). Agora a ficha é do
     * usuário e a participação fica em room_members. Guarda as fichas antigas e remove a tabela velha.
     */
    private static function takeOldCharacters(PDO $pdo): array
    {
        if (!self::canQuery($pdo, 'SELECT room_id FROM characters WHERE 1 = 0')) {
            return [];
        }
        $rows = $pdo->query('SELECT * FROM characters ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
        $pdo->exec('DROP TABLE characters');
        return $rows;
    }

    /** Recria as fichas antigas no formato novo e põe cada uma de volta na sala em que estava. */
    private static function restoreOldCharacters(PDO $pdo, array $rows): void
    {
        $columns = ['id', 'user_id', 'name', 'species_id', 'class_id', 'weapon_id', 'level', 'hp', 'spell_slots',
            'attr_for', 'attr_des', 'attr_con', 'attr_int', 'attr_sab', 'attr_car', 'score', 'created_at'];
        $insert = $pdo->prepare('INSERT INTO characters (' . implode(', ', $columns) . ') VALUES ('
            . implode(', ', array_fill(0, count($columns), '?')) . ')');
        $member = $pdo->prepare('INSERT INTO room_members (room_id, user_id, character_id, joined_at) VALUES (?, ?, ?, ?)');

        $usedNames = [];
        foreach ($rows as $row) {
            // O mesmo usuário podia ter fichas com o mesmo nome em salas diferentes.
            $name = $row['name'];
            for ($n = 2; isset($usedNames[$row['user_id']][mb_strtolower($name)]); $n++) {
                $name = $row['name'] . " ($n)";
            }
            $usedNames[$row['user_id']][mb_strtolower($name)] = true;
            $row['name'] = $name;

            $insert->execute(array_map(static fn (string $c) => $row[$c], $columns));
            $member->execute([$row['room_id'], $row['user_id'], $row['id'], $row['created_at']]);
        }
    }

    private static function renameSampleImages(PDO $pdo): void
    {
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
        // Duas visitas simultâneas podem instalar ao mesmo tempo: pula notícias que já existam.
        $exists = $pdo->prepare('SELECT 1 FROM news WHERE title = ?');
        foreach (require ROOT . '/database/seed/news.php' as $row) {
            $exists->execute([$row['title']]);
            if ($exists->fetchColumn()) {
                continue;
            }
            $insert->execute([
                $row['title'], $row['summary'], $row['body'], $row['category'], $row['image'], $row['author'],
                $row['featured'] ? 1 : 0, date('Y-m-d H:i:s', strtotime('-' . $row['hours_ago'] . ' hours')),
            ]);
        }
    }

    private static function seedRules(PDO $pdo): void
    {
        $rules = require ROOT . '/database/seed/rules.php';
        // Os nomes das regras são únicos: se outra visita já inseriu o registro, o banco só o ignora.
        $insert = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite' ? 'INSERT OR IGNORE' : 'INSERT IGNORE';

        $class = $pdo->prepare(
            "$insert INTO classes (name, hit_die, primary_attr, secondary_attr, spell_slots, simple_weapons,
             martial_weapons, light_armor, medium_armor, heavy_armor, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        foreach ($rules['classes'] as $row) {
            $class->execute($row);
        }

        $species = $pdo->prepare("$insert INTO species (name, bonuses, description) VALUES (?, ?, ?)");
        foreach ($rules['species'] as [$name, $bonuses, $description]) {
            $species->execute([$name, json_encode($bonuses), $description]);
        }

        $weapon = $pdo->prepare("$insert INTO weapons (name, damage, category, price, weight, properties) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($rules['weapons'] as $row) {
            $weapon->execute($row);
        }

        $spell = $pdo->prepare("$insert INTO spells (name, level, school, damage, description) VALUES (?, ?, ?, ?, ?)");
        foreach ($rules['spells'] as $row) {
            $spell->execute($row);
        }
    }
}
