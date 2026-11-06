<?php

declare(strict_types=1);

namespace Doppel\Repository;

use Doppel\Support\Database;

final class NewsRepository
{
    public const PER_PAGE = 6;

    /** @return array{items: array[], pages: int} */
    public function paginate(?string $category, int $page): array
    {
        $pdo = Database::pdo();
        $where = $category !== null ? 'WHERE category = :category' : '';
        $params = $category !== null ? ['category' => $category] : [];

        $count = $pdo->prepare("SELECT COUNT(*) FROM news $where");
        $count->execute($params);
        $pages = max(1, (int) ceil((int) $count->fetchColumn() / self::PER_PAGE));
        $page = min(max(1, $page), $pages);

        $stmt = $pdo->prepare(
            "SELECT id, title, summary, category, image, published_at FROM news $where
             ORDER BY published_at DESC, id DESC LIMIT :limit OFFSET :offset"
        );
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue('limit', self::PER_PAGE, \PDO::PARAM_INT);
        $stmt->bindValue('offset', ($page - 1) * self::PER_PAGE, \PDO::PARAM_INT);
        $stmt->execute();

        return ['items' => $stmt->fetchAll(), 'pages' => $pages, 'page' => $page];
    }

    public function find(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM news WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function related(array $article, int $limit = 3): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT id, title, category, published_at FROM news WHERE category = ? AND id != ?
             ORDER BY published_at DESC LIMIT ' . $limit
        );
        $stmt->execute([$article['category'], $article['id']]);
        return $stmt->fetchAll();
    }

    public function all(): array
    {
        return Database::pdo()
            ->query('SELECT id, title, category, published_at FROM news ORDER BY published_at DESC, id DESC')
            ->fetchAll();
    }

    public function save(?int $id, array $data): void
    {
        $pdo = Database::pdo();
        if ($id === null) {
            $pdo->prepare(
                'INSERT INTO news (title, summary, body, category, image, published_at) VALUES (?, ?, ?, ?, ?, ?)'
            )->execute([$data['title'], $data['summary'], $data['body'], $data['category'], $data['image'], date('Y-m-d H:i:s')]);
            return;
        }
        $pdo->prepare('UPDATE news SET title = ?, summary = ?, body = ?, category = ?, image = ? WHERE id = ?')
            ->execute([$data['title'], $data['summary'], $data['body'], $data['category'], $data['image'], $id]);
    }

    public function delete(int $id): void
    {
        Database::pdo()->prepare('DELETE FROM news WHERE id = ?')->execute([$id]);
    }
}
