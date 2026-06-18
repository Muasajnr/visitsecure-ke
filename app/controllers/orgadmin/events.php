<?php
/**
 * app/controllers/orgadmin/events.php
 */

$orgId = Auth::orgId();
$events = Event::allByOrg($orgId);

view('orgadmin/events', [
    'pageTitle' => 'Events',
    'events'    => $events,
]);
