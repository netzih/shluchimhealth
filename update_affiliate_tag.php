#!/usr/bin/env php
<?php
/**
 * Update Amazon Affiliate Tag in All Product URLs
 *
 * This script updates the Amazon affiliate tag in all product URLs
 * stored in the database.
 *
 * Usage: php update_affiliate_tag.php <old_tag> <new_tag>
 * Example: php update_affiliate_tag.php old-tag-20 new-tag-20
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

// Check if running from command line
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

// Get command line arguments
if ($argc !== 3) {
    echo "Usage: php update_affiliate_tag.php <old_tag> <new_tag>\n";
    echo "Example: php update_affiliate_tag.php old-tag-20 new-tag-20\n";
    exit(1);
}

$oldTag = $argv[1];
$newTag = $argv[2];

echo "====================================\n";
echo "Amazon Affiliate Tag Update Tool\n";
echo "====================================\n\n";

echo "Old Tag: $oldTag\n";
echo "New Tag: $newTag\n\n";

// Fetch all product URLs
$urls = db()->fetchAll('SELECT id, name, url FROM product_urls');

if (empty($urls)) {
    echo "No product URLs found in database.\n";
    exit(0);
}

echo "Found " . count($urls) . " product URLs in database.\n\n";

// Track updates
$updatedCount = 0;
$skippedCount = 0;

foreach ($urls as $urlRecord) {
    $originalUrl = $urlRecord['url'];
    $newUrl = $originalUrl;

    // Check if this is an Amazon URL
    if (stripos($originalUrl, 'amazon.com') !== false || stripos($originalUrl, 'amzn.to') !== false) {
        // Replace tag parameter in various formats
        $patterns = [
            '/([?&])tag=' . preg_quote($oldTag, '/') . '([&#]|$)/i',
            '/([?&])tag=' . preg_quote($oldTag, '/') . '$/i',
        ];

        foreach ($patterns as $pattern) {
            $newUrl = preg_replace($pattern, '${1}tag=' . $newTag . '${2}', $newUrl);
        }

        // If URL was changed
        if ($newUrl !== $originalUrl) {
            // Update in database
            db()->query(
                'UPDATE product_urls SET url = :url WHERE id = :id',
                ['url' => $newUrl, 'id' => $urlRecord['id']]
            );

            $updatedCount++;
            echo "✓ Updated: {$urlRecord['name']}\n";
            echo "  Old: $originalUrl\n";
            echo "  New: $newUrl\n\n";
        } else {
            // Amazon URL but tag not found or already updated
            if (stripos($originalUrl, $oldTag) !== false) {
                echo "⚠ Skipped (tag in unexpected format): {$urlRecord['name']}\n";
                echo "  URL: $originalUrl\n\n";
                $skippedCount++;
            }
        }
    }
}

echo "====================================\n";
echo "Summary:\n";
echo "====================================\n";
echo "Total URLs in database: " . count($urls) . "\n";
echo "Updated: $updatedCount\n";
echo "Skipped: $skippedCount\n";
echo "\nDone!\n";
