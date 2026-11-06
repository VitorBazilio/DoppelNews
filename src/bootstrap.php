<?php

declare(strict_types=1);

define('ROOT', dirname(__DIR__));

spl_autoload_register(static function (string $class): void {
    $prefix = 'Doppel\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $file = ROOT . '/src/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

require __DIR__ . '/Support/helpers.php';

date_default_timezone_set('America/Sao_Paulo');

Doppel\Support\Config::load(ROOT . '/.env');

ini_set('display_errors', Doppel\Support\Config::bool('APP_DEBUG') ? '1' : '0');
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    if (Doppel\Support\Config::bool('DB_SESSIONS') || getenv('VERCEL') !== false) {
        session_set_save_handler(new Doppel\Support\DatabaseSessionHandler(), true);
    }
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') || !empty($_SERVER['HTTPS']),
    ]);
    session_start();
}
