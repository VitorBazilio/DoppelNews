<?php /** @var array $users @var int[] $protected contas que não podem ser alteradas (você e o primeiro admin) */ ?>
<div class="container admin">
    <?= Doppel\Support\View::partial('admin/nav') ?>
    <h1 class="display-title">Usuários</h1>
    <p class="muted">Você pode editar, desativar ou apagar contas. A sua conta e a do primeiro administrador ficam protegidas.</p>

    <div class="panel table-wrap">
        <table class="table">
            <thead><tr><th>Nick</th><th>E-mail</th><th>Papel</th><th>Status</th><th>Fichas</th><th>Salas</th><th>Desde</th><th><span class="sr-only">Ações</span></th></tr></thead>
            <tbody>
            <?php foreach ($users as $u): $id = (int) $u['id']; $active = (bool) (int) $u['active']; ?>
                <tr class="<?= $active ? '' : 'is-inactive' ?>">
                    <td>
                        <strong><?= e($u['name']) ?></strong>
                        <?php if ($id === Doppel\Support\Auth::id()): ?><span class="badge">você</span>
                        <?php elseif (in_array($id, $protected, true)): ?><span class="badge">primeiro admin</span><?php endif; ?>
                    </td>
                    <td><?= e($u['email']) ?></td>
                    <td><?= $u['role'] === 'admin' ? 'Administrador' : 'Usuário' ?></td>
                    <td><span class="badge <?= $active ? 'badge-open' : 'badge-full' ?>"><?= $active ? 'Ativa' : 'Desativada' ?></span></td>
                    <td><?= (int) $u['characters'] ?></td>
                    <td><?= (int) $u['rooms'] ?></td>
                    <td><?= e(date('d/m/Y', strtotime($u['created_at']))) ?></td>
                    <td class="actions">
                        <?php if (!in_array($id, $protected, true)): ?>
                            <a href="<?= url("/admin/usuarios/$id/editar") ?>">Editar</a>
                            <form method="post" action="<?= url("/admin/usuarios/$id/status") ?>"
                                  data-confirm="<?= $active ? 'Desativar' : 'Reativar' ?> a conta de <?= e($u['name']) ?>?">
                                <?= csrf_field() ?><button class="link-button" type="submit"><?= $active ? 'Desativar' : 'Reativar' ?></button>
                            </form>
                            <form method="post" action="<?= url("/admin/usuarios/$id/excluir") ?>"
                                  data-confirm="Apagar a conta de <?= e($u['name']) ?>, com suas salas e personagens? Isso não pode ser desfeito.">
                                <?= csrf_field() ?><button class="link-button danger" type="submit">Apagar</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
