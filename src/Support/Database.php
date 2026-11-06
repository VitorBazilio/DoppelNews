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
            [$dsn, $user, $pass] = self::connectionSettings();
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

            self::$pdo = new PDO($dsn, $user, $pass, $options);

            if (self::$pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite') {
                self::$pdo->exec('PRAGMA foreign_keys = ON');
            }
            // Banco novo ou de uma versão anterior: cria o que falta, sem apagar dados.
            if ($isNewSqlite || Installer::needsUpgrade(self::$pdo)) {
                Installer::run(self::$pdo);
            }
        }
        return self::$pdo;
    }

    /**
     * DSN, usuário e senha. DB_DSN aceita o formato do PDO (mysql:host=…;dbname=…) ou uma URL
     * como a que os provedores mostram (mysql://usuario:senha@host:4000/banco).
     * @return array{string, ?string, ?string}
     */
    private static function connectionSettings(): array
    {
        $clean = static fn (string $v): string => trim($v, " \t\n\r\"'");
        $dsn = $clean(Config::get('DB_DSN'));
        $user = $clean(Config::get('DB_USER'));
        $pass = $clean(Config::get('DB_PASS'));

        if ($dsn === '') {
            return ['sqlite:' . ROOT . '/storage/doppel.sqlite', null, null];
        }
        if (preg_match('#^mysql://#i', $dsn)) {
            $url = parse_url($dsn);
            if ($url === false || empty($url['host'])) {
                throw new \PDOException('DB_DSN em formato de URL inválido.');
            }
            $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                $url['host'], $url['port'] ?? 3306, ltrim($url['path'] ?? '', '/') ?: 'doppel');
            $user = $user !== '' ? $user : urldecode($url['user'] ?? '');
            $pass = $pass !== '' ? $pass : urldecode($url['pass'] ?? '');
        }
        return [$dsn, $user ?: null, $pass ?: null];
    }

    /** Executa $work dentro de uma transação, desfazendo tudo se algo falhar. */
    public static function transaction(callable $work): mixed
    {
        $pdo = self::pdo();
        $pdo->beginTransaction();
        try {
            $result = $work($pdo);
            $pdo->commit();
            return $result;
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}
