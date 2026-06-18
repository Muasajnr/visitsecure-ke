<?php
/**
 * app/models/Building.php
 */

class Building
{
    public static function allByOrg(int $orgId): array
    {
        return DB::all(
            "SELECT b.*,
                (SELECT COUNT(*) FROM floors f WHERE f.building_id = b.id) AS floor_count
             FROM buildings b WHERE b.org_id = ? ORDER BY b.name", [$orgId]
        );
    }

    public static function find(int $id, int $orgId): array|false
    {
        return DB::one("SELECT * FROM buildings WHERE id = ? AND org_id = ?", [$id, $orgId]);
    }

    public static function create(int $orgId, array $data): string
    {
        return DB::insert(
            "INSERT INTO buildings (org_id, name, address, description) VALUES (?,?,?,?)",
            [$orgId, $data['name'], $data['address'] ?? null, $data['description'] ?? null]
        );
    }
}
