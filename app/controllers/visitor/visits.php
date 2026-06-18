<?php
/**
 * app/controllers/visitor/visits.php
 */

$user = Auth::user();
$visits = Visit::listByVisitorUser($user['id']);

view('visitor/visits', [
    'pageTitle' => 'My Visits & Passes',
    'visits'    => $visits,
]);
