<?php /** @var int $page @var int $pages */ ?>
<?php if ($pages > 1): ?>
    <nav class="pager pixel" aria-label="Paginação">
        <?php if ($page > 1): ?>
            <a class="pager-link" href="<?= e(query_with(['pg' => $page - 1])) ?>"><?= icon('prev') ?> Anterior</a>
        <?php endif; ?>
        <span class="pager-current"><?= $page ?></span>
        <span>de <?= $pages ?></span>
        <?php if ($page < $pages): ?>
            <a class="pager-link" href="<?= e(query_with(['pg' => $page + 1])) ?>">Próxima <?= icon('next') ?></a>
        <?php endif; ?>
    </nav>
<?php endif; ?>
