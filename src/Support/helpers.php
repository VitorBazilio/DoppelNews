<?php

declare(strict_types=1);

const NEWS_CATEGORIES = ['Games', 'Anime', 'Hqs', 'Manga', 'RPG', 'História'];

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function asset(string $path): string
{
    $file = ROOT . '/public/assets/' . $path;
    return '/assets/' . $path . (is_file($file) ? '?v=' . filemtime($file) : '');
}

/** URL da imagem de uma notícia (upload do admin ou imagem de exemplo), ou null se não houver. */
function news_image(?string $name): ?string
{
    if ($name === null || $name === '') {
        return null;
    }
    if (str_starts_with($name, 'https://')) {
        return $name;
    }
    return is_file(ROOT . '/public/uploads/' . $name) ? '/uploads/' . $name : asset('img/' . $name);
}

function redirect(string $to): never
{
    header('Location: ' . $to);
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function pull_flash(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

function csrf_token(): string
{
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(16));
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $sent = (string) ($_POST['_csrf'] ?? '');
    if (!hash_equals(csrf_token(), $sent)) {
        http_response_code(419);
        exit('Sessão expirada. Volte e tente novamente.');
    }
}

function time_ago(string $datetime): string
{
    $seconds = time() - strtotime($datetime);
    return match (true) {
        $seconds < 60 => 'agora mesmo',
        $seconds < 3600 => 'há ' . intdiv($seconds, 60) . ' min',
        $seconds < 86400 => 'há ' . intdiv($seconds, 3600) . ' h',
        $seconds < 30 * 86400 => 'há ' . intdiv($seconds, 86400) . ' dias',
        default => date('d/m/Y', strtotime($datetime)),
    };
}

/** Converte texto puro em parágrafos HTML seguros. */
function paragraphs(string $text): string
{
    $html = '';
    foreach (preg_split('/\R{2,}/', trim($text)) as $block) {
        $html .= '<p>' . nl2br(e($block)) . '</p>';
    }
    return $html;
}
