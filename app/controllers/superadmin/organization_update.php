<?php
/** POST /superadmin/organizations/{id}/update */

$orgId = (int) $id;

if (!verifyCsrf()) {
    flash('error', 'Session expired, please try again.');
    redirect('/superadmin/organizations/' . $orgId);
}

$org = Organization::find($orgId);
if (!$org) {
    flash('error', 'Organisation not found.');
    redirect('/superadmin/organizations');
}

$name = input('name');
$email = input('email');
if (!$name || !$email) {
    flash('error', 'Name and email are required.');
    redirect('/superadmin/organizations/' . $orgId);
}

$plan = input('subscription_plan', 'basic');
if (!in_array($plan, ['basic', 'standard', 'premium', 'enterprise'], true)) {
    $plan = 'basic';
}

Organization::update($orgId, [
    'name'               => $name,
    'email'              => $email,
    'phone'              => input('phone'),
    'address'            => input('address'),
    'subscription_plan'  => $plan,
]);

logAudit(null, Auth::id(), 'org_updated', "Organisation '{$name}' details updated");
flash('success', "Organisation '{$name}' updated.");
redirect('/superadmin/organizations/' . $orgId);
