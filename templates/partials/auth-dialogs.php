<?php /* Modais de login e cadastro (abertos pelo app.js; sem JavaScript os links levam às páginas). */ ?>
<dialog class="modal" id="dialog-login" aria-labelledby="dialog-login-title">
    <form method="dialog" class="modal-close-form"><button class="modal-close" aria-label="Fechar">&times;</button></form>
    <h2 class="modal-title" id="dialog-login-title">Entrar</h2>
    <?= Doppel\Support\View::partial('partials/login-form') ?>
    <p class="modal-switch">Ainda não tem conta? <a href="<?= url('/cadastrar') ?>" data-dialog="register">Cadastre-se</a></p>
</dialog>

<dialog class="modal" id="dialog-register" aria-labelledby="dialog-register-title">
    <form method="dialog" class="modal-close-form"><button class="modal-close" aria-label="Fechar">&times;</button></form>
    <h2 class="modal-title" id="dialog-register-title">Cadastrar</h2>
    <?= Doppel\Support\View::partial('partials/register-form') ?>
    <p class="modal-switch">Já tem conta? <a href="<?= url('/entrar') ?>" data-dialog="login">Entrar</a></p>
</dialog>
