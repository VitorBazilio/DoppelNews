<?php
/** @var string $content @var string $title @var array $meta @var ?array $user @var array $flashes */
$path = current_path();
$section = match (true) {
    $path === '/' || str_starts_with($path, '/noticias') => 'news',
    str_starts_with($path, '/salas') => 'rooms',
    str_starts_with($path, '/personagens'), str_starts_with($path, '/regras') => 'heroes',
    str_starts_with($path, '/aventuras') => 'stories',
    str_starts_with($path, '/ajuda') => 'help',
    default => '',
};
$nav = [
    'news' => ['/', 'News', 'news'],
    'rooms' => ['/salas', 'Salas', 'axe'],
    'heroes' => ['/personagens', 'Personagens', 'dnd'],
    'stories' => ['/aventuras', 'Aventuras', 'dice'],
    'help' => ['/ajuda', 'Ajuda', 'help'],
];
$description = $meta['description'] ?? 'Notícias geek, salas de RPG de mesa, personagens de D&D e aventuras em livro-jogo.';
$pageTitle = $title === 'DoppelNews' ? 'DoppelNews' : $title . ' · DoppelNews';
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($description) ?>">
    <meta property="og:site_name" content="DoppelNews">
    <meta property="og:title" content="<?= e($title) ?>">
    <meta property="og:description" content="<?= e($description) ?>">
    <meta property="og:type" content="<?= e($meta['type'] ?? 'website') ?>">
    <meta property="og:url" content="<?= e(absolute_url($_SERVER['REQUEST_URI'] ?? '/')) ?>">
    <?php if (!empty($meta['image'])): ?>
        <meta property="og:image" content="<?= e($meta['image']) ?>">
        <meta name="twitter:card" content="summary_large_image">
    <?php endif; ?>
    <?php if (!empty($meta['author'])): ?><meta property="article:author" content="<?= e($meta['author']) ?>"><?php endif; ?>
    <link rel="icon" href="<?= e(asset('img/favicon.ico')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Graduate&family=Orbitron:wght@400;600;700&family=Press+Start+2P&display=swap">
    <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
    <script src="<?= e(asset('js/app.js')) ?>" defer></script>
</head>
<body data-section="<?= e($section) ?>">
<a class="skip-link" href="#conteudo">Pular para o conteúdo</a>

<header class="site-header" id="header">
    <div class="container header-inner">
        <a class="brand" href="<?= url('/') ?>" aria-label="DoppelNews: página inicial">
            <img src="<?= e(asset('img/icon.webp')) ?>" alt="DoppelNews" width="180" height="45">
        </a>

        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="menu" data-nav-toggle>
            <span></span><span></span><span></span><span class="sr-only">Abrir menu</span>
        </button>

        <div class="menu" id="menu">
            <nav class="main-nav" aria-label="Principal">
                <?php foreach ($nav as $key => [$href, $label, $iconName]): ?>
                    <a class="nav-link nav-<?= $key ?>" href="<?= url($href) ?>" <?= $section === $key ? 'aria-current="page"' : '' ?>>
                        <?= icon($iconName) ?><span><?= $label ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="account">
                <?php if ($user): ?>
                    <details class="dropdown">
                        <summary class="pixel">Olá! <?= e($user['name']) ?></summary>
                        <div class="dropdown-menu">
                            <a href="<?= url('/perfil') ?>">Meu perfil</a>
                            <a href="<?= url('/personagens?aba=meus') ?>">Meus personagens</a>
                            <a href="<?= url('/salas/nova') ?>">Criar sala</a>
                            <?php if ($user['role'] === 'admin'): ?>
                                <hr>
                                <a href="<?= url('/admin') ?>">Painel</a>
                                <a href="<?= url('/admin/noticias/nova') ?>">Criar notícia</a>
                                <a href="<?= url('/admin/usuarios') ?>">Usuários</a>
                                <a href="<?= url('/admin/regras') ?>">Regras de D&amp;D</a>
                            <?php endif; ?>
                            <hr>
                            <form method="post" action="<?= url('/sair') ?>"><?= csrf_field() ?><button type="submit">Sair</button></form>
                        </div>
                    </details>
                <?php else: ?>
                    <a class="account-link" href="<?= url('/entrar') ?>" data-dialog="login"><?= icon('user') ?><span>Entrar</span></a>
                    <a class="btn btn-small" href="<?= url('/cadastrar') ?>" data-dialog="register">Cadastrar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<?php if ($flashes): ?>
    <div class="flashes container" role="status">
        <?php foreach ($flashes as $flash): ?>
            <p class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<main id="conteudo">
    <?= $content ?>
</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col footer-news">
                <h2><a href="<?= url('/') ?>"><?= icon('news') ?> News</a></h2>
                <?php foreach (['Games', 'Anime', 'Hqs', 'Manga', 'RPG'] as $cat): ?>
                    <a href="<?= url('/?categoria=' . urlencode($cat)) ?>"><?= e($cat) ?></a>
                <?php endforeach; ?>
            </div>
            <div class="footer-col footer-rooms">
                <h2><a href="<?= url('/salas') ?>"><?= icon('axe') ?> Salas</a></h2>
                <a href="<?= url('/salas?ordem=novas') ?>">Novas</a>
                <a href="<?= url('/salas?ordem=populares') ?>">Populares</a>
                <a href="<?= url('/salas?ordem=mestres') ?>">Mestres</a>
                <a href="<?= url('/salas/sorte') ?>">Sorte</a>
            </div>
            <div class="footer-col footer-heroes">
                <h2><a href="<?= url('/personagens') ?>"><?= icon('dnd') ?> Heróis</a></h2>
                <a href="<?= url('/personagens?aba=meus') ?>">Meus</a>
                <a href="<?= url('/personagens?aba=ranking') ?>">Melhores</a>
                <a href="<?= url('/personagens?aba=exemplo') ?>">Exemplo</a>
                <a href="<?= url('/regras') ?>">Regras</a>
            </div>
            <div class="footer-col footer-help">
                <h2><a href="<?= url('/ajuda') ?>"><?= icon('help') ?> Ajuda</a></h2>
                <a href="<?= url('/ajuda#livros') ?>">Livros de D&amp;D</a>
                <a href="<?= url('/ajuda#como-jogar') ?>">Como jogar</a>
                <a href="<?= url('/ajuda#perguntas') ?>">Perguntas</a>
                <a href="<?= url('/aventuras') ?>">Aventuras</a>
            </div>
            <div class="footer-col footer-contact">
                <h2><a href="<?= url('/ajuda#contato') ?>"><?= icon('contacts') ?> Contatos</a></h2>
                <a href="mailto:DoppelNews.site@gmail.com"><?= icon('mail') ?> E-mail</a>
                <a href="<?= url('/ajuda#contato') ?>">Fale conosco</a>
                <a href="https://github.com/VitorBazilio" rel="noopener" target="_blank">GitHub</a>
            </div>
        </div>
        <div class="footer-credits">
            <p>© 2017–<?= date('Y') ?> Doppel · Vitor Bazilio · Felipe Antenori · Willberson Laurentino · Versão 3.0</p>
            <p>Todas as imagens de jogos, filmes, séries etc. são marcas registradas dos seus respectivos proprietários.</p>
        </div>
    </div>
</footer>

<?php if (!$user): ?>
    <?= Doppel\Support\View::partial('partials/auth-dialogs') ?>
<?php endif; ?>
</body>
</html>
