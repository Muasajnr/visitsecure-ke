<?php
/**
 * app/bootstrap.php
 * Loads everything the app needs before routing happens.
 */

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';   // creates $pdo
require_once __DIR__ . '/config/mail.php';

require_once __DIR__ . '/core/DB.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/QrCode.php';
require_once __DIR__ . '/core/Mailer.php';
require_once __DIR__ . '/core/NotificationService.php';

require_once __DIR__ . '/helpers/functions.php';

// Autoload models
foreach (glob(__DIR__ . '/models/*.php') as $modelFile) {
    require_once $modelFile;
}

Auth::start();
