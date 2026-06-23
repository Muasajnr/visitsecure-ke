<?php
/** POST /orgadmin/visits/{id}/cancel */

$orgId = Auth::orgId();
$visitId = (int) $id;

if (!verifyCsrf()) {
    flash('error', 'Session expired, please try again.');
    redirect('/orgadmin/visits');
}

$visit = Visit::find($visitId, $orgId);
if (!$visit) {
    flash('error', 'Visit not found.');
    redirect('/orgadmin/visits');
}

if (!Visit::cancel($visitId, $orgId)) {
    flash('error', 'This visit cannot be cancelled in its current state.');
    redirect('/orgadmin/visits/' . $visitId);
}

logAudit($orgId, Auth::id(), 'visit_cancelled', "Admin cancelled visit for {$visit['visitor_name']}");
flash('success', "Visit for {$visit['visitor_name']} has been cancelled.");
redirect('/orgadmin/visits/' . $visitId);
