<section class="container narrow">
    <div class="panel form-card">
        <h1 class="panel-title">Entrar</h1>
        <?= Doppel\Support\View::partial('partials/login-form') ?>
        <p class="muted">Ainda não tem conta? <a href="<?= url('/cadastrar') ?>">Cadastre-se</a>.</p>
    </div>
</section>
