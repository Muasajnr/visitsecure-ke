<?php
/**
 * app/controllers/eventmanager/view.php
 * Route: /events/{id}
 */

$user = Auth::user();
$orgId = $user['org_id'];
$eventId = (int) $id;

$event = Event::find($eventId, $orgId);
if (!$event) {
    flash('error', 'Event not found.');
    redirect('/events/dashboard');
}

$registeredVisitors = DB::all(
    "SELECT v.* FROM visits v WHERE v.event_id = ? ORDER BY v.created_at DESC", [$eventId]
);

view('eventmanager/view', [
    'pageTitle' => $event['title'],
    'event'     => $event,
    'visitors'  => $registeredVisitors,
]);
