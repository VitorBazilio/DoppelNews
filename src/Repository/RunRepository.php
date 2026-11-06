<?php

declare(strict_types=1);

namespace Doppel\Repository;

use Doppel\Support\Database;

/** Persistência do progresso de cada jogador em cada aventura. */
final class RunRepository
{
    public function find(string $ownerKey, string $storyId): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM story_runs WHERE owner_key = ? AND story_id = ?');
        $stmt->execute([$ownerKey, $storyId]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }
        $row['state'] = json_decode($row['state'], true);
        return $row;
    }

    /** @return array<string, array> progresso indexado por id da aventura */
    public function allFor(string $ownerKey): array
    {
        $stmt = Database::pdo()->prepare('SELECT story_id, finished, ending, state FROM story_runs WHERE owner_key = ?');
        $stmt->execute([$ownerKey]);
        $runs = [];
        foreach ($stmt->fetchAll() as $row) {
            $row['state'] = json_decode($row['state'], true);
            $runs[$row['story_id']] = $row;
        }
        return $runs;
    }

    public function save(string $ownerKey, string $storyId, array $state, ?string $ending): void
    {
        $pdo = Database::pdo();
        $json = json_encode($state, JSON_UNESCAPED_UNICODE);
        $finished = $ending !== null ? 1 : 0;
        $now = date('Y-m-d H:i:s');

        $update = $pdo->prepare(
            'UPDATE story_runs SET state = ?, finished = ?, ending = ?, updated_at = ? WHERE owner_key = ? AND story_id = ?'
        );
        $update->execute([$json, $finished, $ending, $now, $ownerKey, $storyId]);

        if ($update->rowCount() === 0 && $this->find($ownerKey, $storyId) === null) {
            $pdo->prepare(
                'INSERT INTO story_runs (owner_key, story_id, state, finished, ending, updated_at) VALUES (?, ?, ?, ?, ?, ?)'
            )->execute([$ownerKey, $storyId, $json, $finished, $ending, $now]);
        }
    }

    public function delete(string $ownerKey, string $storyId): void
    {
        Database::pdo()->prepare('DELETE FROM story_runs WHERE owner_key = ? AND story_id = ?')
            ->execute([$ownerKey, $storyId]);
    }

    /** Move o progresso de um visitante para um usuário, sem sobrescrever o que o usuário já tem. */
    public function transfer(string $fromKey, string $toKey): void
    {
        $pdo = Database::pdo();
        $pdo->prepare(
            'DELETE FROM story_runs WHERE owner_key = ? AND story_id IN (SELECT story_id FROM (SELECT story_id FROM story_runs WHERE owner_key = ?) AS mine)'
        )->execute([$fromKey, $toKey]);
        $pdo->prepare('UPDATE story_runs SET owner_key = ? WHERE owner_key = ?')->execute([$toKey, $fromKey]);
    }
}
