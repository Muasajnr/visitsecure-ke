<?php
/**
 * app/controllers/eventmanager/register_visitor.php
 * Route: /events/{id}/register
 */

$user = Auth::user();
$orgId = $user['org_id'];
$eventId = (int) $id;

$event = Event::find($eventId, $orgId);
if (!$event) {
    flash('error', 'Event not found.');
    redirect('/events/dashboard');
}

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Session expired, please try again.');
        redirect('/events/' . $eventId . '/register');
    }

    $visitorName  = input('visitor_name');
    $visitorPhone = input('visitor_phone');
    $visitorEmail = input('visitor_email');
    $company      = input('visitor_company');

    if (!$visitorName || !$visitorPhone) {
        flash('error', 'Visitor name and phone are required.');
        redirect('/events/' . $eventId . '/register');
    }

    if ($event['max_visitors'] && Event::visitorCount($eventId) >= (int)$event['max_visitors']) {
        flash('error', 'This event has reached its maximum number of registered visitors.');
        redirect('/events/' . $eventId);
    }

    $visitId = Visit::create($orgId, [
        'building_id'     => $event['building_id'],
        'floor_id'        => $event['floor_id'],
        'room_id'         => $event['room_id'],
        'event_id'        => $eventId,
        'visitor_name'    => $visitorName,
        'visitor_phone'   => $visitorPhone,
        'visitor_email'   => $visitorEmail,
        'visitor_company' => $company,
        'host_id'         => null,
        'purpose'         => 'Event: ' . $event['title'],
        'created_by'      => $user['id'],
        'source'          => 'event',
        'status'          => 'approved',
    ]);

    $visit = Visit::find((int)$visitId, $orgId);
    $qrPath = QrCode::generate($visit['qr_code'], 'visit_' . $visit['uuid']);
    Visit::setQrImage((int)$visitId, $qrPath);
    $visit = Visit::find((int)$visitId, $orgId);

    if ($visitorEmail) {
        $qrUrl = QrCode::publicUrl($qrPath);
        $html = '<div style="font-family:Arial,sans-serif;max-width:560px;margin:0 auto;padding:24px;border:1px solid #e5e7eb;border-radius:8px;">'
            . "<h2 style=\"color:#0f172a;\">You're registered for {$event['title']}</h2>"
            . "<p style=\"color:#334155;font-size:15px;line-height:1.6;\">Present this QR code at the gate for entry to the event.</p>"
            . "<div style=\"text-align:center;margin:24px 0;\"><img src=\"{$qrUrl}\" style=\"width:200px;height:200px;\"></div>"
            . "<p style=\"color:#334155;font-size:14px;\"><strong>Location:</strong> " . ($visit['building_name'] ?? '') . ' - ' . ($visit['room_name'] ?? '') . "<br><strong>When:</strong> " . formatDate($event['start_datetime']) . "</p>"
            . '<p style="color:#94a3b8;font-size:12px;margin-top:20px;">This is an automated message from VisitSecure KE.</p></div>';
        $overrides = DB::one("SELECT * FROM org_settings WHERE org_id = ?", [$orgId]) ?: [];
        Mailer::send($visitorEmail, "Your gate pass for {$event['title']}", $html, $overrides);
    }

    logAudit($orgId, $user['id'], 'event_visitor_registered', "{$visitorName} registered for event '{$event['title']}'");
    flash('success', "{$visitorName} has been registered for the event and their gate pass sent.");
    redirect('/events/' . $eventId);
}

view('eventmanager/register_visitor', [
    'pageTitle' => 'Register Visitor for ' . $event['title'],
    'event'     => $event,
]);
