<?php
/**
 * app/controllers/superadmin/organization_view.php
 * Route: /superadmin/organizations/{id}
 */

$orgId = (int) $id;
$org = Organization::find($orgId);

if (!$org) {
    flash('error', 'Organisation not found.');
    redirect('/superadmin/organizations');
}

$counts = Organization::counts($orgId);
$staff = User::allByOrg($orgId);

view('superadmin/organization_view', [
    'pageTitle' => $org['name'],
    'org'       => $org,
    'counts'    => $counts,
    'staff'     => $staff,
]);
