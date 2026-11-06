<?php

declare(strict_types=1);

// Cria um administrador, ou promove uma conta existente.
// Uso: php bin/create-admin.php "Nome" email@exemplo.com senha-com-8-ou-mais

require dirname(__DIR__) . '/src/bootstrap.php';

use Doppel\Repository\UserRepository;

[, $name, $email, $password] = $argv + [null, null, null, null];

if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen((string) $password) < 8) {
    fwrite(STDERR, "Uso: php bin/create-admin.php \"Nome\" email@exemplo.com senha(min. 8)\n");
    exit(1);
}

$repo = new UserRepository();
$existing = $repo->findByEmail($email);
if ($existing !== null) {
    $repo->setRole((int) $existing['id'], 'admin');
    echo "Conta existente promovida a administrador: $email\n";
    exit(0);
}

$repo->create($name, $email, $password, 'admin');
echo "Administrador criado: $email\n";
