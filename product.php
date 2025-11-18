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

// Get product
$product = db()->fetch('SELECT * FROM products WHERE slug = :slug', ['slug' => $slug]);

if (!$product) {
    header('HTTP/1.0 404 Not Found');
    include '404.php';
    exit;
}

// Get product URLs
$urls = db()->fetchAll('SELECT * FROM product_urls WHERE product_id = :id ORDER BY display_order', ['id' => $product['id']]);

// Get product tags
$tags = db()->fetchAll('SELECT tag FROM product_tags WHERE product_id = :id', ['id' => $product['id']]);

// Get related products (same category)
$relatedProducts = db()->fetchAll(
    'SELECT * FROM products WHERE category = :category AND id != :id ORDER BY RANDOM() LIMIT 4',
    ['category' => $product['category'], 'id' => $product['id']]
);

// SEO
$pageTitle = $product['title'];
$seoDescription = $product['description'];
$ogImage = $product['image'];

include 'includes/header.php';
?>

<div class="product-page">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?php echo BASE_URL; ?>/">Home</a> /
            <a href="<?php echo BASE_URL; ?>/products/">Products</a> /
            <a href="<?php echo BASE_URL; ?>/products/?category=<?php echo urlencode($product['category']); ?>"><?php echo escape($product['category']); ?></a> /
            <span><?php echo escape($product['title']); ?></span>
        </div>

        <div class="product-detail">
            <div class="product-main">
                <?php if ($product['image']): ?>
                    <div class="product-image-large">
                        <img src="<?php echo escape($product['image']); ?>" alt="<?php echo escape($product['title']); ?>">
                    </div>
                <?php endif; ?>
            </div>

            <div class="product-sidebar">
                <span class="product-category"><?php echo escape($product['category']); ?></span>
                <h1><?php echo escape($product['title']); ?></h1>

                <div class="product-description">
                    <?php echo nl2br(escape($product['description'])); ?>
                </div>

                <?php if (!empty($tags)): ?>
                    <div class="product-tags">
                        <?php foreach ($tags as $tag): ?>
                            <span class="tag"><?php echo escape($tag['tag']); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="product-buy">
                    <h3>Where to Buy</h3>
                    <p class="buy-note">Compare prices and choose your preferred retailer:</p>
                    <div class="buy-buttons">
                        <?php foreach ($urls as $url): ?>
                            <a href="<?php echo escape($url['url']); ?>" class="btn btn-affiliate" target="_blank" rel="nofollow noopener sponsored">
                                <span class="btn-icon">🛒</span>
                                <span class="btn-text">
                                    <strong>Buy on <?php echo escape($url['name']); ?></strong>
                                    <small>Visit retailer</small>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <p class="affiliate-note">
                        <small>💡 We may earn a commission if you make a purchase through our links, at no extra cost to you.</small>
                    </p>
                </div>
            </div>
        </div>

        <?php if (!empty($relatedProducts)): ?>
            <section class="related-products">
                <h2>Related Products</h2>
                <div class="products-grid">
                    <?php foreach ($relatedProducts as $relProduct):
                        $relUrls = db()->fetchAll('SELECT * FROM product_urls WHERE product_id = :id ORDER BY display_order LIMIT 2', ['id' => $relProduct['id']]);
                    ?>
                        <div class="product-card">
                            <?php if ($relProduct['image']): ?>
                                <div class="product-image">
                                    <a href="<?php echo BASE_URL; ?>/product/<?php echo $relProduct['slug']; ?>">
                                        <img src="<?php echo escape($relProduct['image']); ?>" alt="<?php echo escape($relProduct['title']); ?>">
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="product-info">
                                <h3><a href="<?php echo BASE_URL; ?>/product/<?php echo $relProduct['slug']; ?>"><?php echo escape($relProduct['title']); ?></a></h3>
                                <p><?php echo escape(truncate($relProduct['description'], 100)); ?></p>

                                <div class="product-links">
                                    <?php foreach ($relUrls as $url): ?>
                                        <a href="<?php echo escape($url['url']); ?>" class="btn btn-sm btn-affiliate" target="_blank" rel="nofollow noopener">
                                            <?php echo escape($url['name']); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
