<?php

declare(strict_types=1);

namespace Doppel\Controller\Admin;

use Doppel\Repository\NewsRepository;
use Doppel\Support\Auth;
use Doppel\Support\Upload;
use Doppel\Support\View;

final class NewsController
{
    private NewsRepository $news;

    public function __construct()
    {
        Auth::requireAdmin();
        $this->news = new NewsRepository();
    }

    public function index(): void
    {
        View::render('admin/news/index', ['items' => $this->news->all()], 'Notícias');
    }

    public function create(): void
    {
        View::render('admin/news/form', ['article' => null], 'Nova notícia');
    }

    public function edit(string $id): void
    {
        $article = $this->news->find((int) $id) ?? View::notFound();
        View::render('admin/news/form', ['article' => $article], 'Editar notícia');
    }

    public function store(): void
    {
        $this->save(null);
    }

    public function update(string $id): void
    {
        $this->save((int) $id);
    }

    public function destroy(string $id): void
    {
        $article = $this->news->find((int) $id) ?? View::notFound();
        $this->news->delete((int) $article['id']);
        Upload::delete($article['image']);
        flash('success', 'Notícia excluída.');
        redirect('/admin/noticias');
    }

    private function save(?int $id): void
    {
        $existing = $id !== null ? ($this->news->find($id) ?? View::notFound()) : null;
        $data = [
            'title' => input('title'),
            'summary' => input('summary'),
            'body' => str_replace("\r\n", "\n", input('body')),
            'category' => input('category'),
            'author' => input('author') ?: (string) Auth::user()['name'],
            'featured' => isset($_POST['featured']) ? 1 : 0,
            'image' => $existing['image'] ?? null,
        ];
        $back = $id === null ? '/admin/noticias/nova' : "/admin/noticias/$id/editar";

        $error = match (true) {
            $data['title'] === '' || mb_strlen($data['title']) > 200 => 'Informe um título de até 200 caracteres.',
            $data['summary'] === '' || mb_strlen($data['summary']) > 400 => 'Informe uma manchete de até 400 caracteres.',
            $data['body'] === '' => 'O texto não pode ficar vazio.',
            !in_array($data['category'], NEWS_CATEGORIES, true) => 'Escolha uma editoria válida.',
            mb_strlen($data['author']) > 80 => 'A assinatura pode ter no máximo 80 caracteres.',
            default => null,
        };
        if ($error === null) {
            [$data['image'], $error] = Upload::image('image', $data['image']);
        }
        if (isset($_POST['remove_image']) && $error === null) {
            Upload::delete($data['image']);
            $data['image'] = null;
        }
        if ($error !== null) {
            remember_input(array_merge($data, ['featured' => (string) $data['featured']]));
            flash('error', $error);
            redirect($back);
        }

        $newId = $this->news->save($id, $data);
        flash('success', $id === null ? 'Notícia publicada.' : 'Notícia atualizada.');
        redirect($id === null ? "/noticias/$newId" : '/admin/noticias');
    }
}
