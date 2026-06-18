<?php
/**
 * app/controllers/gateman/log.php
 */

$orgId = Auth::orgId();

$filters = [
    'status' => input('status', ''),
    'date'   => input('date', date('Y-m-d')),
];

$visits = Visit::listByOrg($orgId, array_filter($filters), 150);

view('gateman/log', [
    'pageTitle' => 'Gate Log',
    'visits'    => $visits,
    'filters'   => $filters,
]);
