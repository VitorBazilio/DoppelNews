<?php

declare(strict_types=1);

namespace Doppel\Controller;

use Doppel\Repository\CharacterRepository;
use Doppel\Repository\MessageRepository;
use Doppel\Repository\RoomRepository;
use Doppel\Rpg\Dice;
use Doppel\Rpg\CharacterRules;
use Doppel\Support\Auth;
use Doppel\Support\View;

/** Salas de RPG: lista, criação, mesa com chat/dados e pontuação dada pelo mestre. */
final class RoomController
{
    private const MAX_MESSAGE = 500;
    private const MAX_AWARD = 10;

    private RoomRepository $rooms;
    private CharacterRepository $characters;

    public function __construct()
    {
        $this->rooms = new RoomRepository();
        $this->characters = new CharacterRepository();
    }

    public function index(): void
    {
        Auth::requireUser();
        $order = $_GET['ordem'] ?? 'novas';
        if (!isset(RoomRepository::ORDERS[$order])) {
            $order = 'novas';
        }
        View::render('rooms/index', ['rooms' => $this->rooms->list($order), 'order' => $order], 'Salas');
    }

    public function lucky(): void
    {
        Auth::requireUser();
        $id = $this->rooms->randomOpenId();
        if ($id === null) {
            flash('info', 'Nenhuma sala com vaga agora. Que tal criar a sua?');
            redirect('/salas');
        }
        redirect("/salas/$id");
    }

    public function create(): void
    {
        Auth::requireUser();
        View::render('rooms/create', [], 'Criar sala');
    }

    public function store(): void
    {
        $user = Auth::requireUser();
        $name = input('name');
        $description = input('description');
        $maxPlayers = input_int('max_players');
        remember_input(['name' => $name, 'description' => $description, 'max_players' => (string) $maxPlayers]);

        $error = match (true) {
            mb_strlen($name) < 3 || mb_strlen($name) > 60 => 'O nome da sala deve ter entre 3 e 60 caracteres.',
            mb_strlen($description) > 400 => 'A descrição pode ter no máximo 400 caracteres.',
            $maxPlayers < 1 || $maxPlayers > 12 => 'O limite de jogadores deve ficar entre 1 e 12.',
            $this->rooms->nameExists($name) => 'Já existe uma sala com esse nome.',
            default => null,
        };
        if ($error !== null) {
            flash('error', $error);
            redirect('/salas/nova');
        }

        unset($_SESSION['old']);
        $id = $this->rooms->create($name, $description, (int) $user['id'], $maxPlayers);
        $this->system($id, "Sala criada por {$user['name']}. Que comece a aventura!");
        flash('success', 'Sala criada! Você é o mestre. Compartilhe o link com seus jogadores.');
        redirect("/salas/$id");
    }

    public function show(string $id): void
    {
        $user = Auth::requireUser();
        $room = $this->room($id);
        $access = $this->access($room, $user);

        if ($access['role'] === null) {
            redirect("/salas/{$room['id']}/entrar");
        }

        $character = $access['character'];
        View::render('rooms/show', [
            'room' => $room,
            'role' => $access['role'],
            'character' => $character,
            'skills' => $character ? CharacterRules::skills(CharacterRepository::attributes($character)) : [],
            'players' => $this->characters->forRoom((int) $room['id']),
            'messages' => (new MessageRepository())->since((int) $room['id']),
        ], $room['name']);
    }

    /** Tela para escolher com qual dos seus personagens o usuário entra na sala. */
    public function enterForm(string $id): void
    {
        $user = Auth::requireUser();
        $room = $this->joinableRoom($id, $user);
        View::render('rooms/enter', [
            'room' => $room,
            'characters' => $this->characters->forUser((int) $user['id']),
        ], 'Entrar em ' . $room['name']);
    }

    public function enter(string $id): void
    {
        $user = Auth::requireUser();
        $room = $this->joinableRoom($id, $user);
        $character = $this->characters->find(input_int('character_id'));
        if ($character === null || (int) $character['user_id'] !== (int) $user['id']) {
            flash('error', 'Escolha um dos seus personagens.');
            redirect("/salas/{$room['id']}/entrar");
        }

        $this->rooms->join((int) $room['id'], (int) $user['id'], (int) $character['id']);
        $this->system((int) $room['id'], "{$character['name']} ({$character['species_name']} {$character['class_name']}) entrou na sala.");
        flash('success', "{$character['name']} entrou na sala. Boa aventura!");
        redirect("/salas/{$room['id']}");
    }

    /** O jogador sai da sala; a ficha continua com ele. */
    public function leave(string $id): void
    {
        $user = Auth::requireUser();
        $room = $this->room($id);
        $access = $this->access($room, $user);
        if ($access['role'] === 'player') {
            $this->rooms->leave((int) $room['id'], (int) $user['id']);
            $this->system((int) $room['id'], "{$access['character']['name']} saiu da sala.");
            flash('success', 'Você saiu da sala "' . $room['name'] . '".');
        }
        redirect('/salas');
    }

    /** O mestre (ou um admin) remove um jogador da mesa. */
    public function kick(string $id): void
    {
        $user = Auth::requireUser();
        $room = $this->room($id);
        $this->requireMaster($room, $user);

        $character = $this->characters->find(input_int('character_id'));
        if ($character !== null && $this->rooms->isCharacterInRoom((int) $room['id'], (int) $character['id'])) {
            $this->rooms->leave((int) $room['id'], (int) $character['user_id']);
            $this->system((int) $room['id'], "{$character['name']} foi removido da sala pelo mestre.");
            flash('success', "{$character['name']} foi removido da sala.");
        }
        redirect("/salas/{$room['id']}");
    }

    /** JSON com as mensagens novas (o chat consulta a cada poucos segundos). */
    public function messages(string $id): void
    {
        $room = $this->roomOrJsonError($id);
        $user = Auth::user() ?? json_response(['error' => 'Entre na sua conta.'], 401);
        if ($this->access($room, $user)['role'] === null) {
            json_response(['error' => 'Você não participa desta sala.'], 403);
        }
        $after = max(0, (int) ($_GET['depois'] ?? 0));
        json_response(['messages' => $this->present((new MessageRepository())->since((int) $room['id'], $after))]);
    }

    public function send(string $id): void
    {
        $room = $this->roomOrJsonError($id);
        $user = Auth::user() ?? json_response(['error' => 'Entre na sua conta.'], 401);
        $access = $this->access($room, $user);
        if (!in_array($access['role'], ['master', 'player'], true)) {
            json_response(['error' => 'Só o mestre e os jogadores podem escrever aqui.'], 403);
        }

        $body = (string) preg_replace('/\s+/u', ' ', input('message'));
        if ($body === '') {
            json_response(['error' => 'Não dá para enviar uma mensagem vazia.'], 422);
        }
        if (mb_strlen($body) > self::MAX_MESSAGE) {
            json_response(['error' => 'Mensagem muito longa (máximo de ' . self::MAX_MESSAGE . ' caracteres).'], 422);
        }

        $author = $access['role'] === 'master' ? 'Mestre ' . $user['name'] : $access['character']['name'];
        $roll = Dice::roll($body);
        (new MessageRepository())->add(
            (int) $room['id'], (int) $user['id'], $author,
            $roll !== null ? Dice::describe($roll) : $body,
            $roll !== null ? 'roll' : 'chat'
        );

        $after = max(0, input_int('after'));
        json_response(['messages' => $this->present((new MessageRepository())->since((int) $room['id'], $after))]);
    }

    /** O mestre dá (ou tira) pontos de ranking de um personagem da sala. */
    public function award(string $id): void
    {
        $user = Auth::requireUser();
        $room = $this->room($id);
        $this->requireMaster($room, $user);

        $character = $this->characters->find(input_int('character_id'));
        $delta = input_int('points');
        if ($character === null || !$this->rooms->isCharacterInRoom((int) $room['id'], (int) $character['id'])) {
            flash('error', 'Personagem não encontrado nesta sala.');
        } elseif ($delta === 0 || abs($delta) > self::MAX_AWARD) {
            flash('error', 'Use de -' . self::MAX_AWARD . ' a +' . self::MAX_AWARD . ' pontos por vez.');
        } else {
            $this->characters->addScore((int) $character['id'], $delta);
            $text = sprintf('O mestre %s %d ponto%s %s %s.', $delta > 0 ? 'deu' : 'tirou', abs($delta), abs($delta) === 1 ? '' : 's', $delta > 0 ? 'para' : 'de', $character['name']);
            $this->system((int) $room['id'], $text);
            flash('success', $text);
        }
        redirect("/salas/{$room['id']}");
    }

    public function destroy(string $id): void
    {
        $user = Auth::requireUser();
        $room = $this->room($id);
        $this->requireMaster($room, $user);
        $this->rooms->delete((int) $room['id']);
        flash('success', 'Sala "' . $room['name'] . '" excluída.');
        redirect('/salas');
    }

    /**
     * Papel do usuário na sala: 'master', 'player' (tem personagem), 'admin' (só observa) ou null.
     * @return array{role: ?string, character: ?array}
     */
    private function access(array $room, array $user): array
    {
        if ((int) $room['master_id'] === (int) $user['id']) {
            return ['role' => 'master', 'character' => null];
        }
        $membership = $this->rooms->membership((int) $room['id'], (int) $user['id']);
        if ($membership !== null) {
            return ['role' => 'player', 'character' => $this->characters->find((int) $membership['character_id'])];
        }
        return ['role' => $user['role'] === 'admin' ? 'admin' : null, 'character' => null];
    }

    /** Só o mestre da sala (ou um administrador) gerencia a mesa. */
    private function requireMaster(array $room, array $user): void
    {
        if ((int) $room['master_id'] !== (int) $user['id'] && !Auth::isAdmin()) {
            View::forbidden('Só o mestre da sala pode fazer isso.');
        }
    }

    /** Sala em que o usuário ainda pode entrar como jogador (senão, redireciona com o motivo). */
    private function joinableRoom(string $id, array $user): array
    {
        $room = $this->room($id);
        $access = $this->access($room, $user);
        if ($access['role'] === 'master' || $access['role'] === 'player') {
            redirect("/salas/{$room['id']}");
        }
        if ((int) $room['players'] >= (int) $room['max_players']) {
            flash('error', 'Esta sala está cheia.');
            redirect('/salas');
        }
        return $room;
    }

    private function system(int $roomId, string $text): void
    {
        (new MessageRepository())->add($roomId, null, 'Doppel', $text, 'system');
    }

    private function room(string $id): array
    {
        return $this->rooms->find((int) $id) ?? View::notFound();
    }

    private function roomOrJsonError(string $id): array
    {
        return $this->rooms->find((int) $id) ?? json_response(['error' => 'Sala não encontrada.'], 404);
    }

    private function present(array $messages): array
    {
        $userId = Auth::id();
        return array_map(static fn (array $m): array => [
            'id' => (int) $m['id'],
            'author' => $m['author'],
            'body' => $m['body'],
            'kind' => $m['kind'],
            'mine' => $m['user_id'] !== null && (int) $m['user_id'] === $userId,
            'time' => date('H:i', strtotime($m['created_at'])),
        ], $messages);
    }
}
