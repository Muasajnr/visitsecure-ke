<?php
/**
 * app/controllers/visitor/hosts_by_org.php
 * Route: GET /visitor/hosts-by-org/{orgId}
 * Returns JSON list of hosts for the selected organisation, with their location path.
 */

header('Content-Type: application/json');

$orgId = (int) $orgId;
$hosts = User::hostsByOrg($orgId);

$result = array_map(function ($h) {
    return [
        'id'    => $h['id'],
        'label' => $h['full_name'] . ' — ' . ($h['building_name'] ?? '') . ' · ' . ($h['floor_name'] ?? '') . ' · ' . ($h['room_name'] ?? ''),
    ];
}, $hosts);

echo json_encode($result);
exit;
