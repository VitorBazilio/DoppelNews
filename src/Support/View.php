<?php

declare(strict_types=1);

namespace Doppel\Support;

/** Renderiza um template dentro do layout principal (cabeçalho, rodapé e modais do Doppel). */
final class View
{
    /**
     * @param array $data  variáveis do template; 'meta' (opcional) vira tags Open Graph no <head>
     */
    public static function render(string $template, array $data = [], string $title = 'DoppelNews'): void
    {
        old_input(); // consome os dados do formulário anterior mesmo que o template não os use
        $content = self::capture($template, $data);
        $meta = $data['meta'] ?? [];
        $user = Auth::user();
        $flashes = pull_flash();
        require ROOT . '/templates/layout/main.php';
    }

    public static function notFound(): never
    {
        http_response_code(404);
        self::render('errors/404', [], 'Página não encontrada');
        exit;
    }

    public static function forbidden(string $message = 'Esta área é exclusiva para administradores.'): never
    {
        http_response_code(403);
        self::render('errors/403', ['message' => $message], 'Acesso negado');
        exit;
    }

    public static function partial(string $template, array $data = []): string
    {
        return self::capture($template, $data);
    }

    private static function capture(string $template, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require ROOT . "/templates/$template.php";
        return (string) ob_get_clean();
    }
}
