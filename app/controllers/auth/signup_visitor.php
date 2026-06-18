<?php
/**
 * app/controllers/auth/signup_visitor.php
 * Visitors are platform-wide accounts (org_id = NULL) since one visitor
 * may visit many different organisations over time.
 */

if (Auth::check()) {
    redirect(Auth::dashboardUrl());
}

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Your session expired. Please try again.');
        redirect('/signup/visitor');
    }

    $fullName = input('full_name');
    $email    = input('email');
    $phone    = input('phone');
    $idNumber = input('id_number');
    $password = input('password');
    $passwordConfirm = input('password_confirm');

    $errors = [];
    if (!$fullName || !$email || !$phone || !$password) {
        $errors[] = 'Please fill in all required fields.';
    }
    if ($password !== $passwordConfirm) {
        $errors[] = 'Passwords do not match.';
    }
    if ($password && strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
    if ($email && User::findByEmailAnyOrg($email)) {
        $errors[] = 'That email is already registered. Please sign in instead.';
    }

    if ($errors) {
        flash('error', implode(' ', $errors));
        setOld(compact('fullName', 'email', 'phone', 'idNumber'));
        redirect('/signup/visitor');
    }

    $userId = User::create([
        'org_id'      => null,
        'role'        => 'visitor',
        'full_name'   => $fullName,
        'email'       => $email,
        'phone'       => $phone,
        'id_number'   => $idNumber,
        'password'    => $password,
        'auto_verify' => true,
    ]);

    $user = User::find($userId);
    Auth::login($user);
    logAudit(null, $userId, 'visitor_signup', "Visitor '{$fullName}' registered");

    flash('success', 'Welcome to VisitSecure KE! You can now book visits and receive your QR gate pass.');
    redirect('/visitor/dashboard');
}

view('auth/signup_visitor', ['pageTitle' => 'Sign up as a visitor'], 'layouts/public');
