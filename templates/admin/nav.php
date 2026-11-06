<?php
$adminPath = current_path();
$links = ['/admin' => 'Visão geral', '/admin/noticias' => 'Notícias', '/admin/usuarios' => 'Usuários', '/admin/regras' => 'Regras de D&D'];
?>
<nav class="admin-nav pixel" aria-label="Painel">
    <?php foreach ($links as $href => $label): ?>
        <a href="<?= url($href) ?>" <?= ($href === '/admin' ? $adminPath === '/admin' : str_starts_with($adminPath, $href)) ? 'aria-current="page"' : '' ?>><?= $label ?></a>
    <?php endforeach; ?>
</nav>
