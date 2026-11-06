<?php

declare(strict_types=1);

namespace Doppel\Controller;

use Doppel\Repository\CharacterRepository;
use Doppel\Repository\RulesRepository;
use Doppel\Rpg\CharacterRules;
use Doppel\Support\Auth;
use Doppel\Support\View;

/** Personagens de D&D: cada usuário cria os seus e escolhe um deles para entrar numa sala. */
final class CharacterController
{
    public const TABS = ['meus' => 'Meus', 'ranking' => 'Melhores', 'recentes' => 'Recentes', 'exemplo' => 'Exemplo'];

    private CharacterRepository $characters;
    private array $user;

    /** Toda a área de personagens é exclusiva de quem está logado. */
    public function __construct()
    {
        $this->user = Auth::requireUser();
        $this->characters = new CharacterRepository();
    }

    public function index(): void
    {
        $tab = $_GET['aba'] ?? 'meus';
        if (!isset(self::TABS[$tab])) {
            $tab = 'meus';
        }
        $list = match ($tab) {
            'meus' => $this->characters->forUser((int) $this->user['id']),
            'recentes' => $this->characters->recent(),
            'ranking' => $this->characters->ranking(),
            'exemplo' => [],
        };
        View::render('characters/index', ['tab' => $tab, 'characters' => $list], 'Personagens');
    }

    public function show(string $id): void
    {
        $character = $this->characters->find((int) $id) ?? View::notFound();
        $attributes = CharacterRepository::attributes($character);

        View::render('characters/show', [
            'character' => $character,
            'attributes' => $attributes,
            'skills' => CharacterRules::skills($attributes),
            'rooms' => $this->characters->roomsOf((int) $character['id']),
            'canDelete' => (int) $character['user_id'] === (int) $this->user['id'] || Auth::isAdmin(),
        ], $character['name']);
    }

    public function create(): void
    {
        $rules = new RulesRepository();
        View::render('characters/create', [
            'roomId' => $this->returnRoomId(),
            'species' => $rules->all('especies'),
            'classes' => $rules->all('classes'),
            'weapons' => $rules->all('armas'),
        ], 'Criar personagem');
    }

    public function store(): void
    {
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
            $this->characters->nameTakenByUser((int) $this->user['id'], $name) => 'Você já tem um personagem com esse nome.',
            $species === null => 'Escolha uma espécie.',
            $class === null => 'Escolha uma classe.',
            $weapon === null => 'Escolha uma arma.',
            !$this->canWield($class, $weapon) => "A classe {$class['name']} não sabe usar armas marciais. Escolha uma arma simples.",
            default => CharacterRules::validatePointBuy($base),
        };
        $roomId = $this->returnRoomId();
        if ($error !== null) {
            flash('error', $error);
            redirect('/personagens/novo' . ($roomId ? "?sala=$roomId" : ''));
        }

        unset($_SESSION['old']);
        $attributes = CharacterRules::applyBonuses($base, $species['bonuses']);
        $id = $this->characters->create([
            'user_id' => (int) $this->user['id'],
            'name' => $name,
            'species_id' => (int) $species['id'],
            'class_id' => (int) $class['id'],
            'weapon_id' => (int) $weapon['id'],
            'hp' => CharacterRules::hitPoints((int) $class['hit_die'], $attributes['con']),
            'spell_slots' => (int) $class['spell_slots'],
            'attributes' => $attributes,
        ]);
        flash('success', "Personagem $name criado!");
        // Veio de uma sala? Volta para escolher o personagem e entrar nela.
        redirect($roomId ? "/salas/$roomId/entrar" : "/personagens/$id");
    }

    /** Só o dono ou um administrador apagam a ficha (ela sai de todas as salas). */
    public function destroy(string $id): void
    {
        $character = $this->characters->find((int) $id) ?? View::notFound();
        if ((int) $character['user_id'] !== (int) $this->user['id'] && !Auth::isAdmin()) {
            View::forbidden('Você não pode apagar este personagem.');
        }
        $this->characters->delete((int) $character['id']);
        flash('success', "{$character['name']} foi apagado.");
        redirect('/personagens?aba=meus');
    }

    /** Sala para onde voltar depois de criar o personagem (?sala= no link ou campo do formulário). */
    private function returnRoomId(): int
    {
        return max(0, (int) ($_POST['sala'] ?? $_GET['sala'] ?? 0));
    }

    private function canWield(array $class, array $weapon): bool
    {
        return $weapon['category'] === 'simples' ? (bool) $class['simple_weapons'] : (bool) $class['martial_weapons'];
    }
}
