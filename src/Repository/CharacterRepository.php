<?php

declare(strict_types=1);

namespace Doppel\Repository;

use Doppel\Support\Database;

/** Personagens de D&D. Cada ficha pertence a um usuário e pode jogar em várias salas. */
final class CharacterRepository
{
    private const SELECT = 'SELECT ch.*, s.name AS species_name, cl.name AS class_name, cl.hit_die,
            w.name AS weapon_name, w.damage AS weapon_damage, w.properties AS weapon_properties,
            u.name AS player_name,
            (SELECT COUNT(*) FROM room_members m WHERE m.character_id = ch.id) AS rooms_count
        FROM characters ch
        JOIN species s ON s.id = ch.species_id
        JOIN classes cl ON cl.id = ch.class_id
        LEFT JOIN weapons w ON w.id = ch.weapon_id
        JOIN users u ON u.id = ch.user_id';

    public function find(int $id): ?array
    {
        return $this->one(self::SELECT . ' WHERE ch.id = ?', [$id]);
    }

    public function forUser(int $userId): array
    {
        return $this->many(self::SELECT . ' WHERE ch.user_id = ? ORDER BY ch.created_at DESC, ch.id DESC', [$userId]);
    }

    /** Personagens em jogo numa sala, com o dono de cada um. */
    public function forRoom(int $roomId): array
    {
        return $this->many(
            self::SELECT . ' JOIN room_members m ON m.character_id = ch.id WHERE m.room_id = ? ORDER BY ch.score DESC, ch.name',
            [$roomId]
        );
    }

    public function ranking(int $limit = 10): array
    {
        return $this->many(self::SELECT . ' ORDER BY ch.score DESC, ch.created_at ASC LIMIT ' . $limit, []);
    }

    public function recent(int $limit = 12): array
    {
        return $this->many(self::SELECT . ' ORDER BY ch.created_at DESC, ch.id DESC LIMIT ' . $limit, []);
    }

    /** Salas em que o personagem está jogando. */
    public function roomsOf(int $characterId): array
    {
        return $this->many(
            'SELECT r.id, r.name FROM rooms r JOIN room_members m ON m.room_id = r.id WHERE m.character_id = ? ORDER BY r.name',
            [$characterId]
        );
    }

    public function count(): int
    {
        return (int) Database::pdo()->query('SELECT COUNT(*) FROM characters')->fetchColumn();
    }

    public function nameTakenByUser(int $userId, string $name): bool
    {
        return $this->one('SELECT 1 FROM characters WHERE user_id = ? AND LOWER(name) = LOWER(?)', [$userId, $name]) !== null;
    }

    public function create(array $data): int
    {
        $pdo = Database::pdo();
        $pdo->prepare(
            'INSERT INTO characters (user_id, name, species_id, class_id, weapon_id, hp, spell_slots,
             attr_for, attr_des, attr_con, attr_int, attr_sab, attr_car) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        )->execute([
            $data['user_id'], $data['name'], $data['species_id'], $data['class_id'], $data['weapon_id'],
            $data['hp'], $data['spell_slots'], ...array_values($data['attributes']),
        ]);
        return (int) $pdo->lastInsertId();
    }

    public function addScore(int $id, int $delta): void
    {
        Database::pdo()->prepare('UPDATE characters SET score = score + ? WHERE id = ?')->execute([$delta, $id]);
    }

    /** Apaga a ficha; ela sai de todas as salas (ON DELETE CASCADE). */
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
