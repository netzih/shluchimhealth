<?php
/**
 * Test page routing
 * Access: /test-page.php?slug=about
 */

require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';

echo "<!DOCTYPE html><html><head><title>Page Test</title></head><body>";
echo "<h1>Page Routing Test</h1>";

echo "<p><strong>BASE_URL:</strong> " . BASE_URL . "</p>";
echo "<p><strong>Request:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "</p>";

$slug = $_GET['slug'] ?? '';
echo "<p><strong>Slug:</strong> " . htmlspecialchars($slug) . "</p>";

if (!empty($slug)) {
    $page = db()->fetch('SELECT * FROM pages WHERE slug = :slug', ['slug' => $slug]);

    if ($page) {
        echo "<h2>✓ Page Found!</h2>";
        echo "<p><strong>Title:</strong> " . htmlspecialchars($page['title']) . "</p>";
        echo "<p><strong>Slug:</strong> " . htmlspecialchars($page['slug']) . "</p>";
        echo "<h3>Content Preview:</h3>";
        echo "<div style='border: 1px solid #ccc; padding: 10px; background: #f9f9f9;'>";
        echo substr(strip_tags($page['content']), 0, 500) . "...";
        echo "</div>";
    } else {
        echo "<h2>✗ Page NOT Found</h2>";
        echo "<p>No page with slug '$slug' in database</p>";

        // Show all pages
        $allPages = db()->fetchAll('SELECT id, title, slug FROM pages');
        echo "<h3>Available pages:</h3><ul>";
        foreach ($allPages as $p) {
            echo "<li>" . htmlspecialchars($p['title']) . " (slug: " . htmlspecialchars($p['slug']) . ")</li>";
        }
        echo "</ul>";
    }
} else {
    echo "<p>Add ?slug=about or ?slug=privacy-policy to test</p>";

    // Show all pages
    $allPages = db()->fetchAll('SELECT id, title, slug FROM pages');
    echo "<h3>Test these:</h3><ul>";
    foreach ($allPages as $p) {
        echo "<li><a href='?slug=" . urlencode($p['slug']) . "'>" . htmlspecialchars($p['title']) . "</a></li>";
    }
    echo "</ul>";
}

echo "</body></html>";
