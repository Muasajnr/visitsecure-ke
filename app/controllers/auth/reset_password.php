<?php
/**
 * app/controllers/auth/reset_password.php
 */

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Your session expired. Please try again.');
        redirect('/reset-password?token=' . urlencode(input('token')));
    }

    $token = input('token');
    $password = input('password');
    $passwordConfirm = input('password_confirm');

    $reset = $token ? PasswordReset::findValid($token) : false;

    if (!$reset) {
        flash('error', 'This reset link is invalid or has expired.');
        redirect('/forgot-password');
    }

    if ($password !== $passwordConfirm) {
        flash('error', 'Passwords do not match.');
        redirect('/reset-password?token=' . urlencode($token));
    }

    if (strlen($password) < 6) {
        flash('error', 'Password must be at least 6 characters.');
        redirect('/reset-password?token=' . urlencode($token));
    }

    User::updatePassword((int)$reset['user_id'], $password);
    PasswordReset::markUsed((int)$reset['id']);
    logAudit(null, (int)$reset['user_id'], 'password_reset', 'Password was reset successfully');

    flash('success', 'Your password has been updated. You can now sign in.');
    redirect('/login');
}

// GET: validate token before showing the form
$token = input('token', '');
$reset = $token ? PasswordReset::findValid($token) : false;

view('auth/reset_password', [
    'pageTitle'  => 'Reset password',
    'validToken' => (bool)$reset,
    'token'      => $token,
], 'layouts/public');
