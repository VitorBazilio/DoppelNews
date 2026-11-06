# Doppel

Portal de notícias geek **e** plataforma de aventuras de RPG em formato de livro-jogo, ambientadas em momentos históricos (Primeira Guerra Mundial, Roma Antiga…).

## Rodando

Requisitos: PHP 8.1+ com `pdo_sqlite` (padrão) ou `pdo_mysql`.

```powershell
./run.ps1                      # http://localhost:8000
# ou, com PHP configurado:
php -S localhost:8000 -t public public/index.php
```

Na primeira execução o banco SQLite (`storage/doppel.sqlite`) é criado e populado com notícias de exemplo.

Criar um administrador (acesso ao painel `/admin`):

```
php bin/create-admin.php "Seu Nome" voce@exemplo.com suasenha123
```

### MySQL

Crie um arquivo `.env` na raiz:

```
DB_DSN=mysql:host=127.0.0.1;dbname=doppel;charset=utf8mb4
DB_USER=usuario
DB_PASS=senha
```

e rode `php -r "require 'src/bootstrap.php'; Doppel\Support\Installer::run(Doppel\Support\Database::pdo());"` uma vez para criar as tabelas. Use `APP_DEBUG=1` para ver erros em desenvolvimento.

## Estrutura

```
public/        único diretório exposto (index.php = roteador, assets/, uploads/)
src/Controller controllers finos (um por área)
src/Repository acesso a dados com PDO + prepared statements
src/Story      motor do livro-jogo (Gamebook) e carregamento das aventuras
src/Support    config, banco, auth, CSRF, roteador, view, helpers
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

Valide com `php bin/check-stories.php` (detecta destinos quebrados, becos sem saída e cenas inalcançáveis).

## Segurança

Senhas com `password_hash`, consultas parametrizadas, proteção CSRF em todos os POSTs, saída sempre escapada, upload de imagens validado por conteúdo e limite de tentativas de login.
