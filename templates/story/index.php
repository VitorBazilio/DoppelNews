<?php /** @var array $stories @var array $runs */
$endingLabel = ['victory' => 'Vitória', 'survived' => 'Sobreviveu', 'defeat' => 'Morreu'];
?>
<section class="page-head">
    <h1>Aventuras</h1>
    <p>Livros-jogo de RPG em momentos históricos. Você faz as escolhas, os dados decidem o resto. O progresso é salvo automaticamente<?= Doppel\Support\Auth::user() ? '' : ' (crie uma conta para jogar em qualquer dispositivo)' ?>.</p>
</section>

<div class="story-grid">
    <?php foreach ($stories as $id => $story): $run = $runs[$id] ?? null; ?>
        <article class="card story-card">
            <div class="card-body">
                <p class="meta"><span class="tag"><?= e($story['era']) ?></span> <?= e($story['duration']) ?></p>
                <h2><a href="/aventuras/<?= e($id) ?>"><?= e($story['title']) ?></a></h2>
                <p class="subtitle"><?= e($story['subtitle']) ?></p>
                <p><?= e($story['summary']) ?></p>
                <p>
                    <a class="btn" href="/aventuras/<?= e($id) ?>">
                        <?= $run === null ? 'Começar' : ($run['finished'] ? 'Ver final' : 'Continuar') ?>
                    </a>
                    <?php if ($run && $run['finished']): ?>
                        <span class="badge badge-<?= e($run['ending']) ?>"><?= e($endingLabel[$run['ending']] ?? '') ?></span>
                    <?php endif; ?>
                </p>
            </div>
        </article>
    <?php endforeach; ?>
</div>
