<?php
require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';

echo "<!DOCTYPE html><html><head><title>Database Page Check</title>";
echo "<style>body{font-family:monospace;padding:20px;} table{border-collapse:collapse;width:100%;margin:20px 0;} th,td{border:1px solid #ccc;padding:10px;text-align:left;} th{background:#333;color:#fff;} .content-preview{max-width:600px;overflow:auto;background:#f5f5f5;padding:10px;}</style>";
echo "</head><body>";

echo "<h1>Database Page Content Check</h1>";

// Get all pages
$pages = db()->fetchAll('SELECT id, title, slug, content, created_at FROM pages ORDER BY title ASC');

echo "<p><strong>Total pages in database:</strong> " . count($pages) . "</p>";

if (empty($pages)) {
    echo "<p style='color:red;'>No pages found in database!</p>";
} else {
    echo "<table>";
    echo "<thead><tr><th>ID</th><th>Title</th><th>Slug</th><th>Content Preview (first 300 chars)</th><th>Content Length</th><th>Created</th></tr></thead>";
    echo "<tbody>";

    foreach ($pages as $page) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($page['id']) . "</td>";
        echo "<td><strong>" . htmlspecialchars($page['title']) . "</strong></td>";
        echo "<td><code>" . htmlspecialchars($page['slug']) . "</code></td>";

        $preview = substr(strip_tags($page['content']), 0, 300);
        echo "<td class='content-preview'>" . htmlspecialchars($preview) . "...</td>";
        echo "<td>" . strlen($page['content']) . " chars</td>";
        echo "<td>" . htmlspecialchars($page['created_at']) . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";

    // Show full content for each page
    echo "<hr><h2>Full Content for Each Page</h2>";

    foreach ($pages as $page) {
        echo "<div style='margin:30px 0; padding:20px; border:2px solid #333; background:#fff;'>";
        echo "<h3>" . htmlspecialchars($page['title']) . " (slug: " . htmlspecialchars($page['slug']) . ")</h3>";
        echo "<div style='background:#f9f9f9; padding:15px; border-left:4px solid #333;'>";
        echo "<strong>Raw HTML Content:</strong><br>";
        echo "<pre style='white-space:pre-wrap; word-wrap:break-word;'>" . htmlspecialchars($page['content']) . "</pre>";
        echo "</div>";
        echo "<hr>";
        echo "<div style='padding:15px; border:1px solid #ddd;'>";
        echo "<strong>Rendered Content:</strong><br>";
        echo $page['content'];
        echo "</div>";
        echo "</div>";
    }
}

echo "</body></html>";
