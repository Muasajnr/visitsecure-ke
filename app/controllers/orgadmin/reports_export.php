<?php
/** GET /orgadmin/reports/export */

$orgId = Auth::orgId();
$from = input('from', date('Y-m-01'));
$to = input('to', date('Y-m-d'));

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $from)) $from = date('Y-m-01');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) $to = date('Y-m-d');
if ($from > $to) [$from, $to] = [$to, $from];

$rows = DB::all(
    "SELECT v.visitor_name, v.visitor_phone, v.visitor_email, v.visitor_company,
            v.visitor_id_number, v.source, v.is_walk_in, v.status, v.purpose,
            h.full_name AS host_name, o.name AS organisation_name,
            b.name AS building_name, r.name AS room_name,
            v.created_at, v.approved_at, v.checked_in_at, v.checked_out_at,
            TIMESTAMPDIFF(MINUTE, v.checked_in_at, v.checked_out_at) AS duration_minutes
     FROM visits v
     LEFT JOIN users h ON v.host_id = h.id
     LEFT JOIN organizations o ON v.org_id = o.id
     LEFT JOIN buildings b ON v.building_id = b.id
     LEFT JOIN rooms r ON v.room_id = r.id
     WHERE v.org_id = ? AND DATE(v.created_at) BETWEEN ? AND ?
     ORDER BY v.created_at DESC",
    [$orgId, $from, $to]
);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="visitsecure-visits-' . $from . '-to-' . $to . '.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, [
    'Visitor Name', 'Phone', 'Email', 'Company', 'ID/Passport', 'Source', 'Walk-in',
    'Status', 'Purpose', 'Host', 'Organisation', 'Building', 'Room', 'Created At',
    'Approved At', 'Checked In At', 'Checked Out At', 'Duration (minutes)'
]);
foreach ($rows as $row) {
    fputcsv($output, [
        $row['visitor_name'], $row['visitor_phone'], $row['visitor_email'], $row['visitor_company'],
        $row['visitor_id_number'], $row['source'], $row['is_walk_in'] ? 'Yes' : 'No', $row['status'],
        $row['purpose'], $row['host_name'], $row['organisation_name'], $row['building_name'],
        $row['room_name'], $row['created_at'], $row['approved_at'], $row['checked_in_at'],
        $row['checked_out_at'], $row['duration_minutes']
    ]);
}
fclose($output);
exit;
