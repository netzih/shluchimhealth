<?php
/**
 * Debug page - view in browser to check configuration
 * Access at: https://shluchimhealth.com/debug.php
 * DELETE THIS FILE after debugging!
 */

require_once 'config.php';
require_once 'database.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Debug - Shluchim Health</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .box { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #2563eb; margin-top: 0; }
        .success { color: #10b981; }
        .error { color: #ef4444; }
        .label { font-weight: bold; color: #666; }
        pre { background: #f9f9f9; padding: 10px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🔍 Shluchim Health Debug</h1>
    <p><strong>⚠️ DELETE THIS FILE (debug.php) after debugging!</strong></p>

    <div class="box">
        <h2>1. Configuration</h2>
        <p><span class="label">BASE_URL:</span> <code><?php echo BASE_URL; ?></code></p>
        <p><span class="label">SITE_URL:</span> <code><?php echo SITE_URL; ?></code></p>
        <p><span class="label">SITE_PATH:</span> <code><?php echo SITE_PATH; ?></code></p>
    </div>

    <div class="box">
        <h2>2. Expected URLs</h2>
        <p><span class="label">Homepage:</span> <a href="<?php echo BASE_URL; ?>/"><?php echo BASE_URL; ?>/</a></p>
        <p><span class="label">Admin:</span> <a href="<?php echo BASE_URL; ?>/admin/"><?php echo BASE_URL; ?>/admin/</a></p>
        <p><span class="label">Products:</span> <a href="<?php echo BASE_URL; ?>/products/"><?php echo BASE_URL; ?>/products/</a></p>
        <p><span class="label">Admin CSS:</span> <a href="<?php echo BASE_URL; ?>/assets/css/admin.css"><?php echo BASE_URL; ?>/assets/css/admin.css</a></p>
    </div>

    <div class="box">
        <h2>3. Database Status</h2>
        <?php
        try {
            $db = Database::getInstance();
            echo '<p class="success">✓ Database connected</p>';

            $productCount = $db->count('products');
            $pageCount = $db->count('pages');
            $postCount = $db->count('posts');
            $userCount = $db->count('users');

            echo "<p><span class='label'>Products:</span> $productCount</p>";
            echo "<p><span class='label'>Pages:</span> $pageCount</p>";
            echo "<p><span class='label'>Blog Posts:</span> $postCount</p>";
            echo "<p><span class='label'>Users:</span> $userCount</p>";
        } catch (Exception $e) {
            echo '<p class="error">✗ Database Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
        ?>
    </div>

    <div class="box">
        <h2>4. Pages in Database</h2>
        <?php
        try {
            $pages = $db->fetchAll('SELECT title, slug FROM pages ORDER BY title');
            if (empty($pages)) {
                echo '<p class="error">⚠️ No pages found! Run: php init-pages.php</p>';
            } else {
                echo '<ul>';
                foreach ($pages as $page) {
                    $url = BASE_URL . '/page/' . $page['slug'];
                    echo '<li><a href="' . $url . '">' . htmlspecialchars($page['title']) . '</a> (slug: ' . $page['slug'] . ')</li>';
                }
                echo '</ul>';
            }
        } catch (Exception $e) {
            echo '<p class="error">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
        ?>
    </div>

    <div class="box">
        <h2>5. File Checks</h2>
        <?php
        $checks = [
            'admin.css' => __DIR__ . '/assets/css/admin.css',
            'style.css' => __DIR__ . '/assets/css/style.css',
            'database.db' => __DIR__ . '/database.db',
            'products.json' => __DIR__ . '/products.json'
        ];

        foreach ($checks as $name => $path) {
            $exists = file_exists($path);
            $class = $exists ? 'success' : 'error';
            $icon = $exists ? '✓' : '✗';
            echo "<p class='$class'>$icon $name</p>";
        }
        ?>
    </div>

    <div class="box">
        <h2>6. Server Info</h2>
        <p><span class="label">PHP Version:</span> <?php echo PHP_VERSION; ?></p>
        <p><span class="label">Server Software:</span> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></p>
        <p><span class="label">Document Root:</span> <?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown'; ?></p>
        <p><span class="label">Script Filename:</span> <?php echo __FILE__; ?></p>
    </div>

    <div class="box" style="background: #fff3cd; border: 2px solid #ffc107;">
        <h2>⚠️ Security Warning</h2>
        <p><strong>DELETE THIS FILE (debug.php) when done debugging!</strong></p>
        <p>This file exposes sensitive configuration information.</p>
    </div>
</body>
</html>
