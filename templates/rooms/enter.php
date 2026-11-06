<?php /** @var array $room @var array $characters personagens do usuário */ ?>
<section class="container narrow-wide">
    <div class="panel accent-cyan">
        <p class="eyebrow pixel">Sala de RPG · mestre <?= e($room['master_name']) ?></p>
        <h1 class="room-title"><?= e($room['name']) ?></h1>
        <?php if ($room['description'] !== ''): ?><p class="muted"><?= e($room['description']) ?></p><?php endif; ?>
        <p><span class="badge badge-open"><?= (int) $room['players'] ?>/<?= (int) $room['max_players'] ?> jogadores</span></p>

        <h2 class="panel-title">Escolha seu personagem</h2>
        <?php if (!$characters): ?>
            <p class="muted">Você ainda não tem personagens. Crie um e volte para entrar nesta sala.</p>
            <a class="btn" href="<?= url('/personagens/novo?sala=' . (int) $room['id']) ?>">Criar personagem</a>
        <?php else: ?>
            <form method="post" action="<?= url('/salas/' . (int) $room['id'] . '/entrar') ?>" class="pick-list">
                <?= csrf_field() ?>
                <?php foreach ($characters as $i => $c): ?>
                    <label class="pick">
                        <input type="radio" name="character_id" value="<?= (int) $c['id'] ?>" <?= $i === 0 ? 'checked' : '' ?> required>
                        <span class="pick-body">
                            <strong><?= e($c['name']) ?></strong>
                            <small class="muted"><?= e($c['species_name']) ?> · <?= e($c['class_name']) ?> · <?= (int) $c['hp'] ?> PV · <?= (int) $c['score'] ?> pts</small>
                            <?= Doppel\Support\View::partial('characters/attributes', ['character' => $c]) ?>
                        </span>
                    </label>
                <?php endforeach; ?>
                <div class="form-actions">
                    <button class="btn" type="submit">Entrar na sala</button>
                    <a class="btn btn-ghost" href="<?= url('/personagens/novo?sala=' . (int) $room['id']) ?>">Criar outro personagem</a>
                    <a class="btn btn-ghost" href="<?= url('/salas') ?>">Voltar</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</section>
