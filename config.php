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
    // Web request - calculate base URL properly
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ||
                 (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'))
                 ? 'https' : 'http';

    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    define('SITE_URL', $protocol . '://' . $host);

    // Calculate site path relative to document root
    // For most installations at domain root, SITE_PATH should be empty
    $sitePath = '';

    // Try to calculate from DOCUMENT_ROOT if available
    if (!empty($_SERVER['DOCUMENT_ROOT'])) {
        $documentRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
        $configDir = str_replace('\\', '/', __DIR__);

        // If config is in a subdirectory of document root
        if (strpos($configDir, $documentRoot) === 0) {
            $sitePath = substr($configDir, strlen($documentRoot));
        }
    }

    // Clean up the path
    $sitePath = '/' . trim($sitePath, '/');
    if ($sitePath === '/') {
        $sitePath = '';
    }

    define('SITE_PATH', $sitePath);
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
