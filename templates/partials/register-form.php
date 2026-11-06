<form class="form" method="post" action="<?= url('/cadastrar') ?>" data-confirm-password>
    <?= csrf_field() ?>
    <label>Nick<input name="name" value="<?= e(old('name')) ?>" required minlength="2" maxlength="60" autocomplete="nickname"></label>
    <label>E-mail<input type="email" name="email" value="<?= e(old('email')) ?>" required autocomplete="email"></label>
    <label>Senha <small>(mínimo 8 caracteres)</small><input type="password" name="password" required minlength="8" autocomplete="new-password"></label>
    <label>Confirmar senha<input type="password" name="password_confirm" required minlength="8" autocomplete="new-password"></label>
    <button class="btn btn-block" type="submit">Criar conta</button>
</form>
