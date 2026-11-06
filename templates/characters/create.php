<?php
/** @var int $roomId sala para onde voltar depois (0 = nenhuma) @var array $species @var array $classes @var array $weapons */
use Doppel\Rpg\CharacterRules;

$rules = [
    'costs' => CharacterRules::COSTS,
    'budget' => CharacterRules::BUDGET,
    'species' => array_column(array_map(static fn ($s) => [$s['id'], $s['bonuses']], $species), 1, 0),
    'classes' => array_column(array_map(static fn ($c) => [$c['id'], [
        'hitDie' => (int) $c['hit_die'], 'simple' => (bool) $c['simple_weapons'], 'martial' => (bool) $c['martial_weapons'],
        'primary' => $c['primary_attr'], 'secondary' => $c['secondary_attr'],
    ]], $classes), 1, 0),
];
?>
<section class="container narrow-wide">
    <div class="panel accent-green">
        <p class="eyebrow pixel">Seus heróis</p>
        <h1 class="panel-title">Criar personagem</h1>
        <p class="muted">O personagem fica na sua conta e pode entrar em qualquer sala. Escolha espécie, classe e arma, e distribua <?= CharacterRules::BUDGET ?> pontos entre os atributos (de <?= CharacterRules::BASE ?> a <?= CharacterRules::MAX_BUY ?>, antes dos bônus da espécie). Os valores 14 e 15 custam mais caro, como no Livro do Jogador.</p>

        <form class="form character-form" method="post" action="<?= url('/personagens') ?>" data-point-buy>
            <?= csrf_field() ?>
            <?php if ($roomId): ?><input type="hidden" name="sala" value="<?= $roomId ?>"><?php endif; ?>
            <script type="application/json" data-rules><?= json_encode($rules, JSON_HEX_TAG | JSON_UNESCAPED_UNICODE) ?></script>

            <label>Nome do personagem<input name="name" value="<?= e(old('name')) ?>" required minlength="2" maxlength="60" autofocus></label>

            <div class="form-row">
                <label>Espécie
                    <select name="species_id" required data-species>
                        <option value="">Escolha…</option>
                        <?php foreach ($species as $s): ?>
                            <option value="<?= (int) $s['id'] ?>" <?= old('species_id') === (string) $s['id'] ? 'selected' : '' ?>><?= e($s['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Classe
                    <select name="class_id" required data-class>
                        <option value="">Escolha…</option>
                        <?php foreach ($classes as $c): ?>
                            <option value="<?= (int) $c['id'] ?>" <?= old('class_id') === (string) $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?> (d<?= (int) $c['hit_die'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Arma
                    <select name="weapon_id" required data-weapon>
                        <option value="">Escolha…</option>
                        <?php foreach (['simples' => 'Armas simples', 'marcial' => 'Armas marciais'] as $category => $label): ?>
                            <optgroup label="<?= $label ?>">
                                <?php foreach ($weapons as $w): if ($w['category'] !== $category) { continue; } ?>
                                    <option value="<?= (int) $w['id'] ?>" data-category="<?= e($w['category']) ?>" <?= old('weapon_id') === (string) $w['id'] ? 'selected' : '' ?>><?= e($w['name']) ?> (<?= e($w['damage']) ?>)</option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
            <p class="muted small" data-class-hint></p>

            <fieldset class="point-buy">
                <legend>Atributos <span class="points-left pixel" data-points-left><?= CharacterRules::BUDGET ?> pontos restantes</span></legend>
                <?php foreach (ATTRIBUTES as $code => $label): ?>
                    <div class="point-row" data-attr="<?= $code ?>">
                        <label for="attr_<?= $code ?>"><?= e($label) ?></label>
                        <input id="attr_<?= $code ?>" type="number" name="attr_<?= $code ?>" min="<?= CharacterRules::BASE ?>" max="<?= CharacterRules::MAX_BUY ?>"
                               value="<?= e(old('attr_' . $code, (string) CharacterRules::BASE)) ?>" required>
                        <span class="point-bonus" data-bonus></span>
                        <span class="point-final" data-final></span>
                        <span class="point-mod" data-mod></span>
                    </div>
                <?php endforeach; ?>
            </fieldset>
            <p class="muted small" data-summary>Pontos de vida no nível 1: valor máximo do dado de vida da classe + modificador de Constituição.</p>

            <div class="form-actions">
                <button class="btn" type="submit">Criar personagem</button>
                <a class="btn btn-ghost" href="<?= url($roomId ? "/salas/$roomId/entrar" : '/personagens?aba=meus') ?>">Cancelar</a>
            </div>
        </form>
    </div>
</section>
