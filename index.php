<?php
require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';

$pageTitle = 'Home';

// Get featured products
$featuredProducts = db()->fetchAll('SELECT * FROM products WHERE featured = 1 ORDER BY RANDOM() LIMIT 6');

// Get recent blog posts
$recentPosts = db()->fetchAll('SELECT * FROM posts WHERE status = "published" ORDER BY published_at DESC LIMIT 3');

// Get categories
$categories = array_slice(getCategories('products'), 0, 8);

include 'includes/header.php';
?>

<div class="hero">
    <div class="container">
        <h1>Welcome to <?php echo escape(getSetting('site_name')); ?></h1>
        <p class="hero-subtitle"><?php echo escape(getSetting('site_tagline')); ?></p>
        <div class="hero-cta">
            <a href="<?php echo BASE_URL; ?>/products/" class="btn btn-primary">Browse Products</a>
            <a href="<?php echo BASE_URL; ?>/blog/" class="btn btn-secondary">Read Our Blog</a>
        </div>
    </div>
</div>

<?php if (!empty($categories)): ?>
<section class="categories-section">
    <div class="container">
        <h2>Shop by Category</h2>
        <div class="categories-grid">
            <?php foreach ($categories as $category):
                $count = db()->count('products', 'category = :category', ['category' => $category]);
            ?>
                <a href="<?php echo BASE_URL; ?>/products/?category=<?php echo urlencode($category); ?>" class="category-card">
                    <h3><?php echo escape($category); ?></h3>
                    <p><?php echo number_format($count); ?> products</p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($featuredProducts)): ?>
<section class="featured-products">
    <div class="container">
        <h2>Featured Products</h2>
        <div class="products-grid">
            <?php foreach ($featuredProducts as $product):
                $urls = db()->fetchAll('SELECT * FROM product_urls WHERE product_id = :id ORDER BY display_order', ['id' => $product['id']]);
            ?>
                <div class="product-card">
                    <?php if ($product['image']): ?>
                        <div class="product-image">
                            <a href="<?php echo BASE_URL; ?>/product/<?php echo $product['slug']; ?>">
                                <img src="<?php echo escape($product['image']); ?>" alt="<?php echo escape($product['title']); ?>">
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="product-info">
                        <span class="product-category"><?php echo escape($product['category']); ?></span>
                        <h3><a href="<?php echo BASE_URL; ?>/product/<?php echo $product['slug']; ?>"><?php echo escape($product['title']); ?></a></h3>
                        <p><?php echo escape(truncate($product['description'], 100)); ?></p>

                        <div class="product-links">
                            <?php foreach ($urls as $url): ?>
                                <a href="<?php echo escape($url['url']); ?>" class="btn btn-sm btn-affiliate" target="_blank" rel="nofollow noopener">
                                    <?php echo escape($url['name']); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="section-cta">
            <a href="<?php echo BASE_URL; ?>/products/" class="btn btn-primary">View All Products</a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($recentPosts)): ?>
<section class="recent-posts">
    <div class="container">
        <h2>Latest from Our Blog</h2>
        <div class="posts-grid">
            <?php foreach ($recentPosts as $post): ?>
                <article class="post-card">
                    <?php if ($post['featured_image']): ?>
                        <div class="post-image">
                            <a href="<?php echo BASE_URL; ?>/blog/<?php echo $post['slug']; ?>">
                                <img src="<?php echo escape($post['featured_image']); ?>" alt="<?php echo escape($post['title']); ?>">
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="post-content">
                        <?php if ($post['category']): ?>
                            <span class="post-category"><?php echo escape($post['category']); ?></span>
                        <?php endif; ?>

                        <h3><a href="<?php echo BASE_URL; ?>/blog/<?php echo $post['slug']; ?>"><?php echo escape($post['title']); ?></a></h3>

                        <p class="post-excerpt"><?php echo escape($post['excerpt'] ?: getExcerpt($post['content'])); ?></p>

                        <div class="post-meta">
                            <span><?php echo formatDate($post['published_at']); ?></span>
                        </div>

                        <a href="<?php echo BASE_URL; ?>/blog/<?php echo $post['slug']; ?>" class="read-more">Read More →</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <div class="section-cta">
            <a href="<?php echo BASE_URL; ?>/blog/" class="btn btn-primary">View All Posts</a>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="info-section">
    <div class="container">
        <div class="info-grid">
            <div class="info-card">
                <h3>🔍 Expert Reviews</h3>
                <p>In-depth product reviews and comparisons to help you make informed decisions.</p>
            </div>
            <div class="info-card">
                <h3>💯 Trusted Recommendations</h3>
                <p>We only recommend products we trust and believe in.</p>
            </div>
            <div class="info-card">
                <h3>🛒 Multiple Options</h3>
                <p>Compare prices and buy from your preferred retailer.</p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
