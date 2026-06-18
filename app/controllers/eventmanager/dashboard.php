<?php
/**
 * app/controllers/eventmanager/dashboard.php
 */

$user = Auth::user();
$events = Event::allByCreator($user['id']);

view('eventmanager/dashboard', [
    'pageTitle' => 'Event Manager Dashboard',
    'events'    => $events,
]);
