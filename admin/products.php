<?php
require_once '../config.php';
require_once '../database.php';
require_once '../functions.php';

requireLogin();

$pageTitle = 'Products';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    db()->delete('products', 'id = :id', ['id' => $id]);
    setFlash('success', 'Product deleted successfully.');
    redirect(BASE_URL . '/admin/products.php');
}

// Filter
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$where = '1';
$params = [];

if ($category) {
    $where .= ' AND category = :category';
    $params['category'] = $category;
}

if ($search) {
    $where .= ' AND (title LIKE :search OR description LIKE :search)';
    $params['search'] = '%' . $search . '%';
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 50;
$totalProducts = db()->count('products', $where, $params);
$pagination = paginate($totalProducts, $perPage, $page);

// Get products
$products = db()->fetchAll(
    "SELECT * FROM products WHERE {$where} ORDER BY title ASC LIMIT :limit OFFSET :offset",
    array_merge($params, ['limit' => $perPage, 'offset' => $pagination['offset']])
);

$categories = getCategories('products');

include 'header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <h1>Products (<?php echo number_format($totalProducts); ?>)</h1>
        <div>
            <a href="import-products.php" class="btn btn-secondary">Import from JSON</a>
            <a href="product-edit.php" class="btn btn-primary">Add New Product</a>
        </div>
    </div>

    <div class="filters">
        <form method="GET" class="filter-form">
            <input type="text" name="search" value="<?php echo escape($search); ?>" placeholder="Search products...">

            <select name="category">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo escape($cat); ?>" <?php echo $category === $cat ? 'selected' : ''; ?>>
                        <?php echo escape($cat); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-secondary">Filter</button>
            <a href="products.php" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <h3>No products found</h3>
            <p>Import products from your JSON file to get started.</p>
            <a href="import-products.php" class="btn btn-primary">Import Products</a>
        </div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="60">Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Affiliates</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product):
                    $urls = db()->fetchAll('SELECT * FROM product_urls WHERE product_id = :id ORDER BY display_order', ['id' => $product['id']]);
                ?>
                    <tr>
                        <td>
                            <?php if ($product['image']): ?>
                                <img src="<?php echo escape($product['image']); ?>" alt="" style="width: 50px; height: 50px; object-fit: cover;">
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><a href="product-edit.php?id=<?php echo $product['id']; ?>"><?php echo escape($product['title']); ?></a></strong>
                            <?php if ($product['featured']): ?>
                                <span class="badge badge-featured">Featured</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo escape($product['category']); ?></td>
                        <td><?php echo count($urls); ?> sources</td>
                        <td class="actions">
                            <a href="product-edit.php?id=<?php echo $product['id']; ?>" class="btn btn-sm">Edit</a>
                            <a href="<?php echo BASE_URL; ?>/product/<?php echo $product['slug']; ?>" target="_blank" class="btn btn-sm">View</a>
                            <a href="?delete=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php echo renderPagination($pagination, BASE_URL . '/admin/products.php' . ($category ? '?category=' . urlencode($category) : '')); ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
