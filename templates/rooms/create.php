<section class="container narrow">
    <div class="panel form-card accent-cyan">
        <h1 class="panel-title">Criar sala</h1>
        <p class="muted">Você será o mestre. Os jogadores criam o personagem ao entrar na sala.</p>
        <form class="form" method="post" action="<?= url('/salas') ?>">
            <?= csrf_field() ?>
            <label>Nome da sala<input name="name" value="<?= e(old('name')) ?>" required minlength="3" maxlength="60" autofocus></label>
            <label>Descrição <small>(opcional: cenário, horário das sessões…)</small>
                <textarea name="description" rows="3" maxlength="400"><?= e(old('description')) ?></textarea></label>
            <label>Máximo de jogadores<input type="number" name="max_players" value="<?= e(old('max_players', '5')) ?>" min="1" max="12" required></label>
            <div class="form-actions">
                <button class="btn" type="submit">Criar sala</button>
                <a class="btn btn-ghost" href="<?= url('/salas') ?>">Cancelar</a>
            </div>
        </form>
    </div>
</section>
