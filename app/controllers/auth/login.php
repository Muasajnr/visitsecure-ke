<?php
/**
 * app/controllers/auth/login.php
 */

if (Auth::check()) {
    redirect(Auth::dashboardUrl());
}

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Your session expired. Please try again.');
        redirect('/login');
    }

    $email = input('email');
    $password = input('password');

    if (!$email || !$password) {
        flash('error', 'Please enter both email and password.');
        setOld(['email' => $email]);
        redirect('/login');
    }

    $user = User::findByEmailAnyOrg($email);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        flash('error', 'Incorrect email or password.');
        setOld(['email' => $email]);
        redirect('/login');
    }

    if (!$user['is_active']) {
        flash('error', 'Your account has been deactivated. Please contact your organisation administrator.');
        redirect('/login');
    }

    // If this user belongs to an org, make sure the org itself is active/subscribed
    if ($user['org_id']) {
        $org = Organization::find($user['org_id']);
        if (!$org || !$org['is_active'] || $org['subscription_status'] === 'suspended' || $org['subscription_status'] === 'expired') {
            flash('error', 'Your organisation\'s subscription is not active. Please contact your administrator.');
            redirect('/login');
        }
    }

    Auth::login($user);
    User::updateLastLogin($user['id']);
    logAudit($user['org_id'], $user['id'], 'login', 'User logged in');
    clearOld();

    redirect(Auth::dashboardUrl());
}

view('auth/login', ['pageTitle' => 'Sign in'], 'layouts/public');
