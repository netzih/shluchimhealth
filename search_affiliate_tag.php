#!/usr/bin/env php
<?php
/**
 * Search for Affiliate Tag References
 *
 * This script searches the entire database and codebase for any
 * references to a specific affiliate tag.
 *
 * Usage: php search_affiliate_tag.php <tag_to_search>
 * Example: php search_affiliate_tag.php shluchimhealt-20
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

// Check if running from command line
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

// Get command line arguments
if ($argc !== 2) {
    echo "Usage: php search_affiliate_tag.php <tag_to_search>\n";
    echo "Example: php search_affiliate_tag.php shluchimhealt-20\n";
    exit(1);
}

$searchTag = $argv[1];

echo "====================================\n";
echo "Affiliate Tag Search Tool\n";
echo "====================================\n\n";

echo "Searching for: $searchTag\n\n";

$foundCount = 0;

// ========================================
// 1. Search Database
// ========================================
echo "1. SEARCHING DATABASE...\n";
echo str_repeat("-", 80) . "\n\n";

// Search product_urls table
$urls = db()->fetchAll(
    'SELECT id, product_id, name, url FROM product_urls WHERE url LIKE :search',
    ['search' => '%' . $searchTag . '%']
);

if (!empty($urls)) {
    echo "Found in product_urls table:\n";
    foreach ($urls as $url) {
        $foundCount++;
        echo "  • Product ID: {$url['product_id']}, Link: {$url['name']}\n";
        echo "    URL: {$url['url']}\n";
        echo "    Link ID: {$url['id']}\n\n";
    }
} else {
    echo "✓ No matches in product_urls table\n\n";
}

// Search products table (description field)
$products = db()->fetchAll(
    'SELECT id, title, slug FROM products
     WHERE description LIKE :search',
    ['search' => '%' . $searchTag . '%']
);

if (!empty($products)) {
    echo "Found in products table:\n";
    foreach ($products as $product) {
        $foundCount++;
        echo "  • Product: {$product['title']}\n";
        echo "    Product ID: {$product['id']}\n";
        echo "    Slug: {$product['slug']}\n\n";
    }
} else {
    echo "✓ No matches in products table\n\n";
}

// Search posts table
$posts = db()->fetchAll(
    'SELECT id, title, slug FROM posts
     WHERE content LIKE :search OR excerpt LIKE :search',
    ['search' => '%' . $searchTag . '%']
);

if (!empty($posts)) {
    echo "Found in posts table:\n";
    foreach ($posts as $post) {
        $foundCount++;
        echo "  • Post: {$post['title']}\n";
        echo "    Post ID: {$post['id']}\n";
        echo "    Slug: {$post['slug']}\n\n";
    }
} else {
    echo "✓ No matches in posts table\n\n";
}

// Search settings table
$settings = db()->fetchAll(
    'SELECT setting_key, setting_value FROM settings WHERE setting_value LIKE :search',
    ['search' => '%' . $searchTag . '%']
);

if (!empty($settings)) {
    echo "Found in settings table:\n";
    foreach ($settings as $setting) {
        $foundCount++;
        echo "  • Setting: {$setting['setting_key']}\n";
        echo "    Value: {$setting['setting_value']}\n\n";
    }
} else {
    echo "✓ No matches in settings table\n\n";
}

// ========================================
// 2. Search Files
// ========================================
echo "\n2. SEARCHING FILES...\n";
echo str_repeat("-", 80) . "\n\n";

$filesToSearch = [
    'config.php',
    'functions.php',
    '.env',
    '.htaccess',
];

// Add all PHP files in root
$phpFiles = glob(__DIR__ . '/*.php');
$adminFiles = glob(__DIR__ . '/admin/*.php');
$includeFiles = glob(__DIR__ . '/includes/*.php');

$allFiles = array_merge($phpFiles, $adminFiles, $includeFiles);

$fileFoundCount = 0;

foreach ($allFiles as $file) {
    if (file_exists($file) && is_file($file)) {
        $content = file_get_contents($file);
        if (stripos($content, $searchTag) !== false) {
            $foundCount++;
            $fileFoundCount++;
            $relativePath = str_replace(__DIR__ . '/', '', $file);
            echo "  • Found in: $relativePath\n";

            // Show line numbers where it appears
            $lines = explode("\n", $content);
            foreach ($lines as $lineNum => $line) {
                if (stripos($line, $searchTag) !== false) {
                    $actualLineNum = $lineNum + 1;
                    echo "    Line $actualLineNum: " . trim($line) . "\n";
                }
            }
            echo "\n";
        }
    }
}

if ($fileFoundCount === 0) {
    echo "✓ No matches in PHP files\n\n";
}

// Check .env file specifically
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $content = file_get_contents($envFile);
    if (stripos($content, $searchTag) !== false) {
        $foundCount++;
        echo "  • Found in: .env file\n";
        $lines = explode("\n", $content);
        foreach ($lines as $lineNum => $line) {
            if (stripos($line, $searchTag) !== false) {
                $actualLineNum = $lineNum + 1;
                echo "    Line $actualLineNum: " . trim($line) . "\n";
            }
        }
        echo "\n";
    }
}

// Check .htaccess
$htaccessFile = __DIR__ . '/.htaccess';
if (file_exists($htaccessFile)) {
    $content = file_get_contents($htaccessFile);
    if (stripos($content, $searchTag) !== false) {
        $foundCount++;
        echo "  • Found in: .htaccess file\n";
        $lines = explode("\n", $content);
        foreach ($lines as $lineNum => $line) {
            if (stripos($line, $searchTag) !== false) {
                $actualLineNum = $lineNum + 1;
                echo "    Line $actualLineNum: " . trim($line) . "\n";
            }
        }
        echo "\n";
    }
}

// ========================================
// Summary
// ========================================
echo "\n" . str_repeat("=", 80) . "\n\n";
echo "SUMMARY:\n";
echo "--------\n";

if ($foundCount === 0) {
    echo "✓ No references to '$searchTag' found in database or files!\n";
    echo "  Your old affiliate tag has been completely removed.\n";
} else {
    echo "⚠ Found $foundCount reference(s) to '$searchTag'\n";
    echo "  Please review the results above and update manually.\n";
}

echo "\n";
