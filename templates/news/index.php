<?php
/** @var ?string $category @var array $result @var array $stories */
$stories = array_values($stories);
$featured = $stories[0] ?? null;
$base = $category ? '/?categoria=' . urlencode($category) . '&' : '/?';
?>
<?php if ($featured && $category === null && $result['page'] === 1): ?>
    <section class="hero">
        <p class="eyebrow">Novo · Livro-jogo</p>
        <h1>Decida o rumo da história.</h1>
        <p>Aventuras de RPG de mesa em momentos históricos, da Roma antiga às trincheiras da Primeira Guerra. Cada escolha tem consequências.</p>
        <a class="btn" href="/aventuras">Jogar agora</a>
    </section>
<?php endif; ?>

<nav class="chips" aria-label="Categorias">
    <a href="/" <?= $category === null ? 'aria-current="true"' : '' ?>>Tudo</a>
    <?php foreach (NEWS_CATEGORIES as $name): ?>
        <a href="/?categoria=<?= urlencode($name) ?>" <?= $category === $name ? 'aria-current="true"' : '' ?>><?= e($name) ?></a>
    <?php endforeach; ?>
</nav>

<?php if (!$result['items']): ?>
    <p class="empty">Nenhuma notícia nesta categoria ainda.</p>
<?php endif; ?>

<div class="news-grid">
    <?php foreach ($result['items'] as $i => $item): $img = news_image($item['image']); ?>
        <article class="card<?= $featured && $category === null && $result['page'] === 1 && $i === 0 ? ' card-featured' : '' ?>">
            <a class="card-media" href="/noticias/<?= (int) $item['id'] ?>" tabindex="-1" aria-hidden="true">
                <?php if ($img): ?>
                    <img src="<?= e($img) ?>" alt="" loading="lazy" width="640" height="360">
                <?php else: ?>
                    <div class="media-fallback"><?= e(mb_substr($item['category'], 0, 1)) ?></div>
                <?php endif; ?>
            </a>
            <div class="card-body">
                <p class="meta"><span class="tag"><?= e($item['category']) ?></span> <?= e(time_ago($item['published_at'])) ?></p>
                <h2><a href="/noticias/<?= (int) $item['id'] ?>"><?= e($item['title']) ?></a></h2>
                <p><?= e($item['summary']) ?></p>
            </div>
        </article>
    <?php endforeach; ?>
</div>

<?php if ($result['pages'] > 1): ?>
    <nav class="pager" aria-label="Paginação">
        <?php if ($result['page'] > 1): ?><a href="<?= e($base) ?>pg=<?= $result['page'] - 1 ?>">← Anterior</a><?php endif; ?>
        <span>Página <?= $result['page'] ?> de <?= $result['pages'] ?></span>
        <?php if ($result['page'] < $result['pages']): ?><a href="<?= e($base) ?>pg=<?= $result['page'] + 1 ?>">Próxima →</a><?php endif; ?>
    </nav>
<?php endif; ?>
