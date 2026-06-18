<?php
/**
 * app/controllers/orgadmin/reports.php
 */

$orgId = Auth::orgId();

$monthlyTrend = Visit::monthlyTrend($orgId, 6);

$statusBreakdown = DB::all(
    "SELECT status, COUNT(*) AS total FROM visits WHERE org_id = ? GROUP BY status", [$orgId]
);

$byBuilding = DB::all(
    "SELECT b.name, COUNT(v.id) AS total
     FROM buildings b
     LEFT JOIN visits v ON v.building_id = b.id
     WHERE b.org_id = ?
     GROUP BY b.id ORDER BY total DESC", [$orgId]
);

$topHosts = DB::all(
    "SELECT u.full_name, COUNT(v.id) AS total
     FROM users u
     JOIN visits v ON v.host_id = u.id
     WHERE u.org_id = ?
     GROUP BY u.id ORDER BY total DESC LIMIT 5", [$orgId]
);

view('orgadmin/reports', [
    'pageTitle'        => 'Analytics & Reports',
    'monthlyTrend'     => $monthlyTrend,
    'statusBreakdown'  => $statusBreakdown,
    'byBuilding'       => $byBuilding,
    'topHosts'         => $topHosts,
]);
