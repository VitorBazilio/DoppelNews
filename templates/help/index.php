<?php
use Doppel\Rpg\CharacterRules;

$books = [
    ['Guia_do_Mestre.pdf', 'guia-do-mestre.webp', 'Guia do Mestre', 'Venha aprender a ser um mestre: criação de aventuras, mundos e recompensas.', '20 MB'],
    ['Livro_do_Jogador.pdf', 'livro-do-jogador.webp', 'Livro do Jogador', 'Melhore seu modo de jogo: classes, espécies, magias e todas as regras básicas.', '2,9 MB'],
    ['Manual_dos_Monstros.pdf', 'manual-dos-monstros.webp', 'Manual dos Monstros', 'Aprenda sobre os inimigos que vão cruzar o caminho do seu grupo.', '2,7 MB'],
];
$faq = [
    'Preciso de conta para usar o site?' => 'Para ler notícias e jogar as Aventuras, não. Para entrar em salas, criar personagens e acompanhar o ranking, sim: é grátis e leva um minuto.',
    'Como entro em uma sala?' => 'Abra a página Salas, escolha uma mesa com vaga (ou clique em Sorte) e crie seu personagem. Cada personagem pertence a uma sala, e você pode ter um por sala.',
    'Como crio minha própria mesa?' => 'Em Salas, clique em Criar sala. Você vira o mestre: não precisa de personagem, conversa como "Mestre" no chat e pode dar pontos de ranking aos jogadores.',
    'Como funciona o ranking?' => 'O mestre de cada sala dá (ou tira) pontos dos personagens pelo desempenho nas sessões. Os 10 mais pontuados aparecem em Personagens › Melhores.',
    'Qual a diferença entre Salas e Aventuras?' => 'As Salas são mesas com outras pessoas e um mestre. As Aventuras são livros-jogo para jogar sozinho, com escolhas e testes de dados decididos pelo próprio site.',
];
?>
<?= Doppel\Support\View::partial('partials/page-hero', [
    'title' => 'Ajuda',
    'subtitle' => 'Livros de D&D, como jogar no Doppel, perguntas frequentes e contato.',
    'image' => 'banner-dnd.webp',
    'accent' => 'gold',
]) ?>

<div class="container help-page">
    <section class="panel accent-gold" id="livros">
        <h2 class="panel-title">Livros de D&amp;D</h2>
        <div class="books">
            <?php foreach ($books as [$file, $cover, $title, $text, $size]): ?>
                <a class="book" href="<?= e(asset('livros/' . $file)) ?>" target="_blank" rel="noopener">
                    <img src="<?= e(asset('img/' . $cover)) ?>" alt="" loading="lazy">
                    <strong><?= e($title) ?></strong>
                    <span class="muted"><?= e($text) ?></span>
                    <span class="book-size pixel">PDF · <?= e($size) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="panel accent-gold" id="como-jogar">
        <h2 class="panel-title">Como jogar no Doppel</h2>
        <div class="help-columns">
            <div>
                <h3>Rolando dados no chat</h3>
                <p>Na sala, envie só a expressão do dado e o site rola por você, para todos verem:</p>
                <ul>
                    <li><code>d20</code>: um dado de 20 lados</li>
                    <li><code>2d6+3</code>: dois d6 somando 3</li>
                    <li><code>/r 1d8-1</code>: também funciona com <code>/r</code></li>
                </ul>
                <p class="muted">Dados aceitos: d2, d4, d6, d8, d10, d12, d20 e d100, até 20 de uma vez.</p>
            </div>
            <div>
                <h3>Criando o personagem</h3>
                <p>Usamos a compra de pontos do Livro do Jogador: todo atributo começa em <?= CharacterRules::BASE ?> e você tem <?= CharacterRules::BUDGET ?> pontos para distribuir até <?= CharacterRules::MAX_BUY ?>.</p>
                <table class="table compact">
                    <thead><tr><th>Valor</th><?php foreach (CharacterRules::COSTS as $value => $cost): ?><th><?= $value ?></th><?php endforeach; ?></tr></thead>
                    <tbody><tr><th>Custo</th><?php foreach (CharacterRules::COSTS as $cost): ?><td><?= $cost ?></td><?php endforeach; ?></tr></tbody>
                </table>
                <p>Depois entram os bônus da espécie. O modificador de cada atributo é (valor − 10) ÷ 2, arredondado para baixo, e os pontos de vida iniciais são o máximo do dado de vida da classe + o modificador de Constituição.</p>
            </div>
        </div>
    </section>

    <section class="panel accent-gold" id="perguntas">
        <h2 class="panel-title">Perguntas frequentes</h2>
        <?php foreach ($faq as $question => $answer): ?>
            <details class="faq">
                <summary><?= e($question) ?></summary>
                <p><?= e($answer) ?></p>
            </details>
        <?php endforeach; ?>
    </section>

    <section class="panel accent-gold" id="contato">
        <h2 class="panel-title">Fale conosco</h2>
        <p>Dúvidas, sugestões de pauta ou problemas no site? Escreva para a equipe:</p>
        <p><a class="btn" href="mailto:DoppelNews.site@gmail.com"><?= icon('mail') ?> DoppelNews.site@gmail.com</a></p>
    </section>
</div>
