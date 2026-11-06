<?php

declare(strict_types=1);

namespace Doppel\Repository;

use Doppel\Support\Database;

/** Personagens de D&D. Cada usuário pode ter um personagem por sala. */
final class CharacterRepository
{
    private const SELECT = 'SELECT ch.*, s.name AS species_name, cl.name AS class_name, cl.hit_die,
            w.name AS weapon_name, w.damage AS weapon_damage, w.properties AS weapon_properties,
            r.name AS room_name, u.name AS player_name
        FROM characters ch
        JOIN species s ON s.id = ch.species_id
        JOIN classes cl ON cl.id = ch.class_id
        LEFT JOIN weapons w ON w.id = ch.weapon_id
        JOIN rooms r ON r.id = ch.room_id
        JOIN users u ON u.id = ch.user_id';

    public function find(int $id): ?array
    {
        return $this->one(self::SELECT . ' WHERE ch.id = ?', [$id]);
    }

    public function findForUserInRoom(int $userId, int $roomId): ?array
    {
        return $this->one(self::SELECT . ' WHERE ch.user_id = ? AND ch.room_id = ?', [$userId, $roomId]);
    }

    public function forRoom(int $roomId): array
    {
        return $this->many(self::SELECT . ' WHERE ch.room_id = ? ORDER BY ch.score DESC, ch.name', [$roomId]);
    }

    public function forUser(int $userId): array
    {
        return $this->many(self::SELECT . ' WHERE ch.user_id = ? ORDER BY ch.created_at DESC', [$userId]);
    }

    public function ranking(int $limit = 10): array
    {
        return $this->many(self::SELECT . ' ORDER BY ch.score DESC, ch.created_at ASC LIMIT ' . $limit, []);
    }

    public function recent(int $limit = 12): array
    {
        return $this->many(self::SELECT . ' ORDER BY ch.created_at DESC, ch.id DESC LIMIT ' . $limit, []);
    }

    public function count(): int
    {
        return (int) Database::pdo()->query('SELECT COUNT(*) FROM characters')->fetchColumn();
    }

    public function nameTakenInRoom(int $roomId, string $name): bool
    {
        $stmt = Database::pdo()->prepare('SELECT 1 FROM characters WHERE room_id = ? AND LOWER(name) = LOWER(?)');
        $stmt->execute([$roomId, $name]);
        return (bool) $stmt->fetchColumn();
    }

    public function create(array $data): int
    {
        $pdo = Database::pdo();
        $pdo->prepare(
            'INSERT INTO characters (user_id, room_id, name, species_id, class_id, weapon_id, hp, spell_slots,
             attr_for, attr_des, attr_con, attr_int, attr_sab, attr_car) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        )->execute([
            $data['user_id'], $data['room_id'], $data['name'], $data['species_id'], $data['class_id'], $data['weapon_id'],
            $data['hp'], $data['spell_slots'], ...array_values($data['attributes']),
        ]);
        return (int) $pdo->lastInsertId();
    }

    public function addScore(int $id, int $delta): void
    {
        Database::pdo()->prepare('UPDATE characters SET score = score + ? WHERE id = ?')->execute([$delta, $id]);
    }

    public function delete(int $id): void
    {
        Database::pdo()->prepare('DELETE FROM characters WHERE id = ?')->execute([$id]);
    }

    /** Atributos finais de uma linha do banco no formato ['for' => 15, ...]. */
    public static function attributes(array $character): array
    {
        $values = [];
        foreach (array_keys(ATTRIBUTES) as $attr) {
            $values[$attr] = (int) $character['attr_' . $attr];
        }
        return $values;
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
