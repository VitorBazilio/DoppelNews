<?php

declare(strict_types=1);

namespace Doppel\Support;

use Doppel\Repository\RunRepository;
use Doppel\Repository\UserRepository;

final class Auth
{
    private static ?array $user = null;

    public static function user(): ?array
    {
        if (self::$user === null && isset($_SESSION['user_id'])) {
            self::$user = (new UserRepository())->find((int) $_SESSION['user_id']);
            if (self::$user === null) {
                unset($_SESSION['user_id']); // conta excluída enquanto a sessão estava aberta
            }
        }
        return self::$user;
    }

    public static function id(): ?int
    {
        return self::user()['id'] ?? null;
    }

    public static function isAdmin(): bool
    {
        return (self::user()['role'] ?? null) === 'admin';
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

    public static function refresh(): void
    {
        self::$user = null;
    }

    /** Exige login. Visitantes vão para a tela de entrar e voltam para onde estavam depois. */
    public static function requireUser(): array
    {
        $user = self::user();
        if ($user === null) {
            $_SESSION['after_login'] = $_SERVER['REQUEST_METHOD'] === 'GET' ? ($_SERVER['REQUEST_URI'] ?? null) : null;
            flash('info', 'Entre na sua conta para continuar.');
            redirect('/entrar');
        }
        return $user;
    }

    public static function requireAdmin(): array
    {
        $user = self::user();
        if ($user === null || $user['role'] !== 'admin') {
            View::forbidden();
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

    /** Ao logar, o progresso de visitante nas aventuras passa a pertencer ao usuário. */
    public static function adoptGuestProgress(int $userId): void
    {
        if (isset($_SESSION['guest_id'])) {
            (new RunRepository())->transfer('s:' . $_SESSION['guest_id'], 'u:' . $userId);
        }
    }
}
