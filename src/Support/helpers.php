<?php

declare(strict_types=1);

use Doppel\Support\Config;

/** Editorias de notícias. */
const NEWS_CATEGORIES = ['Games', 'Anime', 'Hqs', 'Manga', 'RPG', 'História'];

/** Os seis atributos de D&D: código => nome. */
const ATTRIBUTES = [
    'for' => 'Força', 'des' => 'Destreza', 'con' => 'Constituição',
    'int' => 'Inteligência', 'sab' => 'Sabedoria', 'car' => 'Carisma',
];

const PT_MONTHS = ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho',
    'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Prefixo do site quando ele roda numa subpasta (ex.: http://localhost/Doppel/public). */
function base_path(): string
{
    static $base = null;
    if ($base === null) {
        $base = match (true) {
            Config::get('APP_BASE') !== '' => rtrim(Config::get('APP_BASE'), '/'),
            getenv('VERCEL') !== false, PHP_SAPI === 'cli-server', PHP_SAPI === 'cli' => '',
            default => rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/'),
        };
    }
    return $base;
}

function url(string $path = '/'): string
{
    return base_path() . $path;
}

/** Caminho da requisição sem o prefixo da subpasta e sem barra final. */
function current_path(?string $uri = null): string
{
    $path = parse_url($uri ?? ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
    $base = base_path();
    if ($base !== '' && str_starts_with($path, $base)) {
        $path = substr($path, strlen($base));
    }
    return rtrim($path, '/') ?: '/';
}

function asset(string $path): string
{
    $file = ROOT . '/public/assets/' . $path;
    return url('/assets/' . $path) . (is_file($file) ? '?v=' . filemtime($file) : '');
}

/** URL de uma mídia salva no banco: upload do painel, URL externa ou arquivo de exemplo em assets/img. */
function media_url(?string $name): ?string
{
    if ($name === null || $name === '') {
        return null;
    }
    if (str_starts_with($name, 'https://')) {
        return $name;
    }
    if (is_file(ROOT . '/public/uploads/' . $name)) {
        return url('/uploads/' . rawurlencode($name));
    }
    return is_file(ROOT . '/public/assets/img/' . $name) ? asset('img/' . $name) : null;
}

function absolute_url(string $path): string
{
    if (preg_match('#^https?://#', $path)) {
        return $path;
    }
    $https = (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') || !empty($_SERVER['HTTPS']);
    return ($https ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $path;
}

function redirect(string $to): never
{
    header('Location: ' . (str_starts_with($to, '/') ? url($to) : $to));
    exit;
}

function back(string $fallback = '/'): never
{
    $referer = (string) ($_SERVER['HTTP_REFERER'] ?? '');
    $host = (string) ($_SERVER['HTTP_HOST'] ?? '');
    $sameSite = $referer !== '' && parse_url($referer, PHP_URL_HOST) === parse_url('http://' . $host, PHP_URL_HOST);
    header('Location: ' . ($sameSite ? $referer : url($fallback)));
    exit;
}

function json_response(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

function wants_json(): bool
{
    return str_contains((string) ($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json');
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

/** Guarda os campos enviados para reapresentar o formulário depois de um erro. */
function remember_input(array $fields): void
{
    $_SESSION['old'] = $fields;
}

/** Valor enviado antes do erro (ou $default). Os dados valem só para a próxima página exibida. */
function old(string $key, string $default = ''): string
{
    return (string) (old_input()[$key] ?? $default);
}

function old_input(): array
{
    static $old = null;
    if ($old === null) {
        $old = $_SESSION['old'] ?? [];
        unset($_SESSION['old']);
    }
    return $old;
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
    $sent = (string) ($_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    if (!hash_equals(csrf_token(), $sent)) {
        if (wants_json()) {
            json_response(['error' => 'Sessão expirada. Recarregue a página.'], 419);
        }
        http_response_code(419);
        flash('error', 'Sua sessão expirou. Tente novamente.');
        back();
    }
}

/** Campo de formulário como texto UTF-8 válido (bytes inválidos viram "?"), sem espaços nas pontas. */
function input(string $key): string
{
    $value = $_POST[$key] ?? '';
    return is_string($value) ? trim(mb_scrub($value, 'UTF-8')) : '';
}

function input_int(string $key, int $default = 0): int
{
    $value = filter_var($_POST[$key] ?? null, FILTER_VALIDATE_INT);
    return $value === false ? $default : $value;
}

function query_page(): int
{
    return max(1, (int) ($_GET['pg'] ?? 1));
}

/** "Agora mesmo", "há 5 minutos"… e a data completa depois de 30 dias (como no Doppel original). */
function time_ago(string $datetime): string
{
    $seconds = max(0, time() - strtotime($datetime));
    $plural = static fn (int $n, string $one, string $many): string => "há $n " . ($n === 1 ? $one : $many);
    return match (true) {
        $seconds < 60 => 'Agora mesmo',
        $seconds < 3600 => $plural(intdiv($seconds, 60), 'minuto', 'minutos'),
        $seconds < 86400 => $plural(intdiv($seconds, 3600), 'hora', 'horas'),
        $seconds < 30 * 86400 => $plural(intdiv($seconds, 86400), 'dia', 'dias'),
        default => date('d/m/Y', strtotime($datetime)),
    };
}

/** "6 de março de 2019 às 10h30" */
function date_long(string $datetime, bool $withTime = true): string
{
    $ts = strtotime($datetime);
    $text = date('j', $ts) . ' de ' . PT_MONTHS[(int) date('n', $ts) - 1] . ' de ' . date('Y', $ts);
    return $withTime ? $text . ' às ' . date('H\hi', $ts) : $text;
}

/** Converte texto puro em parágrafos HTML seguros (linha em branco separa parágrafos). */
function paragraphs(string $text): string
{
    $html = '';
    foreach (preg_split('/\R{2,}/', trim($text)) as $block) {
        $html .= '<p>' . nl2br(e(trim($block))) . '</p>';
    }
    return $html;
}

function excerpt(string $text, int $length = 160): string
{
    $text = trim(preg_replace('/\s+/', ' ', $text));
    return mb_strlen($text) > $length ? rtrim(mb_substr($text, 0, $length - 1)) . '…' : $text;
}

/** Ícone SVG embutido (sem dependência de JavaScript). */
function icon(string $name, string $class = ''): string
{
    static $icons = null;
    $icons ??= require __DIR__ . '/icons.php';
    if (!isset($icons[$name])) {
        return '';
    }
    [$viewBox, $path] = $icons[$name];
    return '<svg class="icon ' . e($class) . '" viewBox="' . $viewBox . '" aria-hidden="true" focusable="false">'
        . '<path fill="currentColor" d="' . $path . '"/></svg>';
}

function signed(int $value): string
{
    return ($value >= 0 ? '+' : '') . $value;
}

/** Monta a query string mantendo os filtros atuais e trocando só o que for informado. */
function query_with(array $changes): string
{
    $params = array_filter(array_merge($_GET, $changes), static fn ($v) => $v !== null && $v !== '');
    return $params ? '?' . http_build_query($params) : '';
}
