<?php
/** POST /orgadmin/floors/{id}/delete */

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

$buildingId = $floor['building_id'];
$name = $floor['name'];
Floor::delete($floorId, $orgId);

logAudit($orgId, Auth::id(), 'floor_deleted', "Floor '{$name}' deleted");
flash('success', "Floor '{$name}' and its rooms were removed.");
redirect('/orgadmin/buildings/' . $buildingId . '/floors');
