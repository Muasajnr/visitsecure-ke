<?php
/**
 * app/controllers/superadmin/users.php
 */

$users = DB::all(
    "SELECT u.*, o.name AS org_name FROM users u
     LEFT JOIN organizations o ON u.org_id = o.id
     ORDER BY u.created_at DESC LIMIT 300"
);

view('superadmin/users', [
    'pageTitle' => 'All Users',
    'users'     => $users,
]);
