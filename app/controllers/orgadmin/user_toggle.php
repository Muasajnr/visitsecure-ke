<?php
/**
 * app/controllers/orgadmin/user_toggle.php
 * Route: POST /orgadmin/users/{id}/toggle
 */

$orgId = Auth::orgId();
$userId = (int) $id;

if (!verifyCsrf()) {
    flash('error', 'Session expired, please try again.');
    redirect('/orgadmin/users');
}

$user = User::find($userId);
if (!$user || (int)$user['org_id'] !== $orgId) {
    flash('error', 'User not found.');
    redirect('/orgadmin/users');
}

$newStatus = $user['is_active'] ? 0 : 1;
User::toggleActive($userId, $newStatus);
logAudit($orgId, Auth::id(), 'user_toggled', "User {$user['full_name']} set to " . ($newStatus ? 'active' : 'inactive'));

flash('success', "{$user['full_name']}'s account has been " . ($newStatus ? 'activated' : 'deactivated') . '.');
redirect('/orgadmin/users');
