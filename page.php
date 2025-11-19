<?php
require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';

// Get slug from URL
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
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
