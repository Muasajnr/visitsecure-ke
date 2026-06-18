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
    $qrPath = QrCode::generate($visit['qr_code'], 'visit_' . $visit['uuid']);
    Visit::setQrImage((int)$visitId, $qrPath);
    $visit = Visit::find((int)$visitId, $orgId);

    // Email the visitor their gate pass directly (visitor may not have an account)
    if ($visitorEmail) {
        $qrUrl = QrCode::publicUrl($qrPath);
        $html = '<div style="font-family:Arial,sans-serif;max-width:560px;margin:0 auto;padding:24px;border:1px solid #e5e7eb;border-radius:8px;">'
            . '<h2 style="color:#0f172a;">Your Gate Pass</h2>'
            . "<p style=\"color:#334155;font-size:15px;line-height:1.6;\">You have been invited by {$user['full_name']}. Present this QR code at the gate for entry.</p>"
            . "<div style=\"text-align:center;margin:24px 0;\"><img src=\"{$qrUrl}\" style=\"width:200px;height:200px;\"></div>"
            . "<p style=\"color:#334155;font-size:14px;\"><strong>Visitor:</strong> {$visitorName}<br><strong>Host:</strong> {$user['full_name']}<br><strong>Location:</strong> " . ($visit['building_name'] ?? '') . ' - ' . ($visit['room_name'] ?? '') . "<br><strong>Scheduled:</strong> " . formatDate($scheduledStart) . "</p>"
            . '<p style="color:#94a3b8;font-size:12px;margin-top:20px;">This is an automated message from VisitSecure KE.</p></div>';

        $overrides = DB::one("SELECT * FROM org_settings WHERE org_id = ?", [$orgId]) ?: [];
        Mailer::send($visitorEmail, 'Your Gate Pass for ' . ($visit['building_name'] ?? 'your visit'), $html, $overrides);
    }

    logAudit($orgId, $user['id'], 'visitor_invited', "{$user['full_name']} invited {$visitorName}");

    flash('success', "{$visitorName} has been invited and their gate pass has been generated" . ($visitorEmail ? ' and emailed.' : '.'));
    redirect('/host/invite');
}

view('host/invite', ['pageTitle' => 'Invite a Visitor']);
