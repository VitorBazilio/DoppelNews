<?php
/** @var array $character @var array $attributes @var array $skills @var bool $canDelete */
use Doppel\Rpg\CharacterRules;
?>
<section class="container narrow-wide">
    <div class="panel sheet-page accent-green">
        <header class="sheet-head">
            <div>
                <p class="eyebrow pixel">Ficha de personagem</p>
                <h1 class="room-title"><?= e($character['name']) ?></h1>
                <p class="muted"><?= e($character['species_name']) ?> · <?= e($character['class_name']) ?> · nível <?= (int) $character['level'] ?> · jogador <?= e($character['player_name']) ?></p>
            </div>
            <dl class="vitals">
                <div><dt>PV</dt><dd><?= (int) $character['hp'] ?></dd></div>
                <div><dt>Dado de vida</dt><dd>d<?= (int) $character['hit_die'] ?></dd></div>
                <div><dt>Magia</dt><dd><?= (int) $character['spell_slots'] ?></dd></div>
                <div><dt>Pontos</dt><dd><?= (int) $character['score'] ?></dd></div>
            </dl>
        </header>

        <h2>Atributos</h2>
        <?= Doppel\Support\View::partial('characters/attributes', ['character' => $character]) ?>

        <div class="sheet-columns">
            <div>
                <h2>Perícias</h2>
                <ul class="skill-list">
                    <?php foreach ($skills as $name => $skill): ?>
                        <li><span><?= e($name) ?> <small class="muted">(<?= e(ATTRIBUTES[$skill['attr']]) ?>)</small></span><strong><?= signed($skill['bonus']) ?></strong></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h2>Equipamento de batalha</h2>
                <?php if ($character['weapon_name']): ?>
                    <p><strong><?= e($character['weapon_name']) ?></strong> · dano <?= e($character['weapon_damage']) ?></p>
                    <?php if ($character['weapon_properties'] !== ''): ?><p class="muted"><?= e($character['weapon_properties']) ?></p><?php endif; ?>
                <?php else: ?>
                    <p class="muted">Nenhuma arma.</p>
                <?php endif; ?>
                <h2>Sala</h2>
                <p><a href="<?= url('/salas/' . (int) $character['room_id']) ?>"><?= e($character['room_name']) ?></a></p>
                <p class="muted small">Criado em <?= e(date_long($character['created_at'], false)) ?>. Iniciativa <?= signed(CharacterRules::modifier($attributes['des'])) ?>.</p>
            </div>
        </div>

        <?php if ($canDelete): ?>
            <form method="post" action="<?= url('/personagens/' . (int) $character['id'] . '/excluir') ?>" data-confirm="Remover <?= e($character['name']) ?> da sala?">
                <?= csrf_field() ?><button class="link-button danger" type="submit">Remover personagem</button>
            </form>
        <?php endif; ?>
    </div>
</section>
