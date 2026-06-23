<?php
/**
 * app/models/Room.php
 */

class Room
{
    public static function allByFloor(int $floorId, int $orgId): array
    {
        return DB::all("SELECT * FROM rooms WHERE floor_id = ? AND org_id = ? ORDER BY name", [$floorId, $orgId]);
    }

    public static function find(int $id, int $orgId): array|false
    {
        return DB::one("SELECT * FROM rooms WHERE id = ? AND org_id = ?", [$id, $orgId]);
    }

    public static function create(int $orgId, int $floorId, array $data): string
    {
        return DB::insert(
            "INSERT INTO rooms (org_id, floor_id, name, room_type, firm_name) VALUES (?,?,?,?,?)",
            [$orgId, $floorId, $data['name'], $data['room_type'] ?? 'office', $data['firm_name'] ?? null]
        );
    }

    public static function update(int $id, int $orgId, array $data): int
    {
        return DB::run(
            "UPDATE rooms SET name = ?, room_type = ?, firm_name = ? WHERE id = ? AND org_id = ?",
            [$data['name'], $data['room_type'] ?? 'office', $data['firm_name'] ?? null, $id, $orgId]
        );
    }

    public static function delete(int $id, int $orgId): int
    {
        return DB::run("DELETE FROM rooms WHERE id = ? AND org_id = ?", [$id, $orgId]);
    }

    public static function allByOrg(int $orgId): array
    {
        return DB::all(
            "SELECT r.*, f.name AS floor_name, b.name AS building_name, b.id AS building_id
             FROM rooms r
             JOIN floors f ON r.floor_id = f.id
             JOIN buildings b ON f.building_id = b.id
             WHERE r.org_id = ? ORDER BY b.name, f.floor_number, r.name", [$orgId]
        );
    }

    /** Full location path for display: Building > Floor > Room */
    public static function locationPath(int $roomId, int $orgId): ?string
    {
        $row = DB::one(
            "SELECT b.name AS building, f.name AS floor, r.name AS room
             FROM rooms r
             JOIN floors f ON r.floor_id = f.id
             JOIN buildings b ON f.building_id = b.id
             WHERE r.id = ? AND r.org_id = ?", [$roomId, $orgId]
        );
        if (!$row) return null;
        return "{$row['building']} \u{2192} {$row['floor']} \u{2192} {$row['room']}";
    }
}
