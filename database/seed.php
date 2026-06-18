<?php
/**
 * database/seed.php
 *
 * Run this ONCE after importing schema.sql to set a guaranteed-correct
 * bcrypt password for the super admin account.
 *
 * Usage (from project root, in browser or CLI):
 *   http://localhost/visitsecure-ke/database/seed.php
 *   or: php database/seed.php
 */

require_once __DIR__ . '/../app/config/database.php';

$email = 'admin@visitsecure.ke';
$plainPassword = 'Admin@123';
$hash = password_hash($plainPassword, PASSWORD_BCRYPT);

$stmt = $pdo->prepare("UPDATE users SET password_hash = :hash WHERE email = :email AND role = 'super_admin'");
$stmt->execute(['hash' => $hash, 'email' => $email]);

if ($stmt->rowCount() > 0) {
    echo "Super admin password set successfully.\n";
    echo "Login email: {$email}\n";
    echo "Login password: {$plainPassword}\n";
    echo "IMPORTANT: Change this password after your first login.\n";
} else {
    echo "No super_admin row updated. Did you import database/schema.sql first?\n";
}
