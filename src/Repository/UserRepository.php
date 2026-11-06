<?php

declare(strict_types=1);

namespace Doppel\Repository;

use Doppel\Support\Database;

final class UserRepository
{
    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT id, name, email, role, active, created_at FROM users WHERE id = ?', [$id]);
    }

    public function findByEmail(string $email): ?array
    {
        return $this->fetchOne('SELECT * FROM users WHERE email = ?', [mb_strtolower(trim($email))]);
    }

    public function create(string $name, string $email, string $password, string $role = 'user'): int
    {
        $pdo = Database::pdo();
        $pdo->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)')
            ->execute([$name, mb_strtolower(trim($email)), password_hash($password, PASSWORD_DEFAULT), $role]);
        return (int) $pdo->lastInsertId();
    }

    /** Lista para o painel, com quantos personagens e salas cada usuário tem. */
    public function allWithStats(): array
    {
        return Database::pdo()->query(
            'SELECT u.id, u.name, u.email, u.role, u.active, u.created_at,
                    (SELECT COUNT(*) FROM characters c WHERE c.user_id = u.id) AS characters,
                    (SELECT COUNT(*) FROM rooms r WHERE r.master_id = u.id) AS rooms
             FROM users u ORDER BY u.created_at DESC, u.id DESC'
        )->fetchAll();
    }

    /** O primeiro administrador (o mais antigo) é protegido: ninguém o altera pelo painel. */
    public function firstAdminId(): ?int
    {
        $id = Database::pdo()->query("SELECT MIN(id) FROM users WHERE role = 'admin'")->fetchColumn();
        return $id === null || $id === false ? null : (int) $id;
    }

    public function count(): int
    {
        return (int) Database::pdo()->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    public function emailTaken(string $email, int $exceptId): bool
    {
        return $this->fetchOne('SELECT 1 FROM users WHERE email = ? AND id != ?', [mb_strtolower(trim($email)), $exceptId]) !== null;
    }

    public function updateName(int $id, string $name): void
    {
        Database::pdo()->prepare('UPDATE users SET name = ? WHERE id = ?')->execute([$name, $id]);
    }

    /** Edição feita pelo administrador. */
    public function updateAccount(int $id, string $name, string $email, string $role): void
    {
        Database::pdo()->prepare('UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?')
            ->execute([$name, mb_strtolower(trim($email)), $role, $id]);
    }

    public function updatePassword(int $id, string $password): void
    {
        Database::pdo()->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
            ->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
    }

    public function verifyPassword(int $id, string $password): bool
    {
        $hash = $this->fetchOne('SELECT password_hash FROM users WHERE id = ?', [$id])['password_hash'] ?? '';
        return $hash !== '' && password_verify($password, $hash);
    }

    public function setRole(int $id, string $role): void
    {
        Database::pdo()->prepare('UPDATE users SET role = ? WHERE id = ?')->execute([$role, $id]);
    }

    public function setActive(int $id, bool $active): void
    {
        Database::pdo()->prepare('UPDATE users SET active = ? WHERE id = ?')->execute([$active ? 1 : 0, $id]);
    }

    /** Remove o usuário; salas, personagens e progresso nas aventuras vão junto. */
    public function delete(int $id): void
    {
        Database::transaction(static function (\PDO $pdo) use ($id): void {
            $pdo->prepare('DELETE FROM story_runs WHERE owner_key = ?')->execute(['u:' . $id]);
            $pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
        });
    }

    private function fetchOne(string $sql, array $params): ?array
    {
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() ?: null;
    }
}
