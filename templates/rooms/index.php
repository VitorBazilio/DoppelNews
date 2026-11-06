<?php
/** @var array $rooms @var string $order */
use Doppel\Repository\RoomRepository;
?>
<?= Doppel\Support\View::partial('partials/page-hero', [
    'title' => 'Salas',
    'subtitle' => 'Mesas de RPG abertas. Entre com um dos seus personagens e role os dados com o grupo.',
    'image' => 'banner-dnd.webp',
    'accent' => 'cyan',
]) ?>

<div class="container">
    <section class="panel accent-cyan">
        <div class="toolbar">
            <nav class="category-menu pixel" aria-label="Ordenar salas">
                <?php foreach (RoomRepository::ORDERS as $key => $label): ?>
                    <a href="<?= url('/salas?ordem=' . $key) ?>" <?= $order === $key ? 'aria-current="true"' : '' ?>><?= $label ?></a>
                <?php endforeach; ?>
                <a href="<?= url('/salas/sorte') ?>">Sorte</a>
            </nav>
            <a class="btn" href="<?= url('/salas/nova') ?>">Criar sala</a>
        </div>

        <?php if (!$rooms): ?>
            <p class="empty">Nenhuma sala criada ainda. Seja o primeiro mestre!</p>
        <?php else: ?>
            <div class="card-grid">
                <?php foreach ($rooms as $room): $full = (int) $room['players'] >= (int) $room['max_players']; ?>
                    <article class="room-card">
                        <header>
                            <h2><a href="<?= url('/salas/' . (int) $room['id']) ?>"><?= e($room['name']) ?></a></h2>
                            <span class="badge <?= $full ? 'badge-full' : 'badge-open' ?>"><?= $full ? 'Cheia' : 'Aberta' ?></span>
                        </header>
                        <?php if ($room['description'] !== ''): ?><p class="muted"><?= e(excerpt($room['description'], 140)) ?></p><?php endif; ?>
                        <dl class="room-stats">
                            <div><dt>Mestre</dt><dd><?= e($room['master_name']) ?></dd></div>
                            <div><dt>Jogadores</dt><dd><?= (int) $room['players'] ?>/<?= (int) $room['max_players'] ?></dd></div>
                            <div><dt>Atividade</dt><dd><?= e(time_ago($room['last_activity'] ?? $room['created_at'])) ?></dd></div>
                        </dl>
                        <a class="btn btn-outline btn-block" href="<?= url('/salas/' . (int) $room['id']) ?>"><?= $full ? 'Ver sala' : 'Entrar' ?></a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>
