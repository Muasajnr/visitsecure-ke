<?php
/** POST /orgadmin/users/{id}/update */

$orgId = Auth::orgId();
$userId = (int) $id;

if (!verifyCsrf()) {
    flash('error', 'Session expired, please try again.');
    redirect('/orgadmin/users');
}

$staff = User::find($userId);
if (!$staff || (int)$staff['org_id'] !== $orgId || $staff['role'] === 'org_admin') {
    flash('error', 'User not found or cannot be edited.');
    redirect('/orgadmin/users');
}

$fullName = input('full_name');
$email = input('email');
$phone = input('phone');
$role = input('role');
$roomId = input('room_id');
$password = input('password');

$allowedRoles = ['gateman', 'host', 'event_manager'];
$errors = [];

if (!$fullName || !$email || !$role) {
    $errors[] = 'Name, email, and role are required.';
}
if (!in_array($role, $allowedRoles, true)) {
    $errors[] = 'Invalid role selected.';
}
if ($password && strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters.';
}
if ($email && User::emailExistsForOther($email, $orgId, $userId)) {
    $errors[] = 'That email is already used by another account.';
}
if ($role === 'host' && !$roomId) {
    $errors[] = 'Please assign the host to a room.';
}

if ($errors) {
    flash('error', implode(' ', $errors));
    redirect('/orgadmin/users');
}

User::updateStaff($userId, $orgId, [
    'full_name' => $fullName,
    'email'     => $email,
    'phone'     => $phone,
    'role'      => $role,
    'room_id'   => $role === 'host' ? $roomId : null,
    'password'  => $password ?: null,
]);

logAudit($orgId, Auth::id(), 'user_updated', "Updated staff account: {$fullName}");
flash('success', "Account for {$fullName} updated.");
redirect('/orgadmin/users');
