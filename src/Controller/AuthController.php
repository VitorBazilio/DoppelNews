<?php

declare(strict_types=1);

namespace Doppel\Controller;

use Doppel\Repository\UserRepository;
use Doppel\Support\Auth;
use Doppel\Support\View;

final class AuthController
{
    private const MAX_ATTEMPTS = 5;
    private const LOCK_SECONDS = 60;

    public function loginForm(): void
    {
        if (Auth::user() !== null) {
            redirect('/perfil');
        }
        View::render('auth/login', [], 'Entrar');
    }

    public function login(): void
    {
        $email = input('email');
        remember_input(['email' => $email]);

        if ($this->isLockedOut()) {
            flash('error', 'Muitas tentativas. Aguarde um minuto e tente de novo.');
            redirect('/entrar');
        }

        $user = (new UserRepository())->findByEmail($email);
        if ($user === null || !password_verify((string) ($_POST['password'] ?? ''), $user['password_hash'])) {
            $_SESSION['login_failures'] = ($_SESSION['login_failures'] ?? 0) + 1;
            $_SESSION['login_last_failure'] = time();
            flash('error', 'E-mail ou senha inválidos.');
            redirect('/entrar');
        }

        unset($_SESSION['login_failures'], $_SESSION['login_last_failure'], $_SESSION['old']);
        Auth::adoptGuestProgress((int) $user['id']);
        Auth::login((int) $user['id']);
        flash('success', 'Olá, ' . $user['name'] . '!');
        $this->redirectAfterLogin($user['role'] === 'admin' ? '/admin' : '/');
    }

    public function registerForm(): void
    {
        if (Auth::user() !== null) {
            redirect('/perfil');
        }
        View::render('auth/register', [], 'Cadastrar');
    }

    public function register(): void
    {
        $name = input('name');
        $email = input('email');
        $password = (string) ($_POST['password'] ?? '');
        $repo = new UserRepository();
        remember_input(['name' => $name, 'email' => $email]);

        $error = match (true) {
            mb_strlen($name) < 2 || mb_strlen($name) > 60 => 'Informe um nick entre 2 e 60 caracteres.',
            !filter_var($email, FILTER_VALIDATE_EMAIL) => 'E-mail inválido.',
            strlen($password) < 8 => 'A senha precisa de pelo menos 8 caracteres.',
            $password !== (string) ($_POST['password_confirm'] ?? '') => 'As senhas não conferem.',
            $repo->findByEmail($email) !== null => 'Este e-mail já está cadastrado.',
            default => null,
        };
        if ($error !== null) {
            flash('error', $error);
            redirect('/cadastrar');
        }

        unset($_SESSION['old']);
        $userId = $repo->create($name, $email, $password);
        Auth::adoptGuestProgress($userId);
        Auth::login($userId);
        flash('success', 'Conta criada. Bem-vindo ao Doppel, ' . $name . '!');
        $this->redirectAfterLogin('/');
    }

    public function logout(): void
    {
        Auth::logout();
        redirect('/');
    }

    private function isLockedOut(): bool
    {
        $failures = $_SESSION['login_failures'] ?? 0;
        $waited = time() - ($_SESSION['login_last_failure'] ?? 0);
        if ($failures >= self::MAX_ATTEMPTS && $waited >= self::LOCK_SECONDS) {
            unset($_SESSION['login_failures']);
            return false;
        }
        return $failures >= self::MAX_ATTEMPTS;
    }

    /** Volta para a página que pediu login, se ela for do próprio site. */
    private function redirectAfterLogin(string $fallback): never
    {
        $target = (string) ($_SESSION['after_login'] ?? '');
        unset($_SESSION['after_login']);
        if ($target !== '' && str_starts_with($target, '/') && !str_starts_with($target, '//')) {
            header('Location: ' . $target);
            exit;
        }
        redirect($fallback);
    }
}
