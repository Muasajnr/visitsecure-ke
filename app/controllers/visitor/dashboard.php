<?php
/**
 * app/controllers/visitor/dashboard.php
 */

$user = Auth::user();
$visits = Visit::listByVisitorUser($user['id']);
$upcoming = array_filter($visits, fn($v) => in_array($v['status'], ['pending', 'approved'], true));
$recent = array_slice($visits, 0, 6);

view('visitor/dashboard', [
    'pageTitle' => 'My Dashboard',
    'upcoming'  => $upcoming,
    'recent'    => $recent,
    'total'     => count($visits),
]);
