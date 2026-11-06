<?php

declare(strict_types=1);

namespace Doppel\Controller;

use Doppel\Repository\NewsRepository;
use Doppel\Support\Auth;
use Doppel\Support\View;

final class AdminController
{
    private const MAX_IMAGE_BYTES = 3 * 1024 * 1024;
    private const IMAGE_TYPES = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];

    private NewsRepository $news;

    public function __construct()
    {
        $this->news = new NewsRepository();
    }

    public function index(): void
    {
        Auth::requireAdmin();
        View::render('admin/index', ['items' => $this->news->all()], 'Painel');
    }

    public function create(): void
    {
        Auth::requireAdmin();
        View::render('admin/form', ['article' => null, 'old' => []], 'Nova notícia');
    }

    public function edit(string $id): void
    {
        Auth::requireAdmin();
        $article = $this->news->find((int) $id) ?? View::notFound();
        View::render('admin/form', ['article' => $article, 'old' => $article], 'Editar notícia');
    }

    public function store(): void
    {
        Auth::requireAdmin();
        $this->save(null);
    }

    public function update(string $id): void
    {
        Auth::requireAdmin();
        $this->save((int) $id);
    }

    public function destroy(string $id): void
    {
        Auth::requireAdmin();
        $this->news->delete((int) $id);
        flash('success', 'Notícia excluída.');
        redirect('/admin');
    }

    private function save(?int $id): void
    {
        $existing = $id !== null ? ($this->news->find($id) ?? View::notFound()) : null;
        $data = [
            'title' => trim((string) ($_POST['title'] ?? '')),
            'summary' => trim((string) ($_POST['summary'] ?? '')),
            'body' => trim((string) ($_POST['body'] ?? '')),
            'category' => (string) ($_POST['category'] ?? ''),
            'image' => $existing['image'] ?? null,
        ];

        $error = match (true) {
            $data['title'] === '' || mb_strlen($data['title']) > 200 => 'Informe um título de até 200 caracteres.',
            $data['summary'] === '' || mb_strlen($data['summary']) > 400 => 'Informe um resumo de até 400 caracteres.',
            $data['body'] === '' => 'O texto não pode ficar vazio.',
            !in_array($data['category'], NEWS_CATEGORIES, true) => 'Categoria inválida.',
            default => null,
        };

        if ($error === null) {
            [$data['image'], $error] = $this->handleUpload($data['image']);
        }
        if ($error !== null) {
            flash('error', $error);
            View::render('admin/form', ['article' => $existing, 'old' => $data], 'Notícia');
            return;
        }

        $this->news->save($id, $data);
        flash('success', 'Notícia salva.');
        redirect('/admin');
    }

    /** @return array{?string, ?string} [nome do arquivo, erro] */
    private function handleUpload(?string $current): array
    {
        $url = trim((string) ($_POST['image_url'] ?? ''));
        if ($url !== '') {
            $valid = preg_match('#^https://#i', $url) && filter_var($url, FILTER_VALIDATE_URL) && strlen($url) <= 255;
            return $valid ? [$url, null] : [$current, 'A URL da imagem deve começar com https://.'];
        }

        $file = $_FILES['image'] ?? null;
        if ($file === null || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return [$current, null];
        }
        if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > self::MAX_IMAGE_BYTES) {
            return [$current, 'Imagem inválida ou maior que 3 MB.'];
        }
        $type = @getimagesize($file['tmp_name'])[2] ?? null;
        if (!isset(self::IMAGE_TYPES[$type])) {
            return [$current, 'Use uma imagem JPG, PNG ou WEBP.'];
        }

        $name = bin2hex(random_bytes(8)) . '.' . self::IMAGE_TYPES[$type];
        if (!move_uploaded_file($file['tmp_name'], ROOT . '/public/uploads/' . $name)) {
            return [$current, 'Não foi possível salvar a imagem.'];
        }
        return [$name, null];
    }
}
