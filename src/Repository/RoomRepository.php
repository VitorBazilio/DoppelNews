<?php

declare(strict_types=1);

namespace Doppel\Repository;

use Doppel\Support\Database;

/** Salas de RPG: cada uma tem um mestre (usuário) e um limite de jogadores. */
final class RoomRepository
{
    public const ORDERS = ['novas' => 'Novas', 'populares' => 'Populares', 'mestres' => 'Mestres'];

    private const SELECT = 'SELECT r.*, u.name AS master_name,
            (SELECT COUNT(*) FROM characters c WHERE c.room_id = r.id) AS players,
            (SELECT MAX(m.created_at) FROM messages m WHERE m.room_id = r.id) AS last_activity
        FROM rooms r JOIN users u ON u.id = r.master_id';

    public function list(string $order): array
    {
        $orderBy = match ($order) {
            'populares' => 'players DESC, last_activity DESC',
            'mestres' => 'u.name ASC, r.name ASC',
            default => 'r.created_at DESC, r.id DESC',
        };
        return Database::pdo()->query(self::SELECT . " ORDER BY $orderBy")->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = Database::pdo()->prepare(self::SELECT . ' WHERE r.id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function forMaster(int $userId): array
    {
        $stmt = Database::pdo()->prepare(self::SELECT . ' WHERE r.master_id = ? ORDER BY r.created_at DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /** Uma sala aleatória com vaga (botão "Sorte"). */
    public function randomOpenId(): ?int
    {
        $ids = Database::pdo()->query(
            'SELECT r.id FROM rooms r WHERE (SELECT COUNT(*) FROM characters c WHERE c.room_id = r.id) < r.max_players'
        )->fetchAll(\PDO::FETCH_COLUMN);
        return $ids ? (int) $ids[array_rand($ids)] : null;
    }

    public function nameExists(string $name): bool
    {
        $stmt = Database::pdo()->prepare('SELECT 1 FROM rooms WHERE LOWER(name) = LOWER(?)');
        $stmt->execute([$name]);
        return (bool) $stmt->fetchColumn();
    }

    public function count(): int
    {
        return (int) Database::pdo()->query('SELECT COUNT(*) FROM rooms')->fetchColumn();
    }

    public function create(string $name, string $description, int $masterId, int $maxPlayers): int
    {
        $pdo = Database::pdo();
        $pdo->prepare('INSERT INTO rooms (name, description, master_id, max_players) VALUES (?, ?, ?, ?)')
            ->execute([$name, $description, $masterId, $maxPlayers]);
        return (int) $pdo->lastInsertId();
    }

    /** Apaga a sala; personagens e mensagens dela saem junto (ON DELETE CASCADE). */
    public function delete(int $id): void
    {
        Database::pdo()->prepare('DELETE FROM rooms WHERE id = ?')->execute([$id]);
    }
}
