<?php
/** POST /orgadmin/visits/{id}/reject */

$orgId = Auth::orgId();
$visitId = (int) $id;
$userId = Auth::id();

if (!verifyCsrf()) {
    flash('error', 'Session expired, please try again.');
    redirect('/orgadmin/visits');
}

$visit = Visit::find($visitId, $orgId);
if (!$visit || $visit['status'] !== 'pending') {
    flash('error', 'Visit not found or already processed.');
    redirect('/orgadmin/visits');
}

$reason = input('rejected_reason', 'Rejected by organisation admin');
Visit::reject($visitId, $orgId, $userId, $reason);

logAudit($orgId, $userId, 'visit_rejected_by_admin', "Admin rejected visit for {$visit['visitor_name']}");
flash('success', "Visit for {$visit['visitor_name']} rejected.");
redirect('/orgadmin/visits/' . $visitId);
