<?php
/**
 * app/controllers/gateman/walkin.php
 *
 * A visitor with no gate pass arrives. The gateman registers their details
 * and picks the host/location. The visit is created as 'pending' and the
 * host is notified to approve before a gate pass is generated/checked in.
 */

$orgId = Auth::orgId();

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Session expired, please try again.');
        redirect('/gateman/walkin');
    }

    $visitorName  = input('visitor_name');
    $visitorPhone = input('visitor_phone');
    $visitorEmail = input('visitor_email');
    $visitorId    = input('visitor_id_number');
    $company      = input('visitor_company');
    $hostId       = input('host_id');
    $purpose      = input('purpose');

    $errors = [];
    if (!$visitorName || !$visitorPhone || !$hostId) {
        $errors[] = 'Visitor name, phone, and host are required.';
    }

    $host = $hostId ? User::find((int)$hostId) : false;
    if (!$host || (int)$host['org_id'] !== $orgId || $host['role'] !== 'host') {
        $errors[] = 'Please select a valid host.';
    }

    if ($errors) {
        flash('error', implode(' ', $errors));
        redirect('/gateman/walkin');
    }

    $room = Room::find((int)$host['room_id'], $orgId);
    $floor = $room ? Floor::find((int)$room['floor_id'], $orgId) : null;
    $buildingId = $floor['building_id'] ?? null;

    $visitId = Visit::create($orgId, [
        'building_id'        => $buildingId,
        'floor_id'           => $floor['id'] ?? null,
        'room_id'            => $room['id'] ?? null,
        'visitor_name'       => $visitorName,
        'visitor_phone'      => $visitorPhone,
        'visitor_email'      => $visitorEmail,
        'visitor_id_number'  => $visitorId,
        'visitor_company'    => $company,
        'host_id'            => $hostId,
        'purpose'            => $purpose,
        'created_by'         => Auth::id(),
        'source'             => 'gateman',
        'status'             => 'pending',
        'is_walk_in'         => 1,
    ]);

    $visit = Visit::find((int)$visitId, $orgId);

    // Generate the QR pass image now (will only grant entry once host approves)
    $qrPath = QrCode::generate($visit['qr_code'], 'visit_' . $visit['uuid']);
    Visit::setQrImage((int)$visitId, $qrPath);

    // Notify the host that a walk-in is waiting for their approval
    NotificationService::notify(
        (int)$host['id'],
        $orgId,
        'walkin_pending_approval',
        'A visitor is waiting for your approval',
        "{$visitorName} has arrived at the gate to see you" . ($purpose ? " regarding: {$purpose}" : '') . '. Please approve or reject this visit from your dashboard.',
        (int)$visitId
    );

    logAudit($orgId, Auth::id(), 'walkin_registered', "Walk-in visitor {$visitorName} registered, pending approval from {$host['full_name']}");

    flash('success', "{$visitorName} has been registered and {$host['full_name']} has been notified for approval.");
    redirect('/gateman/walkin');
}

$hosts = User::hostsByOrg($orgId);

view('gateman/walkin', [
    'pageTitle' => 'Register Walk-in Visitor',
    'hosts'     => $hosts,
]);
