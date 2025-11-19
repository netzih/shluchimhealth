<?php
require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';

// Show what we received
echo "<h1>Page Debug Info</h1>";
echo "<pre>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "\n";
echo "QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'NOT SET') . "\n";
echo "\$_GET contents:\n";
print_r($_GET);
echo "\n\nSlug from \$_GET['slug']: " . ($_GET['slug'] ?? 'NOT SET') . "\n";
echo "</pre>";

// Get slug from URL
$slug = $_GET['slug'] ?? '';

echo "<p><strong>Slug being queried:</strong> " . htmlspecialchars($slug) . "</p>";

if (empty($slug)) {
    echo "<p style='color: red;'>ERROR: Slug is empty! The URL rewrite isn't passing the slug parameter.</p>";
} else {
    // Get page
    $page = db()->fetch('SELECT * FROM pages WHERE slug = :slug', ['slug' => $slug]);

    if ($page) {
        echo "<p style='color: green;'>✓ Page found in database!</p>";
        echo "<p><strong>Title:</strong> " . htmlspecialchars($page['title']) . "</p>";
        echo "<p><strong>Content preview:</strong></p>";
        echo "<div style='border: 1px solid #ccc; padding: 10px; background: #f9f9f9;'>";
        echo htmlspecialchars(substr(strip_tags($page['content']), 0, 200)) . "...";
        echo "</div>";
    } else {
        echo "<p style='color: red;'>✗ Page NOT found for slug: " . htmlspecialchars($slug) . "</p>";
    }
}

echo "<hr>";
echo "<h2>Test Links:</h2>";
echo "<ul>";
echo "<li><a href='/page/about'>Via rewrite: /page/about</a></li>";
echo "<li><a href='/page-debug.php?slug=about'>Direct query: /page-debug.php?slug=about</a></li>";
echo "<li><a href='/page/privacy-policy'>Via rewrite: /page/privacy-policy</a></li>";
echo "<li><a href='/page-debug.php?slug=privacy-policy'>Direct query: /page-debug.php?slug=privacy-policy</a></li>";
echo "</ul>";
