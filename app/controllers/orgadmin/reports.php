<?php
/**
 * app/controllers/orgadmin/reports.php
 */

$orgId = Auth::orgId();
$filters = [
    'from' => input('from', date('Y-m-01')),
    'to'   => input('to', date('Y-m-d')),
];

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $filters['from'])) $filters['from'] = date('Y-m-01');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $filters['to'])) $filters['to'] = date('Y-m-d');
if ($filters['from'] > $filters['to']) [$filters['from'], $filters['to']] = [$filters['to'], $filters['from']];

$where = 'v.org_id = ? AND DATE(v.created_at) BETWEEN ? AND ?';
$params = [$orgId, $filters['from'], $filters['to']];

$summary = DB::one(
    "SELECT COUNT(*) AS total,
            SUM(status = 'approved') AS approved,
            SUM(status = 'checked_in') AS checked_in,
            SUM(status = 'checked_out') AS checked_out,
            SUM(status = 'rejected') AS rejected,
            SUM(is_walk_in = 1) AS walk_ins,
            ROUND(AVG(CASE WHEN checked_in_at IS NOT NULL AND checked_out_at IS NOT NULL
                      THEN TIMESTAMPDIFF(MINUTE, checked_in_at, checked_out_at) END), 1) AS avg_duration
     FROM visits v WHERE {$where}", $params
) ?: [];

$dailyTrend = DB::all(
    "SELECT DATE(v.created_at) AS day, COUNT(*) AS total
     FROM visits v WHERE {$where} GROUP BY day ORDER BY day", $params
);

$statusBreakdown = DB::all(
    "SELECT status, COUNT(*) AS total FROM visits v WHERE {$where} GROUP BY status ORDER BY total DESC", $params
);

$sourceBreakdown = DB::all(
    "SELECT source, COUNT(*) AS total FROM visits v WHERE {$where} GROUP BY source ORDER BY total DESC", $params
);

$byBuilding = DB::all(
    "SELECT b.name, COUNT(v.id) AS total
     FROM buildings b
         LEFT JOIN visits v ON v.building_id = b.id AND v.org_id = b.org_id
       AND DATE(v.created_at) BETWEEN ? AND ?
     WHERE b.org_id = ?
         GROUP BY b.id ORDER BY total DESC", [$filters['from'], $filters['to'], $orgId]
);

$topHosts = DB::all(
    "SELECT u.full_name, COUNT(v.id) AS total
     FROM users u
     JOIN visits v ON v.host_id = u.id
    WHERE u.org_id = ? AND DATE(v.created_at) BETWEEN ? AND ?
    GROUP BY u.id ORDER BY total DESC LIMIT 5", [$orgId, $filters['from'], $filters['to']]
);

view('orgadmin/reports', [
    'pageTitle'        => 'Analytics & Reports',
    'filters'          => $filters,
    'summary'          => $summary,
    'dailyTrend'       => $dailyTrend,
    'statusBreakdown'  => $statusBreakdown,
    'sourceBreakdown'  => $sourceBreakdown,
    'byBuilding'       => $byBuilding,
    'topHosts'         => $topHosts,
]);
