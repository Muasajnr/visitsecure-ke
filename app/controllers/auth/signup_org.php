<?php
/**
 * app/controllers/auth/signup_org.php
 * Creates a new tenant organisation AND its first org_admin user.
 */

if (Auth::check()) {
    redirect(Auth::dashboardUrl());
}

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Your session expired. Please try again.');
        redirect('/signup/organization');
    }

    $orgName    = input('org_name');
    $orgEmail   = input('org_email');
    $orgPhone   = input('org_phone');
    $adminName  = input('admin_name');
    $adminEmail = input('admin_email');
    $password   = input('password');
    $passwordConfirm = input('password_confirm');

    $errors = [];
    if (!$orgName || !$orgEmail || !$adminName || !$adminEmail || !$password) {
        $errors[] = 'Please fill in all required fields.';
    }
    if ($password !== $passwordConfirm) {
        $errors[] = 'Passwords do not match.';
    }
    if ($password && strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
    if ($adminEmail && User::findByEmailAnyOrg($adminEmail)) {
        $errors[] = 'That admin email is already registered. Please sign in instead.';
    }

    if ($errors) {
        flash('error', implode(' ', $errors));
        setOld(compact('orgName', 'orgEmail', 'orgPhone', 'adminName', 'adminEmail'));
        redirect('/signup/organization');
    }

    // Generate a unique slug from the organisation name
    $baseSlug = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($orgName)), '-');
    $slug = $baseSlug;
    $i = 1;
    while (Organization::slugExists($slug)) {
        $slug = $baseSlug . '-' . (++$i);
    }

    try {
        DB::beginTransaction();

        $orgId = Organization::create([
            'name'  => $orgName,
            'slug'  => $slug,
            'email' => $orgEmail,
            'phone' => $orgPhone,
            'subscription_status' => 'trial',
        ]);

        $userId = User::create([
            'org_id'      => $orgId,
            'role'        => 'org_admin',
            'full_name'   => $adminName,
            'email'       => $adminEmail,
            'password'    => $password,
            'auto_verify' => true,
        ]);

        DB::commit();
    } catch (Throwable $e) {
        DB::rollBack();
        flash('error', 'Something went wrong while creating your organisation. Please try again.');
        redirect('/signup/organization');
    }

    $user = User::find($userId);
    Auth::login($user);
    logAudit($orgId, $userId, 'org_signup', "Organisation '{$orgName}' registered");

    flash('success', 'Welcome to VisitSecure KE! Your organisation is set up on a trial plan. Start by adding your first building.');
    redirect('/orgadmin/dashboard');
}

view('auth/signup_org', ['pageTitle' => 'Register your organisation'], 'layouts/public');
