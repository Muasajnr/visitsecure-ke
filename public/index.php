<?php
/**
 * public/index.php
 * Single entry point. All requests are rewritten here by .htaccess.
 */

require_once __DIR__ . '/../app/bootstrap.php';

$router = new Router();
require __DIR__ . '/../routes/web.php';

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
