<?php
/**
 * Setup script to initialize the database
 * Run this file once to create the database and tables
 */

require_once 'config.php';

echo "=== Shluchim Health Setup ===\n\n";

// Check if database already exists
if (file_exists(DB_FILE)) {
    echo "Warning: Database file already exists.\n";
    echo "Do you want to reset the database? This will delete all existing data! (yes/no): ";

    $handle = fopen("php://stdin", "r");
    $line = trim(fgets($handle));

    if (strtolower($line) !== 'yes') {
        echo "Setup cancelled.\n";
        exit;
    }

    unlink(DB_FILE);
    echo "Existing database deleted.\n\n";
}

// Create database connection
require_once 'database.php';
$db = Database::getInstance()->getConnection();

echo "Creating database tables...\n";

// Read and execute schema
$schema = file_get_contents('schema.sql');
try {
    $db->exec($schema);
    echo "✓ Database tables created successfully.\n\n";
} catch (PDOException $e) {
    die("Error creating tables: " . $e->getMessage() . "\n");
}

// Create uploads directory
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
    echo "✓ Uploads directory created.\n";
}

// Create subdirectories
$subdirs = ['products', 'posts', 'temp'];
foreach ($subdirs as $dir) {
    $path = UPLOAD_DIR . '/' . $dir;
    if (!file_exists($path)) {
        mkdir($path, 0755, true);
    }
}

echo "\n=== Setup Complete! ===\n\n";
echo "Default admin credentials:\n";
echo "Username: admin\n";
echo "Password: admin123\n\n";
echo "⚠️  IMPORTANT: Change the default password after first login!\n\n";
echo "Next steps:\n";
echo "1. Import products: php import-products.php\n";
echo "2. Access admin panel: " . BASE_URL . "/admin/\n";
echo "3. Visit your site: " . BASE_URL . "/\n\n";
