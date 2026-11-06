<?php /** @var array $old */ ?>
<section class="form-card">
    <h1>Criar conta</h1>
    <form method="post" action="/cadastrar">
        <?= csrf_field() ?>
        <label>Apelido<input name="name" value="<?= e($old['name']) ?>" required minlength="2" maxlength="60" autocomplete="nickname" autofocus></label>
        <label>E-mail<input type="email" name="email" value="<?= e($old['email']) ?>" required autocomplete="email"></label>
        <label>Senha <small>(mínimo 8 caracteres)</small><input type="password" name="password" required minlength="8" autocomplete="new-password"></label>
        <label>Confirmar senha<input type="password" name="password_confirm" required minlength="8" autocomplete="new-password"></label>
        <button class="btn">Criar conta</button>
    </form>
    <p class="muted">Já tem conta? <a href="/entrar">Entrar</a>.</p>
</section>
