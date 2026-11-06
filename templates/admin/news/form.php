<?php
/** @var ?array $article */
use Doppel\Support\Upload;

$action = $article ? '/admin/noticias/' . (int) $article['id'] : '/admin/noticias';
$value = static fn (string $key, string $default = ''): string => old($key, (string) ($article[$key] ?? $default));
$currentImage = media_url($article['image'] ?? null);
$featured = old('featured', (string) ($article['featured'] ?? '0')) === '1';
?>
<div class="container admin">
    <?= Doppel\Support\View::partial('admin/nav') ?>
    <div class="panel form-card wide">
        <h1 class="panel-title"><?= $article ? 'Editar notícia' : 'Criar notícia' ?></h1>
        <form class="form" method="post" action="<?= url($action) ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <label>Título<input name="title" value="<?= e($value('title')) ?>" required maxlength="200"></label>
            <label>Manchete <small>(resumo que aparece na lista)</small><textarea name="summary" rows="2" required maxlength="400"><?= e($value('summary')) ?></textarea></label>
            <div class="form-row">
                <label>Editoria
                    <select name="category" required>
                        <?php foreach (NEWS_CATEGORIES as $name): ?>
                            <option <?= $value('category') === $name ? 'selected' : '' ?>><?= e($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Assinatura<input name="author" value="<?= e($value('author', (string) Doppel\Support\Auth::user()['name'])) ?>" maxlength="80"></label>
            </div>
            <label>Texto <small>(deixe uma linha em branco entre parágrafos)</small>
                <textarea name="body" rows="14" required><?= e($value('body')) ?></textarea></label>

            <fieldset class="image-field">
                <legend>Foto de exibição</legend>
                <?php if ($currentImage): ?>
                    <div class="current-image">
                        <img src="<?= e($currentImage) ?>" alt="Imagem atual">
                        <label class="check"><input type="checkbox" name="remove_image" value="1"> Remover imagem</label>
                    </div>
                <?php endif; ?>
                <?php if (Upload::canStoreFiles()): ?>
                    <label>Enviar arquivo <small>(JPG, PNG, WEBP ou GIF, até 3 MB; convertido para WebP)</small><input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif"></label>
                <?php endif; ?>
                <label><?= Upload::canStoreFiles() ? '…ou' : 'Informe' ?> a URL da imagem <small>(https://)</small><input type="url" name="image_url" placeholder="https://"></label>
            </fieldset>

            <label class="check"><input type="checkbox" name="featured" value="1" <?= $featured ? 'checked' : '' ?>> Destaque no carrossel da página inicial</label>

            <div class="form-actions">
                <button class="btn" type="submit"><?= $article ? 'Salvar alterações' : 'Publicar' ?></button>
                <a class="btn btn-ghost" href="<?= url('/admin/noticias') ?>">Cancelar</a>
            </div>
        </form>
    </div>
</div>
