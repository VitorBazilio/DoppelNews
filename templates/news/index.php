<?php
/** @var ?string $category @var string $search @var array $result @var array $featured @var array $stories */
?>
<?php if ($featured): ?>
    <section class="carousel" aria-roledescription="carrossel" aria-label="Destaques" data-carousel>
        <?php foreach ($featured as $i => $item): ?>
            <article class="carousel-slide<?= $i === 0 ? ' is-active' : '' ?>" aria-roledescription="slide" aria-label="<?= $i + 1 ?> de <?= count($featured) ?>" <?= $i === 0 ? '' : 'hidden' ?>>
                <img src="<?= e(media_url($item['image'])) ?>" alt="" <?= $i === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>>
                <div class="carousel-caption">
                    <a class="carousel-title" href="<?= url('/noticias/' . (int) $item['id']) ?>"><?= e($item['title']) ?></a>
                    <p class="carousel-text"><?= e($item['summary']) ?></p>
                </div>
            </article>
        <?php endforeach; ?>
        <?php if (count($featured) > 1): ?>
            <button class="carousel-next pixel" type="button" data-carousel-next>Next Game</button>
            <div class="carousel-dots" role="tablist" aria-label="Escolher destaque">
                <?php foreach ($featured as $i => $item): ?>
                    <button type="button" role="tab" aria-label="Destaque <?= $i + 1 ?>" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>" data-carousel-dot="<?= $i ?>"></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>

<div class="container">
    <section class="panel news-panel" id="noticias">
        <header class="section-head">
            <h1 class="display-title"><?= $category ? e($category) : 'Conteúdo Geek' ?></h1>
            <p class="pixel muted">Tudo sobre games, animes, HQs, RPG e muito mais.</p>
        </header>

        <nav class="category-menu pixel" aria-label="Editorias">
            <a href="<?= url('/') ?>#noticias" <?= $category === null ? 'aria-current="true"' : '' ?>>Tudo</a>
            <?php foreach (NEWS_CATEGORIES as $name): ?>
                <a href="<?= url('/?categoria=' . urlencode($name)) ?>#noticias" <?= $category === $name ? 'aria-current="true"' : '' ?>><?= e($name) ?></a>
            <?php endforeach; ?>
        </nav>

        <form class="search" method="get" action="<?= url('/') ?>#noticias" role="search">
            <?php if ($category): ?><input type="hidden" name="categoria" value="<?= e($category) ?>"><?php endif; ?>
            <label class="sr-only" for="busca">Buscar notícias</label>
            <input id="busca" type="search" name="busca" value="<?= e($search) ?>" placeholder="Buscar notícias…" maxlength="80">
            <button class="btn btn-small" type="submit">Buscar</button>
        </form>

        <?php if ($search !== ''): ?>
            <p class="muted search-summary"><?= $result['total'] ?> resultado<?= $result['total'] === 1 ? '' : 's' ?> para “<?= e($search) ?>”. <a href="<?= url('/' . ($category ? '?categoria=' . urlencode($category) : '')) ?>">Limpar busca</a></p>
        <?php endif; ?>

        <?php if (!$result['items']): ?>
            <p class="empty">Nenhuma notícia encontrada<?= $category ? ' em ' . e($category) : '' ?>.</p>
        <?php endif; ?>

        <div class="news-list">
            <?php foreach ($result['items'] as $item): $img = media_url($item['image']); $link = url('/noticias/' . (int) $item['id']); ?>
                <article class="news-item">
                    <a class="news-thumb" href="<?= $link ?>" tabindex="-1" aria-hidden="true">
                        <?php if ($img): ?>
                            <img src="<?= e($img) ?>" alt="" loading="lazy" width="480" height="270">
                        <?php else: ?>
                            <span class="thumb-fallback"><?= icon('news') ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="news-body">
                        <p class="news-meta">
                            <a class="news-tag" href="<?= url('/?categoria=' . urlencode($item['category'])) ?>"><?= e($item['category']) ?></a>
                            <time datetime="<?= e($item['published_at']) ?>"><?= e(time_ago($item['published_at'])) ?></time>
                        </p>
                        <h2 class="news-title"><a href="<?= $link ?>"><?= e($item['title']) ?></a></h2>
                        <p class="news-summary"><?= e($item['summary']) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <?= Doppel\Support\View::partial('partials/pager', ['page' => $result['page'], 'pages' => $result['pages']]) ?>
    </section>

    <?php if ($stories): ?>
        <section class="promo">
            <div class="promo-text">
                <p class="eyebrow pixel">Novo · Livro-jogo</p>
                <h2>Decida o rumo da história.</h2>
                <p>Aventuras de RPG em momentos históricos, da Roma antiga às trincheiras da Primeira Guerra. Cada escolha tem consequências, e os dados decidem o resto.</p>
                <a class="btn" href="<?= url('/aventuras') ?>">Jogar agora</a>
            </div>
            <ul class="promo-list">
                <?php foreach ($stories as $id => $story): ?>
                    <li><a href="<?= url('/aventuras/' . e($id)) ?>"><strong><?= e($story['title']) ?></strong><span><?= e($story['era']) ?></span></a></li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>
</div>
