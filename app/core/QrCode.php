<?php
/**
 * app/core/QrCode.php
 *
 * Thin wrapper around the bundled phpqrcode library (vendor/phpqrcode).
 * Requires the PHP GD extension (enabled by default in Laragon).
 *
 * Named QrCodeGenerator (not QrCode) because PHP class names are case-insensitive
 * and would collide with the library's QRcode class.
 */

require_once ROOT_PATH . '/vendor/phpqrcode/qrlib.php';

class QrCodeGenerator
{
    /**
     * Generates a QR code PNG for the given text/token and saves it
     * under public/uploads/qrcodes/. Returns the relative path
     * (relative to /public) to store in the DB, e.g. 'uploads/qrcodes/ABC123.png'
     */
    public static function generate(string $text, string $filename): string
    {
        $dir = UPLOADS_PATH . '/qrcodes';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $fullPath = $dir . '/' . $filename . '.png';

        // ECC level L, size 6 (scale factor), margin 2
        QRcode::png($text, $fullPath, QR_ECLEVEL_L, 6, 2);

        return 'uploads/qrcodes/' . $filename . '.png';
    }

    public static function publicUrl(string $relativePath): string
    {
        return BASE_URL . '/' . ltrim($relativePath, '/');
    }

    /** Absolute filesystem path for a stored QR image. */
    public static function absolutePath(string $relativePath): string
    {
        return PUBLIC_PATH . '/' . ltrim($relativePath, '/');
    }
}
