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

$confirmation = preg_replace('/\s+/', ' ', trim(input('checkout_confirmation', '')));
$visitorName = preg_replace('/\s+/', ' ', trim($visit['visitor_name']));

if (strcasecmp($confirmation, $visitorName) !== 0) {
    flash('error', 'Checkout cancelled. Type the visitor\'s full name exactly as shown to confirm.');
    redirect('/gateman/dashboard');
}

if (Visit::checkOut($visitId, Auth::id()) !== 1) {
    flash('error', 'This visitor could not be checked out. Please refresh and try again.');
    redirect('/gateman/dashboard');
}

logAudit($orgId, Auth::id(), 'visitor_checked_out', "Checked out {$visit['visitor_name']}");

flash('success', "{$visit['visitor_name']} has been checked out.");
redirect('/gateman/dashboard');
