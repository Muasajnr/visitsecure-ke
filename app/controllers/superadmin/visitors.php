<?php
/** GET /superadmin/visitors */

$filters = [
    'organisation_id' => (int) input('organisation_id', 0),
    'status'          => input('status', ''),
    'search'          => trim(input('search', '')),
];

$where = ['1 = 1'];
$params = [];

if ($filters['organisation_id'] > 0) {
    $where[] = 'v.org_id = ?';
    $params[] = $filters['organisation_id'];
}
if (in_array($filters['status'], ['pending', 'approved', 'rejected', 'checked_in', 'checked_out', 'expired', 'cancelled'], true)) {
    $where[] = 'v.status = ?';
    $params[] = $filters['status'];
}
if ($filters['search'] !== '') {
    $where[] = '(v.visitor_name LIKE ? OR v.visitor_phone LIKE ? OR v.visitor_email LIKE ? OR v.visitor_company LIKE ?)';
    $search = '%' . $filters['search'] . '%';
    array_push($params, $search, $search, $search, $search);
}

$visitors = DB::all(
    "SELECT v.*, o.name AS organisation_name, h.full_name AS host_name,
            b.name AS building_name, r.name AS room_name
     FROM visits v
     JOIN organizations o ON v.org_id = o.id
     LEFT JOIN users h ON v.host_id = h.id
     LEFT JOIN buildings b ON v.building_id = b.id
     LEFT JOIN rooms r ON v.room_id = r.id
     WHERE " . implode(' AND ', $where) . "
     ORDER BY v.created_at DESC LIMIT 500",
    $params
);

$organisations = DB::all("SELECT id, name FROM organizations ORDER BY name");

view('superadmin/visitors', [
    'pageTitle'     => 'Visitor Management',
    'visitors'      => $visitors,
    'organisations' => $organisations,
    'filters'       => $filters,
]);
