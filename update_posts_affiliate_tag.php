#!/usr/bin/env php
<?php
/**
 * Update Affiliate Tag in Blog Posts
 *
 * This script updates the Amazon affiliate tag in blog post content.
 *
 * Usage: php update_posts_affiliate_tag.php <old_tag> <new_tag>
 * Example: php update_posts_affiliate_tag.php shluchimhealt-20 shluchimhea07-20
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

// Check if running from command line
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

// Get command line arguments
if ($argc !== 3) {
    echo "Usage: php update_posts_affiliate_tag.php <old_tag> <new_tag>\n";
    echo "Example: php update_posts_affiliate_tag.php shluchimhealt-20 shluchimhea07-20\n";
    exit(1);
}

$oldTag = $argv[1];
$newTag = $argv[2];

echo "====================================\n";
echo "Blog Posts Affiliate Tag Update Tool\n";
echo "====================================\n\n";

echo "Old Tag: $oldTag\n";
echo "New Tag: $newTag\n\n";

// Find all posts with the old tag in content or excerpt
$posts = db()->fetchAll(
    'SELECT id, title, slug, content, excerpt FROM posts
     WHERE content LIKE :search OR excerpt LIKE :search',
    ['search' => '%' . $oldTag . '%']
);

if (empty($posts)) {
    echo "No blog posts found with affiliate tag '$oldTag'.\n";
    exit(0);
}

echo "Found " . count($posts) . " blog post(s) with the old tag:\n\n";
echo str_repeat("=", 80) . "\n\n";

$updatedCount = 0;

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
        // Update in database
        db()->query(
            'UPDATE posts SET content = :content, excerpt = :excerpt, updated_at = CURRENT_TIMESTAMP WHERE id = :id',
            [
                'content' => $newContent,
                'excerpt' => $newExcerpt,
                'id' => $post['id']
            ]
        );

        $updatedCount++;
        echo "✓ Updated Post #{$post['id']}: {$post['title']}\n";
        echo "  Slug: {$post['slug']}\n";
        echo "  URL: " . SITE_URL . "/blog/{$post['slug']}\n";
        echo "  Admin Edit: " . SITE_URL . "/admin/post-edit.php?id={$post['id']}\n\n";
    }
}

echo str_repeat("=", 80) . "\n\n";
echo "Summary:\n";
echo "--------\n";
echo "Posts found: " . count($posts) . "\n";
echo "Posts updated: $updatedCount\n";
echo "\nDone!\n";
