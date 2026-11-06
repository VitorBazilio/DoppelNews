<?php /** @var array $stats @var array $news @var array $rooms */ ?>
<div class="container admin">
    <?= Doppel\Support\View::partial('admin/nav') ?>
    <h1 class="display-title">Painel</h1>

    <div class="stat-cards">
        <?php foreach ($stats as $label => $value): ?>
            <div class="panel stat-card"><strong><?= (int) $value ?></strong><span class="pixel"><?= e($label) ?></span></div>
        <?php endforeach; ?>
    </div>

    <div class="profile-grid">
        <section class="panel">
            <div class="toolbar">
                <h2 class="panel-title">Últimas notícias</h2>
                <a class="btn btn-small" href="<?= url('/admin/noticias/nova') ?>">Criar notícia</a>
            </div>
            <ul class="simple-list">
                <?php foreach ($news as $item): ?>
                    <li><a href="<?= url('/admin/noticias/' . (int) $item['id'] . '/editar') ?>"><?= e($item['title']) ?></a> <small class="muted"><?= e($item['category']) ?> · <?= e(time_ago($item['published_at'])) ?></small></li>
                <?php endforeach; ?>
            </ul>
        </section>
        <section class="panel accent-cyan">
            <h2 class="panel-title">Salas recentes</h2>
            <?php if (!$rooms): ?><p class="muted">Nenhuma sala criada.</p><?php endif; ?>
            <ul class="simple-list">
                <?php foreach ($rooms as $room): ?>
                    <li><a href="<?= url('/salas/' . (int) $room['id']) ?>"><?= e($room['name']) ?></a> <small class="muted">mestre <?= e($room['master_name']) ?> · <?= (int) $room['players'] ?>/<?= (int) $room['max_players'] ?></small></li>
                <?php endforeach; ?>
            </ul>
        </section>
    </div>
</div>
