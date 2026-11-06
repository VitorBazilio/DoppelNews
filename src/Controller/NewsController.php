<?php

declare(strict_types=1);

namespace Doppel\Controller;

use Doppel\Repository\NewsRepository;
use Doppel\Story\StoryLibrary;
use Doppel\Support\Auth;
use Doppel\Support\View;

final class NewsController
{
    public function index(): void
    {
        $category = $_GET['categoria'] ?? null;
        if (!in_array($category, NEWS_CATEGORIES, true)) {
            $category = null;
        }
        $search = is_string($_GET['busca'] ?? null) ? trim(mb_scrub($_GET['busca'], 'UTF-8')) : '';
        $repo = new NewsRepository();
        $isFrontPage = $category === null && $search === '' && query_page() === 1;

        View::render('news/index', [
            'category' => $category,
            'search' => $search,
            'result' => $repo->paginate($category, query_page(), mb_substr($search, 0, 80)),
            'featured' => $isFrontPage ? $repo->featured() : [],
            'stories' => $isFrontPage && Auth::user() !== null ? StoryLibrary::all() : [],
        ], $category ?? 'Conteúdo Geek');
    }

    public function show(string $id): void
    {
        $repo = new NewsRepository();
        $article = $repo->find((int) $id) ?? View::notFound();
        $image = media_url($article['image']);

        View::render('news/show', [
            'article' => $article,
            'recent' => $repo->recent(4, (int) $article['id']),
            'related' => $repo->related($article),
            'meta' => [
                'description' => $article['summary'],
                'type' => 'article',
                'image' => $image !== null ? absolute_url($image) : null,
                'author' => $article['author'],
            ],
        ], $article['title']);
    }
}
