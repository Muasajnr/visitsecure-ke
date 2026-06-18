<?php
/**
 * app/config/mail.php
 *
 * Default platform-wide SMTP settings (Gmail + App Password).
 * Individual organizations can override these via org_settings table
 * (set in Org Admin > Settings > Email).
 *
 * HOW TO GET A GMAIL APP PASSWORD:
 * 1. Enable 2-Step Verification on the Gmail account.
 * 2. Go to https://myaccount.google.com/apppasswords
 * 3. Generate a 16-character app password for "Mail".
 * 4. Paste it below as MAIL_PASSWORD (no spaces).
 */

define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_ENCRYPTION', 'tls'); // tls or ssl
define('MAIL_USERNAME', 'youraddress@gmail.com');   // <-- change this
define('MAIL_PASSWORD', 'your16charapppassword');    // <-- change this (Gmail App Password)
define('MAIL_FROM_EMAIL', 'youraddress@gmail.com');  // <-- change this
define('MAIL_FROM_NAME', 'VisitSecure KE');
