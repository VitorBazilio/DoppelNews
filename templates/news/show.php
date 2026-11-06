<?php
/** @var array $article @var array $recent @var array $related */
$img = media_url($article['image']);
$shareUrl = rawurlencode(absolute_url($_SERVER['REQUEST_URI'] ?? '/'));
$shareText = rawurlencode($article['title']);
?>
<?php if ($img): ?>
    <div class="article-cover"><img src="<?= e($img) ?>" alt="" fetchpriority="high"></div>
<?php endif; ?>

<div class="container">
    <div class="panel article-layout<?= $img ? ' has-cover' : '' ?>">
        <article class="article">
            <div class="article-tag-row">
                <a class="tag-button" href="<?= url('/?categoria=' . urlencode($article['category'])) ?>"><?= e($article['category']) ?></a>
            </div>
            <h1 class="article-title"><?= e($article['title']) ?></h1>
            <p class="article-lead"><?= e($article['summary']) ?></p>
            <p class="article-date"><time datetime="<?= e($article['published_at']) ?>"><?= e(date_long($article['published_at'])) ?></time></p>

            <div class="prose"><?= paragraphs($article['body']) ?></div>

            <footer class="article-footer">
                <p class="article-author">Publicado por <strong><?= e($article['author']) ?></strong></p>
                <div class="share">
                    <p>Compartilhe</p>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>" target="_blank" rel="noopener" aria-label="Compartilhar no Facebook"><?= icon('facebook') ?></a>
                    <a href="https://twitter.com/intent/tweet?url=<?= $shareUrl ?>&amp;text=<?= $shareText ?>" target="_blank" rel="noopener" aria-label="Compartilhar no Twitter"><?= icon('twitter') ?></a>
                    <a href="mailto:?subject=<?= $shareText ?>&amp;body=<?= $shareUrl ?>" aria-label="Enviar por e-mail"><?= icon('mail') ?></a>
                </div>
            </footer>

            <?php if ($related): ?>
                <aside class="related">
                    <h2>Mais de <?= e($article['category']) ?></h2>
                    <ul>
                        <?php foreach ($related as $item): ?>
                            <li><a href="<?= url('/noticias/' . (int) $item['id']) ?>"><?= e($item['title']) ?></a> <span class="muted"><?= e(time_ago($item['published_at'])) ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                </aside>
            <?php endif; ?>
        </article>

        <aside class="recent-posts">
            <h2>Posts recentes</h2>
            <?php foreach ($recent as $item): $thumb = media_url($item['image']); ?>
                <a class="recent-post" href="<?= url('/noticias/' . (int) $item['id']) ?>">
                    <?php if ($thumb): ?><img src="<?= e($thumb) ?>" alt="" loading="lazy"><?php endif; ?>
                    <span class="recent-post-title"><?= e($item['title']) ?></span>
                </a>
            <?php endforeach; ?>
        </aside>
    </div>
</div>
