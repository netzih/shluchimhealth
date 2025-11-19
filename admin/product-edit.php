<?php
require_once '../config.php';
require_once '../database.php';
require_once '../functions.php';

requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;

if ($id) {
    $product = db()->fetch('SELECT * FROM products WHERE id = :id', ['id' => $id]);
    if (!$product) {
        setFlash('error', 'Product not found.');
        redirect(BASE_URL . '/admin/products.php');
    }
}

$pageTitle = $id ? 'Edit Product' : 'New Product';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $featured = isset($_POST['featured']) ? 1 : 0;

    // Handle URLs (multiple affiliate links)
    $urls = [];
    if (isset($_POST['url_name']) && is_array($_POST['url_name'])) {
        foreach ($_POST['url_name'] as $index => $name) {
            $url = $_POST['url_link'][$index] ?? '';
            if (!empty($name) && !empty($url)) {
                $urls[] = ['name' => $name, 'url' => $url];
            }
        }
    }

    if (empty($title)) {
        setFlash('error', 'Title is required.');
    } else {
        $productId = $id ?: (int)($_POST['product_id'] ?? 0);
        if (!$productId) {
            $productId = time(); // Generate unique ID if creating new
        }

        $slug = $id ? generateUniqueSlug($title, 'products', $id) : generateUniqueSlug($title, 'products');

        $data = [
            'id' => $productId,
            'category' => $category,
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'slug' => $slug,
            'featured' => $featured,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($id) {
            // Update existing product
            unset($data['id']); // Don't update ID
            db()->update('products', $data, 'id = :id', ['id' => $id]);

            // Delete and re-add URLs
            db()->delete('product_urls', 'product_id = :id', ['id' => $id]);
        } else {
            // Insert new product
            $data['created_at'] = date('Y-m-d H:i:s');
            db()->insert('products', $data);
            $id = $productId;
        }

        // Insert URLs
        $order = 0;
        foreach ($urls as $urlData) {
            db()->insert('product_urls', [
                'product_id' => $id,
                'name' => $urlData['name'],
                'url' => $urlData['url'],
                'display_order' => $order++
            ]);
        }

        setFlash('success', $product ? 'Product updated successfully.' : 'Product created successfully.');
        redirect(BASE_URL . '/admin/product-edit.php?id=' . $id);
    }
}

// Get existing URLs
$urls = [];
if ($id) {
    $urls = db()->fetchAll('SELECT * FROM product_urls WHERE product_id = :id ORDER BY display_order', ['id' => $id]);
}

$categories = getCategories('products');

include 'header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <h1><?php echo $pageTitle; ?></h1>
        <a href="products.php" class="btn btn-secondary">← Back to Products</a>
    </div>

    <form method="POST" class="post-form">
        <div class="form-row">
            <div class="form-main">
                <?php if (!$id): ?>
                <div class="form-group">
                    <label for="product_id">Product ID</label>
                    <input type="number" id="product_id" name="product_id" value="<?php echo time(); ?>" class="form-control" required>
                    <small>Unique numeric ID for this product</small>
                </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" value="<?php echo escape($product['title'] ?? ''); ?>" required class="form-control-lg">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" class="form-control"><?php echo escape($product['description'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="image">Image URL</label>
                    <input type="url" id="image" name="image" value="<?php echo escape($product['image'] ?? ''); ?>" class="form-control">
                    <small>Direct URL to product image</small>
                </div>

                <div class="form-group">
                    <label>Affiliate Links</label>
                    <div id="urls-container">
                        <?php if (empty($urls)): ?>
                            <div class="url-row" style="display: flex; gap: 1rem; margin-bottom: 0.5rem;">
                                <input type="text" name="url_name[]" placeholder="Amazon" class="form-control" style="flex: 1;">
                                <input type="url" name="url_link[]" placeholder="https://amazon.com/..." class="form-control" style="flex: 2;">
                                <button type="button" onclick="removeUrlRow(this)" class="btn btn-sm btn-danger">Remove</button>
                            </div>
                        <?php else: ?>
                            <?php foreach ($urls as $url): ?>
                                <div class="url-row" style="display: flex; gap: 1rem; margin-bottom: 0.5rem;">
                                    <input type="text" name="url_name[]" value="<?php echo escape($url['name']); ?>" placeholder="Amazon" class="form-control" style="flex: 1;">
                                    <input type="url" name="url_link[]" value="<?php echo escape($url['url']); ?>" placeholder="https://amazon.com/..." class="form-control" style="flex: 2;">
                                    <button type="button" onclick="removeUrlRow(this)" class="btn btn-sm btn-danger">Remove</button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" onclick="addUrlRow()" class="btn btn-secondary btn-sm" style="margin-top: 0.5rem;">+ Add Another Link</button>
                </div>
            </div>

            <div class="form-sidebar">
                <div class="sidebar-section">
                    <h3>Settings</h3>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <input type="text" id="category" name="category" list="categories" value="<?php echo escape($product['category'] ?? ''); ?>" class="form-control">
                        <datalist id="categories">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo escape($cat); ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="featured" <?php echo ($product['featured'] ?? 0) ? 'checked' : ''; ?>>
                            Featured Product
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <?php echo $id ? 'Update Product' : 'Create Product'; ?>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function addUrlRow() {
    const container = document.getElementById('urls-container');
    const row = document.createElement('div');
    row.className = 'url-row';
    row.style.cssText = 'display: flex; gap: 1rem; margin-bottom: 0.5rem;';
    row.innerHTML = `
        <input type="text" name="url_name[]" placeholder="iHerb" class="form-control" style="flex: 1;">
        <input type="url" name="url_link[]" placeholder="https://..." class="form-control" style="flex: 2;">
        <button type="button" onclick="removeUrlRow(this)" class="btn btn-sm btn-danger">Remove</button>
    `;
    container.appendChild(row);
}

function removeUrlRow(button) {
    button.parentElement.remove();
}
</script>

<?php include 'footer.php'; ?>
