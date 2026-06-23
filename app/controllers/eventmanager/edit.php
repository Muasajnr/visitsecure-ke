<?php
/**
 * app/controllers/eventmanager/edit.php
 * Route: GET|POST /events/{id}/edit
 */

$user = Auth::user();
$orgId = $user['org_id'];
$eventId = (int) $id;

$event = Event::find($eventId, $orgId);
if (!$event) {
    flash('error', 'Event not found.');
    redirect('/events/dashboard');
}

if ($event['status'] === 'cancelled') {
    flash('error', 'Cancelled events cannot be edited.');
    redirect('/events/' . $eventId);
}

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Session expired, please try again.');
        redirect('/events/' . $eventId . '/edit');
    }

    $title = input('title');
    $description = input('description');
    $buildingId = input('building_id');
    $floorId = input('floor_id');
    $roomId = input('room_id');
    $startDatetime = input('start_datetime');
    $endDatetime = input('end_datetime');
    $maxVisitors = input('max_visitors');

    if (!$title || !$buildingId || !$startDatetime || !$endDatetime) {
        flash('error', 'Title, building, start time, and end time are required.');
        redirect('/events/' . $eventId . '/edit');
    }

    if (strtotime($endDatetime) <= strtotime($startDatetime)) {
        flash('error', 'End time must be after the start time.');
        redirect('/events/' . $eventId . '/edit');
    }

    Event::update($eventId, $orgId, [
        'title'          => $title,
        'description'    => $description,
        'building_id'    => $buildingId,
        'floor_id'       => $floorId ?: null,
        'room_id'        => $roomId ?: null,
        'start_datetime' => $startDatetime,
        'end_datetime'   => $endDatetime,
        'max_visitors'   => $maxVisitors ?: null,
    ]);

    logAudit($orgId, $user['id'], 'event_updated', "Event '{$title}' updated");
    flash('success', "Event '{$title}' updated.");
    redirect('/events/' . $eventId);
}

$buildings = Building::allByOrg($orgId);
$floors = Floor::allByOrg($orgId);
$rooms = Room::allByOrg($orgId);

view('eventmanager/edit', [
    'pageTitle' => 'Edit Event',
    'event'     => $event,
    'buildings' => $buildings,
    'floors'    => $floors,
    'rooms'     => $rooms,
]);
