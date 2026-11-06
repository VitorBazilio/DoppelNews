<?php /** @var array $story */ ?>
<section class="story-intro">
    <p class="eyebrow"><?= e($story['era']) ?></p>
    <h1><?= e($story['title']) ?></h1>
    <p class="lead"><?= e($story['summary']) ?></p>

    <h2>Seu personagem</h2>
    <ul class="stat-list">
        <?php foreach ($story['stats'] as $stat): ?>
            <li><strong><?= e($stat['label']) ?></strong> <?= (int) $stat['start'] ?><?= isset($stat['max']) ? ' / ' . (int) $stat['max'] : '' ?></li>
        <?php endforeach; ?>
    </ul>
    <p class="muted">Testes de risco usam 2d6 + seu atributo contra uma dificuldade. Se a Vida chegar a zero, a aventura termina.</p>

    <form method="post" action="/aventuras/<?= e($story['id']) ?>/iniciar">
        <?= csrf_field() ?>
        <button class="btn">Começar aventura</button>
        <a class="btn btn-ghost" href="/aventuras">Voltar</a>
    </form>
</section>
