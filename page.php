<?php
require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';

// DEBUG MODE - Remove this block when fixed
$debugMode = true;
if ($debugMode) {
    echo "<div style='background: #ffeb3b; padding: 20px; border: 3px solid #f00; margin: 20px;'>";
    echo "<h2>🔍 PAGE.PHP DEBUG</h2>";
    echo "<p><strong>REQUEST_URI:</strong> " . htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'N/A') . "</p>";
    echo "<p><strong>QUERY_STRING:</strong> " . htmlspecialchars($_SERVER['QUERY_STRING'] ?? 'N/A') . "</p>";
    echo "<p><strong>\$_GET:</strong> <pre>" . htmlspecialchars(print_r($_GET, true)) . "</pre></p>";
    echo "<p><strong>Slug from \$_GET['slug']:</strong> " . htmlspecialchars($_GET['slug'] ?? 'NOT SET') . "</p>";
    echo "</div>";
}

// Get slug from URL
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    if ($debugMode) {
        echo "<div style='background: #f00; color: #fff; padding: 20px; margin: 20px;'>";
        echo "<h2>❌ ERROR: Slug is empty!</h2>";
        echo "<p>The .htaccess rewrite is NOT passing the slug parameter.</p>";
        echo "<p>This means when you access /page/about, it's not converting it to page.php?slug=about</p>";
        echo "</div>";
    }
    header('HTTP/1.0 404 Not Found');
    include '404.php';
    exit;
}

// Get page
$page = db()->fetch('SELECT * FROM pages WHERE slug = :slug', ['slug' => $slug]);

if (!$page) {
    header('HTTP/1.0 404 Not Found');
    include '404.php';
    exit;
}

// SEO
$pageTitle = $page['seo_title'] ?: $page['title'];
$seoDescription = $page['seo_description'] ?: getExcerpt($page['content']);

include 'includes/header.php';
?>

<div class="page-single">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?php echo BASE_URL; ?>/">Home</a> / <span><?php echo escape($page['title']); ?></span>
        </div>

        <article>
            <h1><?php echo escape($page['title']); ?></h1>

            <div class="page-content">
                <?php echo $page['content']; ?>
            </div>
        </article>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
