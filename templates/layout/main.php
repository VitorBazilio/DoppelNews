<?php
/** @var string $content @var string $title @var ?array $user @var array $flashes */
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$nav = [['/', 'Notícias'], ['/aventuras', 'Aventuras']];
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> · Doppel</title>
    <meta name="description" content="Notícias geek e aventuras de RPG em livro-jogo ambientadas em momentos históricos.">
    <link rel="icon" href="<?= e(asset('img/top.ico')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="/">Doppel<span>News</span></a>
        <nav class="main-nav" aria-label="Principal">
            <?php foreach ($nav as [$href, $label]): ?>
                <a href="<?= $href ?>" <?= ($href === '/' ? $path === '/' || str_starts_with($path, '/noticias') : str_starts_with($path, $href)) ? 'aria-current="page"' : '' ?>><?= $label ?></a>
            <?php endforeach; ?>
        </nav>
        <div class="account">
            <?php if ($user): ?>
                <?php if ($user['role'] === 'admin'): ?><a href="/admin">Painel</a><?php endif; ?>
                <span class="muted">Olá, <?= e($user['name']) ?></span>
                <form method="post" action="/sair"><?= csrf_field() ?><button class="link-button">Sair</button></form>
            <?php else: ?>
                <a href="/entrar">Entrar</a>
                <a class="btn btn-small" href="/cadastrar">Criar conta</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="container">
    <?php foreach ($flashes as $flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div>
    <?php endforeach; ?>
    <?= $content ?>
</main>

<footer class="site-footer">
    <div class="container footer-inner">
        <div>
            <a class="brand" href="/">Doppel<span>News</span></a>
            <p class="footer-credit">Nosso primeiro projeto, criado pelos programadores <strong>Vitor</strong> e <strong>Felipe</strong> na época do TCC do ensino médio.</p>
        </div>
        <p class="muted small">© <?= date('Y') ?> Doppel. Imagens de jogos e filmes pertencem aos seus respectivos proprietários.</p>
    </div>
</footer>
</body>
</html>
