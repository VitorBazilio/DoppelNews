<?php /** @var array $account usuário sendo editado */ ?>
<div class="container admin">
    <?= Doppel\Support\View::partial('admin/nav') ?>
    <div class="panel form-card">
        <h1 class="panel-title">Editar usuário</h1>
        <p class="muted">Conta criada em <?= e(date_long($account['created_at'], false)) ?> · <?= (int) $account['active'] ? 'ativa' : 'desativada' ?></p>
        <form class="form" method="post" action="<?= url('/admin/usuarios/' . (int) $account['id']) ?>">
            <?= csrf_field() ?>
            <label>Nick<input name="name" value="<?= e(old('name', $account['name'])) ?>" required minlength="2" maxlength="60"></label>
            <label>E-mail<input type="email" name="email" value="<?= e(old('email', $account['email'])) ?>" required></label>
            <label>Papel
                <select name="role">
                    <?php foreach (['user' => 'Usuário', 'admin' => 'Administrador'] as $value => $label): ?>
                        <option value="<?= $value ?>" <?= old('role', $account['role']) === $value ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <div class="form-actions">
                <button class="btn" type="submit">Salvar</button>
                <a class="btn btn-ghost" href="<?= url('/admin/usuarios') ?>">Cancelar</a>
            </div>
        </form>
    </div>
</div>
