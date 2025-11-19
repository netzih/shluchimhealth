<?php
require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';

echo "<!DOCTYPE html><html><head><title>Check Blog Posts</title>";
echo "<style>body{font-family:Arial,sans-serif;max-width:800px;margin:50px auto;padding:20px;}</style>";
echo "</head><body>";

$posts = db()->fetchAll('SELECT id, title, slug, category, status FROM posts ORDER BY created_at DESC');

echo "<h1>Blog Posts in Database</h1>";
echo "<p><strong>Total posts:</strong> " . count($posts) . "</p>";

if (count($posts) > 0) {
    echo "<table border='1' cellpadding='10' style='width:100%;border-collapse:collapse;'>";
    echo "<tr><th>ID</th><th>Title</th><th>Slug</th><th>Category</th><th>Status</th><th>Link</th></tr>";
    foreach ($posts as $post) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($post['id']) . "</td>";
        echo "<td>" . htmlspecialchars($post['title']) . "</td>";
        echo "<td>" . htmlspecialchars($post['slug']) . "</td>";
        echo "<td>" . htmlspecialchars($post['category']) . "</td>";
        echo "<td>" . htmlspecialchars($post['status']) . "</td>";
        echo "<td><a href='" . SITE_URL . "/blog/" . htmlspecialchars($post['slug']) . "' target='_blank'>View</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No blog posts found. You can run create-blog-posts-web.php to create them.</p>";
}

echo "</body></html>";
