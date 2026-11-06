<?php

declare(strict_types=1);

namespace Doppel\Controller;

use Doppel\Repository\RunRepository;
use Doppel\Story\Gamebook;
use Doppel\Story\StoryLibrary;
use Doppel\Support\Auth;
use Doppel\Support\View;

/** Aventuras em livro-jogo: abertas também para visitantes (o progresso fica na sessão). */
final class StoryController
{
    private RunRepository $runs;

    public function __construct()
    {
        $this->runs = new RunRepository();
    }

    public function index(): void
    {
        View::render('story/index', [
            'stories' => StoryLibrary::all(),
            'runs' => $this->runs->allFor(Auth::ownerKey()),
        ], 'Aventuras');
    }

    public function play(string $slug): void
    {
        $story = $this->story($slug);
        $run = $this->runs->find(Auth::ownerKey(), $slug);

        if ($run === null) {
            View::render('story/intro', ['story' => $story], $story['title']);
            return;
        }

        $state = $run['state'];
        View::render('story/play', [
            'story' => $story,
            'state' => $state,
            'node' => Gamebook::node($story, $state),
            'choices' => Gamebook::choices($story, $state),
            'ending' => Gamebook::ending($story, $state),
        ], $story['title']);
    }

    public function start(string $slug): void
    {
        $story = $this->story($slug);
        $this->saveState($slug, $story, Gamebook::newState($story));
        redirect("/aventuras/$slug");
    }

    public function choose(string $slug): void
    {
        $story = $this->story($slug);
        $run = $this->runs->find(Auth::ownerKey(), $slug);
        $index = filter_var($_POST['choice'] ?? null, FILTER_VALIDATE_INT);

        $next = ($run !== null && $index !== false) ? Gamebook::choose($story, $run['state'], $index) : null;
        if ($next === null) {
            flash('error', 'Essa escolha não está disponível.');
        } else {
            $this->saveState($slug, $story, $next);
        }
        redirect("/aventuras/$slug");
    }

    public function restart(string $slug): void
    {
        $this->story($slug);
        $this->runs->delete(Auth::ownerKey(), $slug);
        redirect("/aventuras/$slug");
    }

    private function story(string $slug): array
    {
        return StoryLibrary::find($slug) ?? View::notFound();
    }

    private function saveState(string $slug, array $story, array $state): void
    {
        $this->runs->save(Auth::ownerKey(), $slug, $state, Gamebook::ending($story, $state));
    }
}
