<?php
/**
 * app/controllers/host/dashboard.php
 */

$user = Auth::user();
$pendingVisits = Visit::listByHost($user['id'], 'pending');
$upcomingVisits = Visit::listByHost($user['id'], 'approved');
$recentVisits = array_slice(Visit::listByHost($user['id']), 0, 8);

view('host/dashboard', [
    'pageTitle'      => 'Host Dashboard',
    'pendingVisits'  => $pendingVisits,
    'upcomingVisits' => $upcomingVisits,
    'recentVisits'   => $recentVisits,
]);
