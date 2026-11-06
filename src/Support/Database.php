<?php

declare(strict_types=1);

namespace Doppel\Support;

use PDO;

/** Conexão PDO única. SQLite por padrão (zero configuração); MySQL via DB_DSN. */
final class Database
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            $dsn = Config::get('DB_DSN', 'sqlite:' . ROOT . '/storage/doppel.sqlite');
            $isNewSqlite = str_starts_with($dsn, 'sqlite:') && !is_file(substr($dsn, 7));

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            // TLS com verificação do certificado: aponte DB_SSL_CA para o bundle de CAs do provedor.
            if (Config::get('DB_SSL_CA') !== '') {
                $options[PDO::MYSQL_ATTR_SSL_CA] = Config::get('DB_SSL_CA');
            }

            self::$pdo = new PDO($dsn, Config::get('DB_USER') ?: null, Config::get('DB_PASS') ?: null, $options);

            if ($isNewSqlite) {
                Installer::run(self::$pdo);
            }
        }
        return self::$pdo;
    }
}
