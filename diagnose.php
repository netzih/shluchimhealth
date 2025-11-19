<?php
/**
 * Quick diagnostic script
 */
require_once 'config.php';
require_once 'database.php';

echo "=== Shluchim Health Diagnostics ===\n\n";

// Check BASE_URL
echo "1. Configuration:\n";
echo "   BASE_URL: " . BASE_URL . "\n";
echo "   SITE_URL: " . SITE_URL . "\n";
echo "   SITE_PATH: " . SITE_PATH . "\n\n";

// Check database
try {
    $db = Database::getInstance();
    echo "2. Database: ✓ Connected\n\n";

    // Check pages
    echo "3. Pages in database:\n";
    $pages = $db->fetchAll('SELECT id, title, slug FROM pages ORDER BY title');
    if (empty($pages)) {
        echo "   ⚠️  NO PAGES FOUND!\n";
        echo "   You need to run: php init-pages.php\n\n";
    } else {
        foreach ($pages as $page) {
            echo "   - {$page['title']} (slug: {$page['slug']})\n";
        }
        echo "\n";
    }

    // Check products
    echo "4. Products in database:\n";
    $productCount = $db->count('products');
    echo "   Total: $productCount products\n";
    if ($productCount == 0) {
        echo "   ⚠️  NO PRODUCTS FOUND!\n";
        echo "   You need to run: php import-products.php\n";
    }
    echo "\n";

    // Check users
    echo "5. Admin users:\n";
    $users = $db->fetchAll('SELECT username, email FROM users');
    foreach ($users as $user) {
        echo "   - {$user['username']} ({$user['email']})\n";
    }
    echo "\n";

} catch (Exception $e) {
    echo "✗ Database Error: " . $e->getMessage() . "\n\n";
}

// Check file paths
echo "6. File checks:\n";
echo "   admin.css exists: " . (file_exists(__DIR__ . '/assets/css/admin.css') ? '✓' : '✗') . "\n";
echo "   style.css exists: " . (file_exists(__DIR__ . '/assets/css/style.css') ? '✓' : '✗') . "\n";
echo "   database.db exists: " . (file_exists(__DIR__ . '/database.db') ? '✓' : '✗') . "\n";
echo "\n";

echo "7. URLs to test:\n";
echo "   Homepage: " . BASE_URL . "/\n";
echo "   Admin: " . BASE_URL . "/admin/\n";
echo "   Products: " . BASE_URL . "/products/\n";
echo "   Admin CSS: " . BASE_URL . "/assets/css/admin.css\n";
echo "\n";
