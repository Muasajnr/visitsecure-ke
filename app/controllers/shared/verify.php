<?php
/**
 * app/controllers/shared/verify.php
 * Route: GET /verify/{token}
 * Public page: shows gate pass status without requiring login.
 * Does not perform check-in/out here -- that only happens via the
 * authenticated gateman scan flow. This is a read-only verification view.
 */

$visit = Visit::findByQrToken($token);

view('shared/verify', [
    'pageTitle' => 'Verify Gate Pass',
    'visit'     => $visit,
], 'layouts/public');
