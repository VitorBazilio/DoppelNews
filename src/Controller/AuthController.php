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
        View::render('auth/login', ['email' => ''], 'Entrar');
    }

    public function login(): void
    {
        $email = trim((string) ($_POST['email'] ?? ''));
        $this->guardAgainstBruteForce();

        $user = (new UserRepository())->findByEmail($email);
        if ($user === null || !password_verify((string) ($_POST['password'] ?? ''), $user['password_hash'])) {
            $_SESSION['login_failures'] = ($_SESSION['login_failures'] ?? 0) + 1;
            $_SESSION['login_last_failure'] = time();
            flash('error', 'E-mail ou senha inválidos.');
            View::render('auth/login', ['email' => $email], 'Entrar');
            return;
        }

        unset($_SESSION['login_failures'], $_SESSION['login_last_failure']);
        Auth::adoptGuestProgress((int) $user['id']);
        Auth::login((int) $user['id']);
        redirect($user['role'] === 'admin' ? '/admin' : '/aventuras');
    }

    public function registerForm(): void
    {
        View::render('auth/register', ['old' => ['name' => '', 'email' => '']], 'Criar conta');
    }

    public function register(): void
    {
        $old = ['name' => trim((string) ($_POST['name'] ?? '')), 'email' => trim((string) ($_POST['email'] ?? ''))];
        $password = (string) ($_POST['password'] ?? '');
        $repo = new UserRepository();

        $error = match (true) {
            mb_strlen($old['name']) < 2 || mb_strlen($old['name']) > 60 => 'Informe um apelido entre 2 e 60 caracteres.',
            !filter_var($old['email'], FILTER_VALIDATE_EMAIL) => 'E-mail inválido.',
            strlen($password) < 8 => 'A senha precisa de pelo menos 8 caracteres.',
            $password !== (string) ($_POST['password_confirm'] ?? '') => 'As senhas não conferem.',
            $repo->findByEmail($old['email']) !== null => 'Este e-mail já está cadastrado.',
            default => null,
        };

        if ($error !== null) {
            flash('error', $error);
            View::render('auth/register', ['old' => $old], 'Criar conta');
            return;
        }

        $userId = $repo->create($old['name'], $old['email'], $password);
        Auth::adoptGuestProgress($userId);
        Auth::login($userId);
        flash('success', 'Conta criada. Bem-vindo ao Doppel!');
        redirect('/aventuras');
    }

    public function logout(): void
    {
        Auth::logout();
        redirect('/');
    }

    private function guardAgainstBruteForce(): void
    {
        $failures = $_SESSION['login_failures'] ?? 0;
        $waited = time() - ($_SESSION['login_last_failure'] ?? 0);
        if ($failures >= self::MAX_ATTEMPTS && $waited < self::LOCK_SECONDS) {
            flash('error', 'Muitas tentativas. Aguarde um minuto e tente de novo.');
            View::render('auth/login', ['email' => ''], 'Entrar');
            exit;
        }
        if ($failures >= self::MAX_ATTEMPTS) {
            unset($_SESSION['login_failures']);
        }
    }
}
