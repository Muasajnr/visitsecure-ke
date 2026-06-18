<?php
/**
 * app/models/User.php
 */

class User
{
    public static function find(int $id): array|false
    {
        return DB::one("SELECT * FROM users WHERE id = ?", [$id]);
    }

    public static function findByEmail(string $email, ?int $orgId = null): array|false
    {
        if ($orgId === null) {
            return DB::one("SELECT * FROM users WHERE email = ? AND org_id IS NULL", [$email]);
        }
        return DB::one("SELECT * FROM users WHERE email = ? AND org_id = ?", [$email, $orgId]);
    }

    /** Search across all orgs by email (used at login since user doesn't pick org first) */
    public static function findByEmailAnyOrg(string $email): array|false
    {
        return DB::one("SELECT * FROM users WHERE email = ? ORDER BY id LIMIT 1", [$email]);
    }

    public static function create(array $data): string
    {
        return DB::insert(
            "INSERT INTO users (uuid, org_id, role, room_id, full_name, email, phone, id_number, password_hash, is_active, email_verified_at)
             VALUES (?,?,?,?,?,?,?,?,?,?,?)",
            [
                generateUuid(),
                $data['org_id'] ?? null,
                $data['role'],
                $data['room_id'] ?? null,
                $data['full_name'],
                $data['email'],
                $data['phone'] ?? null,
                $data['id_number'] ?? null,
                password_hash($data['password'], PASSWORD_BCRYPT),
                $data['is_active'] ?? 1,
                $data['auto_verify'] ?? false ? date('Y-m-d H:i:s') : null,
            ]
        );
    }

    public static function updateLastLogin(int $id): void
    {
        DB::run("UPDATE users SET last_login_at = NOW() WHERE id = ?", [$id]);
    }

    public static function allByOrg(int $orgId, ?string $role = null): array
    {
        if ($role) {
            return DB::all("SELECT * FROM users WHERE org_id = ? AND role = ? ORDER BY full_name", [$orgId, $role]);
        }
        return DB::all("SELECT * FROM users WHERE org_id = ? ORDER BY role, full_name", [$orgId]);
    }

    public static function hostsByOrg(int $orgId): array
    {
        return DB::all(
            "SELECT u.*, r.name AS room_name, f.name AS floor_name, b.name AS building_name
             FROM users u
             LEFT JOIN rooms r ON u.room_id = r.id
             LEFT JOIN floors f ON r.floor_id = f.id
             LEFT JOIN buildings b ON f.building_id = b.id
             WHERE u.org_id = ? AND u.role = 'host'
             ORDER BY u.full_name", [$orgId]
        );
    }

    public static function toggleActive(int $id, int $isActive): int
    {
        return DB::run("UPDATE users SET is_active = ? WHERE id = ?", [$isActive, $id]);
    }

    public static function updateProfile(int $id, array $data): int
    {
        return DB::run(
            "UPDATE users SET full_name = ?, phone = ? WHERE id = ?",
            [$data['full_name'], $data['phone'] ?? null, $id]
        );
    }

    public static function updatePassword(int $id, string $plainPassword): int
    {
        return DB::run(
            "UPDATE users SET password_hash = ? WHERE id = ?",
            [password_hash($plainPassword, PASSWORD_BCRYPT), $id]
        );
    }

    public static function emailExists(string $email, ?int $orgId): bool
    {
        if ($orgId === null) {
            return (bool) DB::one("SELECT id FROM users WHERE email = ? AND org_id IS NULL", [$email]);
        }
        return (bool) DB::one("SELECT id FROM users WHERE email = ? AND org_id = ?", [$email, $orgId]);
    }
}
