<?php
/**
 * app/controllers/visitor/book.php
 *
 * A self-registered visitor books their own visit. Since they're choosing
 * which organisation/building/host to visit, the booking starts as
 * 'pending' until the host approves it.
 */

$user = Auth::user();

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Session expired, please try again.');
        redirect('/visitor/book');
    }

    $orgId = input('org_id');
    $hostId = input('host_id');
    $purpose = input('purpose');
    $scheduledStart = input('scheduled_start');

    $org = $orgId ? Organization::find((int)$orgId) : false;
    $host = $hostId ? User::find((int)$hostId) : false;

    if (!$org || !$host || (int)$host['org_id'] !== (int)$orgId || $host['role'] !== 'host') {
        flash('error', 'Please select a valid organisation and host.');
        redirect('/visitor/book');
    }

    if (!$scheduledStart) {
        flash('error', 'Please choose when you plan to visit.');
        redirect('/visitor/book');
    }

    $room = !empty($host['room_id']) ? Room::find((int)$host['room_id'], (int)$orgId) : null;
    $floor = $room ? Floor::find((int)$room['floor_id'], (int)$orgId) : null;
    $buildingId = $floor['building_id'] ?? null;

    $visitId = Visit::create((int)$orgId, [
        'building_id'      => $buildingId,
        'floor_id'         => $floor['id'] ?? null,
        'room_id'          => $room['id'] ?? null,
        'visitor_user_id'  => $user['id'],
        'visitor_name'     => $user['full_name'],
        'visitor_phone'    => DB::one("SELECT phone FROM users WHERE id = ?", [$user['id']])['phone'] ?? '',
        'visitor_email'    => $user['email'],
        'host_id'          => $hostId,
        'purpose'          => $purpose,
        'created_by'       => $user['id'],
        'source'           => 'self',
        'status'           => 'pending',
        'scheduled_start'  => $scheduledStart,
    ]);

    $visit = Visit::find((int)$visitId, (int)$orgId);
    $qrPath = QrCodeGenerator::generate($visit['qr_code'], 'visit_' . $visit['uuid']);
    Visit::setQrImage((int)$visitId, $qrPath);

    NotificationService::notify(
        (int)$hostId,
        (int)$orgId,
        'new_booking_request',
        'New visit request awaiting your approval',
        "{$user['full_name']} has requested to visit you" . ($purpose ? " regarding: {$purpose}" : '') . '. Please approve or reject from your dashboard.',
        (int)$visitId
    );

    logAudit((int)$orgId, $user['id'], 'visit_booked', "{$user['full_name']} booked a visit with host #{$hostId}");

    flash('success', 'Your visit request has been sent. You\'ll be notified once the host approves it.');
    redirect('/visitor/dashboard');
}

$organizations = Organization::all();
// only show organizations that are active/trial, not suspended
$organizations = array_values(array_filter($organizations, fn($o) => $o['is_active'] && $o['subscription_status'] !== 'suspended' && $o['subscription_status'] !== 'expired'));

view('visitor/book', [
    'pageTitle'     => 'Book a Visit',
    'organizations' => $organizations,
]);
