<?php
require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';

// DEBUG: Log what we're receiving
error_log("PAGE.PHP - REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A'));
error_log("PAGE.PHP - QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'N/A'));
error_log("PAGE.PHP - GET params: " . print_r($_GET, true));

// Get slug from URL
$slug = $_GET['slug'] ?? '';

error_log("PAGE.PHP - Slug: " . $slug);

if (empty($slug)) {
    error_log("PAGE.PHP - Empty slug, redirecting to 404");
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
