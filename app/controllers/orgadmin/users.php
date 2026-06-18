<?php
/**
 * app/controllers/orgadmin/users.php
 */

$orgId = Auth::orgId();

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Session expired, please try again.');
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
    if (!$fullName || !$email || !$role || !$password) {
        $errors[] = 'Please fill in all required fields.';
    }
    if (!in_array($role, $allowedRoles, true)) {
        $errors[] = 'Invalid role selected.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
    if ($email && User::emailExists($email, $orgId)) {
        $errors[] = 'A user with that email already exists in your organisation.';
    }
    if ($role === 'host' && !$roomId) {
        $errors[] = 'Please assign the host to a room.';
    }

    if ($errors) {
        flash('error', implode(' ', $errors));
        redirect('/orgadmin/users');
    }

    User::create([
        'org_id'    => $orgId,
        'role'      => $role,
        'room_id'   => $role === 'host' ? $roomId : null,
        'full_name' => $fullName,
        'email'     => $email,
        'phone'     => $phone,
        'password'  => $password,
        'auto_verify' => true,
    ]);

    logAudit($orgId, Auth::id(), 'user_created', "Created {$role} account: {$fullName}");
    flash('success', ucfirst(str_replace('_', ' ', $role)) . " account for {$fullName} created successfully.");
    redirect('/orgadmin/users');
}

$staff = User::allByOrg($orgId);
$rooms = Room::allByOrg($orgId);

view('orgadmin/users', [
    'pageTitle' => 'Staff & Users',
    'staff'     => $staff,
    'rooms'     => $rooms,
]);
