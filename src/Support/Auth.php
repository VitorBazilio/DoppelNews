<?php

declare(strict_types=1);

namespace Doppel\Support;

use Doppel\Repository\UserRepository;

final class Auth
{
    private static ?array $user = null;

    public static function user(): ?array
    {
        if (self::$user === null && isset($_SESSION['user_id'])) {
            self::$user = (new UserRepository())->find((int) $_SESSION['user_id']);
        }
        return self::$user;
    }

    public static function login(int $userId): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $userId;
        self::$user = null;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_regenerate_id(true);
        self::$user = null;
    }

    public static function requireAdmin(): array
    {
        $user = self::user();
        if ($user === null || $user['role'] !== 'admin') {
            http_response_code(403);
            View::render('403', [], 'Acesso negado');
            exit;
        }
        return $user;
    }

    /** Chave que identifica o dono do progresso nas aventuras: usuário logado ou visitante. */
    public static function ownerKey(): string
    {
        if (isset($_SESSION['user_id'])) {
            return 'u:' . $_SESSION['user_id'];
        }
        return 's:' . ($_SESSION['guest_id'] ??= bin2hex(random_bytes(8)));
    }

    /** Ao logar, o progresso de visitante passa a pertencer ao usuário. */
    public static function adoptGuestProgress(int $userId): void
    {
        if (isset($_SESSION['guest_id'])) {
            (new \Doppel\Repository\RunRepository())->transfer('s:' . $_SESSION['guest_id'], 'u:' . $userId);
        }
    }
}
