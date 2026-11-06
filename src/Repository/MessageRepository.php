<?php

declare(strict_types=1);

namespace Doppel\Repository;

use Doppel\Support\Database;

/** Mensagens do chat das salas. */
final class MessageRepository
{
    public const HISTORY = 50;

    /** Mensagens depois de $afterId (ou as últimas HISTORY), em ordem cronológica. */
    public function since(int $roomId, int $afterId = 0): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT id, user_id, author, body, kind, created_at FROM messages
             WHERE room_id = ? AND id > ? ORDER BY id DESC LIMIT ' . self::HISTORY
        );
        $stmt->execute([$roomId, $afterId]);
        return array_reverse($stmt->fetchAll());
    }

    public function add(int $roomId, ?int $userId, string $author, string $body, string $kind = 'chat'): int
    {
        $pdo = Database::pdo();
        $pdo->prepare('INSERT INTO messages (room_id, user_id, author, body, kind, created_at) VALUES (?, ?, ?, ?, ?, ?)')
            ->execute([$roomId, $userId, $author, $body, $kind, date('Y-m-d H:i:s')]);
        return (int) $pdo->lastInsertId();
    }
}
