<?php
/**
 * Write configuration info to a text file
 * Access this via browser, then check config-output.txt
 */

require_once 'config.php';

$output = "=== Configuration Check ===\n";
$output .= date('Y-m-d H:i:s') . "\n\n";

$output .= "BASE_URL: " . BASE_URL . "\n";
$output .= "SITE_URL: " . SITE_URL . "\n";
$output .= "SITE_PATH: " . SITE_PATH . "\n\n";

$output .= "SERVER INFO:\n";
$output .= "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'NOT SET') . "\n";
$output .= "HTTPS: " . ($_SERVER['HTTPS'] ?? 'NOT SET') . "\n";
$output .= "HTTP_X_FORWARDED_PROTO: " . ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'NOT SET') . "\n";
$output .= "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'NOT SET') . "\n";
$output .= "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'NOT SET') . "\n";
$output .= "__DIR__: " . __DIR__ . "\n";
$output .= "php_sapi_name(): " . php_sapi_name() . "\n";

file_put_contents(__DIR__ . '/config-output.txt', $output);

echo "Configuration written to config-output.txt\n";
echo "BASE_URL is: " . BASE_URL . "\n";
?>
