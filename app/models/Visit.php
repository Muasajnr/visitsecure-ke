<?php
/**
 * app/models/Visit.php
 * Core entity: a visitor's booking / gate pass, from creation through
 * approval, check-in, and check-out.
 */

class Visit
{
    public static function find(int $id, int $orgId): array|false
    {
        return DB::one(
            "SELECT v.*, b.name AS building_name, f.name AS floor_name, r.name AS room_name,
                    h.full_name AS host_name, h.phone AS host_phone, h.email AS host_email,
                    e.title AS event_title
             FROM visits v
             LEFT JOIN buildings b ON v.building_id = b.id
             LEFT JOIN floors f ON v.floor_id = f.id
             LEFT JOIN rooms r ON v.room_id = r.id
             LEFT JOIN users h ON v.host_id = h.id
             LEFT JOIN events e ON v.event_id = e.id
             WHERE v.id = ? AND v.org_id = ?", [$id, $orgId]
        );
    }

    public static function findByQrToken(string $token): array|false
    {
        return DB::one(
            "SELECT v.*, b.name AS building_name, f.name AS floor_name, r.name AS room_name,
                    h.full_name AS host_name, h.phone AS host_phone, o.name AS org_name
             FROM visits v
             LEFT JOIN buildings b ON v.building_id = b.id
             LEFT JOIN floors f ON v.floor_id = f.id
             LEFT JOIN rooms r ON v.room_id = r.id
             LEFT JOIN users h ON v.host_id = h.id
             LEFT JOIN organizations o ON v.org_id = o.id
             WHERE v.qr_code = ?", [$token]
        );
    }

    public static function create(int $orgId, array $data): string
    {
        $uuid = generateUuid();
        $qrToken = generateQrToken();

        return DB::insert(
            "INSERT INTO visits
                (uuid, org_id, building_id, floor_id, room_id, event_id,
                 visitor_user_id, visitor_name, visitor_phone, visitor_email, visitor_id_number, visitor_company,
                 host_id, purpose, created_by, source, status, is_walk_in,
                 qr_code, scheduled_start, scheduled_end, notes)
             VALUES (?,?,?,?,?,?, ?,?,?,?,?,?, ?,?,?,?,?,?, ?,?,?,?)",
            [
                $uuid, $orgId,
                $data['building_id'], $data['floor_id'] ?? null, $data['room_id'] ?? null, $data['event_id'] ?? null,
                $data['visitor_user_id'] ?? null, $data['visitor_name'], $data['visitor_phone'],
                $data['visitor_email'] ?? null, $data['visitor_id_number'] ?? null, $data['visitor_company'] ?? null,
                $data['host_id'] ?? null, $data['purpose'] ?? null, $data['created_by'] ?? null,
                $data['source'] ?? 'self', $data['status'] ?? 'pending', $data['is_walk_in'] ?? 0,
                $qrToken, $data['scheduled_start'] ?? null, $data['scheduled_end'] ?? null, $data['notes'] ?? null,
            ]
        );
    }

    public static function setQrImage(int $id, string $path): void
    {
        DB::run("UPDATE visits SET qr_image_path = ? WHERE id = ?", [$path, $id]);
    }

    public static function approve(int $id, int $orgId, int $approvedBy): int
    {
        return DB::run(
            "UPDATE visits SET status = 'approved', approved_by = ?, approved_at = NOW()
             WHERE id = ? AND org_id = ? AND status = 'pending'",
            [$approvedBy, $id, $orgId]
        );
    }

    public static function reject(int $id, int $orgId, int $rejectedBy, string $reason = ''): int
    {
        return DB::run(
            "UPDATE visits SET status = 'rejected', approved_by = ?, approved_at = NOW(), rejected_reason = ?
             WHERE id = ? AND org_id = ? AND status = 'pending'",
            [$rejectedBy, $reason, $id, $orgId]
        );
    }

    public static function checkIn(int $id, int $gatemanId): int
    {
        return DB::run(
            "UPDATE visits SET status = 'checked_in', checked_in_at = NOW(), checked_in_by = ?
             WHERE id = ? AND status IN ('approved','pending')",
            [$gatemanId, $id]
        );
    }

    public static function checkOut(int $id, int $gatemanId): int
    {
        return DB::run(
            "UPDATE visits SET status = 'checked_out', checked_out_at = NOW(), checked_out_by = ?
             WHERE id = ? AND status = 'checked_in'",
            [$gatemanId, $id]
        );
    }

    public static function listByOrg(int $orgId, array $filters = [], int $limit = 100): array
    {
        $sql = "SELECT v.*, b.name AS building_name, r.name AS room_name, h.full_name AS host_name
                FROM visits v
                LEFT JOIN buildings b ON v.building_id = b.id
                LEFT JOIN rooms r ON v.room_id = r.id
                LEFT JOIN users h ON v.host_id = h.id
                WHERE v.org_id = ?";
        $params = [$orgId];

        if (!empty($filters['status'])) {
            $sql .= " AND v.status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['building_id'])) {
            $sql .= " AND v.building_id = ?";
            $params[] = $filters['building_id'];
        }
        if (!empty($filters['date'])) {
            $sql .= " AND DATE(v.created_at) = ?";
            $params[] = $filters['date'];
        }

        $sql .= " ORDER BY v.created_at DESC LIMIT " . (int)$limit;
        return DB::all($sql, $params);
    }

    public static function listByHost(int $hostId, ?string $status = null): array
    {
        $sql = "SELECT v.*, b.name AS building_name, r.name AS room_name
                FROM visits v
                LEFT JOIN buildings b ON v.building_id = b.id
                LEFT JOIN rooms r ON v.room_id = r.id
                WHERE v.host_id = ?";
        $params = [$hostId];
        if ($status) {
            $sql .= " AND v.status = ?";
            $params[] = $status;
        }
        $sql .= " ORDER BY v.created_at DESC";
        return DB::all($sql, $params);
    }

    public static function listByVisitorUser(int $userId): array
    {
        return DB::all(
            "SELECT v.*, b.name AS building_name, r.name AS room_name, h.full_name AS host_name
             FROM visits v
             LEFT JOIN buildings b ON v.building_id = b.id
             LEFT JOIN rooms r ON v.room_id = r.id
             LEFT JOIN users h ON v.host_id = h.id
             WHERE v.visitor_user_id = ? ORDER BY v.created_at DESC", [$userId]
        );
    }

    public static function currentlyInBuilding(int $orgId): array
    {
        return DB::all(
            "SELECT v.*, b.name AS building_name, r.name AS room_name, h.full_name AS host_name
             FROM visits v
             LEFT JOIN buildings b ON v.building_id = b.id
             LEFT JOIN rooms r ON v.room_id = r.id
             LEFT JOIN users h ON v.host_id = h.id
             WHERE v.org_id = ? AND v.status = 'checked_in'
             ORDER BY v.checked_in_at DESC", [$orgId]
        );
    }

    public static function dashboardCounts(int $orgId): array
    {
        $today = date('Y-m-d');
        $totalToday = DB::one("SELECT COUNT(*) c FROM visits WHERE org_id = ? AND DATE(created_at) = ?", [$orgId, $today])['c'];
        $checkedIn  = DB::one("SELECT COUNT(*) c FROM visits WHERE org_id = ? AND status = 'checked_in'", [$orgId])['c'];
        $pending    = DB::one("SELECT COUNT(*) c FROM visits WHERE org_id = ? AND status = 'pending'", [$orgId])['c'];
        $thisMonth  = DB::one("SELECT COUNT(*) c FROM visits WHERE org_id = ? AND MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())", [$orgId])['c'];
        return compact('totalToday', 'checkedIn', 'pending', 'thisMonth');
    }

    public static function monthlyTrend(int $orgId, int $months = 6): array
    {
        return DB::all(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS total
             FROM visits WHERE org_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL ? MONTH)
             GROUP BY ym ORDER BY ym", [$orgId, $months]
        );
    }
}
