<?php /** @var ?array $article @var array $old */
$action = $article ? '/admin/noticias/' . (int) $article['id'] : '/admin/noticias';
?>
<section class="form-card wide">
    <h1><?= $article ? 'Editar notícia' : 'Nova notícia' ?></h1>
    <form method="post" action="<?= $action ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <label>Título<input name="title" value="<?= e($old['title'] ?? '') ?>" required maxlength="200"></label>
        <label>Resumo<textarea name="summary" rows="2" required maxlength="400"><?= e($old['summary'] ?? '') ?></textarea></label>
        <label>Categoria
            <select name="category">
                <?php foreach (NEWS_CATEGORIES as $name): ?>
                    <option <?= ($old['category'] ?? '') === $name ? 'selected' : '' ?>><?= e($name) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Texto <small>(deixe uma linha em branco entre parágrafos)</small>
            <textarea name="body" rows="12" required><?= e($old['body'] ?? '') ?></textarea></label>
        <label>URL da imagem <small>(https://…, funciona em qualquer hospedagem)</small><input type="url" name="image_url" placeholder="https://"></label>
        <?php if (is_writable(ROOT . '/public/uploads')): ?>
            <label>…ou envie um arquivo <small>(JPG, PNG ou WEBP, até 3 MB)</small><input type="file" name="image" accept="image/jpeg,image/png,image/webp"></label>
        <?php endif; ?>
        <button class="btn">Salvar</button>
        <a class="btn btn-ghost" href="/admin">Cancelar</a>
    </form>
</section>
