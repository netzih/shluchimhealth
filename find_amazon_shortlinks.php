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
echo "Amazon Short Links Finder (amzn.to)\n";
echo "====================================\n\n";

// Fetch all product URLs that are amzn.to short links
$allShortLinks = db()->fetchAll(
    'SELECT pu.id, pu.product_id, pu.name, pu.url, p.title as product_title, p.slug as product_slug
     FROM product_urls pu
     JOIN products p ON pu.product_id = p.id
     WHERE pu.url LIKE :pattern
     ORDER BY p.title, pu.display_order',
    ['pattern' => '%amzn.to%']
);

if (empty($allShortLinks)) {
    echo "No amzn.to short links found.\n";
    exit(0);
}

echo "Found " . count($allShortLinks) . " amzn.to short link(s) that need manual updating:\n\n";
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
        echo "Product ID: {$urlRecord['product_id']}\n";
        echo "Product Slug: {$urlRecord['product_slug']}\n";
        echo "Edit URL: " . SITE_URL . "/admin/product-edit.php?id={$urlRecord['product_id']}\n\n";
    }

    $linkCount++;

    echo "  [{$linkCount}] Link Name: {$urlRecord['name']}\n";
    echo "      Short URL: {$urlRecord['url']}\n";
    echo "      Link ID: {$urlRecord['id']}\n\n";
}

echo str_repeat("=", 80) . "\n\n";

echo "Summary:\n";
echo "--------\n";
echo "Total amzn.to short links found: " . count($allShortLinks) . "\n\n";

echo "How to update these short links:\n";
echo "1. Visit each amzn.to URL in your browser to expand it to full Amazon URL\n";
echo "2. Add your affiliate tag to the expanded URL: ?tag=shluchimhea07-20\n";
echo "3. Update via admin panel using the 'Edit URL' links above\n";
echo "4. Or update directly in database:\n";
echo "   UPDATE product_urls SET url = 'FULL_AMAZON_URL?tag=shluchimhea07-20' WHERE id = <link_id>;\n\n";

echo "Note: amzn.to links cannot have affiliate tags added directly.\n";
echo "They must be expanded to full amazon.com URLs first.\n";
