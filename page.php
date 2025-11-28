<?php
require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';

// DEBUG MODE - Remove this block when fixed
$debugMode = false;
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

if ($debugMode) {
    echo "<div style='background: #e3f2fd; padding: 20px; border: 3px solid #2196f3; margin: 20px;'>";
    echo "<h2>🔍 DATABASE QUERY RESULT</h2>";
    echo "<p><strong>Query:</strong> SELECT * FROM pages WHERE slug = :slug</p>";
    echo "<p><strong>Slug parameter:</strong> " . htmlspecialchars($slug) . "</p>";
    if ($page) {
        echo "<p style='color:green;'><strong>✓ Page found in database!</strong></p>";
        echo "<p><strong>Page ID:</strong> " . htmlspecialchars($page['id']) . "</p>";
        echo "<p><strong>Page Title:</strong> " . htmlspecialchars($page['title']) . "</p>";
        echo "<p><strong>Page Slug:</strong> " . htmlspecialchars($page['slug']) . "</p>";
        echo "<p><strong>Content Length:</strong> " . strlen($page['content']) . " characters</p>";
        echo "<p><strong>Content Preview (first 200 chars):</strong></p>";
        echo "<pre style='background:#fff;padding:10px;overflow:auto;'>" . htmlspecialchars(substr(strip_tags($page['content']), 0, 200)) . "...</pre>";
    } else {
        echo "<p style='color:red;'><strong>✗ No page found with slug: " . htmlspecialchars($slug) . "</strong></p>";
    }
    echo "</div>";
}

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

        <article class="page-article">
            <div class="page-content">
                <?php echo $page['content']; ?>
            </div>
        </article>
    </div>
</div>

<style>
.page-single {
    padding: 3rem 0;
    min-height: 60vh;
}

.page-article {
    max-width: 900px;
    margin: 0 auto;
    background: white;
    padding: 3rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.page-content {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #333;
}

.page-content h1 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: var(--dark-color);
    line-height: 1.2;
    font-weight: 700;
}

.page-content h2 {
    font-size: 1.75rem;
    margin-top: 2.5rem;
    margin-bottom: 1rem;
    color: var(--dark-color);
    font-weight: 600;
    border-bottom: 2px solid var(--primary-color);
    padding-bottom: 0.5rem;
}

.page-content h3 {
    font-size: 1.35rem;
    margin-top: 2rem;
    margin-bottom: 0.75rem;
    color: var(--dark-color);
    font-weight: 600;
}

.page-content h4 {
    font-size: 1.15rem;
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
    color: var(--dark-color);
    font-weight: 600;
}

.page-content p {
    margin-bottom: 1.25rem;
}

.page-content ul,
.page-content ol {
    margin: 1.25rem 0;
    padding-left: 2rem;
}

.page-content li {
    margin-bottom: 0.75rem;
    line-height: 1.7;
}

.page-content ul li {
    list-style-type: disc;
}

.page-content ol li {
    list-style-type: decimal;
}

.page-content strong {
    font-weight: 600;
    color: var(--dark-color);
}

.page-content a {
    color: var(--primary-color);
    text-decoration: underline;
    transition: color 0.2s;
}

.page-content a:hover {
    color: var(--secondary-color);
}

.page-content blockquote {
    border-left: 4px solid var(--primary-color);
    padding: 1rem 1.5rem;
    margin: 1.5rem 0;
    background: #f8f9fa;
    font-style: italic;
    color: #555;
}

.page-content code {
    background: #f4f4f4;
    padding: 0.2rem 0.4rem;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
    color: #d63384;
}

.page-content pre {
    background: #f4f4f4;
    padding: 1rem;
    border-radius: 6px;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.page-content pre code {
    background: none;
    padding: 0;
    color: inherit;
}

.page-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 1.5rem 0;
}

.page-content table th,
.page-content table td {
    border: 1px solid #ddd;
    padding: 0.75rem;
    text-align: left;
}

.page-content table th {
    background: #f8f9fa;
    font-weight: 600;
    color: var(--dark-color);
}

.page-content table tr:nth-child(even) {
    background: #f8f9fa;
}

.page-content hr {
    border: none;
    border-top: 2px solid #e0e0e0;
    margin: 2.5rem 0;
}

/* Highlighted boxes */
.page-content div[style*="background"] {
    border-radius: 8px;
    margin: 1.5rem 0;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .page-single {
        padding: 2rem 0;
    }

    .page-article {
        padding: 2rem 1.5rem;
        border-radius: 0;
        box-shadow: none;
    }

    .page-content {
        font-size: 1rem;
    }

    .page-content h1 {
        font-size: 2rem;
    }

    .page-content h2 {
        font-size: 1.5rem;
        margin-top: 2rem;
    }

    .page-content h3 {
        font-size: 1.25rem;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
