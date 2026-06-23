<?php
/** POST /orgadmin/buildings/{id}/update */

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

$name = input('name');
if (!$name) {
    flash('error', 'Building name is required.');
    redirect('/orgadmin/buildings');
}

Building::update($buildingId, $orgId, [
    'name'        => $name,
    'address'     => input('address'),
    'description' => input('description'),
]);

logAudit($orgId, Auth::id(), 'building_updated', "Building '{$name}' updated");
flash('success', "Building '{$name}' updated.");
redirect('/orgadmin/buildings');
