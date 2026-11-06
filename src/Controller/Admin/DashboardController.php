<?php

declare(strict_types=1);

namespace Doppel\Controller\Admin;

use Doppel\Repository\CharacterRepository;
use Doppel\Repository\NewsRepository;
use Doppel\Repository\RoomRepository;
use Doppel\Repository\UserRepository;
use Doppel\Support\Auth;
use Doppel\Support\View;

final class DashboardController
{
    public function index(): void
    {
        Auth::requireAdmin();
        View::render('admin/dashboard', [
            'stats' => [
                'Notícias' => (new NewsRepository())->count(),
                'Usuários' => (new UserRepository())->count(),
                'Salas' => (new RoomRepository())->count(),
                'Personagens' => (new CharacterRepository())->count(),
            ],
            'news' => (new NewsRepository())->recent(5),
            'rooms' => array_slice((new RoomRepository())->list('novas'), 0, 5),
        ], 'Painel');
    }
}
