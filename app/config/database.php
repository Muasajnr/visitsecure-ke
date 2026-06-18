<?php
/**
 * app/config/database.php
 * Creates a single shared PDO connection ($pdo) used across the app.
 */

require_once __DIR__ . '/app.php';

// ---- Edit these to match your Laragon MySQL setup ----
define('DB_HOST', 'localhost');
define('DB_NAME', 'visitsecure_ke');
define('DB_USER', 'root');
define('DB_PASS', '');        // Laragon default MySQL root password is usually empty
define('DB_CHARSET', 'utf8mb4');

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    if (APP_DEBUG) {
        die('Database connection failed: ' . $e->getMessage());
    } else {
        die('Database connection failed. Please contact the system administrator.');
    }
}
