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
    $qrPath = QrCodeGenerator::generate($visit['qr_code'], 'visit_' . $visit['uuid']);
    Visit::setQrImage((int)$visitId, $qrPath);
    $visit = Visit::find((int)$visitId, $orgId);

    if ($visitorEmail) {
        NotificationService::emailGatePass(
            $visitorEmail,
            $orgId,
            "Your gate pass for {$event['title']}",
            $visit,
            "You're registered for {$event['title']}",
            'Present this QR code at the gate for entry to the event.',
            [
                'Location: ' . trim(($visit['building_name'] ?? '') . ($visit['room_name'] ? ' - ' . $visit['room_name'] : '')),
                'When: ' . formatDate($event['start_datetime']),
            ]
        );
    }

    logAudit($orgId, $user['id'], 'event_visitor_registered', "{$visitorName} registered for event '{$event['title']}'");
    flash('success', "{$visitorName} has been registered for the event and their gate pass sent.");
    redirect('/events/' . $eventId);
}

view('eventmanager/register_visitor', [
    'pageTitle' => 'Register Visitor for ' . $event['title'],
    'event'     => $event,
]);
