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
    $qrPath = QrCodeGenerator::generate($visit['qr_code'], 'visit_' . $visit['uuid']);
    Visit::setQrImage($visitId, $qrPath);
    $visit = Visit::find($visitId, $orgId);
}

// Notify the visitor (by account if they have one, otherwise just email)
if (!empty($visit['visitor_user_id'])) {
    NotificationService::sendGatePass($visit, ['id' => $visit['visitor_user_id']]);
} elseif (!empty($visit['visitor_email'])) {
    NotificationService::emailGatePass(
        $visit['visitor_email'],
        $orgId,
        'Your visit has been approved',
        $visit,
        'Your visit has been approved',
        "Your visit to {$visit['host_name']} has been approved. Present this QR code at the gate for entry."
    );
}

logAudit($orgId, $user['id'], 'visit_approved', "Approved visit for {$visit['visitor_name']}");

flash('success', "{$visit['visitor_name']}'s visit has been approved and their gate pass sent.");
redirect('/host/dashboard');
