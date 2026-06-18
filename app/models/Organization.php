<?php
/**
 * app/models/Organization.php
 */

class Organization
{
    public static function all(): array
    {
        return DB::all("SELECT * FROM organizations ORDER BY created_at DESC");
    }

    public static function find(int $id): array|false
    {
        return DB::one("SELECT * FROM organizations WHERE id = ?", [$id]);
    }

    public static function findBySlug(string $slug): array|false
    {
        return DB::one("SELECT * FROM organizations WHERE slug = ?", [$slug]);
    }

    public static function create(array $data): string
    {
        return DB::insert(
            "INSERT INTO organizations (uuid, name, slug, email, phone, address, subscription_status, subscription_plan, is_active)
             VALUES (?,?,?,?,?,?,?,?,?)",
            [
                generateUuid(),
                $data['name'],
                $data['slug'],
                $data['email'],
                $data['phone'] ?? null,
                $data['address'] ?? null,
                $data['subscription_status'] ?? 'trial',
                $data['subscription_plan'] ?? 'basic',
                $data['is_active'] ?? 1,
            ]
        );
    }

    public static function updateStatus(int $id, string $status): int
    {
        return DB::run("UPDATE organizations SET subscription_status = ? WHERE id = ?", [$status, $id]);
    }

    public static function toggleActive(int $id, int $isActive): int
    {
        return DB::run("UPDATE organizations SET is_active = ? WHERE id = ?", [$isActive, $id]);
    }

    public static function slugExists(string $slug): bool
    {
        return (bool) DB::one("SELECT id FROM organizations WHERE slug = ?", [$slug]);
    }

    public static function counts(int $orgId): array
    {
        $buildings = DB::one("SELECT COUNT(*) c FROM buildings WHERE org_id = ?", [$orgId])['c'];
        $users     = DB::one("SELECT COUNT(*) c FROM users WHERE org_id = ?", [$orgId])['c'];
        $visits    = DB::one("SELECT COUNT(*) c FROM visits WHERE org_id = ?", [$orgId])['c'];
        $events    = DB::one("SELECT COUNT(*) c FROM events WHERE org_id = ?", [$orgId])['c'];
        return compact('buildings', 'users', 'visits', 'events');
    }
}
