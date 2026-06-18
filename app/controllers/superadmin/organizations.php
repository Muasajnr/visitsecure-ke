<?php
/**
 * app/controllers/superadmin/organizations.php
 */

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Session expired, please try again.');
        redirect('/superadmin/organizations');
    }

    $name = input('name');
    $email = input('email');
    $phone = input('phone');
    $adminName = input('admin_name');
    $adminEmail = input('admin_email');
    $adminPassword = input('admin_password');

    if (!$name || !$email || !$adminName || !$adminEmail || !$adminPassword) {
        flash('error', 'Please fill in all required fields.');
        redirect('/superadmin/organizations');
    }

    if (User::findByEmailAnyOrg($adminEmail)) {
        flash('error', 'That admin email is already in use.');
        redirect('/superadmin/organizations');
    }

    $baseSlug = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($name)), '-');
    $slug = $baseSlug;
    $i = 1;
    while (Organization::slugExists($slug)) {
        $slug = $baseSlug . '-' . (++$i);
    }

    try {
        DB::beginTransaction();
        $orgId = Organization::create([
            'name' => $name, 'slug' => $slug, 'email' => $email, 'phone' => $phone,
            'subscription_status' => 'trial',
        ]);
        User::create([
            'org_id' => $orgId, 'role' => 'org_admin', 'full_name' => $adminName,
            'email' => $adminEmail, 'password' => $adminPassword, 'auto_verify' => true,
        ]);
        DB::commit();
    } catch (Throwable $e) {
        DB::rollBack();
        flash('error', 'Could not create organisation. Please try again.');
        redirect('/superadmin/organizations');
    }

    logAudit(null, Auth::id(), 'org_created_by_superadmin', "Organisation '{$name}' onboarded");
    flash('success', "Organisation '{$name}' has been created.");
    redirect('/superadmin/organizations');
}

$organizations = Organization::all();

view('superadmin/organizations', [
    'pageTitle' => 'Organisations',
    'organizations' => $organizations,
]);
