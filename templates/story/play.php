<?php
/** @var array $story @var array $state @var array $node @var array $choices @var ?string $ending */
$endingTitle = ['victory' => 'Vitória', 'survived' => 'Você sobreviveu', 'defeat' => 'Fim de jogo'];
$base = '/aventuras/' . $story['id'];
?>
<div class="container play-layout">
    <section class="panel accent-purple scene">
        <?php if (!empty($node['date'])): ?><p class="eyebrow pixel"><?= e($node['date']) ?></p><?php endif; ?>
        <h1 class="room-title"><?= e($node['title']) ?></h1>

        <?php if ($state['notice']): ?>
            <p class="notice"><?= icon('dice') ?> <?= e($state['notice']) ?></p>
        <?php endif; ?>

        <div class="prose narrative">
            <?php foreach ($node['text'] as $paragraph): ?><p><?= e($paragraph) ?></p><?php endforeach; ?>
        </div>

        <?php if ($ending): ?>
            <div class="ending ending-<?= e($ending) ?>">
                <h2><?= e($endingTitle[$ending]) ?></h2>
                <p>Você chegou ao fim em <?= (int) $state['steps'] ?> decisões.</p>
                <form class="form-actions" method="post" action="<?= url($base . '/reiniciar') ?>">
                    <?= csrf_field() ?>
                    <button class="btn" type="submit">Jogar de novo</button>
                    <a class="btn btn-ghost" href="<?= url('/aventuras') ?>">Outras aventuras</a>
                </form>
            </div>
        <?php else: ?>
            <form method="post" action="<?= url($base . '/escolher') ?>" class="choices">
                <?= csrf_field() ?>
                <?php foreach ($choices as $choice): ?>
                    <button class="choice" name="choice" value="<?= (int) $choice['index'] ?>" <?= $choice['available'] ? '' : 'disabled' ?>>
                        <span><?= e($choice['text']) ?></span>
                        <?php if ($choice['risky']): ?><small class="risk pixel">teste de dados</small><?php endif; ?>
                        <?php if (!$choice['available']): ?><small><?= e($choice['reason']) ?></small><?php endif; ?>
                    </button>
                <?php endforeach; ?>
            </form>
        <?php endif; ?>
    </section>

    <aside class="panel sheet accent-purple" aria-label="Ficha do personagem">
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
            <form method="post" action="<?= url($base . '/reiniciar') ?>" data-confirm="Recomeçar e perder o progresso?">
                <?= csrf_field() ?><button class="link-button" type="submit">Recomeçar</button>
            </form>
        <?php endif; ?>
    </aside>
</div>
