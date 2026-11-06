<?php
/** @var array $room @var string $role @var ?array $character @var array $skills @var array $players @var array $messages */
$roomId = (int) $room['id'];
$canWrite = in_array($role, ['master', 'player'], true);
$canManage = $role === 'master' || $role === 'admin';
$lastId = $messages ? (int) end($messages)['id'] : 0;
?>
<div class="container room-page">
    <header class="room-head panel accent-cyan">
        <div>
            <p class="eyebrow pixel">Sala de RPG · mestre <?= e($room['master_name']) ?></p>
            <h1 class="room-title"><?= e($room['name']) ?></h1>
            <?php if ($room['description'] !== ''): ?><p class="muted"><?= e($room['description']) ?></p><?php endif; ?>
        </div>
        <div class="room-head-side">
            <span class="badge badge-open"><?= count($players) ?>/<?= (int) $room['max_players'] ?> jogadores</span>
            <?php if ($role === 'admin'): ?><span class="badge">Observando como admin</span><?php endif; ?>
            <?php if ($canManage): ?>
                <form method="post" action="<?= url("/salas/$roomId/excluir") ?>" data-confirm="Excluir a sala, todos os personagens e o chat?">
                    <?= csrf_field() ?><button class="link-button danger" type="submit">Excluir sala</button>
                </form>
            <?php endif; ?>
        </div>
    </header>

    <div class="room-layout">
        <section class="panel chat" aria-label="Chat da sala"
                 data-chat data-url="<?= url("/salas/$roomId/mensagens") ?>" data-last-id="<?= $lastId ?>" data-csrf="<?= e(csrf_token()) ?>">
            <ol class="chat-log" data-chat-log aria-live="polite">
                <?php if (!$messages): ?>
                    <li class="chat-empty">Nova sala de RPG, vamos começar?</li>
                <?php endif; ?>
                <?php foreach ($messages as $m): ?>
                    <li class="chat-msg chat-<?= e($m['kind']) ?><?= $m['user_id'] !== null && (int) $m['user_id'] === Doppel\Support\Auth::id() ? ' chat-mine' : '' ?>" data-id="<?= (int) $m['id'] ?>">
                        <?php if ($m['kind'] !== 'system'): ?><span class="chat-author"><?= e($m['author']) ?></span><?php endif; ?>
                        <span class="chat-body"><?= e($m['body']) ?></span>
                        <time class="chat-time"><?= e(date('H:i', strtotime($m['created_at']))) ?></time>
                    </li>
                <?php endforeach; ?>
            </ol>

            <?php if ($canWrite): ?>
                <form class="chat-form" method="post" action="<?= url("/salas/$roomId/mensagens") ?>" data-chat-form>
                    <label class="sr-only" for="mensagem">Mensagem</label>
                    <input id="mensagem" name="message" maxlength="500" autocomplete="off" required
                           placeholder="Escreva, ou role os dados: d20, 2d6+3…">
                    <button class="btn" type="submit" aria-label="Enviar">&rang;</button>
                </form>
                <div class="dice-bar" aria-label="Rolagem rápida">
                    <?php foreach (['d4', 'd6', 'd8', 'd10', 'd12', 'd20', 'd100'] as $die): ?>
                        <button type="button" class="dice-button pixel" data-roll="<?= $die ?>"><?= $die ?></button>
                    <?php endforeach; ?>
                </div>
                <p class="chat-error" data-chat-error role="alert" hidden></p>
            <?php endif; ?>
        </section>

        <aside class="room-side">
            <?php if ($character): ?>
                <section class="panel sheet accent-green">
                    <h2><a href="<?= url('/personagens/' . (int) $character['id']) ?>"><?= e($character['name']) ?></a></h2>
                    <p class="muted"><?= e($character['species_name']) ?> · <?= e($character['class_name']) ?> · nível <?= (int) $character['level'] ?></p>
                    <dl class="vitals">
                        <div><dt>PV</dt><dd><?= (int) $character['hp'] ?></dd></div>
                        <div><dt>Magia</dt><dd><?= (int) $character['spell_slots'] ?></dd></div>
                        <div><dt>Pontos</dt><dd><?= (int) $character['score'] ?></dd></div>
                    </dl>
                    <?= Doppel\Support\View::partial('characters/attributes', ['character' => $character]) ?>
                    <?php if ($character['weapon_name']): ?>
                        <p class="weapon"><strong><?= e($character['weapon_name']) ?></strong> · <?= e($character['weapon_damage']) ?></p>
                    <?php endif; ?>
                    <details class="skills">
                        <summary>Perícias</summary>
                        <ul>
                            <?php foreach ($skills as $name => $skill): ?>
                                <li><span><?= e($name) ?></span><strong><?= signed($skill['bonus']) ?></strong></li>
                            <?php endforeach; ?>
                        </ul>
                    </details>
                </section>
            <?php endif; ?>

            <section class="panel players">
                <h2>Jogadores</h2>
                <?php if (!$players): ?>
                    <p class="muted">Ninguém entrou ainda. Mande o link desta página para o grupo.</p>
                <?php endif; ?>
                <ul class="player-list">
                    <?php foreach ($players as $p): ?>
                        <li>
                            <div class="player-info">
                                <a href="<?= url('/personagens/' . (int) $p['id']) ?>"><?= e($p['name']) ?></a>
                                <small class="muted"><?= e($p['class_name']) ?> · <?= e($p['player_name']) ?> · <?= (int) $p['score'] ?> pts</small>
                            </div>
                            <?php if ($canManage): ?>
                                <form class="award" method="post" action="<?= url("/salas/$roomId/pontos") ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="character_id" value="<?= (int) $p['id'] ?>">
                                    <button name="points" value="-1" class="award-button" aria-label="Tirar 1 ponto de <?= e($p['name']) ?>">−</button>
                                    <button name="points" value="1" class="award-button" aria-label="Dar 1 ponto para <?= e($p['name']) ?>">+</button>
                                </form>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php if ($role === 'master'): ?>
                    <p class="muted small">Como mestre, use + e − para dar pontos de ranking aos jogadores.</p>
                <?php endif; ?>
            </section>
        </aside>
    </div>
</div>
