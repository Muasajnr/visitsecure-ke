<?php
/** app/controllers/superadmin/reports.php */

$filters = [
    'from' => input('from', date('Y-m-01')),
    'to'   => input('to', date('Y-m-d')),
];

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $filters['from'])) $filters['from'] = date('Y-m-01');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $filters['to'])) $filters['to'] = date('Y-m-d');
if ($filters['from'] > $filters['to']) [$filters['from'], $filters['to']] = [$filters['to'], $filters['from']];

$where = 'DATE(v.created_at) BETWEEN ? AND ?';
$params = [$filters['from'], $filters['to']];

$summary = DB::one(
    "SELECT COUNT(*) AS total,
            COUNT(DISTINCT v.org_id) AS organisations,
            SUM(v.status = 'checked_in') AS checked_in,
            SUM(v.status = 'checked_out') AS checked_out,
            SUM(v.status = 'rejected') AS rejected,
            SUM(v.is_walk_in = 1) AS walk_ins,
            ROUND(AVG(CASE WHEN v.checked_in_at IS NOT NULL AND v.checked_out_at IS NOT NULL
                      THEN TIMESTAMPDIFF(MINUTE, v.checked_in_at, v.checked_out_at) END), 1) AS avg_duration
     FROM visits v WHERE {$where}", $params
) ?: [];

$visitsByOrg = DB::all(
    "SELECT o.name, COUNT(v.id) AS total
     FROM organizations o
     LEFT JOIN visits v ON v.org_id = o.id
       AND DATE(v.created_at) BETWEEN ? AND ?
     GROUP BY o.id ORDER BY total DESC LIMIT 20", [$filters['from'], $filters['to']]
);

$signupsTrend = DB::all(
    "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS total
     FROM organizations WHERE DATE(created_at) BETWEEN ? AND ?
     GROUP BY ym ORDER BY ym"
    , [$filters['from'], $filters['to']]
);

$planBreakdown = DB::all("SELECT subscription_status, COUNT(*) AS total FROM organizations GROUP BY subscription_status");
$statusBreakdown = DB::all(
    "SELECT status, COUNT(*) AS total FROM visits v WHERE {$where} GROUP BY status ORDER BY total DESC", $params
);
$sourceBreakdown = DB::all(
    "SELECT source, COUNT(*) AS total FROM visits v WHERE {$where} GROUP BY source ORDER BY total DESC", $params
);
$dailyTrend = DB::all(
    "SELECT DATE(v.created_at) AS day, COUNT(*) AS total FROM visits v
     WHERE {$where} GROUP BY day ORDER BY day", $params
);

view('superadmin/reports', [
    'pageTitle'     => 'Platform Reports',
    'filters'       => $filters,
    'summary'       => $summary,
    'visitsByOrg'   => $visitsByOrg,
    'signupsTrend'  => $signupsTrend,
    'planBreakdown' => $planBreakdown,
    'statusBreakdown'=> $statusBreakdown,
    'sourceBreakdown'=> $sourceBreakdown,
    'dailyTrend'    => $dailyTrend,
]);
