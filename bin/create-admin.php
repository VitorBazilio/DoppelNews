<?php

declare(strict_types=1);

// Uso: php bin/create-admin.php "Nome" email@exemplo.com senha-com-8-ou-mais

require dirname(__DIR__) . '/src/bootstrap.php';

use Doppel\Repository\UserRepository;

[, $name, $email, $password] = $argv + [null, null, null, null];

if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen((string) $password) < 8) {
    fwrite(STDERR, "Uso: php bin/create-admin.php \"Nome\" email@exemplo.com senha(min. 8)\n");
    exit(1);
}

$repo = new UserRepository();
if ($repo->findByEmail($email) !== null) {
    fwrite(STDERR, "Já existe um usuário com esse e-mail.\n");
    exit(1);
}

$repo->create($name, $email, $password, 'admin');
echo "Administrador criado: $email\n";
