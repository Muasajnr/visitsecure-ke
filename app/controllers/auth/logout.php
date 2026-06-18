<?php
/**
 * app/controllers/auth/logout.php
 */
logAudit(Auth::orgId(), Auth::id(), 'logout', 'User logged out');
Auth::logout();
redirect('/login');
