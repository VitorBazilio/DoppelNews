<?php /** @var array $users @var int $me */ ?>
<div class="container admin">
    <?= Doppel\Support\View::partial('admin/nav') ?>
    <h1 class="display-title">Usuários</h1>

    <div class="panel table-wrap">
        <table class="table">
            <thead><tr><th>Nick</th><th>E-mail</th><th>Papel</th><th>Personagens</th><th>Salas</th><th>Desde</th><th><span class="sr-only">Ações</span></th></tr></thead>
            <tbody>
            <?php foreach ($users as $u): $isMe = (int) $u['id'] === $me; ?>
                <tr>
                    <td><strong><?= e($u['name']) ?></strong><?= $isMe ? ' <span class="badge">você</span>' : '' ?></td>
                    <td><?= e($u['email']) ?></td>
                    <td><?= $u['role'] === 'admin' ? 'Administrador' : 'Usuário' ?></td>
                    <td><?= (int) $u['characters'] ?></td>
                    <td><?= (int) $u['rooms'] ?></td>
                    <td><?= e(date('d/m/Y', strtotime($u['created_at']))) ?></td>
                    <td class="actions">
                        <?php if (!$isMe): ?>
                            <form method="post" action="<?= url('/admin/usuarios/' . (int) $u['id'] . '/papel') ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="role" value="<?= $u['role'] === 'admin' ? 'user' : 'admin' ?>">
                                <button class="link-button" type="submit"><?= $u['role'] === 'admin' ? 'Tornar usuário' : 'Tornar admin' ?></button>
                            </form>
                            <form method="post" action="<?= url('/admin/usuarios/' . (int) $u['id'] . '/excluir') ?>" data-confirm="Excluir a conta de <?= e($u['name']) ?>, com suas salas e personagens?">
                                <?= csrf_field() ?><button class="link-button danger" type="submit">Excluir</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
