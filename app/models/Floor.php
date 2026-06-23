<?php
/**
 * app/models/Floor.php
 */

class Floor
{
    public static function allByBuilding(int $buildingId, int $orgId): array
    {
        return DB::all(
            "SELECT f.*,
                (SELECT COUNT(*) FROM rooms r WHERE r.floor_id = f.id) AS room_count
             FROM floors f WHERE f.building_id = ? AND f.org_id = ? ORDER BY f.floor_number, f.name",
            [$buildingId, $orgId]
        );
    }

    public static function find(int $id, int $orgId): array|false
    {
        return DB::one("SELECT * FROM floors WHERE id = ? AND org_id = ?", [$id, $orgId]);
    }

    public static function create(int $orgId, int $buildingId, array $data): string
    {
        return DB::insert(
            "INSERT INTO floors (org_id, building_id, name, floor_number) VALUES (?,?,?,?)",
            [$orgId, $buildingId, $data['name'], $data['floor_number'] ?? null]
        );
    }

    public static function update(int $id, int $orgId, array $data): int
    {
        return DB::run(
            "UPDATE floors SET name = ?, floor_number = ? WHERE id = ? AND org_id = ?",
            [$data['name'], $data['floor_number'] ?? null, $id, $orgId]
        );
    }

    public static function delete(int $id, int $orgId): int
    {
        return DB::run("DELETE FROM floors WHERE id = ? AND org_id = ?", [$id, $orgId]);
    }

    public static function allByOrg(int $orgId): array
    {
        return DB::all(
            "SELECT f.*, b.name AS building_name FROM floors f
             JOIN buildings b ON f.building_id = b.id
             WHERE f.org_id = ? ORDER BY b.name, f.floor_number", [$orgId]
        );
    }
}
