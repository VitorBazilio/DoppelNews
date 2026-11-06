<?php

declare(strict_types=1);

namespace Doppel\Repository;

use Doppel\Support\Database;

final class UserRepository
{
    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT id, name, email, role FROM users WHERE id = ?', [$id]);
    }

    public function findByEmail(string $email): ?array
    {
        return $this->fetchOne('SELECT * FROM users WHERE email = ?', [strtolower($email)]);
    }

    public function create(string $name, string $email, string $password, string $role = 'user'): int
    {
        $pdo = Database::pdo();
        $pdo->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)')
            ->execute([$name, strtolower($email), password_hash($password, PASSWORD_DEFAULT), $role]);
        return (int) $pdo->lastInsertId();
    }

    private function fetchOne(string $sql, array $params): ?array
    {
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() ?: null;
    }
}
