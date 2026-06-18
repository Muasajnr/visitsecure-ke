<?php
/**
 * app/controllers/shared/notifications.php
 */

$user = Auth::user();
$notifications = Notification::forUser($user['id'], 50);

view('shared/notifications', [
    'pageTitle'     => 'Notifications',
    'notifications' => $notifications,
]);
