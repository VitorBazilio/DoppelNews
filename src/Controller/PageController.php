<?php

declare(strict_types=1);

namespace Doppel\Controller;

use Doppel\Repository\RulesRepository;
use Doppel\Support\View;

/** Páginas de consulta: compêndio de regras e ajuda. */
final class PageController
{
    public function rules(): void
    {
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
