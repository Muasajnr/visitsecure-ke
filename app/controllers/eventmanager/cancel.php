<?php
/** POST /events/{id}/cancel */

$user = Auth::user();
$orgId = $user['org_id'];
$eventId = (int) $id;

if (!verifyCsrf()) {
    flash('error', 'Session expired, please try again.');
    redirect('/events/' . $eventId);
}

$event = Event::find($eventId, $orgId);
if (!$event) {
    flash('error', 'Event not found.');
    redirect('/events/dashboard');
}

if (!Event::cancel($eventId, $orgId)) {
    flash('error', 'This event is already cancelled.');
    redirect('/events/' . $eventId);
}

logAudit($orgId, $user['id'], 'event_cancelled', "Event '{$event['title']}' cancelled");
flash('success', "Event '{$event['title']}' has been cancelled.");
redirect('/events/' . $eventId);
