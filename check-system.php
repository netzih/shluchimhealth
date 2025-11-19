#!/usr/bin/env php
<?php
/**
 * Check PHP environment for Shluchim Health requirements
 */

echo "=== Shluchim Health - System Check ===\n\n";

// PHP Version
echo "PHP Version: " . PHP_VERSION . "\n";
$phpOk = version_compare(PHP_VERSION, '7.4.0', '>=');
echo $phpOk ? "✓ PHP 7.4+ requirement met\n" : "✗ PHP 7.4+ required\n";
echo "\n";

// PDO
echo "PDO Extension: ";
if (extension_loaded('pdo')) {
    echo "✓ Installed\n";
} else {
    echo "✗ NOT installed\n";
}

// PDO SQLite
echo "PDO SQLite Driver: ";
if (extension_loaded('pdo_sqlite')) {
    echo "✓ Installed\n";
    $sqliteOk = true;
} else {
    echo "✗ NOT installed\n";
    $sqliteOk = false;
}

// SQLite3
echo "SQLite3 Extension: ";
if (extension_loaded('sqlite3')) {
    echo "✓ Installed\n";
    if (class_exists('SQLite3')) {
        $version = SQLite3::version();
        echo "  SQLite Version: " . $version['versionString'] . "\n";
    }
} else {
    echo "✗ NOT installed\n";
}

echo "\n";

// Available PDO Drivers
echo "Available PDO Drivers:\n";
if (extension_loaded('pdo')) {
    $drivers = PDO::getAvailableDrivers();
    if (empty($drivers)) {
        echo "  None found!\n";
    } else {
        foreach ($drivers as $driver) {
            echo "  - " . $driver . "\n";
        }
    }
} else {
    echo "  PDO not available\n";
}

echo "\n";

// Loaded Extensions
echo "All Loaded Extensions:\n";
$extensions = get_loaded_extensions();
sort($extensions);
foreach ($extensions as $ext) {
    if (stripos($ext, 'sqlite') !== false || stripos($ext, 'pdo') !== false) {
        echo "  - " . $ext . " ← Related\n";
    }
}

echo "\n";
echo "=== Recommendations ===\n\n";

if (!$sqliteOk) {
    echo "⚠️  SQLite PDO driver is NOT installed!\n\n";

    echo "To fix this, you need to enable the PDO SQLite extension:\n\n";

    echo "For Plesk/cPanel:\n";
    echo "1. Log into Plesk/cPanel\n";
    echo "2. Go to 'PHP Settings' or 'Select PHP Version'\n";
    echo "3. Enable these extensions:\n";
    echo "   - pdo\n";
    echo "   - pdo_sqlite\n";
    echo "   - sqlite3\n";
    echo "4. Click 'Apply' or 'Save'\n\n";

    echo "For Ubuntu/Debian:\n";
    echo "  sudo apt-get install php-sqlite3\n";
    echo "  sudo systemctl restart apache2\n\n";

    echo "For CentOS/RHEL:\n";
    echo "  sudo yum install php-pdo php-sqlite\n";
    echo "  sudo systemctl restart httpd\n\n";

    echo "For php.ini:\n";
    echo "  Add or uncomment these lines:\n";
    echo "  extension=pdo.so\n";
    echo "  extension=pdo_sqlite.so\n";
    echo "  extension=sqlite3.so\n\n";

    echo "After enabling, run this script again to verify.\n";
} else {
    echo "✓ All requirements met!\n";
    echo "You can now run: php setup.php\n";
}

echo "\n";
echo "=== Alternative: Use MySQL Instead ===\n\n";
echo "If you cannot enable SQLite, I can modify the code to use MySQL instead.\n";
echo "Most shared hosting includes MySQL by default.\n\n";

// Check for MySQL
if (extension_loaded('pdo_mysql')) {
    echo "✓ MySQL PDO driver is available on your system!\n";
    echo "  Let me know if you want to switch to MySQL.\n";
} else {
    echo "✗ MySQL PDO driver is also not available.\n";
}

echo "\n";
