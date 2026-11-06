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
        // Remove espaços, aspas, caracteres invisíveis e um "NOME=" colado por engano no valor.
        $clean = static function (string $value, string $name): string {
            $value = (string) preg_replace('/[\x{00A0}\x{200B}-\x{200D}\x{FEFF}]/u', '', $value);
            $value = trim($value, " \t\n\r\"'");
            return trim((string) preg_replace('/^' . $name . '\s*=\s*/i', '', $value), " \t\n\r\"'");
        };
        $dsn = $clean(Config::get('DB_DSN'), 'DB_DSN');
        $user = $clean(Config::get('DB_USER'), 'DB_USER');
        $pass = $clean(Config::get('DB_PASS'), 'DB_PASS');

        if ($dsn === '') {
            return ['sqlite:' . ROOT . '/storage/doppel.sqlite', null, null];
        }
        // "host=…;dbname=…" sem o prefixo do driver
        if (!preg_match('#^[a-z]+:#i', $dsn) && preg_match('/(^|;)\s*host\s*=/i', $dsn)) {
            $dsn = 'mysql:' . $dsn;
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
        if (!preg_match('#^(mysql|sqlite):#i', $dsn)) {
            // Mostra só o formato do valor recebido (sem senha) para facilitar a correção.
            throw new \PDOException(sprintf(
                'DB_DSN deve começar com "mysql:host=". Valor recebido começa com "%s" (%d caracteres).',
                mb_substr($dsn, 0, 12), mb_strlen($dsn)
            ));
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
