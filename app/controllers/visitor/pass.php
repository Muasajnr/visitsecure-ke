<?php
/**
 * app/controllers/visitor/pass.php
 * Route: /visitor/visits/{id}/pass
 */

$user = Auth::user();
$visitId = (int) $id;

$visit = DB::one(
    "SELECT v.*, b.name AS building_name, f.name AS floor_name, r.name AS room_name,
            h.full_name AS host_name, o.name AS org_name
     FROM visits v
     LEFT JOIN buildings b ON v.building_id = b.id
     LEFT JOIN floors f ON v.floor_id = f.id
     LEFT JOIN rooms r ON v.room_id = r.id
     LEFT JOIN users h ON v.host_id = h.id
     LEFT JOIN organizations o ON v.org_id = o.id
     WHERE v.id = ? AND v.visitor_user_id = ?", [$visitId, $user['id']]
);

if (!$visit) {
    flash('error', 'Gate pass not found.');
    redirect('/visitor/visits');
}

if (empty($visit['qr_image_path'])) {
    flash('error', 'This visit has not been approved yet, so no gate pass is available.');
    redirect('/visitor/visits');
}

view('visitor/pass', [
    'pageTitle' => 'Gate Pass',
    'visit'     => $visit,
]);
