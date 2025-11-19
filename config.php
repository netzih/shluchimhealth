<?php
// Configuration file for Shluchim Health

// Database configuration
define('DB_FILE', __DIR__ . '/database.db');

// Site configuration
// Handle both CLI and web contexts
if (php_sapi_name() === 'cli') {
    // Command line - use defaults
    define('SITE_URL', 'http://localhost');
    define('SITE_PATH', '');
    define('BASE_URL', SITE_URL);
} else {
    // Web request - calculate base URL from config.php location
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    define('SITE_URL', $protocol . '://' . $_SERVER['HTTP_HOST']);

    // Get the directory where config.php is located (application root)
    $configDir = dirname($_SERVER['SCRIPT_FILENAME']);
    $docRoot = $_SERVER['DOCUMENT_ROOT'];

    // Find config.php relative to document root
    $scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']);
    $documentRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);

    // Get the directory of the currently executing script
    $currentDir = dirname($scriptPath);

    // Find config.php by going up from current location
    $configPath = __DIR__;
    $relativePath = str_replace($documentRoot, '', $configPath);

    define('SITE_PATH', $relativePath);
    define('BASE_URL', SITE_URL . SITE_PATH);
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
