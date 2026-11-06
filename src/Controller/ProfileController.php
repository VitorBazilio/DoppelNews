<?php

declare(strict_types=1);

namespace Doppel\Controller;

use Doppel\Repository\CharacterRepository;
use Doppel\Repository\RoomRepository;
use Doppel\Repository\RunRepository;
use Doppel\Repository\UserRepository;
use Doppel\Story\StoryLibrary;
use Doppel\Support\Auth;
use Doppel\Support\View;

final class ProfileController
{
    public function show(): void
    {
        $user = Auth::requireUser();
        $rooms = new RoomRepository();
        View::render('profile/show', [
            'user' => $user,
            'characters' => (new CharacterRepository())->forUser((int) $user['id']),
            'rooms' => $rooms->forMaster((int) $user['id']),
            'playing' => $rooms->forPlayer((int) $user['id']),
            'runs' => (new RunRepository())->allFor(Auth::ownerKey()),
            'stories' => StoryLibrary::all(),
        ], 'Meu perfil');
    }

    public function updateName(): void
    {
        $user = Auth::requireUser();
        $name = input('name');
        if (mb_strlen($name) < 2 || mb_strlen($name) > 60) {
            flash('error', 'Informe um nick entre 2 e 60 caracteres.');
        } else {
            (new UserRepository())->updateName((int) $user['id'], $name);
            flash('success', 'Nick atualizado.');
        }
        redirect('/perfil');
    }

    public function updatePassword(): void
    {
        $user = Auth::requireUser();
        $repo = new UserRepository();
        $password = (string) ($_POST['password'] ?? '');

        $error = match (true) {
            !$repo->verifyPassword((int) $user['id'], (string) ($_POST['current_password'] ?? '')) => 'A senha atual está incorreta.',
            strlen($password) < 8 => 'A nova senha precisa de pelo menos 8 caracteres.',
            $password !== (string) ($_POST['password_confirm'] ?? '') => 'As senhas não conferem.',
            default => null,
        };
        if ($error !== null) {
            flash('error', $error);
            redirect('/perfil');
        }

        $repo->updatePassword((int) $user['id'], $password);
        Auth::login((int) $user['id']); // nova sessão; a pessoa continua conectada
        flash('success', 'Senha alterada.');
        redirect('/perfil');
    }
}
