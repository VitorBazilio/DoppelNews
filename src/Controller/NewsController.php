<?php

declare(strict_types=1);

namespace Doppel\Controller;

use Doppel\Repository\NewsRepository;
use Doppel\Story\StoryLibrary;
use Doppel\Support\View;

final class NewsController
{
    public function index(): void
    {
        $category = $_GET['categoria'] ?? null;
        if (!in_array($category, NEWS_CATEGORIES, true)) {
            $category = null;
        }
        $page = (int) ($_GET['pg'] ?? 1);

        View::render('news/index', [
            'category' => $category,
            'result' => (new NewsRepository())->paginate($category, $page),
            'stories' => StoryLibrary::all(),
        ], 'Doppel — notícias e aventuras');
    }

    public function show(string $id): void
    {
        $repo = new NewsRepository();
        $article = $repo->find((int) $id);
        if ($article === null) {
            View::notFound();
        }
        View::render('news/show', ['article' => $article, 'related' => $repo->related($article)], $article['title']);
    }
}
