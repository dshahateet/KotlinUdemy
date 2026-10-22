<?php
/**
 * Main configuration.
 * Edit the database credentials below to match your hosting account.
 */

// ---- Database ----
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_NAME', 'gbsitsaue');
define('DB_USER', 'gbsitsaue_user');
define('DB_PASS', 'CHANGE_ME');

// ---- Site ----
define('SITE_URL', 'https://gbsitsaue.com');   // no trailing slash
define('SITE_DOMAIN', 'gbsitsaue.com');
define('BASE_PATH', '/');                      // '/' when installed at the domain root
define('INFO_EMAIL', 'info@gbsitsaue.com');    // contact form messages are sent here
define('MAIL_FROM', 'no-reply@gbsitsaue.com'); // sender address for outgoing mail
define('DEFAULT_LANG', 'en');                  // 'en' or 'ar'
define('ASSET_VERSION', '1.0.0');

// ---- Debug (keep false in production) ----
define('APP_DEBUG', false);
