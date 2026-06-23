<?php
/** POST /orgadmin/rooms/{id}/update */

$orgId = Auth::orgId();
$roomId = (int) $id;

if (!verifyCsrf()) {
    flash('error', 'Session expired, please try again.');
    redirect('/orgadmin/buildings');
}

$room = Room::find($roomId, $orgId);
if (!$room) {
    flash('error', 'Room not found.');
    redirect('/orgadmin/buildings');
}

$name = input('name');
if (!$name) {
    flash('error', 'Room name is required.');
    redirect('/orgadmin/floors/' . $room['floor_id'] . '/rooms');
}

Room::update($roomId, $orgId, [
    'name'      => $name,
    'room_type' => input('room_type', 'office'),
    'firm_name' => input('firm_name'),
]);

logAudit($orgId, Auth::id(), 'room_updated', "Room '{$name}' updated");
flash('success', "Room '{$name}' updated.");
redirect('/orgadmin/floors/' . $room['floor_id'] . '/rooms');
