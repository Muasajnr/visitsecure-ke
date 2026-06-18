<?php
/**
 * app/controllers/host/reject.php
 * Route: POST /host/visits/{id}/reject
 */

$user = Auth::user();
$orgId = $user['org_id'];
$visitId = (int) $id;

if (!verifyCsrf()) {
    flash('error', 'Session expired, please try again.');
    redirect('/host/dashboard');
}

$visit = Visit::find($visitId, $orgId);
if (!$visit || (int)$visit['host_id'] !== $user['id']) {
    flash('error', 'Visit not found.');
    redirect('/host/dashboard');
}

if ($visit['status'] !== 'pending') {
    flash('error', 'This visit has already been processed.');
    redirect('/host/dashboard');
}

$reason = input('reason', 'Not specified');
Visit::reject($visitId, $orgId, $user['id'], $reason);

if (!empty($visit['visitor_email'])) {
    $html = '<div style="font-family:Arial,sans-serif;max-width:560px;margin:0 auto;padding:24px;border:1px solid #e5e7eb;border-radius:8px;">'
        . '<h2 style="color:#0f172a;">Your visit request was declined</h2>'
        . "<p style=\"color:#334155;font-size:15px;line-height:1.6;\">Unfortunately, your visit to {$visit['host_name']} was not approved. Please contact them directly if you have questions.</p>"
        . '</div>';
    $overrides = DB::one("SELECT * FROM org_settings WHERE org_id = ?", [$orgId]) ?: [];
    Mailer::send($visit['visitor_email'], 'Your visit request was declined', $html, $overrides);
}

logAudit($orgId, $user['id'], 'visit_rejected', "Rejected visit for {$visit['visitor_name']}");

flash('success', "{$visit['visitor_name']}'s visit request has been rejected.");
redirect('/host/dashboard');
