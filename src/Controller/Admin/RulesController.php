<?php

declare(strict_types=1);

namespace Doppel\Controller\Admin;

use Doppel\Repository\RulesRepository;
use Doppel\Rpg\Dice;
use Doppel\Support\Auth;
use Doppel\Support\View;

/** Cadastro das regras de D&D usadas na criação de personagens. */
final class RulesController
{
    private RulesRepository $rules;

    public function __construct()
    {
        Auth::requireAdmin();
        $this->rules = new RulesRepository();
    }

    public function index(): void
    {
        $tab = $_GET['aba'] ?? 'classes';
        if (!isset(RulesRepository::TYPES[$tab])) {
            $tab = 'classes';
        }
        View::render('admin/rules', ['tab' => $tab, 'items' => $this->rules->all($tab)], 'Regras');
    }

    public function store(string $type): void
    {
        $this->assertType($type);
        $data = $this->read($type);
        $error = $this->validate($type, $data);
        if ($error !== null) {
            remember_input($_POST);
            flash('error', $error);
        } else {
            $this->rules->create($type, $data);
            flash('success', ucfirst(RulesRepository::TYPES[$type][1]) . " \"{$data['name']}\" cadastrada.");
        }
        redirect("/admin/regras?aba=$type");
    }

    public function destroy(string $type, string $id): void
    {
        $this->assertType($type);
        $item = $this->rules->find($type, (int) $id) ?? View::notFound();
        $usage = $this->rules->usage($type, (int) $item['id']);
        if ($usage > 0) {
            flash('error', "\"{$item['name']}\" é usada por $usage personagem(ns) e não pode ser excluída.");
        } else {
            $this->rules->delete($type, (int) $item['id']);
            flash('success', "\"{$item['name']}\" excluída.");
        }
        redirect("/admin/regras?aba=$type");
    }

    private function assertType(string $type): void
    {
        if (!isset(RulesRepository::TYPES[$type])) {
            View::notFound();
        }
    }

    private function read(string $type): array
    {
        $flag = static fn (string $key): int => isset($_POST[$key]) ? 1 : 0;
        return match ($type) {
            'classes' => [
                'name' => input('name'), 'hit_die' => input_int('hit_die'),
                'primary_attr' => input('primary_attr'), 'secondary_attr' => input('secondary_attr'),
                'spell_slots' => input_int('spell_slots'),
                'simple_weapons' => $flag('simple_weapons'), 'martial_weapons' => $flag('martial_weapons'),
                'light_armor' => $flag('light_armor'), 'medium_armor' => $flag('medium_armor'), 'heavy_armor' => $flag('heavy_armor'),
                'description' => input('description'),
            ],
            'especies' => [
                'name' => input('name'),
                'bonuses' => json_encode(array_filter(
                    array_map(static fn (string $a): int => input_int('bonus_' . $a), array_combine(array_keys(ATTRIBUTES), array_keys(ATTRIBUTES)))
                )),
                'description' => input('description'),
            ],
            'armas' => [
                'name' => input('name'), 'damage' => input('damage'), 'category' => input('category'),
                'price' => input_int('price'), 'weight' => (float) str_replace(',', '.', input('weight')),
                'properties' => input('properties'),
            ],
            'magias' => [
                'name' => input('name'), 'level' => input_int('level'), 'school' => input('school'),
                'damage' => input('damage'), 'description' => input('description'),
            ],
        };
    }

    private function validate(string $type, array $data): ?string
    {
        if (mb_strlen($data['name']) < 2 || mb_strlen($data['name']) > 45) {
            return 'O nome deve ter entre 2 e 45 caracteres.';
        }
        if ($this->rules->nameExists($type, $data['name'])) {
            return "Já existe uma {$this->singular($type)} com esse nome.";
        }
        return match ($type) {
            'classes' => match (true) {
                !in_array($data['hit_die'], [6, 8, 10, 12], true) => 'O dado de vida deve ser d6, d8, d10 ou d12.',
                !isset(ATTRIBUTES[$data['primary_attr']], ATTRIBUTES[$data['secondary_attr']]) => 'Escolha os atributos principais.',
                $data['primary_attr'] === $data['secondary_attr'] => 'Os atributos principal e secundário devem ser diferentes.',
                $data['spell_slots'] < 0 || $data['spell_slots'] > 4 => 'Espaços de magia no nível 1: de 0 a 4.',
                !$data['simple_weapons'] && !$data['martial_weapons'] => 'A classe precisa saber usar algum tipo de arma.',
                $data['description'] === '' => 'Escreva uma descrição.',
                default => null,
            },
            'especies' => match (true) {
                array_filter(json_decode($data['bonuses'], true), static fn (int $b) => $b < 0 || $b > 2) !== [] => 'Cada bônus de atributo vai de 0 a 2.',
                $data['description'] === '' => 'Escreva uma descrição.',
                default => null,
            },
            'armas' => match (true) {
                Dice::roll($data['damage']) === null && !preg_match('/^\d{1,2}$/', $data['damage']) => 'Informe o dano na notação de dados, ex.: 1d8 ou 2d6.',
                !in_array($data['category'], ['simples', 'marcial'], true) => 'A categoria deve ser simples ou marcial.',
                $data['price'] < 0 || $data['weight'] < 0 => 'Preço e peso não podem ser negativos.',
                mb_strlen($data['properties']) > 200 => 'As propriedades podem ter até 200 caracteres.',
                default => null,
            },
            'magias' => match (true) {
                $data['level'] < 0 || $data['level'] > 9 => 'O nível da magia vai de 0 (truque) a 9.',
                $data['description'] === '' => 'Escreva uma descrição.',
                default => null,
            },
        };
    }

    private function singular(string $type): string
    {
        return RulesRepository::TYPES[$type][1];
    }
}
