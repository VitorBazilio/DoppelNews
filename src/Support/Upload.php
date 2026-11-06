<?php

declare(strict_types=1);

namespace Doppel\Support;

/**
 * Upload de imagens do painel. O tipo é conferido pelo conteúdo do arquivo (não pela extensão),
 * o nome final é aleatório e, quando a extensão GD está disponível, a imagem é salva em WebP.
 * Também aceita uma URL https, útil em hospedagens sem disco persistente (Vercel).
 */
final class Upload
{
    private const MAX_BYTES = 3 * 1024 * 1024;
    private const MAX_WIDTH = 1920;
    private const TYPES = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp', IMAGETYPE_GIF => 'gif'];
    private const HINT = 'Use uma imagem JPG, PNG, WEBP ou GIF de até 3 MB.';

    /** @return array{?string, ?string} [nome do arquivo ou URL, mensagem de erro] */
    public static function image(string $field, ?string $current): array
    {
        $url = trim((string) ($_POST[$field . '_url'] ?? ''));
        if ($url !== '') {
            $valid = preg_match('#^https://#i', $url) && filter_var($url, FILTER_VALIDATE_URL) && strlen($url) <= 255;
            return $valid ? [$url, null] : [$current, 'A URL da imagem deve começar com https://.'];
        }

        $file = $_FILES[$field] ?? null;
        if ($file === null || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return [$current, null];
        }
        if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > self::MAX_BYTES) {
            return [$current, self::HINT];
        }
        $type = @getimagesize($file['tmp_name'])[2] ?? null;
        if (!isset(self::TYPES[$type])) {
            return [$current, self::HINT];
        }

        $name = self::store($file['tmp_name'], $type);
        if ($name === null) {
            return [$current, 'Não foi possível salvar a imagem.'];
        }
        self::delete($current);
        return [$name, null];
    }

    public static function canStoreFiles(): bool
    {
        return is_writable(ROOT . '/public/uploads') && getenv('VERCEL') === false;
    }

    /** Apaga uma imagem enviada pelo painel (URLs externas e imagens de exemplo são ignoradas). */
    public static function delete(?string $name): void
    {
        if ($name !== null && preg_match('/^[a-f0-9]{16}\.\w{3,4}$/', $name)) {
            @unlink(ROOT . '/public/uploads/' . $name);
        }
    }

    private static function store(string $tmp, int $type): ?string
    {
        $base = ROOT . '/public/uploads/' . bin2hex(random_bytes(8));

        // GIF pode ser animado: guarda como veio. Os demais viram WebP (menores), se o GD permitir.
        if ($type !== IMAGETYPE_GIF && function_exists('imagewebp')) {
            $image = match ($type) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($tmp),
                IMAGETYPE_PNG => @imagecreatefrompng($tmp),
                IMAGETYPE_WEBP => @imagecreatefromwebp($tmp),
            };
            if ($image !== false) {
                imagepalettetotruecolor($image);
                imagesavealpha($image, true);
                if (imagesx($image) > self::MAX_WIDTH) {
                    $image = imagescale($image, self::MAX_WIDTH);
                }
                if (imagewebp($image, $base . '.webp', 82)) {
                    return basename($base) . '.webp';
                }
            }
        }

        $path = $base . '.' . self::TYPES[$type];
        return move_uploaded_file($tmp, $path) ? basename($path) : null;
    }
}
