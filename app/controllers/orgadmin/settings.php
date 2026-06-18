<?php
/**
 * app/controllers/orgadmin/settings.php
 */

$orgId = Auth::orgId();

if (isPost()) {
    if (!verifyCsrf()) {
        flash('error', 'Session expired, please try again.');
        redirect('/orgadmin/settings');
    }

    $smtpHost = input('smtp_host');
    $smtpPort = input('smtp_port', 587);
    $smtpUsername = input('smtp_username');
    $smtpAppPassword = input('smtp_app_password');
    $smtpFromName = input('smtp_from_name');
    $smtpFromEmail = input('smtp_from_email');

    $existing = DB::one("SELECT id FROM org_settings WHERE org_id = ?", [$orgId]);

    if ($existing) {
        DB::run(
            "UPDATE org_settings SET smtp_host=?, smtp_port=?, smtp_username=?, smtp_app_password=?, smtp_from_name=?, smtp_from_email=? WHERE org_id=?",
            [$smtpHost, $smtpPort, $smtpUsername, $smtpAppPassword, $smtpFromName, $smtpFromEmail, $orgId]
        );
    } else {
        DB::insert(
            "INSERT INTO org_settings (org_id, smtp_host, smtp_port, smtp_username, smtp_app_password, smtp_from_name, smtp_from_email) VALUES (?,?,?,?,?,?,?)",
            [$orgId, $smtpHost, $smtpPort, $smtpUsername, $smtpAppPassword, $smtpFromName, $smtpFromEmail]
        );
    }

    logAudit($orgId, Auth::id(), 'settings_updated', 'Email/SMTP settings updated');
    flash('success', 'Email settings saved successfully.');
    redirect('/orgadmin/settings');
}

$settings = DB::one("SELECT * FROM org_settings WHERE org_id = ?", [$orgId]);

view('orgadmin/settings', [
    'pageTitle' => 'Settings',
    'settings'  => $settings ?: [],
]);
