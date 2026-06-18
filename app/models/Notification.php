<?php
/**
 * app/models/Notification.php
 */

class Notification
{
    public static function create(array $data): string
    {
        return DB::insert(
            "INSERT INTO notifications (org_id, user_id, visit_id, type, title, message, channel, email_status)
             VALUES (?,?,?,?,?,?,?,?)",
            [
                $data['org_id'] ?? null, $data['user_id'], $data['visit_id'] ?? null,
                $data['type'], $data['title'], $data['message'],
                $data['channel'] ?? 'both', $data['email_status'] ?? 'pending',
            ]
        );
    }

    public static function forUser(int $userId, int $limit = 30): array
    {
        return DB::all("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?", [$userId, $limit]);
    }

    public static function unreadCount(int $userId): int
    {
        return (int) DB::one("SELECT COUNT(*) c FROM notifications WHERE user_id = ? AND is_read = 0", [$userId])['c'];
    }

    public static function markRead(int $id, int $userId): int
    {
        return DB::run("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?", [$id, $userId]);
    }

    public static function markEmailStatus(int $id, string $status): void
    {
        DB::run("UPDATE notifications SET email_status = ? WHERE id = ?", [$status, $id]);
    }
}
