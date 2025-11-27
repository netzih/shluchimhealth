<?php
require_once '../config.php';
require_once '../database.php';
require_once '../functions.php';

$pageTitle = 'Products';

// Filters
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$where = '1';
$params = [];

if ($category) {
    $where .= ' AND category = :category';
    $params['category'] = $category;
    $pageTitle = $category . ' - Products';
}

if ($search) {
    $where .= ' AND (title LIKE :search OR description LIKE :search)';
    $params['search'] = '%' . $search . '%';
    $pageTitle = 'Search Results - Products';
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = (int)getSetting('products_per_page', 24);
$totalProducts = db()->count('products', $where, $params);
$pagination = paginate($totalProducts, $perPage, $page);

// Get products
$products = db()->fetchAll(
    "SELECT * FROM products WHERE {$where} ORDER BY title ASC LIMIT :limit OFFSET :offset",
    array_merge($params, ['limit' => $perPage, 'offset' => $pagination['offset']])
);

$categories = getCategories('products');

include '../includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1><?php echo escape($pageTitle); ?></h1>
        <?php if ($category): ?>
            <p class="breadcrumb">
                <a href="<?php echo BASE_URL; ?>/products/">All Products</a> / <?php echo escape($category); ?>
            </p>
        <?php endif; ?>
    </div>
</div>

<div class="products-page">
    <div class="container">
        <div class="products-layout">
            <aside class="products-sidebar">
                <div class="sidebar-widget">
                    <h3>Search Products</h3>
                    <form method="GET" class="search-form">
                        <input type="text" name="search" value="<?php echo escape($search); ?>" placeholder="Search...">
                        <?php if ($category): ?>
                            <input type="hidden" name="category" value="<?php echo escape($category); ?>">
                        <?php endif; ?>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>

                <div class="sidebar-widget">
                    <h3>Categories</h3>
                    <ul class="category-list">
                        <li>
                            <a href="<?php echo BASE_URL; ?>/products/" <?php echo !$category ? 'class="active"' : ''; ?>>
                                All Products (<?php echo number_format(db()->count('products')); ?>)
                            </a>
                        </li>
                        <?php foreach ($categories as $cat):
                            $count = db()->count('products', 'category = :category', ['category' => $cat]);
                        ?>
                            <li>
                                <a href="?category=<?php echo urlencode($cat); ?>" <?php echo $category === $cat ? 'class="active"' : ''; ?>>
                                    <?php echo escape($cat); ?> (<?php echo number_format($count); ?>)
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </aside>

            <div class="products-main">
                <?php if ($search): ?>
                    <div class="search-info">
                        <p>Found <?php echo number_format($totalProducts); ?> result(s) for "<?php echo escape($search); ?>"</p>
                        <a href="<?php echo BASE_URL; ?>/products/<?php echo $category ? '?category=' . urlencode($category) : ''; ?>">Clear search</a>
                    </div>
                <?php endif; ?>

                <?php if (empty($products)): ?>
                    <div class="empty-state">
                        <h3>No products found</h3>
                        <p>Try a different search or category.</p>
                        <a href="<?php echo BASE_URL; ?>/products/" class="btn btn-primary">View All Products</a>
                    </div>
                <?php else: ?>
                    <div class="products-grid">
                        <?php foreach ($products as $product):
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
                                    <p><?php echo escape(truncate($product['description'], 120)); ?></p>

                                    <div class="product-links">
                                        <?php foreach ($urls as $url): ?>
                                            <a href="<?php echo escape($url['url']); ?>" class="btn btn-sm btn-affiliate" target="_blank" rel="nofollow noopener sponsored">
                                                <?php echo escape($url['name']); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php echo renderPagination($pagination, BASE_URL . '/products/' . ($category ? '?category=' . urlencode($category) : '')); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
