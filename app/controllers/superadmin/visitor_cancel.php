<?php
/** POST /superadmin/visitors/{id}/cancel */

$visitId = (int) $id;

if (!verifyCsrf()) {
    flash('error', 'Session expired, please try again.');
    redirect('/superadmin/visitors');
}

$visit = DB::one("SELECT * FROM visits WHERE id = ?", [$visitId]);
if (!$visit) {
    flash('error', 'Visitor visit not found.');
    redirect('/superadmin/visitors');
}

if (!in_array($visit['status'], ['pending', 'approved'], true)) {
    flash('error', 'Only pending or approved visits can be cancelled.');
    redirect('/superadmin/visitors');
}

if (Visit::cancel($visitId, (int)$visit['org_id']) !== 1) {
    flash('error', 'This visitor visit could not be cancelled. Please refresh and try again.');
    redirect('/superadmin/visitors');
}

logAudit((int)$visit['org_id'], Auth::id(), 'visit_cancelled_by_superadmin', "Cancelled visit for {$visit['visitor_name']}");
flash('success', "{$visit['visitor_name']}\'s visit has been cancelled.");
redirect('/superadmin/visitors');
