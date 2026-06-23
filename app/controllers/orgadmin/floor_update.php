<?php
/** POST /orgadmin/floors/{id}/update */

$orgId = Auth::orgId();
$floorId = (int) $id;

if (!verifyCsrf()) {
    flash('error', 'Session expired, please try again.');
    redirect('/orgadmin/buildings');
}

$floor = Floor::find($floorId, $orgId);
if (!$floor) {
    flash('error', 'Floor not found.');
    redirect('/orgadmin/buildings');
}

$name = input('name');
if (!$name) {
    flash('error', 'Floor name is required.');
    redirect('/orgadmin/buildings/' . $floor['building_id'] . '/floors');
}

$floorNumber = input('floor_number');
Floor::update($floorId, $orgId, [
    'name'          => $name,
    'floor_number'  => $floorNumber !== '' ? $floorNumber : null,
]);

logAudit($orgId, Auth::id(), 'floor_updated', "Floor '{$name}' updated");
flash('success', "Floor '{$name}' updated.");
redirect('/orgadmin/buildings/' . $floor['building_id'] . '/floors');
