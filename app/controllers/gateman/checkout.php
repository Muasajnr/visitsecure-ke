<?php
/**
 * app/controllers/gateman/checkout.php
 * Route: POST /gateman/checkout/{id}
 */

$orgId = Auth::orgId();
$visitId = (int) $id;

if (!verifyCsrf()) {
    flash('error', 'Session expired, please try again.');
    redirect('/gateman/dashboard');
}

$visit = Visit::find($visitId, $orgId);
if (!$visit) {
    flash('error', 'Visit not found.');
    redirect('/gateman/dashboard');
}

if ($visit['status'] !== 'checked_in') {
    flash('error', 'This visitor is not currently checked in.');
    redirect('/gateman/dashboard');
}

Visit::checkOut($visitId, Auth::id());
logAudit($orgId, Auth::id(), 'visitor_checked_out', "Checked out {$visit['visitor_name']}");

flash('success', "{$visit['visitor_name']} has been checked out.");
redirect('/gateman/dashboard');
