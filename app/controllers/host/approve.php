<?php
/**
 * app/controllers/host/approve.php
 * Route: POST /host/visits/{id}/approve
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

Visit::approve($visitId, $orgId, $user['id']);

// Generate the QR gate pass now that it's approved
$visit = Visit::find($visitId, $orgId);
if (empty($visit['qr_image_path'])) {
    $qrPath = QrCode::generate($visit['qr_code'], 'visit_' . $visit['uuid']);
    Visit::setQrImage($visitId, $qrPath);
    $visit = Visit::find($visitId, $orgId);
}

// Notify the visitor (by account if they have one, otherwise just email)
if (!empty($visit['visitor_user_id'])) {
    NotificationService::sendGatePass($visit, ['id' => $visit['visitor_user_id']]);
} elseif (!empty($visit['visitor_email'])) {
    $qrUrl = QrCode::publicUrl($visit['qr_image_path']);
    $html = '<div style="font-family:Arial,sans-serif;max-width:560px;margin:0 auto;padding:24px;border:1px solid #e5e7eb;border-radius:8px;">'
        . '<h2 style="color:#0f172a;">Your visit has been approved</h2>'
        . "<p style=\"color:#334155;font-size:15px;line-height:1.6;\">Your visit to {$visit['host_name']} has been approved. Present this QR code at the gate for entry.</p>"
        . "<div style=\"text-align:center;margin:24px 0;\"><img src=\"{$qrUrl}\" style=\"width:200px;height:200px;\"></div>"
        . '<p style="color:#94a3b8;font-size:12px;margin-top:20px;">This is an automated message from VisitSecure KE.</p></div>';
    $overrides = DB::one("SELECT * FROM org_settings WHERE org_id = ?", [$orgId]) ?: [];
    Mailer::send($visit['visitor_email'], 'Your visit has been approved', $html, $overrides);
}

logAudit($orgId, $user['id'], 'visit_approved', "Approved visit for {$visit['visitor_name']}");

flash('success', "{$visit['visitor_name']}'s visit has been approved and their gate pass sent.");
redirect('/host/dashboard');
