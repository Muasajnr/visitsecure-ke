<?php
/** POST /orgadmin/rooms/{id}/delete */

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

$floorId = $room['floor_id'];
$name = $room['name'];
Room::delete($roomId, $orgId);

logAudit($orgId, Auth::id(), 'room_deleted', "Room '{$name}' deleted");
flash('success', "Room '{$name}' removed.");
redirect('/orgadmin/floors/' . $floorId . '/rooms');
