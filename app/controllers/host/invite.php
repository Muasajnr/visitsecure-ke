<?php
/**
 * app/controllers/host/invite.php
 *
 * A host pre-registers a visitor they're expecting. Since the host is
 * creating the booking themselves, it's auto-approved and a QR pass is
 * generated and emailed immediately.
 */

$user = Auth::user();
$orgId = $user['org_id'];

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Session expired, please try again.');
        redirect('/host/invite');
    }

    $visitorName  = input('visitor_name');
    $visitorPhone = input('visitor_phone');
    $visitorEmail = input('visitor_email');
    $company      = input('visitor_company');
    $purpose      = input('purpose');
    $scheduledStart = input('scheduled_start');
    $scheduledEnd   = input('scheduled_end');

    if (!$visitorName || !$visitorPhone || !$scheduledStart) {
        flash('error', 'Visitor name, phone, and scheduled date/time are required.');
        redirect('/host/invite');
    }

    $hostRoom = Room::find((int)$user['room_id'], $orgId);
    $floor = $hostRoom ? Floor::find((int)$hostRoom['floor_id'], $orgId) : null;
    $buildingId = $floor['building_id'] ?? null;

    $visitId = Visit::create($orgId, [
        'building_id'    => $buildingId,
        'floor_id'       => $floor['id'] ?? null,
        'room_id'        => $hostRoom['id'] ?? null,
        'visitor_name'   => $visitorName,
        'visitor_phone'  => $visitorPhone,
        'visitor_email'  => $visitorEmail,
        'visitor_company'=> $company,
        'host_id'        => $user['id'],
        'purpose'        => $purpose,
        'created_by'     => $user['id'],
        'source'         => 'host',
        'status'         => 'approved',
        'scheduled_start'=> $scheduledStart,
        'scheduled_end'  => $scheduledEnd ?: null,
    ]);

    $visit = Visit::find((int)$visitId, $orgId);
    $qrPath = QrCodeGenerator::generate($visit['qr_code'], 'visit_' . $visit['uuid']);
    Visit::setQrImage((int)$visitId, $qrPath);
    $visit = Visit::find((int)$visitId, $orgId);

    // Email the visitor their gate pass directly (visitor may not have an account)
    if ($visitorEmail) {
        NotificationService::emailGatePass(
            $visitorEmail,
            $orgId,
            'Your Gate Pass for ' . ($visit['building_name'] ?? 'your visit'),
            $visit,
            'Your Gate Pass',
            "You have been invited by {$user['full_name']}. Present this QR code at the gate for entry.",
            [
                "Visitor: {$visitorName}",
                "Host: {$user['full_name']}",
                'Location: ' . trim(($visit['building_name'] ?? '') . ($visit['room_name'] ? ' - ' . $visit['room_name'] : '')),
                'Scheduled: ' . formatDate($scheduledStart),
            ]
        );
    }

    logAudit($orgId, $user['id'], 'visitor_invited', "{$user['full_name']} invited {$visitorName}");

    flash('success', "{$visitorName} has been invited and their gate pass has been generated" . ($visitorEmail ? ' and emailed.' : '.'));
    redirect('/host/invite');
}

view('host/invite', ['pageTitle' => 'Invite a Visitor']);
