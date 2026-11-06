<?php

declare(strict_types=1);

// Cria as tabelas (e notícias de exemplo, se vazio) no banco configurado em DB_DSN.
// Uso: php bin/install.php

require dirname(__DIR__) . '/src/bootstrap.php';

use Doppel\Support\Database;
use Doppel\Support\Installer;

Installer::run(Database::pdo());
echo "Banco pronto.\n";
