<?php

declare(strict_types=1);

namespace Doppel\Support;

/** Renderiza um template dentro do layout principal. */
final class View
{
    public static function render(string $template, array $data = [], string $title = 'Doppel'): void
    {
        $content = self::capture($template, $data);
        $user = Auth::user();
        $flashes = pull_flash();
        require ROOT . '/templates/layout/main.php';
    }

    public static function notFound(): never
    {
        http_response_code(404);
        self::render('404', [], 'Página não encontrada');
        exit;
    }

    private static function capture(string $template, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require ROOT . "/templates/$template.php";
        return (string) ob_get_clean();
    }
}
