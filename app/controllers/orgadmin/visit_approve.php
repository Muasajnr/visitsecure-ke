<?php
/** POST /orgadmin/visits/{id}/approve */

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

Visit::approve($visitId, $orgId, $userId);

$visit = Visit::find($visitId, $orgId);
if (empty($visit['qr_image_path'])) {
    $qrPath = QrCodeGenerator::generate($visit['qr_code'], 'visit_' . $visit['uuid']);
    Visit::setQrImage($visitId, $qrPath);
    $visit = Visit::find($visitId, $orgId);
}

if (!empty($visit['visitor_user_id'])) {
    NotificationService::sendGatePass($visit, ['id' => $visit['visitor_user_id']]);
} elseif (!empty($visit['visitor_email'])) {
    NotificationService::emailGatePass(
        $visit['visitor_email'],
        $orgId,
        'Your visit has been approved',
        $visit,
        'Your visit has been approved',
        "Your visit has been approved. Present this QR code at the gate for entry."
    );
}

logAudit($orgId, $userId, 'visit_approved_by_admin', "Admin approved visit for {$visit['visitor_name']}");
flash('success', "Visit for {$visit['visitor_name']} approved and gate pass sent.");
redirect('/orgadmin/visits/' . $visitId);
