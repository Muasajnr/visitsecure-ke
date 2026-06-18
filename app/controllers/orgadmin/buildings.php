<?php
/**
 * app/controllers/orgadmin/buildings.php
 */

$orgId = Auth::orgId();

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Session expired, please try again.');
        redirect('/orgadmin/buildings');
    }

    $name = input('name');
    $address = input('address');
    $description = input('description');

    if (!$name) {
        flash('error', 'Building name is required.');
        redirect('/orgadmin/buildings');
    }

    Building::create($orgId, compact('name', 'address', 'description'));
    logAudit($orgId, Auth::id(), 'building_created', "Building '{$name}' created");

    flash('success', "Building '{$name}' added successfully.");
    redirect('/orgadmin/buildings');
}

$buildings = Building::allByOrg($orgId);

view('orgadmin/buildings', [
    'pageTitle' => 'Buildings & Floors',
    'buildings' => $buildings,
]);
