<?php
/**
 * app/controllers/eventmanager/create.php
 */

$user = Auth::user();
$orgId = $user['org_id'];

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Session expired, please try again.');
        redirect('/events/create');
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
        redirect('/events/create');
    }

    if (strtotime($endDatetime) <= strtotime($startDatetime)) {
        flash('error', 'End time must be after the start time.');
        redirect('/events/create');
    }

    $eventId = Event::create($orgId, $user['id'], [
        'building_id'    => $buildingId,
        'floor_id'       => $floorId ?: null,
        'room_id'        => $roomId ?: null,
        'title'          => $title,
        'description'    => $description,
        'start_datetime' => $startDatetime,
        'end_datetime'   => $endDatetime,
        'max_visitors'   => $maxVisitors ?: null,
    ]);

    logAudit($orgId, $user['id'], 'event_created', "Event '{$title}' scheduled");
    flash('success', "Event '{$title}' has been scheduled.");
    redirect('/events/' . $eventId);
}

$buildings = Building::allByOrg($orgId);
$floors = Floor::allByOrg($orgId);
$rooms = Room::allByOrg($orgId);

view('eventmanager/create', [
    'pageTitle' => 'Schedule Event',
    'buildings' => $buildings,
    'floors'    => $floors,
    'rooms'     => $rooms,
]);
