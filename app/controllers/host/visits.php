<?php
/**
 * app/controllers/host/visits.php
 */

$user = Auth::user();
$status = input('status', '');
$visits = Visit::listByHost($user['id'], $status ?: null);

view('host/visits', [
    'pageTitle' => 'My Visitors',
    'visits'    => $visits,
    'status'    => $status,
]);
