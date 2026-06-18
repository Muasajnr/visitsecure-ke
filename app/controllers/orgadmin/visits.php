<?php
/**
 * app/controllers/orgadmin/visits.php
 */

$orgId = Auth::orgId();

$filters = [
    'status'      => input('status', ''),
    'building_id' => input('building_id', ''),
    'date'        => input('date', ''),
];

$visits = Visit::listByOrg($orgId, array_filter($filters), 200);
$buildings = Building::allByOrg($orgId);

view('orgadmin/visits', [
    'pageTitle' => 'All Visits',
    'visits'    => $visits,
    'buildings' => $buildings,
    'filters'   => $filters,
]);
