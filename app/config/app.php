<?php
/**
 * app/config/app.php
 * Global application configuration.
 * Edit BASE_URL to match your Laragon setup.
 */

// ---- Environment ----
define('APP_ENV', 'local'); // local | production
define('APP_DEBUG', true);  // set to false in production

// ---- URLs ----
// If your Laragon project is at C:/laragon/www/visitsecure-ke
// and Laragon auto-domain is on, BASE_URL might be http://visitsecure-ke.test
// Otherwise it's http://localhost/visitsecure-ke/public
define('BASE_URL', 'http://visitsecure-ke.test');

// ---- Paths ----
define('ROOT_PATH', dirname(__DIR__, 2));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('UPLOADS_PATH', PUBLIC_PATH . '/uploads');

// ---- App Identity ----
define('APP_NAME', 'VisitSecure KE');
define('APP_TAGLINE', 'Smart Visitor & Booking Management for Kenyan Organisations');

// ---- Session ----
define('SESSION_NAME', 'visitsecure_session');

// ---- QR / Gate Pass ----
define('QR_EXPIRY_HOURS', 24); // gate pass QR validity window after scheduled visit end

// ---- Timezone ----
date_default_timezone_set('Africa/Nairobi');

// ---- Error display ----
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}
