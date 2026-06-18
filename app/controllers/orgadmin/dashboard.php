<?php
/**
 * app/controllers/orgadmin/dashboard.php
 */

$orgId = Auth::orgId();
$counts = Organization::counts($orgId);
$visitStats = Visit::dashboardCounts($orgId);
$recentVisits = Visit::listByOrg($orgId, [], 8);
$org = Organization::find($orgId);

view('orgadmin/dashboard', [
    'pageTitle'    => 'Dashboard',
    'counts'       => $counts,
    'visitStats'   => $visitStats,
    'recentVisits' => $recentVisits,
    'org'          => $org,
]);
