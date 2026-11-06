<?php

declare(strict_types=1);

namespace Doppel\Controller;

use Doppel\Repository\CharacterRepository;
use Doppel\Repository\MessageRepository;
use Doppel\Repository\RoomRepository;
use Doppel\Repository\RulesRepository;
use Doppel\Rpg\CharacterRules;
use Doppel\Support\Auth;
use Doppel\Support\View;

/** Personagens de D&D: galeria, ranking, ficha e criação (um por sala). */
final class CharacterController
{
    public const TABS = ['ranking' => 'Melhores', 'recentes' => 'Recentes', 'meus' => 'Meus', 'exemplo' => 'Exemplo'];

    private CharacterRepository $characters;

    /** Toda a área de personagens é exclusiva de quem está logado. */
    public function __construct()
    {
        Auth::requireUser();
        $this->characters = new CharacterRepository();
    }

    public function index(): void
    {
        $tab = $_GET['aba'] ?? 'ranking';
        if (!isset(self::TABS[$tab])) {
            $tab = 'ranking';
        }

        $list = match ($tab) {
            'meus' => $this->characters->forUser((int) Auth::id()),
            'recentes' => $this->characters->recent(),
            'exemplo' => [],
            default => $this->characters->ranking(),
        };
        View::render('characters/index', ['tab' => $tab, 'characters' => $list], 'Personagens');
    }

    public function show(string $id): void
    {
        $character = $this->characters->find((int) $id) ?? View::notFound();
        $attributes = CharacterRepository::attributes($character);
        $userId = Auth::id();

        View::render('characters/show', [
            'character' => $character,
            'attributes' => $attributes,
            'skills' => CharacterRules::skills($attributes),
            'canDelete' => $userId !== null && ((int) $character['user_id'] === $userId || Auth::isAdmin()),
        ], $character['name']);
    }

    public function create(string $id): void
    {
        $user = Auth::requireUser();
        $room = $this->joinableRoom($id, $user);
        $rules = new RulesRepository();

        View::render('characters/create', [
            'room' => $room,
            'species' => $rules->all('especies'),
            'classes' => $rules->all('classes'),
            'weapons' => $rules->all('armas'),
        ], 'Criar personagem');
    }

    public function store(string $id): void
    {
        $user = Auth::requireUser();
        $room = $this->joinableRoom($id, $user);
        $rules = new RulesRepository();

        $name = input('name');
        $base = [];
        foreach (array_keys(ATTRIBUTES) as $attr) {
            $base[$attr] = input_int('attr_' . $attr, -1);
        }
        remember_input(array_merge($_POST, ['_csrf' => null]));

        $species = $rules->find('especies', input_int('species_id'));
        $class = $rules->find('classes', input_int('class_id'));
        $weapon = $rules->find('armas', input_int('weapon_id'));

        $error = match (true) {
            mb_strlen($name) < 2 || mb_strlen($name) > 60 => 'O nome do personagem deve ter entre 2 e 60 caracteres.',
            $this->characters->nameTakenInRoom((int) $room['id'], $name) => 'Já existe um personagem com esse nome nesta sala.',
            $species === null => 'Escolha uma espécie.',
            $class === null => 'Escolha uma classe.',
            $weapon === null => 'Escolha uma arma.',
            !$this->canWield($class, $weapon) => "A classe {$class['name']} não sabe usar armas marciais. Escolha uma arma simples.",
            default => CharacterRules::validatePointBuy($base),
        };
        if ($error !== null) {
            flash('error', $error);
            redirect("/salas/{$room['id']}/personagem");
        }

        unset($_SESSION['old']);
        $attributes = CharacterRules::applyBonuses($base, $species['bonuses']);
        $this->characters->create([
            'user_id' => (int) $user['id'],
            'room_id' => (int) $room['id'],
            'name' => $name,
            'species_id' => (int) $species['id'],
            'class_id' => (int) $class['id'],
            'weapon_id' => (int) $weapon['id'],
            'hp' => CharacterRules::hitPoints((int) $class['hit_die'], $attributes['con']),
            'spell_slots' => (int) $class['spell_slots'],
            'attributes' => $attributes,
        ]);
        (new MessageRepository())->add((int) $room['id'], null, 'Doppel', "$name ({$species['name']} {$class['name']}) entrou na sala.", 'system');
        flash('success', "Personagem $name criado. Boa aventura!");
        redirect("/salas/{$room['id']}");
    }

    /** O dono, o mestre da sala ou um admin podem remover um personagem. */
    public function destroy(string $id): void
    {
        $user = Auth::requireUser();
        $character = $this->characters->find((int) $id) ?? View::notFound();
        $room = (new RoomRepository())->find((int) $character['room_id']);
        $isOwner = (int) $character['user_id'] === (int) $user['id'];
        $isMaster = $room !== null && (int) $room['master_id'] === (int) $user['id'];
        if (!$isOwner && !$isMaster && !Auth::isAdmin()) {
            View::forbidden('Você não pode remover este personagem.');
        }

        $this->characters->delete((int) $character['id']);
        (new MessageRepository())->add((int) $character['room_id'], null, 'Doppel', "{$character['name']} saiu da sala.", 'system');
        flash('success', "{$character['name']} foi removido.");
        $isOwner ? redirect('/personagens?aba=meus') : redirect("/salas/{$character['room_id']}");
    }

    private function joinableRoom(string $roomId, array $user): array
    {
        $room = (new RoomRepository())->find((int) $roomId) ?? View::notFound();
        if ((int) $room['master_id'] === (int) $user['id']) {
            flash('info', 'Você é o mestre desta sala e não precisa de personagem.');
            redirect("/salas/{$room['id']}");
        }
        if ($this->characters->findForUserInRoom((int) $user['id'], (int) $room['id']) !== null) {
            redirect("/salas/{$room['id']}");
        }
        if ((int) $room['players'] >= (int) $room['max_players']) {
            flash('error', 'Esta sala está cheia.');
            redirect('/salas');
        }
        return $room;
    }

    private function canWield(array $class, array $weapon): bool
    {
        return $weapon['category'] === 'simples' ? (bool) $class['simple_weapons'] : (bool) $class['martial_weapons'];
    }
}
