<?php
/** @var string $tab @var array $characters */
use Doppel\Controller\CharacterController;
use Doppel\Rpg\CharacterRules;

// Ficha de exemplo do Doppel original (Malus Leucis, tiefling guerreiro).
$example = ['attr_for' => 15, 'attr_des' => 15, 'attr_con' => 10, 'attr_int' => 12, 'attr_sab' => 10, 'attr_car' => 12];
?>
<?= Doppel\Support\View::partial('partials/page-hero', [
    'title' => 'Personagens',
    'subtitle' => 'Os heróis das nossas mesas: ranking, fichas recentes e os seus personagens.',
    'image' => 'banner-dnd.webp',
    'accent' => 'green',
]) ?>

<div class="container">
    <section class="panel accent-green">
        <div class="toolbar">
            <nav class="category-menu pixel" aria-label="Filtrar personagens">
                <?php foreach (CharacterController::TABS as $key => $label): ?>
                    <a href="<?= url('/personagens?aba=' . $key) ?>" <?= $tab === $key ? 'aria-current="true"' : '' ?>><?= $label ?></a>
                <?php endforeach; ?>
            </nav>
            <a class="btn btn-outline" href="<?= url('/regras') ?>">Ver regras</a>
        </div>

        <?php if ($tab === 'exemplo'): ?>
            <article class="example-sheet">
                <img src="<?= e(asset('img/personagem-exemplo.webp')) ?>" alt="Ilustração de Malus Leucis" loading="lazy">
                <div>
                    <h2>Malus Leucis</h2>
                    <p class="muted">Tiefling · Guerreiro · nível 1 · Arco Longo (1d8)</p>
                    <?= Doppel\Support\View::partial('characters/attributes', ['character' => $example]) ?>
                    <p>Uma ficha montada com a compra de pontos (27 pontos) já com os bônus da espécie. Guerreiros usam d10 como dado de vida: com Constituição 10, Malus começa com <?= CharacterRules::hitPoints(10, 10) ?> PV.</p>
                    <p class="muted">Para criar o seu, entre em uma <a href="<?= url('/salas') ?>">sala</a>: cada personagem pertence a uma mesa.</p>
                </div>
            </article>
        <?php elseif (!$characters): ?>
            <p class="empty">
                <?= $tab === 'meus' ? 'Você ainda não tem personagens.' : 'Nenhum personagem criado ainda.' ?>
                <a href="<?= url('/salas') ?>">Escolha uma sala</a> para criar o seu.
            </p>
        <?php else: ?>
            <?php if ($tab === 'ranking'): ?>
                <ol class="ranking">
                    <?php foreach ($characters as $i => $c): ?>
                        <li>
                            <span class="rank-pos pixel"><?= $i + 1 ?></span>
                            <div class="rank-info">
                                <a href="<?= url('/personagens/' . (int) $c['id']) ?>"><?= e($c['name']) ?></a>
                                <small class="muted"><?= e($c['species_name']) ?> <?= e($c['class_name']) ?> · <?= e($c['player_name']) ?> · sala <?= e($c['room_name']) ?></small>
                            </div>
                            <strong class="rank-score"><?= (int) $c['score'] ?> pts</strong>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php else: ?>
                <div class="card-grid">
                    <?php foreach ($characters as $c): ?>
                        <article class="character-card">
                            <h2><a href="<?= url('/personagens/' . (int) $c['id']) ?>"><?= e($c['name']) ?></a></h2>
                            <p class="muted"><?= e($c['species_name']) ?> · <?= e($c['class_name']) ?> · <?= (int) $c['hp'] ?> PV</p>
                            <?= Doppel\Support\View::partial('characters/attributes', ['character' => $c]) ?>
                            <p class="small muted">Sala <a href="<?= url('/salas/' . (int) $c['room_id']) ?>"><?= e($c['room_name']) ?></a> · <?= (int) $c['score'] ?> pts</p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</div>
