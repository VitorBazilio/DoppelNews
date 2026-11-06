<?php
/** @var array $story @var array $state @var array $node @var array $choices @var ?string $ending */
$endingTitle = ['victory' => 'Vitória', 'survived' => 'Você sobreviveu', 'defeat' => 'Fim de jogo'];
?>
<div class="play-layout">
    <section class="scene">
        <?php if (!empty($node['date'])): ?><p class="eyebrow"><?= e($node['date']) ?></p><?php endif; ?>
        <h1><?= e($node['title']) ?></h1>

        <?php if ($state['notice']): ?>
            <p class="notice"><?= e($state['notice']) ?></p>
        <?php endif; ?>

        <div class="prose narrative">
            <?php foreach ($node['text'] as $paragraph): ?><p><?= e($paragraph) ?></p><?php endforeach; ?>
        </div>

        <?php if ($ending): ?>
            <div class="ending ending-<?= e($ending) ?>">
                <h2><?= e($endingTitle[$ending]) ?></h2>
                <p>Você chegou ao fim em <?= (int) $state['steps'] ?> decisões.</p>
                <form method="post" action="/aventuras/<?= e($story['id']) ?>/reiniciar">
                    <?= csrf_field() ?>
                    <button class="btn">Jogar de novo</button>
                    <a class="btn btn-ghost" href="/aventuras">Outras aventuras</a>
                </form>
            </div>
        <?php else: ?>
            <form method="post" action="/aventuras/<?= e($story['id']) ?>/escolher" class="choices">
                <?= csrf_field() ?>
                <?php foreach ($choices as $choice): ?>
                    <button class="choice" name="choice" value="<?= (int) $choice['index'] ?>" <?= $choice['available'] ? '' : 'disabled' ?>>
                        <span><?= e($choice['text']) ?></span>
                        <?php if ($choice['risky']): ?><small class="risk">🎲 teste</small><?php endif; ?>
                        <?php if (!$choice['available']): ?><small><?= e($choice['reason']) ?></small><?php endif; ?>
                    </button>
                <?php endforeach; ?>
            </form>
        <?php endif; ?>
    </section>

    <aside class="sheet" aria-label="Ficha do personagem">
        <h2>Ficha</h2>
        <?php foreach ($story['stats'] as $key => $def): $value = $state['stats'][$key]; $max = $def['max'] ?? max($value, 1); ?>
            <div class="stat">
                <div class="stat-row"><span><?= e($def['label']) ?></span><strong><?= $value ?></strong></div>
                <div class="bar"><div class="bar-fill" style="width: <?= min(100, (int) round($value / $max * 100)) ?>%"></div></div>
            </div>
        <?php endforeach; ?>
        <h3>Inventário</h3>
        <?php if ($state['items']): ?>
            <ul><?php foreach ($state['items'] as $item): ?><li><?= e($item) ?></li><?php endforeach; ?></ul>
        <?php else: ?>
            <p class="muted">Vazio.</p>
        <?php endif; ?>
        <?php if (!$ending): ?>
            <form method="post" action="/aventuras/<?= e($story['id']) ?>/reiniciar" onsubmit="return confirm('Recomeçar e perder o progresso?')">
                <?= csrf_field() ?>
                <button class="link-button">Recomeçar</button>
            </form>
        <?php endif; ?>
    </aside>
</div>
