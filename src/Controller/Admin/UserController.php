<?php

declare(strict_types=1);

namespace Doppel\Controller\Admin;

use Doppel\Repository\UserRepository;
use Doppel\Support\Auth;
use Doppel\Support\View;

/** Gestão de contas: promover/rebaixar administradores e excluir usuários. */
final class UserController
{
    private UserRepository $users;
    private array $admin;

    public function __construct()
    {
        $this->admin = Auth::requireAdmin();
        $this->users = new UserRepository();
    }

    public function index(): void
    {
        View::render('admin/users', ['users' => $this->users->allWithStats(), 'me' => (int) $this->admin['id']], 'Usuários');
    }

    public function role(string $id): void
    {
        $user = $this->target($id);
        $role = input('role') === 'admin' ? 'admin' : 'user';
        $this->users->setRole((int) $user['id'], $role);
        flash('success', $user['name'] . ($role === 'admin' ? ' agora é administrador.' : ' agora é usuário comum.'));
        redirect('/admin/usuarios');
    }

    public function destroy(string $id): void
    {
        $user = $this->target($id);
        $this->users->delete((int) $user['id']);
        flash('success', 'Conta de ' . $user['name'] . ' excluída, junto com suas salas e personagens.');
        redirect('/admin/usuarios');
    }

    /** O admin não pode alterar a própria conta por aqui (evita ficar sem nenhum administrador). */
    private function target(string $id): array
    {
        $user = $this->users->find((int) $id) ?? View::notFound();
        if ((int) $user['id'] === (int) $this->admin['id']) {
            flash('error', 'Você não pode alterar a sua própria conta pelo painel.');
            redirect('/admin/usuarios');
        }
        return $user;
    }
}
