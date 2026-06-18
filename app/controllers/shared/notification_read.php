<?php
/**
 * app/controllers/shared/notification_read.php
 * Route: POST /notifications/{id}/read
 */

$user = Auth::user();
$notificationId = (int) $id;

if (verifyCsrf()) {
    Notification::markRead($notificationId, $user['id']);
}

redirect('/notifications');
