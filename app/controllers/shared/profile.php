<?php
/**
 * app/controllers/shared/profile.php
 */

$userId = Auth::id();
$user = User::find($userId);

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Session expired, please try again.');
        redirect('/profile');
    }

    $action = input('form_action');

    if ($action === 'update_profile') {
        $fullName = input('full_name');
        $phone = input('phone');

        if (!$fullName) {
            flash('error', 'Full name is required.');
            redirect('/profile');
        }

        User::updateProfile($userId, ['full_name' => $fullName, 'phone' => $phone]);

        // refresh session display name
        $_SESSION['full_name'] = $fullName;

        flash('success', 'Profile updated successfully.');
        redirect('/profile');
    }

    if ($action === 'change_password') {
        $currentPassword = input('current_password');
        $newPassword = input('new_password');
        $confirmPassword = input('confirm_password');

        if (!password_verify($currentPassword, $user['password_hash'])) {
            flash('error', 'Your current password is incorrect.');
            redirect('/profile');
        }
        if ($newPassword !== $confirmPassword) {
            flash('error', 'New passwords do not match.');
            redirect('/profile');
        }
        if (strlen($newPassword) < 6) {
            flash('error', 'New password must be at least 6 characters.');
            redirect('/profile');
        }

        User::updatePassword($userId, $newPassword);
        logAudit(Auth::orgId(), $userId, 'password_changed', 'User changed their own password');

        flash('success', 'Password updated successfully.');
        redirect('/profile');
    }
}

view('shared/profile', [
    'pageTitle' => 'My Profile',
    'user'      => $user,
]);
