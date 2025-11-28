#!/usr/bin/env php
<?php
/**
 * Find Amazon Short Links
 *
 * This script identifies Amazon short links (amzn.to, a.co) that need
 * manual updating for affiliate tags.
 *
 * Usage: php find_amazon_shortlinks.php
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

// Check if running from command line
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

echo "====================================\n";
echo "Amazon Short Links Finder\n";
echo "====================================\n\n";

// Fetch all product URLs that might be Amazon short links
$shortLinkPatterns = [
    '%amzn.to%',
    '%a.co%',
    '%amzn.com%',
    '%amazon.com/dp/%',
    '%amazon.com/gp/product/%'
];

$allShortLinks = [];

foreach ($shortLinkPatterns as $pattern) {
    $urls = db()->fetchAll(
        'SELECT pu.id, pu.product_id, pu.name, pu.url, p.title as product_title
         FROM product_urls pu
         JOIN products p ON pu.product_id = p.id
         WHERE pu.url LIKE :pattern
         ORDER BY p.title, pu.display_order',
        ['pattern' => $pattern]
    );

    foreach ($urls as $url) {
        // Avoid duplicates
        if (!isset($allShortLinks[$url['id']])) {
            $allShortLinks[$url['id']] = $url;
        }
    }
}

if (empty($allShortLinks)) {
    echo "No Amazon short links found.\n";
    exit(0);
}

echo "Found " . count($allShortLinks) . " Amazon short link(s):\n\n";
echo str_repeat("=", 80) . "\n\n";

$currentProduct = null;
$linkCount = 0;

foreach ($allShortLinks as $urlRecord) {
    // Print product header if it's a new product
    if ($currentProduct !== $urlRecord['product_id']) {
        if ($currentProduct !== null) {
            echo "\n" . str_repeat("-", 80) . "\n\n";
        }
        $currentProduct = $urlRecord['product_id'];
        echo "Product: {$urlRecord['product_title']}\n";
        echo "Product ID: {$urlRecord['product_id']}\n\n";
    }

    $linkCount++;

    // Determine link type
    $linkType = 'Unknown';
    if (stripos($urlRecord['url'], 'amzn.to') !== false) {
        $linkType = 'amzn.to (Short Link)';
    } elseif (stripos($urlRecord['url'], 'a.co') !== false) {
        $linkType = 'a.co (Short Link)';
    } elseif (stripos($urlRecord['url'], '/dp/') !== false) {
        $linkType = 'Direct Product Link (/dp/)';
    } elseif (stripos($urlRecord['url'], '/gp/product/') !== false) {
        $linkType = 'Product Link (/gp/product/)';
    }

    echo "  [{$linkCount}] Link Name: {$urlRecord['name']}\n";
    echo "      Type: $linkType\n";
    echo "      URL: {$urlRecord['url']}\n";
    echo "      Link ID: {$urlRecord['id']}\n\n";
}

echo str_repeat("=", 80) . "\n\n";

echo "Summary:\n";
echo "--------\n";
echo "Total short/special links found: " . count($allShortLinks) . "\n\n";

echo "To update these links:\n";
echo "1. Expand each short link to get the full Amazon URL with your affiliate tag\n";
echo "2. Update in admin panel: /admin/product-edit.php?id=<product_id>\n";
echo "3. Or update directly in database:\n";
echo "   UPDATE product_urls SET url = 'NEW_URL' WHERE id = <link_id>;\n\n";
