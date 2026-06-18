<?php
/**
 * app/controllers/superadmin/dashboard.php
 */

$totalOrgs = DB::one("SELECT COUNT(*) c FROM organizations")['c'];
$activeOrgs = DB::one("SELECT COUNT(*) c FROM organizations WHERE subscription_status = 'active'")['c'];
$trialOrgs = DB::one("SELECT COUNT(*) c FROM organizations WHERE subscription_status = 'trial'")['c'];
$totalUsers = DB::one("SELECT COUNT(*) c FROM users")['c'];
$totalVisitsToday = DB::one("SELECT COUNT(*) c FROM visits WHERE DATE(created_at) = CURDATE()")['c'];

$recentOrgs = DB::all("SELECT * FROM organizations ORDER BY created_at DESC LIMIT 8");

view('superadmin/dashboard', [
    'pageTitle' => 'Platform Dashboard',
    'totalOrgs' => $totalOrgs,
    'activeOrgs' => $activeOrgs,
    'trialOrgs' => $trialOrgs,
    'totalUsers' => $totalUsers,
    'totalVisitsToday' => $totalVisitsToday,
    'recentOrgs' => $recentOrgs,
]);
