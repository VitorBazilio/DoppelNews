<?php

// Notícias de exemplo: as do TCC original (revisadas) e as da versão DoppelNews.
// 'hours_ago' define a data de publicação em relação ao momento da instalação.
// 'featured' => true coloca a notícia no carrossel da página inicial.

return [
    [
        'title' => 'Cyberpunk 2077 é inspirado em Deus Ex e Vampire: The Masquerade',
        'summary' => 'Designer da CD Projekt Red revela as influências do RPG ambientado em Night City.',
        'body' => "Em entrevista, um dos designers de Cyberpunk 2077 contou que a equipe bebeu de clássicos como Deus Ex e Vampire: The Masquerade – Bloodlines na hora de pensar a liberdade do jogador.\n\nA ideia é que cada missão possa ser resolvida de vários jeitos: no tiroteio, na conversa ou com implantes cibernéticos que abrem caminhos alternativos.\n\nNight City promete ser a cidade mais densa que o estúdio já criou.",
        'category' => 'Games', 'image' => 'destaque-cyberpunk.webp', 'author' => 'Vitor Bazilio',
        'featured' => true, 'hours_ago' => 2,
    ],
    [
        'title' => 'Final Fantasy VII Remake ainda será lançado em episódios',
        'summary' => 'A Square Enix confirmou que o remake continua dividido em várias partes.',
        'body' => "A Square Enix reafirmou que Final Fantasy VII Remake será lançado em episódios, cada um com o tamanho de um jogo completo.\n\nSegundo a produtora, a divisão permite recriar Midgar e o restante do mundo com o nível de detalhe que os fãs esperam, sem cortar conteúdo da história original.",
        'category' => 'Games', 'image' => 'destaque-ff7.webp', 'author' => 'Felipe Antenori',
        'featured' => true, 'hours_ago' => 5,
    ],
    [
        'title' => 'Sekiro: Shadows Die Twice vendeu mais de dois milhões de cópias',
        'summary' => 'O novo jogo da FromSoftware passou dos dois milhões em menos de dez dias.',
        'body' => "Sekiro: Shadows Die Twice ultrapassou a marca de dois milhões de cópias vendidas em menos de dez dias, segundo a Activision.\n\nO jogo troca a fantasia sombria de Dark Souls pelo Japão da era Sengoku e aposta em um combate baseado em postura e contra-ataques.",
        'category' => 'Games', 'image' => 'destaque-sekiro.webp', 'author' => 'Willberson Laurentino',
        'featured' => true, 'hours_ago' => 9,
    ],
    [
        'title' => 'Novo The Legend of Zelda já está em produção',
        'summary' => 'Eiji Aonuma confirmou que a próxima aventura de Link está em desenvolvimento.',
        'body' => "O produtor Eiji Aonuma afirmou que a equipe já trabalha no próximo capítulo da série.\n\nAinda não há data de lançamento nem plataformas confirmadas.",
        'category' => 'Games', 'image' => 'zelda-ruinas.webp', 'author' => 'Equipe Doppel',
        'featured' => false, 'hours_ago' => 20,
    ],
    [
        'title' => 'Por que os livros-jogo estão voltando',
        'summary' => 'Aventuras em que você decide o rumo da história ganham uma nova geração de leitores.',
        'body' => "Os livros-jogo, populares nos anos 80, ressurgem em versões digitais.\n\nA mistura de narrativa, escolhas e consequências conquista tanto fãs de RPG quanto leitores casuais.\n\nExperimente os cenários históricos na seção Aventuras do Doppel.",
        'category' => 'RPG', 'image' => 'banner-dnd.webp', 'author' => 'Equipe Doppel',
        'featured' => false, 'hours_ago' => 30,
    ],
    [
        'title' => 'A Primeira Guerra Mundial vista como RPG',
        'summary' => 'Cenários históricos transformam o conflito de 1914–1918 em aventura interativa.',
        'body' => "Da lama da Frente Ocidental às ruas da Roma antiga, jogos de escolhas ajudam a entender decisões históricas.\n\nEscolha com cuidado: nem todo soldado volta para casa.",
        'category' => 'História', 'image' => null, 'author' => 'Equipe Doppel',
        'featured' => false, 'hours_ago' => 44,
    ],
    [
        'title' => 'Review de Boku no Hero Academia',
        'summary' => 'Veja nossa análise sobre Boku no Hero Academia e tudo o que achamos da obra.',
        'body' => "Boku no Hero Academia surpreendeu a todos e se destacou na Shonen Jump. Com o anime em produção, a expectativa dos leitores subiu ainda mais. Mas, afinal, o que é Boku no Hero Academia? Em um mundo onde ter superpoderes se tornou algo comum, as pessoas usam suas habilidades para aperfeiçoar suas funções na sociedade e ajudar a humanidade.\n\nNo entanto, entre os diversos benefícios que as \"individualidades\" oferecem, surgem também consequências, como novas profissões para combater quem as usa para espalhar terror e caos: os heróis profissionais.\n\nCom a grande demanda por heróis no combate aos vilões, as escolas que formam heróis se tornaram essenciais e cobiçadas pelos jovens, já que ser um herói profissional é o sonho de muitos.\n\nO protagonista, Izuku Midoriya, é um desses jovens, mas não possui nenhuma individualidade, o que faz com que seja menosprezado pelo rival Bakugou. Até o dia em que encontra seu maior ídolo, All Might, o Símbolo da Paz, que o escolhe como sucessor para receber seus poderes.\n\nA trama acompanha Midoriya tentando seguir os passos de All Might na U.A., uma das escolas de heróis mais prestigiadas do Japão, onde conhece os colegas da turma 1-A e enfrenta diversos desafios para se formar.",
        'category' => 'Anime', 'image' => 'boku-no-hero.webp', 'author' => 'Lucas Pereira',
        'featured' => false, 'hours_ago' => 60,
    ],
    [
        'title' => 'O nome do Coringa é revelado',
        'summary' => 'O verdadeiro nome do Coringa foi revelado em uma HQ. Veja nossa opinião.',
        'body' => "O Coringa, um dos vilões de maior destaque da DC, ganhará uma nova história. A editora anunciou um universo à parte, que não afeta a cronologia principal, em que os papéis se invertem: escrito e ilustrado por Sean Gordon Murphy, o quadrinho terá o Coringa como o novo herói de Gotham City e o Batman como seu antagonista.\n\nA notícia chamou ainda mais atenção pela revelação do verdadeiro nome do vilão. Murphy anunciou nas redes sociais: \"Grande novidade: Jack Napier será o nome do meu Coringa. A Warner acabou de aprovar. É a primeira vez que Napier aparece nos quadrinhos (exceto na adaptação do filme de Burton).\"\n\nJack Napier foi o nome usado por Jack Nicholson em Batman, filme de 1989 dirigido por Tim Burton.",
        'category' => 'Hqs', 'image' => 'coringa.webp', 'author' => 'LinkMcCall',
        'featured' => false, 'hours_ago' => 80,
    ],
    [
        'title' => 'Cyberpunk 2077: o que esperar até agora',
        'summary' => 'Nossa opinião sobre Cyberpunk 2077 até aqui. Leia a matéria e pense com a gente sobre o game.',
        'body' => "O que podemos esperar do novo game da CD Projekt Red? Depois de passarmos horas explorando o mundo fantástico de Andrzej Sapkowski em The Witcher 3, será que vem aí algo parecido em RPG e exploração, só que agora em um futuro distópico e caótico?\n\nCom o gameplay de 48 minutos divulgado pelo estúdio, não tem como não ficar maluco com um mundo que parece tão vivo: carros voadores, modificações corporais robóticas, uma ambientação futurista no melhor estilo Blade Runner e NPCs que parecem ter uma inteligência artificial única.\n\nA CD Projekt Red já anunciou que a história está pronta do começo ao fim. Agora só nos resta esperar as otimizações e o polimento, e torcer para que ele seja realmente incrível!",
        'category' => 'Games', 'image' => 'cyberpunk.webp', 'author' => 'Roberto Malus',
        'featured' => false, 'hours_ago' => 100,
    ],
    [
        'title' => 'God of War e seu New Game+',
        'summary' => 'Um monstro volta a despertar: o modo New Game+ chegou de graça.',
        'body' => "O New Game+ de God of War chegou como atualização gratuita em 20 de agosto. Para acessá-lo, é preciso terminar a história em qualquer dificuldade.\n\nInspirado no retorno dos fãs, o modo traz equipamentos novos, inimigos mais difíceis e a opção de pular as cenas da história.\n\nReviva a jornada de Kratos e Atreus com todo o seu equipamento, armaduras, encantamentos, talismãs e recursos, na dificuldade que quiser, contra inimigos de nível mais alto, alguns até com truques novos na manga.\n\nExclusivo do New Game+, há um novo nível de raridade de equipamento. Colete o Skap Slag, um novo recurso, para levar seus itens ao melhor nível possível com os ferreiros.\n\nPor fim, mergulhe direto na ação: a opção de pular as cenas funciona tanto no modo normal quanto no New Game+, depois que você terminar o jogo pelo menos uma vez.",
        'category' => 'Games', 'image' => 'god-of-war.webp', 'author' => 'Equipe Doppel',
        'featured' => false, 'hours_ago' => 130,
    ],
    [
        'title' => 'Scott Snyder é o novo roteirista da HQ da Liga da Justiça',
        'summary' => 'Depois de Dark Nights: Metal, Snyder assume a Liga com a minissérie No Justice.',
        'body' => "Scott Snyder será mesmo o novo roteirista da HQ da Liga da Justiça. Depois de encerrar em março a saga Dark Nights: Metal, ele começa em maio com a minissérie em quatro edições Justice League: No Justice.\n\nA minissérie semanal será coescrita com James Tynion IV e Joshua Williamson, com desenhos de Francis Manapul. Nela, Brainiac chega à Terra para avisar os heróis de uma ameaça extraterrestre que a Liga não conseguiria derrotar sozinha, forçando os maiores heróis a se aliarem aos maiores vilões do universo.\n\n\"Se você achou que meu trabalho em Guerra de Darkseid era gigantesco, No Justice vai levar a Liga aos limites do cosmo. Todo mundo que pediu Arlequina, Caçador de Marte, Safira, Zatanna, Átomo, Ravena e Senhor Destino na Liga vai poder comemorar\", disse Manapul.\n\nA partir de junho, Snyder passa a escrever a série mensal Justice League, dando início a uma nova família de títulos da equipe.\n\n\"Achávamos que conhecíamos todo o mapa da DC. Mas agora sabemos que o Multiverso era só um aquário, e fomos despejados no oceano\", disse Snyder sobre o evento.",
        'category' => 'Hqs', 'image' => 'liga-da-justica.webp', 'author' => 'Felipe',
        'featured' => false, 'hours_ago' => 160,
    ],
    [
        'title' => 'Shenmue I & II tem data de lançamento divulgada pela Microsoft',
        'summary' => 'A coletânea da SEGA chega em 21 de agosto, segundo a loja digital da Microsoft.',
        'body' => "A coletânea Shenmue I & II, da SEGA, chega ainda este ano, para alegria dos fãs que aguardam o relançamento.\n\nA SEGA ainda não fez nenhum anúncio oficial sobre a data, mas a Microsoft já revelou a data de lançamento em sua loja digital e abriu a pré-venda para Xbox One.\n\nDe acordo com a Microsoft, a coletânea chega em 21 de agosto e ocupa cerca de 201,68 MB.\n\nOs jogos sairão para Xbox One, PlayStation 4 e PC, via Steam.",
        'category' => 'Games', 'image' => 'shenmue.webp', 'author' => 'Felipe Antenori',
        'featured' => false, 'hours_ago' => 200,
    ],
];
