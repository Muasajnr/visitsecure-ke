<?php
/**
 * app/controllers/gateman/scan.php
 */

$orgId = Auth::orgId();
$result = null;
$visit = null;

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Session expired, please try again.');
        redirect('/gateman/scan');
    }

    $token = trim(input('token', ''));
    $action = input('action', 'checkin'); // checkin or checkout

    if (!$token) {
        $result = ['type' => 'error', 'message' => 'No QR token provided.'];
    } else {
        $visit = Visit::findByQrToken($token);

        if (!$visit) {
            $result = ['type' => 'error', 'message' => 'Invalid gate pass. This QR code was not recognised.'];
        } elseif ((int)$visit['org_id'] !== $orgId) {
            $result = ['type' => 'error', 'message' => 'This gate pass does not belong to your organisation.'];
        } elseif ($action === 'checkin') {
            if (!in_array($visit['status'], ['approved', 'pending'], true)) {
                $result = ['type' => 'error', 'message' => 'This gate pass cannot be checked in (status: ' . $visit['status'] . ').'];
            } else {
                Visit::checkIn((int)$visit['id'], Auth::id());
                $visit = Visit::findByQrToken($token);
                logAudit($orgId, Auth::id(), 'visitor_checked_in', "Checked in {$visit['visitor_name']}");
                $result = ['type' => 'success', 'message' => 'Visitor checked in successfully.'];
            }
        } elseif ($action === 'checkout') {
            if ($visit['status'] !== 'checked_in') {
                $result = ['type' => 'error', 'message' => 'This visitor is not currently checked in.'];
            } else {
                Visit::checkOut((int)$visit['id'], Auth::id());
                $visit = Visit::findByQrToken($token);
                logAudit($orgId, Auth::id(), 'visitor_checked_out', "Checked out {$visit['visitor_name']}");
                $result = ['type' => 'success', 'message' => 'Visitor checked out successfully.'];
            }
        }
    }
}

view('gateman/scan', [
    'pageTitle' => 'Scan QR Gate Pass',
    'result'    => $result,
    'visit'     => $visit,
]);
