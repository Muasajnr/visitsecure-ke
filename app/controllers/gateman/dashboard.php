<?php
/**
 * app/controllers/gateman/dashboard.php
 */

$orgId = Auth::orgId();
$visitStats = Visit::dashboardCounts($orgId);
$currentlyIn = Visit::currentlyInBuilding($orgId);

view('gateman/dashboard', [
    'pageTitle'   => 'Gate Dashboard',
    'visitStats'  => $visitStats,
    'currentlyIn' => $currentlyIn,
]);
