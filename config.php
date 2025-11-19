<?php
// Configuration file for Shluchim Health

// Database configuration
define('DB_FILE', __DIR__ . '/database.db');

// Site configuration
if (php_sapi_name() === 'cli') {
    // Command line - use defaults for scripts
    define('SITE_URL', 'https://www.shluchimhealth.com');
    define('SITE_PATH', '');
    define('BASE_URL', SITE_URL);
} else {
    // Web request - use actual hostname (supports both www and non-www)
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'https';
    $host = $_SERVER['HTTP_HOST'] ?? 'www.shluchimhealth.com';

    define('SITE_URL', $protocol . '://' . $host);
    define('SITE_PATH', '');
    define('BASE_URL', SITE_URL);
}

// Admin configuration
define('ADMIN_PATH', '/admin');

// Upload directories
define('UPLOAD_DIR', __DIR__ . '/uploads');
define('UPLOAD_URL', BASE_URL . '/uploads');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('America/New_York');

// Web-only configuration
if (php_sapi_name() !== 'cli') {
    // Session configuration
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));

    // Start session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Security headers
    header("X-Frame-Options: SAMEORIGIN");
    header("X-Content-Type-Options: nosniff");
    header("X-XSS-Protection: 1; mode=block");
}
