<?php
/** POST /superadmin/organizations/{id}/toggle */

$orgId = (int) $id;

if (!verifyCsrf()) {
    flash('error', 'Session expired, please try again.');
    redirect('/superadmin/organizations/' . $orgId);
}

$org = Organization::find($orgId);
if (!$org) {
    flash('error', 'Organisation not found.');
    redirect('/superadmin/organizations');
}

$newStatus = $org['is_active'] ? 0 : 1;
Organization::toggleActive($orgId, $newStatus);

$label = $newStatus ? 'reactivated' : 'deactivated';
logAudit(null, Auth::id(), 'org_toggled', "{$org['name']} {$label}");
flash('success', "{$org['name']} has been {$label}.");
redirect('/superadmin/organizations/' . $orgId);
