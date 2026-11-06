# Doppel

Portal de notícias geek **e** plataforma de RPG de mesa: salas com chat e dados, personagens de D&D 5ª edição, ranking, regras e aventuras em livro-jogo.

> Primeiro projeto dos programadores **Vitor Bazilio**, **Felipe Antenori** e **Willberson Laurentino**, criado no TCC do ensino médio. Esta versão 3.0 junta o visual do TCC original com o código limpo da versão DoppelNews.

## O que o site faz

| Área | Funções |
|---|---|
| **News** | Carrossel de destaques, editorias (Games, Anime, Hqs, Manga, RPG, História), busca, paginação, página da notícia com posts recentes e compartilhamento |
| **Salas** | Criar mesa (você vira o mestre), listar por Novas/Populares/Mestres, Sorte (sala aleatória com vaga), chat em tempo real, rolagem de dados (`d20`, `2d6+3`, `/r 1d8-1`), mestre dá pontos de ranking |
| **Personagens** | Criação por sala com compra de pontos (27 pontos, regras do Livro do Jogador), bônus de espécie, PV pelo dado de vida, perícias; ranking, recentes, meus e ficha de exemplo |
| **Regras** | Compêndio público de classes, espécies, armas e magias |
| **Aventuras** | Livros-jogo históricos (Somme 1916, Idos de Março) com testes de 2d6; funciona sem conta |
| **Ajuda** | Livros de D&D em PDF, como jogar, perguntas frequentes e contato |
| **Conta** | Cadastro/login (modal ou página), perfil com personagens, salas e aventuras; troca de nick e senha |
| **Painel** (admin) | Notícias (criar, editar, excluir, destaque no carrossel, upload convertido para WebP), usuários (promover/excluir) e regras de D&D |

## Rodando localmente (Windows)

Requisito: PHP 8.1+ no PATH.

1. Dê dois cliques em **`iniciar.bat`** e abra http://localhost:8000.
2. Crie o administrador (uma vez):
   `php -d extension=pdo_sqlite bin/create-admin.php "Seu Nome" voce@exemplo.com suasenha123`

O banco SQLite (`storage/doppel.sqlite`) é criado sozinho, com notícias e regras de exemplo.

Também roda em Apache (XAMPP, USBWebServer): aponte para esta pasta; o `.htaccess` encaminha tudo para `public/`.

## MySQL e Vercel

Copie `.env.example` para `.env` e preencha `DB_DSN`, `DB_USER` e `DB_PASS`. Depois rode `php bin/install.php`.
Na Vercel (runtime `vercel-php`, já configurado em `vercel.json`) use um MySQL externo; as sessões vão para o banco automaticamente e as imagens das notícias devem ser informadas por URL `https://`.

## Estrutura

```
public/          raiz do site (index.php = rotas, assets/ css, js, imagens WebP, fontes, livros)
src/Controller   controllers finos (Admin/ para o painel)
src/Repository   acesso a dados com PDO e consultas parametrizadas
src/Rpg          regras puras: Dice (rolagens) e CharacterRules (compra de pontos, PV, perícias)
src/Story        motor do livro-jogo
src/Support      config, banco, sessão, auth, CSRF, roteador, view, upload, helpers
templates/       views PHP (layout + páginas)
database/        esquemas SQLite/MySQL e dados iniciais (seed/)
stories/         aventuras em JSON (valide com php bin/check-stories.php)
bin/             scripts de linha de comando
```

## Segurança

Senhas com `password_hash`, consultas parametrizadas, CSRF em todos os POSTs, saída escapada, upload validado pelo conteúdo, limite de tentativas de login e permissões checadas no servidor (só o mestre pontua e exclui a sala; só o dono, o mestre ou um admin removem um personagem).
