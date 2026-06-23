<?php
/**
 * app/models/Event.php
 */

class Event
{
    public static function find(int $id, int $orgId): array|false
    {
        return DB::one(
            "SELECT e.*, b.name AS building_name, f.name AS floor_name, r.name AS room_name
             FROM events e
             LEFT JOIN buildings b ON e.building_id = b.id
             LEFT JOIN floors f ON e.floor_id = f.id
             LEFT JOIN rooms r ON e.room_id = r.id
             WHERE e.id = ? AND e.org_id = ?", [$id, $orgId]
        );
    }

    public static function create(int $orgId, int $createdBy, array $data): string
    {
        return DB::insert(
            "INSERT INTO events (uuid, org_id, building_id, floor_id, room_id, created_by, title, description, start_datetime, end_datetime, max_visitors)
             VALUES (?,?,?,?,?,?,?,?,?,?,?)",
            [
                generateUuid(), $orgId, $data['building_id'], $data['floor_id'] ?? null, $data['room_id'] ?? null,
                $createdBy, $data['title'], $data['description'] ?? null,
                $data['start_datetime'], $data['end_datetime'], $data['max_visitors'] ?? null,
            ]
        );
    }

    public static function allByOrg(int $orgId): array
    {
        return DB::all(
            "SELECT e.*, b.name AS building_name, r.name AS room_name,
                (SELECT COUNT(*) FROM visits v WHERE v.event_id = e.id) AS registered_count
             FROM events e
             LEFT JOIN buildings b ON e.building_id = b.id
             LEFT JOIN rooms r ON e.room_id = r.id
             WHERE e.org_id = ? ORDER BY e.start_datetime DESC", [$orgId]
        );
    }

    public static function allByCreator(int $userId): array
    {
        return DB::all(
            "SELECT e.*, b.name AS building_name, r.name AS room_name,
                (SELECT COUNT(*) FROM visits v WHERE v.event_id = e.id) AS registered_count
             FROM events e
             LEFT JOIN buildings b ON e.building_id = b.id
             LEFT JOIN rooms r ON e.room_id = r.id
             WHERE e.created_by = ? ORDER BY e.start_datetime DESC", [$userId]
        );
    }

    public static function visitorCount(int $eventId): int
    {
        return (int) DB::one("SELECT COUNT(*) c FROM visits WHERE event_id = ?", [$eventId])['c'];
    }

    public static function update(int $id, int $orgId, array $data): int
    {
        return DB::run(
            "UPDATE events SET title = ?, description = ?, building_id = ?, floor_id = ?, room_id = ?,
             start_datetime = ?, end_datetime = ?, max_visitors = ?
             WHERE id = ? AND org_id = ? AND status != 'cancelled'",
            [
                $data['title'],
                $data['description'] ?? null,
                $data['building_id'],
                $data['floor_id'] ?? null,
                $data['room_id'] ?? null,
                $data['start_datetime'],
                $data['end_datetime'],
                $data['max_visitors'] ?? null,
                $id,
                $orgId,
            ]
        );
    }

    public static function cancel(int $id, int $orgId): int
    {
        return DB::run(
            "UPDATE events SET status = 'cancelled' WHERE id = ? AND org_id = ? AND status != 'cancelled'",
            [$id, $orgId]
        );
    }
}
