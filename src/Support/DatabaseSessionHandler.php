<?php

declare(strict_types=1);

namespace Doppel\Support;

use SessionHandlerInterface;

/**
 * Guarda as sessões no banco. Necessário em hospedagem serverless (Vercel),
 * onde cada requisição pode cair em uma instância sem disco compartilhado.
 */
final class DatabaseSessionHandler implements SessionHandlerInterface
{
    public function open(string $path, string $name): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $id): string|false
    {
        $stmt = Database::pdo()->prepare('SELECT data FROM sessions WHERE id = ?');
        $stmt->execute([$id]);
        return (string) ($stmt->fetchColumn() ?: '');
    }

    public function write(string $id, string $data): bool
    {
        $pdo = Database::pdo();
        $update = $pdo->prepare('UPDATE sessions SET data = ?, updated_at = ? WHERE id = ?');
        $update->execute([$data, time(), $id]);

        if ($update->rowCount() === 0) {
            $exists = $pdo->prepare('SELECT 1 FROM sessions WHERE id = ?');
            $exists->execute([$id]);
            if (!$exists->fetchColumn()) {
                $pdo->prepare('INSERT INTO sessions (id, data, updated_at) VALUES (?, ?, ?)')
                    ->execute([$id, $data, time()]);
            }
        }
        return true;
    }

    public function destroy(string $id): bool
    {
        Database::pdo()->prepare('DELETE FROM sessions WHERE id = ?')->execute([$id]);
        return true;
    }

    public function gc(int $max_lifetime): int|false
    {
        $stmt = Database::pdo()->prepare('DELETE FROM sessions WHERE updated_at < ?');
        $stmt->execute([time() - $max_lifetime]);
        return $stmt->rowCount();
    }
}
