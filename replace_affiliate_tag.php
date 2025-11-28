#!/usr/bin/env php
<?php
/**
 * Replace Amazon Affiliate Tag Everywhere
 *
 * This script automatically replaces the Amazon affiliate tag across:
 * - Database: product_urls, posts content/excerpt, settings
 * - Does NOT modify code files (only usage examples)
 *
 * Usage: php replace_affiliate_tag.php <old_tag> <new_tag>
 * Example: php replace_affiliate_tag.php shluchimhealt-20 shluchimhea07-20
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

// Check if running from command line
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

// Get command line arguments
if ($argc !== 3) {
    echo "Usage: php replace_affiliate_tag.php <old_tag> <new_tag>\n";
    echo "Example: php replace_affiliate_tag.php shluchimhealt-20 shluchimhea07-20\n";
    exit(1);
}

$oldTag = $argv[1];
$newTag = $argv[2];

echo "====================================\n";
echo "Replace Amazon Affiliate Tag - ALL\n";
echo "====================================\n\n";

echo "Old Tag: $oldTag\n";
echo "New Tag: $newTag\n\n";

$totalUpdates = 0;

// ========================================
// 1. Update Settings Table
// ========================================
echo "1. UPDATING SETTINGS TABLE...\n";
echo str_repeat("-", 80) . "\n\n";

$setting = db()->fetch(
    'SELECT setting_key, setting_value FROM settings WHERE setting_key = :key',
    ['key' => 'amazon_tag']
);

if ($setting && $setting['setting_value'] === $oldTag) {
    db()->query(
        'UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key',
        ['value' => $newTag, 'key' => 'amazon_tag']
    );
    echo "✓ Updated amazon_tag setting: $oldTag → $newTag\n\n";
    $totalUpdates++;
} elseif ($setting) {
    echo "ℹ amazon_tag setting already has value: {$setting['setting_value']}\n\n";
} else {
    echo "ℹ No amazon_tag setting found in database\n\n";
}

// ========================================
// 2. Update Product URLs
// ========================================
echo "2. UPDATING PRODUCT URLs...\n";
echo str_repeat("-", 80) . "\n\n";

$urls = db()->fetchAll(
    'SELECT id, name, url FROM product_urls WHERE url LIKE :search',
    ['search' => '%' . $oldTag . '%']
);

if (!empty($urls)) {
    $urlUpdatedCount = 0;

    foreach ($urls as $urlRecord) {
        $originalUrl = $urlRecord['url'];
        $newUrl = $originalUrl;

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
            db()->query(
                'UPDATE product_urls SET url = :url WHERE id = :id',
                ['url' => $newUrl, 'id' => $urlRecord['id']]
            );

            $urlUpdatedCount++;
            $totalUpdates++;
            echo "✓ Updated: {$urlRecord['name']}\n";
            echo "  Old: $originalUrl\n";
            echo "  New: $newUrl\n\n";
        }
    }

    echo "Updated $urlUpdatedCount product URL(s)\n\n";
} else {
    echo "✓ No product URLs need updating\n\n";
}

// ========================================
// 3. Update Blog Posts
// ========================================
echo "3. UPDATING BLOG POSTS...\n";
echo str_repeat("-", 80) . "\n\n";

$posts = db()->fetchAll(
    'SELECT id, title, slug, content, excerpt FROM posts
     WHERE content LIKE :search OR excerpt LIKE :search',
    ['search' => '%' . $oldTag . '%']
);

if (!empty($posts)) {
    $postsUpdatedCount = 0;

    foreach ($posts as $post) {
        $updated = false;
        $newContent = $post['content'];
        $newExcerpt = $post['excerpt'];

        // Update content
        if (stripos($post['content'], $oldTag) !== false) {
            $newContent = str_replace($oldTag, $newTag, $newContent);
            $updated = true;
        }

        // Update excerpt
        if ($post['excerpt'] && stripos($post['excerpt'], $oldTag) !== false) {
            $newExcerpt = str_replace($oldTag, $newTag, $newExcerpt);
            $updated = true;
        }

        if ($updated) {
            db()->query(
                'UPDATE posts SET content = :content, excerpt = :excerpt, updated_at = CURRENT_TIMESTAMP WHERE id = :id',
                [
                    'content' => $newContent,
                    'excerpt' => $newExcerpt,
                    'id' => $post['id']
                ]
            );

            $postsUpdatedCount++;
            $totalUpdates++;
            echo "✓ Updated Post: {$post['title']}\n";
            echo "  Slug: {$post['slug']}\n\n";
        }
    }

    echo "Updated $postsUpdatedCount blog post(s)\n\n";
} else {
    echo "✓ No blog posts need updating\n\n";
}

// ========================================
// 4. Update Product Descriptions
// ========================================
echo "4. UPDATING PRODUCT DESCRIPTIONS...\n";
echo str_repeat("-", 80) . "\n\n";

$products = db()->fetchAll(
    'SELECT id, title, slug, description FROM products WHERE description LIKE :search',
    ['search' => '%' . $oldTag . '%']
);

if (!empty($products)) {
    $productsUpdatedCount = 0;

    foreach ($products as $product) {
        $newDescription = str_replace($oldTag, $newTag, $product['description']);

        db()->query(
            'UPDATE products SET description = :description, updated_at = CURRENT_TIMESTAMP WHERE id = :id',
            [
                'description' => $newDescription,
                'id' => $product['id']
            ]
        );

        $productsUpdatedCount++;
        $totalUpdates++;
        echo "✓ Updated Product: {$product['title']}\n";
        echo "  Slug: {$product['slug']}\n\n";
    }

    echo "Updated $productsUpdatedCount product description(s)\n\n";
} else {
    echo "✓ No product descriptions need updating\n\n";
}

// ========================================
// Summary
// ========================================
echo str_repeat("=", 80) . "\n\n";
echo "SUMMARY:\n";
echo "--------\n";

if ($totalUpdates === 0) {
    echo "✓ No updates needed - affiliate tag is already correct everywhere!\n";
} else {
    echo "✓ Successfully updated $totalUpdates item(s) from '$oldTag' to '$newTag'\n\n";
    echo "Recommendation: Run the search script to verify:\n";
    echo "  php search_affiliate_tag.php $oldTag\n";
}

echo "\n";
