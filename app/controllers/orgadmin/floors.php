<?php
/**
 * app/controllers/orgadmin/floors.php
 * Route: /orgadmin/buildings/{id}/floors
 */

$orgId = Auth::orgId();
$buildingId = (int) $id;

$building = Building::find($buildingId, $orgId);
if (!$building) {
    flash('error', 'Building not found.');
    redirect('/orgadmin/buildings');
}

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Session expired, please try again.');
        redirect('/orgadmin/buildings/' . $buildingId . '/floors');
    }

    $name = input('name');
    $floorNumber = input('floor_number');

    if (!$name) {
        flash('error', 'Floor name is required.');
        redirect('/orgadmin/buildings/' . $buildingId . '/floors');
    }

    Floor::create($orgId, $buildingId, ['name' => $name, 'floor_number' => $floorNumber !== '' ? $floorNumber : null]);
    logAudit($orgId, Auth::id(), 'floor_created', "Floor '{$name}' created in {$building['name']}");

    flash('success', "Floor '{$name}' added successfully.");
    redirect('/orgadmin/buildings/' . $buildingId . '/floors');
}

$floors = Floor::allByBuilding($buildingId, $orgId);

view('orgadmin/floors', [
    'pageTitle' => $building['name'] . ' - Floors',
    'building'  => $building,
    'floors'    => $floors,
]);
