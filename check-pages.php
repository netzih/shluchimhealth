<?php
require_once 'config.php';
require_once 'database.php';

header('Content-Type: text/html; charset=utf-8');

echo "<h1>Page Content Check</h1>";
echo "<p>Checking what's actually stored in the database...</p>";

$pages = db()->fetchAll('SELECT id, title, slug, SUBSTRING(content, 1, 200) as preview FROM pages ORDER BY title');

echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>ID</th><th>Title</th><th>Slug</th><th>Content Preview (first 200 chars)</th></tr>";

foreach ($pages as $page) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($page['id']) . "</td>";
    echo "<td>" . htmlspecialchars($page['title']) . "</td>";
    echo "<td>" . htmlspecialchars($page['slug']) . "</td>";
    echo "<td>" . htmlspecialchars(strip_tags($page['preview'])) . "...</td>";
    echo "</tr>";
}

echo "</table>";

echo "<hr>";
echo "<h2>Full Content of Each Page:</h2>";

foreach ($pages as $page) {
    $fullPage = db()->fetch('SELECT * FROM pages WHERE id = :id', ['id' => $page['id']]);

    echo "<div style='border: 2px solid #000; padding: 20px; margin: 20px 0;'>";
    echo "<h3>" . htmlspecialchars($fullPage['title']) . " (slug: " . htmlspecialchars($fullPage['slug']) . ")</h3>";
    echo "<div style='background: #f0f0f0; padding: 10px;'>";
    echo "<strong>First 500 characters:</strong><br>";
    echo htmlspecialchars(substr(strip_tags($fullPage['content']), 0, 500)) . "...";
    echo "</div>";
    echo "</div>";
}
?>
