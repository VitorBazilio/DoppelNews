<?php /** @var array $items */ ?>
<div class="container admin">
    <?= Doppel\Support\View::partial('admin/nav') ?>
    <div class="toolbar">
        <h1 class="display-title">Notícias</h1>
        <a class="btn" href="<?= url('/admin/noticias/nova') ?>">Criar notícia</a>
    </div>

    <div class="panel table-wrap">
        <table class="table">
            <thead><tr><th>Título</th><th>Editoria</th><th>Publicada</th><th><span class="sr-only">Ações</span></th></tr></thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <a href="<?= url('/noticias/' . (int) $item['id']) ?>"><?= e($item['title']) ?></a>
                        <?php if ($item['featured']): ?><span class="badge">Carrossel</span><?php endif; ?>
                    </td>
                    <td><?= e($item['category']) ?></td>
                    <td><?= e(date('d/m/Y H:i', strtotime($item['published_at']))) ?></td>
                    <td class="actions">
                        <a href="<?= url('/admin/noticias/' . (int) $item['id'] . '/editar') ?>">Editar</a>
                        <form method="post" action="<?= url('/admin/noticias/' . (int) $item['id'] . '/excluir') ?>" data-confirm="Excluir esta notícia?">
                            <?= csrf_field() ?><button class="link-button danger" type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
