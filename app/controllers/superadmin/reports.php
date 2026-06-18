<?php
/**
 * app/controllers/superadmin/reports.php
 */

$visitsByOrg = DB::all(
    "SELECT o.name, COUNT(v.id) AS total
     FROM organizations o
     LEFT JOIN visits v ON v.org_id = o.id
     GROUP BY o.id ORDER BY total DESC LIMIT 10"
);

$signupsTrend = DB::all(
    "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS total
     FROM organizations WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
     GROUP BY ym ORDER BY ym"
);

$planBreakdown = DB::all("SELECT subscription_status, COUNT(*) AS total FROM organizations GROUP BY subscription_status");

view('superadmin/reports', [
    'pageTitle'     => 'Platform Reports',
    'visitsByOrg'   => $visitsByOrg,
    'signupsTrend'  => $signupsTrend,
    'planBreakdown' => $planBreakdown,
]);
