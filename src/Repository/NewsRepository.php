<?php

declare(strict_types=1);

namespace Doppel\Repository;

use Doppel\Support\Database;
use PDO;

final class NewsRepository
{
    public const PER_PAGE = 5;
    private const LIST_COLUMNS = 'id, title, summary, category, image, author, featured, published_at';

    /** @return array{items: array[], pages: int, page: int, total: int} */
    public function paginate(?string $category, int $page, string $search = ''): array
    {
        $where = [];
        $params = [];
        if ($category !== null) {
            $where[] = 'category = :category';
            $params['category'] = $category;
        }
        if ($search !== '') {
            $where[] = '(title LIKE :search OR summary LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }
        $sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $pdo = Database::pdo();
        $count = $pdo->prepare("SELECT COUNT(*) FROM news $sqlWhere");
        $count->execute($params);
        $total = (int) $count->fetchColumn();
        $pages = max(1, (int) ceil($total / self::PER_PAGE));
        $page = min(max(1, $page), $pages);

        $stmt = $pdo->prepare(
            'SELECT ' . self::LIST_COLUMNS . " FROM news $sqlWhere
             ORDER BY published_at DESC, id DESC LIMIT :limit OFFSET :offset"
        );
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue('limit', self::PER_PAGE, PDO::PARAM_INT);
        $stmt->bindValue('offset', ($page - 1) * self::PER_PAGE, PDO::PARAM_INT);
        $stmt->execute();

        return ['items' => $stmt->fetchAll(), 'pages' => $pages, 'page' => $page, 'total' => $total];
    }

    /** Notícias do carrossel: as marcadas como destaque; se não houver, as mais recentes com imagem. */
    public function featured(int $limit = 5): array
    {
        $pdo = Database::pdo();
        $sql = 'SELECT ' . self::LIST_COLUMNS . ' FROM news WHERE image IS NOT NULL AND %s ORDER BY published_at DESC LIMIT ' . $limit;
        $items = $pdo->query(sprintf($sql, 'featured = 1'))->fetchAll();
        return $items ?: $pdo->query(sprintf($sql, '1 = 1'))->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM news WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function recent(int $limit, ?int $exceptId = null): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT ' . self::LIST_COLUMNS . ' FROM news WHERE id != ? ORDER BY published_at DESC, id DESC LIMIT ' . $limit
        );
        $stmt->execute([$exceptId ?? 0]);
        return $stmt->fetchAll();
    }

    public function related(array $article, int $limit = 3): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT ' . self::LIST_COLUMNS . ' FROM news WHERE category = ? AND id != ?
             ORDER BY published_at DESC LIMIT ' . $limit
        );
        $stmt->execute([$article['category'], $article['id']]);
        return $stmt->fetchAll();
    }

    public function all(): array
    {
        return Database::pdo()
            ->query('SELECT ' . self::LIST_COLUMNS . ' FROM news ORDER BY published_at DESC, id DESC')
            ->fetchAll();
    }

    public function count(): int
    {
        return (int) Database::pdo()->query('SELECT COUNT(*) FROM news')->fetchColumn();
    }

    public function save(?int $id, array $data): int
    {
        $pdo = Database::pdo();
        $values = [$data['title'], $data['summary'], $data['body'], $data['category'], $data['image'], $data['author'], $data['featured']];
        if ($id === null) {
            $pdo->prepare(
                'INSERT INTO news (title, summary, body, category, image, author, featured, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
            )->execute([...$values, date('Y-m-d H:i:s')]);
            return (int) $pdo->lastInsertId();
        }
        $pdo->prepare('UPDATE news SET title = ?, summary = ?, body = ?, category = ?, image = ?, author = ?, featured = ? WHERE id = ?')
            ->execute([...$values, $id]);
        return $id;
    }

    public function delete(int $id): void
    {
        Database::pdo()->prepare('DELETE FROM news WHERE id = ?')->execute([$id]);
    }
}
