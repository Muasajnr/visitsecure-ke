<?php
/**
 * app/controllers/gateman/checkin.php
 * Route: POST /gateman/checkin/{id}
 */

$orgId = Auth::orgId();
$visitId = (int) $id;

if (!verifyCsrf()) {
    flash('error', 'Session expired, please try again.');
    redirect('/gateman/log');
}

$visit = Visit::find($visitId, $orgId);
if (!$visit) {
    flash('error', 'Visit not found.');
    redirect('/gateman/log');
}

if ($visit['status'] !== 'approved') {
    flash('error', 'Only approved visitors can be checked in.');
    redirect('/gateman/log');
}

if (Visit::checkIn($visitId, Auth::id()) !== 1) {
    flash('error', 'This visitor could not be checked in. Please refresh and try again.');
    redirect('/gateman/log');
}

logAudit($orgId, Auth::id(), 'visitor_checked_in', "Checked in {$visit['visitor_name']} manually");
flash('success', "{$visit['visitor_name']} has been checked in.");
redirect('/gateman/log');
