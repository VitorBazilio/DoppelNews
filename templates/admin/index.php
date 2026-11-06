<?php /** @var array $items */ ?>
<section class="page-head">
    <h1>Painel</h1>
    <a class="btn" href="/admin/noticias/nova">Nova notícia</a>
</section>

<table class="table">
    <thead><tr><th>Título</th><th>Categoria</th><th>Publicada</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($items as $item): ?>
        <tr>
            <td><a href="/noticias/<?= (int) $item['id'] ?>"><?= e($item['title']) ?></a></td>
            <td><?= e($item['category']) ?></td>
            <td><?= e(date('d/m/Y H:i', strtotime($item['published_at']))) ?></td>
            <td class="actions">
                <a href="/admin/noticias/<?= (int) $item['id'] ?>/editar">Editar</a>
                <form method="post" action="/admin/noticias/<?= (int) $item['id'] ?>/excluir" onsubmit="return confirm('Excluir esta notícia?')">
                    <?= csrf_field() ?><button class="link-button danger">Excluir</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
