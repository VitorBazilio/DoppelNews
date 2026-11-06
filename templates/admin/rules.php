<?php
/** @var string $tab @var array $items */
use Doppel\Repository\RulesRepository;

[$label, $singular] = RulesRepository::TYPES[$tab];
$action = url('/admin/regras/' . $tab);
?>
<div class="container admin">
    <?= Doppel\Support\View::partial('admin/nav') ?>
    <h1 class="display-title">Regras de D&amp;D</h1>

    <nav class="category-menu pixel" aria-label="Tipo de regra">
        <?php foreach (RulesRepository::TYPES as $key => [$name]): ?>
            <a href="<?= url('/admin/regras?aba=' . $key) ?>" <?= $tab === $key ? 'aria-current="true"' : '' ?>><?= $name ?></a>
        <?php endforeach; ?>
    </nav>

    <div class="admin-split">
        <section class="panel form-card">
            <h2 class="panel-title">Nova <?= e($singular) ?></h2>
            <form class="form" method="post" action="<?= $action ?>">
                <?= csrf_field() ?>
                <label>Nome<input name="name" value="<?= e(old('name')) ?>" required minlength="2" maxlength="45"></label>

                <?php if ($tab === 'classes'): ?>
                    <div class="form-row">
                        <label>Dado de vida
                            <select name="hit_die"><?php foreach ([6, 8, 10, 12] as $d): ?><option value="<?= $d ?>" <?= old('hit_die', '8') === (string) $d ? 'selected' : '' ?>>d<?= $d ?></option><?php endforeach; ?></select>
                        </label>
                        <label>Espaços de magia (nível 1)<input type="number" name="spell_slots" min="0" max="4" value="<?= e(old('spell_slots', '0')) ?>"></label>
                    </div>
                    <div class="form-row">
                        <?php foreach (['primary_attr' => 'Atributo principal', 'secondary_attr' => 'Atributo secundário'] as $field => $fieldLabel): ?>
                            <label><?= $fieldLabel ?>
                                <select name="<?= $field ?>"><?php foreach (ATTRIBUTES as $code => $attr): ?><option value="<?= $code ?>" <?= old($field) === $code ? 'selected' : '' ?>><?= e($attr) ?></option><?php endforeach; ?></select>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <fieldset class="checks">
                        <legend>Proficiências</legend>
                        <?php foreach (['simple_weapons' => 'Armas simples', 'martial_weapons' => 'Armas marciais', 'light_armor' => 'Armadura leve', 'medium_armor' => 'Armadura média', 'heavy_armor' => 'Armadura pesada'] as $field => $fieldLabel): ?>
                            <label class="check"><input type="checkbox" name="<?= $field ?>" value="1" <?= $field === 'simple_weapons' || old($field) === '1' ? 'checked' : '' ?>> <?= $fieldLabel ?></label>
                        <?php endforeach; ?>
                    </fieldset>
                <?php elseif ($tab === 'especies'): ?>
                    <fieldset>
                        <legend>Bônus nos atributos (0 a 2)</legend>
                        <div class="bonus-grid">
                            <?php foreach (ATTRIBUTES as $code => $attr): ?>
                                <label><?= e($attr) ?><input type="number" name="bonus_<?= $code ?>" min="0" max="2" value="<?= e(old('bonus_' . $code, '0')) ?>"></label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                <?php elseif ($tab === 'armas'): ?>
                    <div class="form-row">
                        <label>Dano<input name="damage" value="<?= e(old('damage')) ?>" placeholder="1d8" required maxlength="12"></label>
                        <label>Categoria
                            <select name="category"><option value="simples">Simples</option><option value="marcial" <?= old('category') === 'marcial' ? 'selected' : '' ?>>Marcial</option></select>
                        </label>
                    </div>
                    <div class="form-row">
                        <label>Preço (po)<input type="number" name="price" min="0" value="<?= e(old('price', '0')) ?>"></label>
                        <label>Peso (kg)<input name="weight" inputmode="decimal" value="<?= e(old('weight', '1')) ?>"></label>
                    </div>
                    <label>Propriedades<input name="properties" value="<?= e(old('properties')) ?>" maxlength="200" placeholder="Leve, acuidade…"></label>
                <?php else: ?>
                    <div class="form-row">
                        <label>Nível <small>(0 = truque)</small><input type="number" name="level" min="0" max="9" value="<?= e(old('level', '1')) ?>"></label>
                        <label>Dano <small>(opcional)</small><input name="damage" value="<?= e(old('damage')) ?>" maxlength="12"></label>
                    </div>
                    <label>Escola<input name="school" value="<?= e(old('school')) ?>" maxlength="30" placeholder="Evocação"></label>
                <?php endif; ?>

                <?php if ($tab !== 'armas'): ?>
                    <label>Descrição<textarea name="description" rows="4" required><?= e(old('description')) ?></textarea></label>
                <?php endif; ?>
                <button class="btn" type="submit">Cadastrar</button>
            </form>
        </section>

        <section class="panel table-wrap">
            <h2 class="panel-title"><?= e($label) ?> cadastradas (<?= count($items) ?>)</h2>
            <table class="table">
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <strong><?= e($item['name']) ?></strong>
                            <small class="muted">
                                <?= match ($tab) {
                                    'classes' => 'd' . (int) $item['hit_die'],
                                    'especies' => e(implode(', ', array_map(static fn ($a, $b) => strtoupper($a) . ' +' . $b, array_keys($item['bonuses']), $item['bonuses']))),
                                    'armas' => e($item['damage'] . ' · ' . $item['category']),
                                    default => (int) $item['level'] === 0 ? 'truque' : 'nível ' . (int) $item['level'],
                                } ?>
                            </small>
                        </td>
                        <td class="actions">
                            <form method="post" action="<?= $action . '/' . (int) $item['id'] . '/excluir' ?>" data-confirm="Excluir <?= e($item['name']) ?>?">
                                <?= csrf_field() ?><button class="link-button danger" type="submit">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</div>
