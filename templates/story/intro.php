<?php /** @var array $story */ ?>
<section class="container narrow-wide">
    <div class="panel accent-purple story-intro">
        <p class="eyebrow pixel"><?= e($story['era']) ?></p>
        <h1 class="room-title"><?= e($story['title']) ?></h1>
        <p class="article-lead"><?= e($story['summary']) ?></p>

        <h2>Seu personagem</h2>
        <ul class="stat-list">
            <?php foreach ($story['stats'] as $stat): ?>
                <li><strong><?= e($stat['label']) ?></strong> <?= (int) $stat['start'] ?><?= isset($stat['max']) ? ' / ' . (int) $stat['max'] : '' ?></li>
            <?php endforeach; ?>
        </ul>
        <p class="muted">Testes de risco usam 2d6 + seu atributo contra uma dificuldade. Se a Vida chegar a zero, a aventura termina.</p>

        <form class="form-actions" method="post" action="<?= url('/aventuras/' . e($story['id']) . '/iniciar') ?>">
            <?= csrf_field() ?>
            <button class="btn" type="submit">Começar aventura</button>
            <a class="btn btn-ghost" href="<?= url('/aventuras') ?>">Voltar</a>
        </form>
    </div>
</section>
