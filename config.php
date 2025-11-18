<?php
// Configuration file for Shluchim Health

// Database configuration
define('DB_FILE', __DIR__ . '/database.db');

// Site configuration
define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST']);
define('SITE_PATH', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', SITE_URL . SITE_PATH);

// Admin configuration
define('ADMIN_PATH', '/admin');

// Upload directories
define('UPLOAD_DIR', __DIR__ . '/uploads');
define('UPLOAD_URL', BASE_URL . '/uploads');

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('America/New_York');

// Security headers
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
