<?php /** @var string $message */ ?>
<section class="container narrow error-page">
    <p class="error-code">403</p>
    <h1>Acesso negado</h1>
    <p class="muted"><?= e($message) ?></p>
    <a class="btn" href="<?= url('/') ?>">Voltar ao início</a>
</section>
