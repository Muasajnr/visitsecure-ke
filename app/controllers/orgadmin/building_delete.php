<?php
/** POST /orgadmin/buildings/{id}/delete */

$orgId = Auth::orgId();
$buildingId = (int) $id;

if (!verifyCsrf()) {
    flash('error', 'Session expired, please try again.');
    redirect('/orgadmin/buildings');
}

$building = Building::find($buildingId, $orgId);
if (!$building) {
    flash('error', 'Building not found.');
    redirect('/orgadmin/buildings');
}

Building::delete($buildingId, $orgId);
logAudit($orgId, Auth::id(), 'building_deleted', "Building '{$building['name']}' deleted");
flash('success', "Building '{$building['name']}' and all its floors/rooms were removed.");
redirect('/orgadmin/buildings');
