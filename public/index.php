<?php

declare(strict_types=1);

// Servidor embutido do PHP: deixa arquivos estáticos (css, imagens, PDFs) passarem direto.
if (PHP_SAPI === 'cli-server') {
    $file = __DIR__ . urldecode((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (is_file($file)) {
        return false;
    }
}

try {
    require dirname(__DIR__) . '/src/bootstrap.php';
} catch (Throwable $e) {
    // Falha antes do site existir (ex.: banco fora do ar ou variáveis erradas na hospedagem).
    // O motivo completo vai para o log; o visitante vê só um aviso, sem expor dados de conexão.
    error_log('[Doppel] Falha ao iniciar: ' . $e::class . ': ' . $e->getMessage());
    http_response_code(503);
    header('Content-Type: text/html; charset=utf-8');
    $isDatabase = $e instanceof PDOException;
    echo '<!doctype html><meta charset="utf-8"><title>DoppelNews</title>',
        '<body style="background:#000;color:#fff;font:16px system-ui;text-align:center;padding:15vh 16px">',
        '<h1 style="color:#ff550a">DoppelNews está fora do ar</h1><p>',
        $isDatabase ? 'Não foi possível conectar ao banco de dados.' : 'Ocorreu um erro ao iniciar o site.',
        ' Tente novamente em instantes.</p></body>';
    exit;
}

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
$router->get('/salas/{id}/entrar', [RoomController::class, 'enterForm']);
$router->post('/salas/{id}/entrar', [RoomController::class, 'enter']);
$router->post('/salas/{id}/sair', [RoomController::class, 'leave']);
$router->post('/salas/{id}/remover', [RoomController::class, 'kick']);

// Personagens e regras
$router->get('/personagens', [CharacterController::class, 'index']);
$router->get('/personagens/novo', [CharacterController::class, 'create']);
$router->post('/personagens', [CharacterController::class, 'store']);
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
$router->get('/admin/usuarios/{id}/editar', [Admin\UserController::class, 'edit']);
$router->post('/admin/usuarios/{id}', [Admin\UserController::class, 'update']);
$router->post('/admin/usuarios/{id}/status', [Admin\UserController::class, 'status']);
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
