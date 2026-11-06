<form class="form" method="post" action="<?= url('/entrar') ?>">
    <?= csrf_field() ?>
    <label>E-mail<input type="email" name="email" value="<?= e(old('email')) ?>" required autocomplete="email"></label>
    <label>Senha<input type="password" name="password" required autocomplete="current-password"></label>
    <button class="btn btn-block" type="submit">Entrar</button>
</form>
