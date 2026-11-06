<?php
/** @var string $tab @var array $items */
use Doppel\Repository\RulesRepository;

$armor = ['light_armor' => 'leve', 'medium_armor' => 'média', 'heavy_armor' => 'pesada'];
?>
<?= Doppel\Support\View::partial('partials/page-hero', [
    'title' => 'Regras',
    'subtitle' => 'Classes, espécies, armas e magias de D&D 5ª edição usadas nas fichas do Doppel.',
    'image' => 'banner-dnd.webp',
    'accent' => 'green',
]) ?>

<div class="container">
    <section class="panel accent-green">
        <nav class="category-menu pixel" aria-label="Tipo de regra">
            <?php foreach (RulesRepository::TYPES as $key => [$label]): ?>
                <a href="<?= url('/regras?aba=' . $key) ?>" <?= $tab === $key ? 'aria-current="true"' : '' ?>><?= $label ?></a>
            <?php endforeach; ?>
        </nav>

        <?php if (!$items): ?>
            <p class="empty">Nada cadastrado aqui ainda.</p>
        <?php elseif ($tab === 'classes'): ?>
            <div class="card-grid">
                <?php foreach ($items as $c): $armors = array_keys(array_filter(array_intersect_key($c, $armor))); ?>
                    <article class="rule-card">
                        <h2><?= e($c['name']) ?> <span class="badge">d<?= (int) $c['hit_die'] ?></span></h2>
                        <p><?= e($c['description']) ?></p>
                        <ul class="rule-facts">
                            <li><strong>Atributos:</strong> <?= e(ATTRIBUTES[$c['primary_attr']]) ?> e <?= e(ATTRIBUTES[$c['secondary_attr']]) ?></li>
                            <li><strong>Armas:</strong> <?= $c['martial_weapons'] ? 'simples e marciais' : 'simples' ?></li>
                            <li><strong>Armaduras:</strong> <?= $armors ? e(implode(', ', array_map(static fn ($k) => $armor[$k], $armors))) : 'nenhuma' ?></li>
                            <li><strong>Espaços de magia (nível 1):</strong> <?= (int) $c['spell_slots'] ?></li>
                        </ul>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php elseif ($tab === 'especies'): ?>
            <div class="card-grid">
                <?php foreach ($items as $s): ?>
                    <article class="rule-card">
                        <h2><?= e($s['name']) ?></h2>
                        <p class="bonuses">
                            <?php foreach ($s['bonuses'] as $attr => $bonus): ?>
                                <span class="badge"><?= e(ATTRIBUTES[$attr] ?? $attr) ?> +<?= (int) $bonus ?></span>
                            <?php endforeach; ?>
                        </p>
                        <p><?= e($s['description']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php elseif ($tab === 'armas'): ?>
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Arma</th><th>Categoria</th><th>Dano</th><th>Preço</th><th>Peso</th><th>Propriedades</th></tr></thead>
                    <tbody>
                    <?php foreach ($items as $w): ?>
                        <tr>
                            <td><strong><?= e($w['name']) ?></strong></td>
                            <td><?= e(ucfirst($w['category'])) ?></td>
                            <td><?= e($w['damage']) ?></td>
                            <td><?= (int) $w['price'] ?> po</td>
                            <td><?= e(rtrim(rtrim(number_format((float) $w['weight'], 2, ',', ''), '0'), ',')) ?> kg</td>
                            <td class="muted"><?= e($w['properties'] ?: '—') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="card-grid">
                <?php foreach ($items as $m): ?>
                    <article class="rule-card">
                        <h2><?= e($m['name']) ?> <span class="badge"><?= (int) $m['level'] === 0 ? 'Truque' : 'Nível ' . (int) $m['level'] ?></span></h2>
                        <p class="muted"><?= e($m['school']) ?><?= $m['damage'] !== '' ? ' · ' . e($m['damage']) : '' ?></p>
                        <p><?= e($m['description']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>
