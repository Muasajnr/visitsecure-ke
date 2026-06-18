<?php
/**
 * app/controllers/superadmin/organization_status.php
 * Route: POST /superadmin/organizations/{id}/status
 */

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

$newStatus = input('subscription_status');
$allowed = ['trial', 'active', 'suspended', 'expired'];

if (!in_array($newStatus, $allowed, true)) {
    flash('error', 'Invalid subscription status.');
    redirect('/superadmin/organizations/' . $orgId);
}

Organization::updateStatus($orgId, $newStatus);
logAudit(null, Auth::id(), 'org_status_changed', "{$org['name']} subscription status changed to {$newStatus}");

flash('success', "{$org['name']}'s subscription status updated to " . ucfirst($newStatus) . '.');
redirect('/superadmin/organizations/' . $orgId);
