<?php
/**
 * routes/web.php
 * All clean-URL routes mapped to controller files.
 * Controller paths are relative to app/controllers/
 */

/** @var Router $router */

// ===================== PUBLIC =====================
$router->get('/',              'landing/index.php');
$router->get('/pricing',       'landing/pricing.php');
$router->get('/about',         'landing/about.php');
$router->get('/contact',       'landing/contact.php');

// ===================== AUTH =====================
$router->get('/login',         'auth/login.php');
$router->post('/login',        'auth/login.php');
$router->get('/logout',        'auth/logout.php');

$router->get('/signup',                'auth/signup.php');
$router->post('/signup',               'auth/signup.php');
$router->get('/signup/organization',   'auth/signup_org.php');
$router->post('/signup/organization',  'auth/signup_org.php');
$router->get('/signup/visitor',        'auth/signup_visitor.php');
$router->post('/signup/visitor',       'auth/signup_visitor.php');

$router->get('/forgot-password',  'auth/forgot_password.php');
$router->post('/forgot-password', 'auth/forgot_password.php');
$router->get('/reset-password',   'auth/reset_password.php');
$router->post('/reset-password',  'auth/reset_password.php');

// ===================== SUPER ADMIN =====================
$router->get('/superadmin/dashboard',          'superadmin/dashboard.php',        ['auth_superadmin']);
$router->get('/superadmin/organizations',      'superadmin/organizations.php',    ['auth_superadmin']);
$router->post('/superadmin/organizations',     'superadmin/organizations.php',    ['auth_superadmin']);
$router->get('/superadmin/organizations/{id}', 'superadmin/organization_view.php',['auth_superadmin']);
$router->post('/superadmin/organizations/{id}/status', 'superadmin/organization_status.php', ['auth_superadmin']);
$router->get('/superadmin/users',              'superadmin/users.php',            ['auth_superadmin']);
$router->get('/superadmin/reports',            'superadmin/reports.php',          ['auth_superadmin']);

// ===================== ORG ADMIN =====================
$router->get('/orgadmin/dashboard',         'orgadmin/dashboard.php',      ['auth_orgadmin']);

$router->get('/orgadmin/buildings',         'orgadmin/buildings.php',      ['auth_orgadmin']);
$router->post('/orgadmin/buildings',        'orgadmin/buildings.php',      ['auth_orgadmin']);
$router->get('/orgadmin/buildings/{id}/floors', 'orgadmin/floors.php',     ['auth_orgadmin']);
$router->post('/orgadmin/buildings/{id}/floors','orgadmin/floors.php',     ['auth_orgadmin']);

$router->get('/orgadmin/floors/{id}/rooms', 'orgadmin/rooms.php',          ['auth_orgadmin']);
$router->post('/orgadmin/floors/{id}/rooms','orgadmin/rooms.php',          ['auth_orgadmin']);

$router->get('/orgadmin/users',             'orgadmin/users.php',          ['auth_orgadmin']);
$router->post('/orgadmin/users',            'orgadmin/users.php',          ['auth_orgadmin']);
$router->post('/orgadmin/users/{id}/toggle','orgadmin/user_toggle.php',    ['auth_orgadmin']);

$router->get('/orgadmin/visits',            'orgadmin/visits.php',         ['auth_orgadmin']);
$router->get('/orgadmin/events',            'orgadmin/events.php',         ['auth_orgadmin']);
$router->get('/orgadmin/reports',           'orgadmin/reports.php',        ['auth_orgadmin']);
$router->get('/orgadmin/settings',          'orgadmin/settings.php',       ['auth_orgadmin']);
$router->post('/orgadmin/settings',         'orgadmin/settings.php',       ['auth_orgadmin']);

// ===================== GATEMAN =====================
$router->get('/gateman/dashboard',     'gateman/dashboard.php',   ['auth_gateman']);
$router->get('/gateman/scan',          'gateman/scan.php',        ['auth_gateman']);
$router->post('/gateman/scan',         'gateman/scan.php',        ['auth_gateman']);
$router->get('/gateman/walkin',        'gateman/walkin.php',      ['auth_gateman']);
$router->post('/gateman/walkin',       'gateman/walkin.php',      ['auth_gateman']);
$router->get('/gateman/log',           'gateman/log.php',         ['auth_gateman']);
$router->post('/gateman/checkout/{id}','gateman/checkout.php',    ['auth_gateman']);

// ===================== HOST =====================
$router->get('/host/dashboard',        'host/dashboard.php',      ['auth_host']);
$router->get('/host/invite',           'host/invite.php',         ['auth_host']);
$router->post('/host/invite',          'host/invite.php',         ['auth_host']);
$router->get('/host/visits',           'host/visits.php',         ['auth_host']);
$router->post('/host/visits/{id}/approve', 'host/approve.php',    ['auth_host']);
$router->post('/host/visits/{id}/reject',  'host/reject.php',     ['auth_host']);

// ===================== EVENT MANAGER =====================
$router->get('/events/dashboard',      'eventmanager/dashboard.php',  ['auth_eventmanager']);
$router->get('/events/create',         'eventmanager/create.php',     ['auth_eventmanager']);
$router->post('/events/create',        'eventmanager/create.php',     ['auth_eventmanager']);
$router->get('/events/{id}',           'eventmanager/view.php',       ['auth_eventmanager']);
$router->get('/events/{id}/register',  'eventmanager/register_visitor.php', ['auth_eventmanager']);
$router->post('/events/{id}/register', 'eventmanager/register_visitor.php', ['auth_eventmanager']);

// ===================== VISITOR =====================
$router->get('/visitor/dashboard',     'visitor/dashboard.php',   ['auth_visitor']);
$router->get('/visitor/book',          'visitor/book.php',        ['auth_visitor']);
$router->post('/visitor/book',         'visitor/book.php',        ['auth_visitor']);
$router->get('/visitor/hosts-by-org/{orgId}', 'visitor/hosts_by_org.php', ['auth_visitor']);
$router->get('/visitor/visits',        'visitor/visits.php',      ['auth_visitor']);
$router->get('/visitor/visits/{id}/pass', 'visitor/pass.php',     ['auth_visitor']);

// ===================== SHARED (any logged in user) =====================
$router->get('/notifications',         'shared/notifications.php',    ['auth_any']);
$router->post('/notifications/{id}/read', 'shared/notification_read.php', ['auth_any']);
$router->get('/profile',               'shared/profile.php',          ['auth_any']);
$router->post('/profile',              'shared/profile.php',          ['auth_any']);

// public verification page for a QR (no login required, gateman can also open via scan)
$router->get('/verify/{token}',        'shared/verify.php');
