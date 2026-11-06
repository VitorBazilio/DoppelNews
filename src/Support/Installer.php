<?php

declare(strict_types=1);

namespace Doppel\Support;

use PDO;

/** Cria o esquema do banco e popula notícias de exemplo. */
final class Installer
{
    public static function run(PDO $pdo): void
    {
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $schema = file_get_contents(ROOT . "/database/schema.$driver.sql");
        foreach (array_filter(array_map('trim', explode(';', $schema))) as $statement) {
            $pdo->exec($statement);
        }

        if ((int) $pdo->query('SELECT COUNT(*) FROM news')->fetchColumn() === 0) {
            self::seedNews($pdo);
        }
    }

    private static function seedNews(PDO $pdo): void
    {
        $insert = $pdo->prepare(
            'INSERT INTO news (title, summary, body, category, image, published_at) VALUES (?, ?, ?, ?, ?, ?)'
        );
        foreach (require ROOT . '/database/seed_news.php' as $i => $row) {
            $insert->execute([
                $row['title'], $row['summary'], $row['body'], $row['category'], $row['image'],
                date('Y-m-d H:i:s', strtotime('-' . ($i * 9 + 2) . ' hours')),
            ]);
        }
    }
}
