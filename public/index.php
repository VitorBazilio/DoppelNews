<?php

declare(strict_types=1);

// Servidor embutido do PHP: deixa arquivos estáticos (css, imagens, PDFs) passarem direto.
if (PHP_SAPI === 'cli-server') {
    $file = __DIR__ . urldecode((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (is_file($file)) {
        return false;
    }
}

require dirname(__DIR__) . '/src/bootstrap.php';

use Doppel\Controller\Admin;
use Doppel\Controller\AuthController;
use Doppel\Controller\CharacterController;
use Doppel\Controller\NewsController;
use Doppel\Controller\PageController;
use Doppel\Controller\ProfileController;
use Doppel\Controller\RoomController;
use Doppel\Controller\StoryController;
use Doppel\Support\Router;

$router = new Router();

// Notícias
$router->get('/', [NewsController::class, 'index']);
$router->get('/noticias/{id}', [NewsController::class, 'show']);

// Salas de RPG
$router->get('/salas', [RoomController::class, 'index']);
$router->get('/salas/sorte', [RoomController::class, 'lucky']);
$router->get('/salas/nova', [RoomController::class, 'create']);
$router->post('/salas', [RoomController::class, 'store']);
$router->get('/salas/{id}', [RoomController::class, 'show']);
$router->get('/salas/{id}/mensagens', [RoomController::class, 'messages']);
$router->post('/salas/{id}/mensagens', [RoomController::class, 'send']);
$router->post('/salas/{id}/pontos', [RoomController::class, 'award']);
$router->post('/salas/{id}/excluir', [RoomController::class, 'destroy']);
$router->get('/salas/{id}/personagem', [CharacterController::class, 'create']);
$router->post('/salas/{id}/personagem', [CharacterController::class, 'store']);

// Personagens e regras
$router->get('/personagens', [CharacterController::class, 'index']);
$router->get('/personagens/{id}', [CharacterController::class, 'show']);
$router->post('/personagens/{id}/excluir', [CharacterController::class, 'destroy']);
$router->get('/regras', [PageController::class, 'rules']);

// Aventuras (livro-jogo)
$router->get('/aventuras', [StoryController::class, 'index']);
$router->get('/aventuras/{slug}', [StoryController::class, 'play']);
$router->post('/aventuras/{slug}/iniciar', [StoryController::class, 'start']);
$router->post('/aventuras/{slug}/escolher', [StoryController::class, 'choose']);
$router->post('/aventuras/{slug}/reiniciar', [StoryController::class, 'restart']);

// Ajuda
$router->get('/ajuda', [PageController::class, 'help']);

// Conta
$router->get('/entrar', [AuthController::class, 'loginForm']);
$router->post('/entrar', [AuthController::class, 'login']);
$router->get('/cadastrar', [AuthController::class, 'registerForm']);
$router->post('/cadastrar', [AuthController::class, 'register']);
$router->post('/sair', [AuthController::class, 'logout']);
$router->get('/perfil', [ProfileController::class, 'show']);
$router->post('/perfil/nome', [ProfileController::class, 'updateName']);
$router->post('/perfil/senha', [ProfileController::class, 'updatePassword']);

// Painel administrativo
$router->get('/admin', [Admin\DashboardController::class, 'index']);
$router->get('/admin/noticias', [Admin\NewsController::class, 'index']);
$router->get('/admin/noticias/nova', [Admin\NewsController::class, 'create']);
$router->post('/admin/noticias', [Admin\NewsController::class, 'store']);
$router->get('/admin/noticias/{id}/editar', [Admin\NewsController::class, 'edit']);
$router->post('/admin/noticias/{id}', [Admin\NewsController::class, 'update']);
$router->post('/admin/noticias/{id}/excluir', [Admin\NewsController::class, 'destroy']);
$router->get('/admin/usuarios', [Admin\UserController::class, 'index']);
$router->post('/admin/usuarios/{id}/papel', [Admin\UserController::class, 'role']);
$router->post('/admin/usuarios/{id}/excluir', [Admin\UserController::class, 'destroy']);
$router->get('/admin/regras', [Admin\RulesController::class, 'index']);
$router->post('/admin/regras/{type}', [Admin\RulesController::class, 'store']);
$router->post('/admin/regras/{type}/{id}/excluir', [Admin\RulesController::class, 'destroy']);

try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $e) {
    error_log((string) $e);
    http_response_code(500);
    if (Doppel\Support\Config::bool('APP_DEBUG')) {
        throw $e;
    }
    Doppel\Support\View::render('errors/500', [], 'Erro');
}
