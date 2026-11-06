<?php

declare(strict_types=1);

namespace Doppel\Support;

/** Lê configurações de variáveis de ambiente, com suporte a um arquivo .env simples. */
final class Config
{
    public static function load(string $envFile): void
    {
        if (!is_file($envFile)) {
            return;
        }
        foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) {
                continue;
            }
            [$key, $value] = array_map('trim', explode('=', $line, 2));
            if (getenv($key) === false) {
                putenv($key . '=' . trim($value, "\"'"));
            }
        }
    }

    public static function get(string $key, string $default = ''): string
    {
        $value = getenv($key);
        return $value === false ? $default : $value;
    }

    public static function bool(string $key): bool
    {
        return in_array(strtolower(self::get($key)), ['1', 'true', 'on', 'yes'], true);
    }
}
