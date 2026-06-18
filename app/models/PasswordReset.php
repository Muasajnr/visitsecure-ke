<?php
/**
 * app/models/PasswordReset.php
 */

class PasswordReset
{
    public static function createToken(int $userId): string
    {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Invalidate previous tokens for this user
        DB::run("UPDATE password_resets SET used = 1 WHERE user_id = ? AND used = 0", [$userId]);

        DB::insert(
            "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?,?,?)",
            [$userId, $token, $expiresAt]
        );

        return $token;
    }

    public static function findValid(string $token): array|false
    {
        return DB::one(
            "SELECT * FROM password_resets WHERE token = ? AND used = 0 AND expires_at > NOW()",
            [$token]
        );
    }

    public static function markUsed(int $id): void
    {
        DB::run("UPDATE password_resets SET used = 1 WHERE id = ?", [$id]);
    }
}
