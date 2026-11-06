# Doppel

Portal de notícias geek **e** plataforma de aventuras de RPG em formato de livro-jogo, ambientadas em momentos históricos (Primeira Guerra Mundial, Roma Antiga…).

> Primeiro projeto criado pelos programadores **Vitor** e **Felipe**, na época do TCC do ensino médio.

## Rodando localmente (Windows)

Requisito: PHP 8.1+ instalado e no PATH.

1. Dê dois cliques em **`iniciar.bat`** (ou rode `iniciar.bat` no terminal).
2. Abra http://localhost:8000.
3. Crie um administrador (uma vez): `php bin/create-admin.php "Seu Nome" voce@exemplo.com suasenha123`
   (se o PHP reclamar de driver, rode com `-d extension=pdo_sqlite -d extension=sqlite3`).

O banco SQLite (`storage/doppel.sqlite`) é criado sozinho, com notícias de exemplo.

## Publicando na Vercel

A Vercel não roda PHP nativamente: usamos o runtime comunitário [`vercel-php`](https://github.com/juicyfx/vercel-php) (configurado em `vercel.json`). Como o disco da Vercel é temporário, **é preciso um MySQL externo** (ex.: TiDB Cloud, Aiven, Railway). As sessões já são guardadas no banco automaticamente.

1. Crie um banco MySQL na nuvem e anote host, banco, usuário e senha.
2. Na máquina local, crie um `.env` (modelo em `.env.example`) com `DB_DSN`, `DB_USER`, `DB_PASS` e, se o provedor exigir TLS, `DB_SSL_CA` apontando para o bundle de CAs.
3. Rode `php bin/install.php` (cria as tabelas) e `php bin/create-admin.php ...` (cria o admin) contra esse banco.
4. Suba o projeto no GitHub e importe na Vercel. Em **Settings → Environment Variables** cadastre as mesmas variáveis (`DB_DSN`, `DB_USER`, `DB_PASS`, `DB_SSL_CA`).
5. Deploy. Imagens de notícias no painel devem ser informadas por **URL https** (a Vercel não guarda uploads).

## Estrutura

```
public/        diretório exposto (index.php = roteador, assets/)
api/index.php  ponto de entrada da Vercel
src/Controller controllers finos (um por área)
src/Repository acesso a dados com PDO + prepared statements
src/Story      motor do livro-jogo (Gamebook) e carregamento das aventuras
src/Support    config, banco, sessão, auth, CSRF, roteador, view, helpers
templates/     views PHP (layout + páginas)
stories/       aventuras em JSON
database/      esquemas SQLite/MySQL e notícias de exemplo
bin/           scripts de linha de comando
```

## Criando uma aventura

Crie `stories/minha-aventura.json` seguindo o formato de `stories/idos-de-marco-44ac.json`:

- `stats`: atributos (`lethal: true` = fim de jogo ao chegar a 0).
- `nodes`: cenas com `text`, `choices` e, opcionalmente, `effects`, `ending` (`victory`, `survived`, `defeat`).
- Escolha: `requires` (atributo/item), `effects` (`stats`, `items`, `remove`) e `check` (teste 2d6 + atributo contra `target`; falha vai para `fail`).

Valide com `php bin/check-stories.php`.

## Segurança

Senhas com `password_hash`, consultas parametrizadas, CSRF em todos os POSTs, saída escapada, upload validado por conteúdo e limite de tentativas de login.
