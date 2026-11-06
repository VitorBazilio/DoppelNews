<?php
/** @var array $user @var array $characters @var array $rooms @var array $playing @var array $runs @var array $stories */
$endingLabel = ['victory' => 'Vitória', 'survived' => 'Sobreviveu', 'defeat' => 'Morreu'];
?>
<div class="container profile">
    <header class="panel profile-head">
        <div>
            <p class="eyebrow pixel"><?= $user['role'] === 'admin' ? 'Administrador' : 'Jogador' ?> desde <?= e(date_long($user['created_at'], false)) ?></p>
            <h1 class="room-title"><?= e($user['name']) ?></h1>
            <p class="muted"><?= e($user['email']) ?></p>
        </div>
        <dl class="vitals">
            <div><dt>Personagens</dt><dd><?= count($characters) ?></dd></div>
            <div><dt>Salas como mestre</dt><dd><?= count($rooms) ?></dd></div>
            <div><dt>Pontos</dt><dd><?= array_sum(array_map('intval', array_column($characters, 'score'))) ?></dd></div>
        </dl>
    </header>

    <div class="profile-grid">
        <section class="panel accent-green">
            <div class="toolbar">
                <h2 class="panel-title">Seus personagens</h2>
                <a class="btn btn-small" href="<?= url('/personagens/novo') ?>">Criar personagem</a>
            </div>
            <?php if (!$characters): ?>
                <p class="muted">Nenhum ainda. Crie seu primeiro herói e use-o para entrar nas salas.</p>
            <?php endif; ?>
            <ul class="simple-list">
                <?php foreach ($characters as $c): ?>
                    <li>
                        <a href="<?= url('/personagens/' . (int) $c['id']) ?>"><?= e($c['name']) ?></a>
                        <small class="muted"><?= e($c['species_name']) ?> <?= e($c['class_name']) ?> · <?= (int) $c['score'] ?> pts · em <?= (int) $c['rooms_count'] ?> sala(s)</small>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="panel accent-cyan">
            <h2 class="panel-title">Suas salas</h2>
            <h3 class="list-heading">Como mestre</h3>
            <?php if (!$rooms): ?>
                <p class="muted">Você ainda não mestrou nenhuma mesa.</p>
            <?php endif; ?>
            <ul class="simple-list">
                <?php foreach ($rooms as $r): ?>
                    <li><a href="<?= url('/salas/' . (int) $r['id']) ?>"><?= e($r['name']) ?></a> <small class="muted"><?= (int) $r['players'] ?>/<?= (int) $r['max_players'] ?> jogadores</small></li>
                <?php endforeach; ?>
            </ul>
            <h3 class="list-heading">Como jogador</h3>
            <?php if (!$playing): ?>
                <p class="muted">Você ainda não entrou em nenhuma sala.</p>
            <?php endif; ?>
            <ul class="simple-list">
                <?php foreach ($playing as $r): ?>
                    <li><a href="<?= url('/salas/' . (int) $r['id']) ?>"><?= e($r['name']) ?></a> <small class="muted">mestre <?= e($r['master_name']) ?></small></li>
                <?php endforeach; ?>
            </ul>
            <div class="form-actions">
                <a class="btn btn-outline" href="<?= url('/salas/nova') ?>">Criar sala</a>
                <a class="btn btn-ghost" href="<?= url('/salas') ?>">Procurar salas</a>
            </div>
        </section>

        <section class="panel accent-purple">
            <h2 class="panel-title">Aventuras</h2>
            <ul class="simple-list">
                <?php foreach ($stories as $id => $story): $run = $runs[$id] ?? null; ?>
                    <li>
                        <a href="<?= url('/aventuras/' . e($id)) ?>"><?= e($story['title']) ?></a>
                        <small class="muted"><?= $run === null ? 'Não iniciada' : ($run['finished'] ? e($endingLabel[$run['ending']] ?? 'Concluída') : 'Em andamento') ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="panel">
            <h2 class="panel-title">Conta</h2>
            <form class="form" method="post" action="<?= url('/perfil/nome') ?>">
                <?= csrf_field() ?>
                <label>Nick<input name="name" value="<?= e($user['name']) ?>" required minlength="2" maxlength="60"></label>
                <button class="btn btn-small" type="submit">Salvar nick</button>
            </form>
            <hr>
            <form class="form" method="post" action="<?= url('/perfil/senha') ?>" data-confirm-password>
                <?= csrf_field() ?>
                <label>Senha atual<input type="password" name="current_password" required autocomplete="current-password"></label>
                <label>Nova senha<input type="password" name="password" required minlength="8" autocomplete="new-password"></label>
                <label>Confirmar nova senha<input type="password" name="password_confirm" required minlength="8" autocomplete="new-password"></label>
                <button class="btn btn-small" type="submit">Trocar senha</button>
            </form>
        </section>
    </div>
</div>
