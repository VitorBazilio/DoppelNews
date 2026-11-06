<?php
/** @var array $article @var array $related */
$img = news_image($article['image']);
?>
<article class="article">
    <p class="meta"><a class="tag" href="/?categoria=<?= urlencode($article['category']) ?>"><?= e($article['category']) ?></a>
        <time datetime="<?= e($article['published_at']) ?>"><?= e(date('d/m/Y H:i', strtotime($article['published_at']))) ?></time></p>
    <h1><?= e($article['title']) ?></h1>
    <p class="lead"><?= e($article['summary']) ?></p>
    <?php if ($img): ?><img class="article-image" src="<?= e($img) ?>" alt="" width="900" height="500"><?php endif; ?>
    <div class="prose"><?= paragraphs($article['body']) ?></div>
</article>

<?php if ($related): ?>
    <aside class="related">
        <h2>Veja também</h2>
        <ul>
            <?php foreach ($related as $item): ?>
                <li><a href="/noticias/<?= (int) $item['id'] ?>"><?= e($item['title']) ?></a> <span class="muted"><?= e(time_ago($item['published_at'])) ?></span></li>
            <?php endforeach; ?>
        </ul>
    </aside>
<?php endif; ?>
