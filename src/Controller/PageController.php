<?php

declare(strict_types=1);

namespace Doppel\Controller;

use Doppel\Repository\RulesRepository;
use Doppel\Support\Auth;
use Doppel\Support\View;

/** Páginas de consulta: compêndio de regras (só logado) e ajuda (pública). */
final class PageController
{
    public function rules(): void
    {
        Auth::requireUser();
        $tab = $_GET['aba'] ?? 'classes';
        if (!isset(RulesRepository::TYPES[$tab])) {
            $tab = 'classes';
        }
        View::render('rules/index', ['tab' => $tab, 'items' => (new RulesRepository())->all($tab)], 'Regras');
    }

    public function help(): void
    {
        View::render('help/index', [], 'Ajuda');
    }
}
