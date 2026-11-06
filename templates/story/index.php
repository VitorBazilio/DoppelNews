<?php
/** @var array $stories @var array $runs */
$endingLabel = ['victory' => 'Vitória', 'survived' => 'Sobreviveu', 'defeat' => 'Morreu'];
?>
<?= Doppel\Support\View::partial('partials/page-hero', [
    'title' => 'Aventuras',
    'subtitle' => 'Livros-jogo de RPG em momentos históricos. Você faz as escolhas, os dados decidem o resto.',
    'image' => 'banner-dnd.webp',
    'accent' => 'purple',
]) ?>

<div class="container">
    <section class="panel accent-purple">
        <p class="muted">O progresso é salvo automaticamente<?= Doppel\Support\Auth::user() ? '' : ' (entre na sua conta para continuar de qualquer dispositivo)' ?>.</p>
        <div class="card-grid">
            <?php foreach ($stories as $id => $story): $run = $runs[$id] ?? null; ?>
                <article class="story-card">
                    <div class="story-banner" aria-hidden="true"><span><?= abs((int) $story['year']) ?></span><small><?= $story['year'] < 0 ? 'a.C.' : 'd.C.' ?></small></div>
                    <div class="story-body">
                        <p class="news-meta"><span class="news-tag"><?= e($story['era']) ?></span> <?= e($story['duration']) ?></p>
                        <h2><a href="<?= url('/aventuras/' . e($id)) ?>"><?= e($story['title']) ?></a></h2>
                        <p class="story-subtitle"><?= e($story['subtitle']) ?></p>
                        <p class="muted"><?= e($story['summary']) ?></p>
                        <p class="story-actions">
                            <a class="btn" href="<?= url('/aventuras/' . e($id)) ?>"><?= $run === null ? 'Começar' : ($run['finished'] ? 'Ver final' : 'Continuar') ?></a>
                            <?php if ($run && $run['finished']): ?>
                                <span class="badge badge-<?= e($run['ending']) ?>"><?= e($endingLabel[$run['ending']] ?? '') ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</div>
