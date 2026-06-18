<?php
/**
 * app/controllers/auth/forgot_password.php
 */

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Your session expired. Please try again.');
        redirect('/forgot-password');
    }

    $email = input('email');
    $user = $email ? User::findByEmailAnyOrg($email) : false;

    // Always show the same success message whether or not the email exists,
    // to avoid leaking which emails are registered.
    if ($user) {
        $token = PasswordReset::createToken($user['id']);
        $resetUrl = url('/reset-password') . '?token=' . $token;

        $html = '<div style="font-family:Arial,sans-serif;max-width:560px;margin:0 auto;padding:24px;border:1px solid #e5e7eb;border-radius:8px;">'
            . '<h2 style="color:#0f172a;">Reset your password</h2>'
            . '<p style="color:#334155;font-size:15px;line-height:1.6;">We received a request to reset your VisitSecure KE password. Click the button below to choose a new password. This link expires in 1 hour.</p>'
            . '<div style="text-align:center;margin:24px 0;"><a href="' . $resetUrl . '" style="background:#0F1B2D;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;">Reset Password</a></div>'
            . '<p style="color:#94a3b8;font-size:12px;">If you did not request this, you can safely ignore this email.</p></div>';

        $overrides = $user['org_id'] ? (DB::one("SELECT * FROM org_settings WHERE org_id = ?", [$user['org_id']]) ?: []) : [];
        Mailer::send($user['email'], 'Reset your VisitSecure KE password', $html, $overrides);

        logAudit($user['org_id'], $user['id'], 'password_reset_requested', 'Password reset link requested');
    }

    flash('success', 'If that email exists in our system, a password reset link has been sent.');
    redirect('/forgot-password');
}

view('auth/forgot_password', ['pageTitle' => 'Forgot password'], 'layouts/public');
