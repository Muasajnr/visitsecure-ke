<?php
/**
 * app/controllers/orgadmin/rooms.php
 * Route: /orgadmin/floors/{id}/rooms
 */

$orgId = Auth::orgId();
$floorId = (int) $id;

$floor = Floor::find($floorId, $orgId);
if (!$floor) {
    flash('error', 'Floor not found.');
    redirect('/orgadmin/buildings');
}
$building = Building::find($floor['building_id'], $orgId);

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Session expired, please try again.');
        redirect('/orgadmin/floors/' . $floorId . '/rooms');
    }

    $name = input('name');
    $roomType = input('room_type', 'office');
    $firmName = input('firm_name');

    if (!$name) {
        flash('error', 'Room/office name is required.');
        redirect('/orgadmin/floors/' . $floorId . '/rooms');
    }

    Room::create($orgId, $floorId, ['name' => $name, 'room_type' => $roomType, 'firm_name' => $firmName]);
    logAudit($orgId, Auth::id(), 'room_created', "Room '{$name}' created on floor '{$floor['name']}'");

    flash('success', "Room '{$name}' added successfully.");
    redirect('/orgadmin/floors/' . $floorId . '/rooms');
}

$rooms = Room::allByFloor($floorId, $orgId);

view('orgadmin/rooms', [
    'pageTitle' => $floor['name'] . ' - Rooms',
    'floor'     => $floor,
    'building'  => $building,
    'rooms'     => $rooms,
]);
