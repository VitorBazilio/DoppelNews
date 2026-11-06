<?php /** @var string $email */ ?>
<section class="form-card">
    <h1>Entrar</h1>
    <form method="post" action="/entrar">
        <?= csrf_field() ?>
        <label>E-mail<input type="email" name="email" value="<?= e($email) ?>" required autocomplete="email" autofocus></label>
        <label>Senha<input type="password" name="password" required autocomplete="current-password"></label>
        <button class="btn">Entrar</button>
    </form>
    <p class="muted">Ainda não tem conta? <a href="/cadastrar">Cadastre-se</a>.</p>
</section>
