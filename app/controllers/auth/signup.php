<?php
/**
 * app/controllers/auth/signup.php
 */
if (Auth::check()) {
    redirect(Auth::dashboardUrl());
}
view('auth/signup', ['pageTitle' => 'Sign up'], 'layouts/public');
