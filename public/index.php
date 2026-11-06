<?php

declare(strict_types=1);

// Servidor embutido do PHP: deixa arquivos estáticos (css, imagens) passarem direto.
if (PHP_SAPI === 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($file)) {
        return false;
    }
}

require dirname(__DIR__) . '/src/bootstrap.php';

use Doppel\Controller\AdminController;
use Doppel\Controller\AuthController;
use Doppel\Controller\NewsController;
use Doppel\Controller\StoryController;
use Doppel\Support\Router;

$router = new Router();

$router->get('/', [NewsController::class, 'index']);
$router->get('/noticias/{id}', [NewsController::class, 'show']);

$router->get('/aventuras', [StoryController::class, 'index']);
$router->get('/aventuras/{slug}', [StoryController::class, 'play']);
$router->post('/aventuras/{slug}/iniciar', [StoryController::class, 'start']);
$router->post('/aventuras/{slug}/escolher', [StoryController::class, 'choose']);
$router->post('/aventuras/{slug}/reiniciar', [StoryController::class, 'restart']);

$router->get('/entrar', [AuthController::class, 'loginForm']);
$router->post('/entrar', [AuthController::class, 'login']);
$router->get('/cadastrar', [AuthController::class, 'registerForm']);
$router->post('/cadastrar', [AuthController::class, 'register']);
$router->post('/sair', [AuthController::class, 'logout']);

$router->get('/admin', [AdminController::class, 'index']);
$router->get('/admin/noticias/nova', [AdminController::class, 'create']);
$router->post('/admin/noticias', [AdminController::class, 'store']);
$router->get('/admin/noticias/{id}/editar', [AdminController::class, 'edit']);
$router->post('/admin/noticias/{id}', [AdminController::class, 'update']);
$router->post('/admin/noticias/{id}/excluir', [AdminController::class, 'destroy']);

try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $e) {
    error_log((string) $e);
    http_response_code(500);
    if (Doppel\Support\Config::bool('APP_DEBUG')) {
        throw $e;
    }
    Doppel\Support\View::render('500', [], 'Erro');
}
