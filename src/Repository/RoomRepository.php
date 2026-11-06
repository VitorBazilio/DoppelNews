<?php

declare(strict_types=1);

namespace Doppel\Repository;

use Doppel\Support\Database;

/**
 * Salas de RPG: cada uma tem um mestre (usuário) e um limite de jogadores.
 * Jogadores entram escolhendo um dos seus personagens (tabela room_members).
 */
final class RoomRepository
{
    public const ORDERS = ['novas' => 'Novas', 'populares' => 'Populares', 'mestres' => 'Mestres'];

    private const SELECT = 'SELECT r.*, u.name AS master_name,
            (SELECT COUNT(*) FROM room_members p WHERE p.room_id = r.id) AS players,
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
        return $this->one(self::SELECT . ' WHERE r.id = ?', [$id]);
    }

    public function forMaster(int $userId): array
    {
        return $this->many(self::SELECT . ' WHERE r.master_id = ? ORDER BY r.created_at DESC', [$userId]);
    }

    /** Salas em que o usuário joga (com o personagem escolhido em cada uma). */
    public function forPlayer(int $userId): array
    {
        return $this->many(
            self::SELECT . ' JOIN room_members rm ON rm.room_id = r.id WHERE rm.user_id = ? ORDER BY rm.joined_at DESC',
            [$userId]
        );
    }

    /** Uma sala aleatória com vaga (botão "Sorte"). */
    public function randomOpenId(): ?int
    {
        $ids = Database::pdo()->query(
            'SELECT r.id FROM rooms r WHERE (SELECT COUNT(*) FROM room_members p WHERE p.room_id = r.id) < r.max_players'
        )->fetchAll(\PDO::FETCH_COLUMN);
        return $ids ? (int) $ids[array_rand($ids)] : null;
    }

    public function nameExists(string $name): bool
    {
        return $this->one('SELECT 1 FROM rooms WHERE LOWER(name) = LOWER(?)', [$name]) !== null;
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

    /** Apaga a sala; participações e mensagens saem junto (ON DELETE CASCADE). As fichas continuam com os donos. */
    public function delete(int $id): void
    {
        Database::pdo()->prepare('DELETE FROM rooms WHERE id = ?')->execute([$id]);
    }

    /** Participação do usuário na sala: ['character_id' => …] ou null. */
    public function membership(int $roomId, int $userId): ?array
    {
        return $this->one('SELECT * FROM room_members WHERE room_id = ? AND user_id = ?', [$roomId, $userId]);
    }

    public function isCharacterInRoom(int $roomId, int $characterId): bool
    {
        return $this->one('SELECT 1 FROM room_members WHERE room_id = ? AND character_id = ?', [$roomId, $characterId]) !== null;
    }

    public function join(int $roomId, int $userId, int $characterId): void
    {
        Database::pdo()->prepare('INSERT INTO room_members (room_id, user_id, character_id, joined_at) VALUES (?, ?, ?, ?)')
            ->execute([$roomId, $userId, $characterId, date('Y-m-d H:i:s')]);
    }

    public function leave(int $roomId, int $userId): void
    {
        Database::pdo()->prepare('DELETE FROM room_members WHERE room_id = ? AND user_id = ?')->execute([$roomId, $userId]);
    }

    private function one(string $sql, array $params): ?array
    {
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() ?: null;
    }

    private function many(string $sql, array $params): array
    {
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
