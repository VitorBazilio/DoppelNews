<?php

declare(strict_types=1);

namespace Doppel\Controller\Admin;

use Doppel\Repository\UserRepository;
use Doppel\Support\Auth;
use Doppel\Support\View;

/**
 * Gestão de contas: editar, ativar/desativar e apagar usuários.
 * Ninguém altera a própria conta por aqui nem a do primeiro administrador (evita ficar sem acesso).
 */
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
        View::render('admin/users/index', [
            'users' => $this->users->allWithStats(),
            'protected' => $this->protectedIds(),
        ], 'Usuários');
    }

    public function edit(string $id): void
    {
        View::render('admin/users/form', ['account' => $this->target($id)], 'Editar usuário');
    }

    public function update(string $id): void
    {
        $account = $this->target($id);
        $name = input('name');
        $email = input('email');
        $role = input('role') === 'admin' ? 'admin' : 'user';
        $back = "/admin/usuarios/{$account['id']}/editar";

        $error = match (true) {
            mb_strlen($name) < 2 || mb_strlen($name) > 60 => 'O nick deve ter entre 2 e 60 caracteres.',
            !filter_var($email, FILTER_VALIDATE_EMAIL) => 'E-mail inválido.',
            $this->users->emailTaken($email, (int) $account['id']) => 'Este e-mail já é usado por outra conta.',
            default => null,
        };
        if ($error !== null) {
            remember_input(['name' => $name, 'email' => $email, 'role' => $role]);
            flash('error', $error);
            redirect($back);
        }

        $this->users->updateAccount((int) $account['id'], $name, $email, $role);
        flash('success', "Conta de $name atualizada.");
        redirect('/admin/usuarios');
    }

    /** Desativar impede o login e encerra o acesso na próxima página que a pessoa abrir. */
    public function status(string $id): void
    {
        $account = $this->target($id);
        $activate = !(int) $account['active'];
        $this->users->setActive((int) $account['id'], $activate);
        flash('success', "Conta de {$account['name']} " . ($activate ? 'reativada.' : 'desativada.'));
        redirect('/admin/usuarios');
    }

    public function destroy(string $id): void
    {
        $account = $this->target($id);
        $this->users->delete((int) $account['id']);
        flash('success', "Conta de {$account['name']} apagada, junto com suas salas e personagens.");
        redirect('/admin/usuarios');
    }

    /** Contas que não podem ser alteradas: a do próprio admin e a do primeiro administrador. */
    private function protectedIds(): array
    {
        return array_values(array_unique(array_filter([(int) $this->admin['id'], $this->users->firstAdminId()])));
    }

    private function target(string $id): array
    {
        $account = $this->users->find((int) $id) ?? View::notFound();
        if (in_array((int) $account['id'], $this->protectedIds(), true)) {
            flash('error', (int) $account['id'] === (int) $this->admin['id']
                ? 'Você não pode alterar a sua própria conta pelo painel. Use a página Meu perfil.'
                : 'O primeiro administrador do site é protegido e não pode ser alterado.');
            redirect('/admin/usuarios');
        }
        return $account;
    }
}
