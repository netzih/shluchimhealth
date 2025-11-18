<?php
require_once '../config.php';
require_once '../database.php';
require_once '../functions.php';

requireLogin();

$stats = [
    'products' => db()->count('products'),
    'posts' => db()->count('posts'),
    'pages' => db()->count('pages'),
    'categories' => count(getCategories('products'))
];

$recentPosts = db()->fetchAll('SELECT * FROM posts ORDER BY created_at DESC LIMIT 5');
$recentProducts = db()->fetchAll('SELECT * FROM products ORDER BY created_at DESC LIMIT 5');

include 'header.php';
?>

<div class="dashboard">
    <h1>Dashboard</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <h3><?php echo number_format($stats['products']); ?></h3>
            <p>Products</p>
            <a href="products.php">Manage Products →</a>
        </div>

        <div class="stat-card">
            <h3><?php echo number_format($stats['posts']); ?></h3>
            <p>Blog Posts</p>
            <a href="posts.php">Manage Posts →</a>
        </div>

        <div class="stat-card">
            <h3><?php echo number_format($stats['pages']); ?></h3>
            <p>Pages</p>
            <a href="pages.php">Manage Pages →</a>
        </div>

        <div class="stat-card">
            <h3><?php echo number_format($stats['categories']); ?></h3>
            <p>Categories</p>
            <a href="products.php">View All →</a>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-section">
            <h2>Recent Blog Posts</h2>
            <?php if (empty($recentPosts)): ?>
                <p class="empty-state">No posts yet. <a href="post-edit.php">Create your first post</a></p>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentPosts as $post): ?>
                            <tr>
                                <td><a href="post-edit.php?id=<?php echo $post['id']; ?>"><?php echo escape($post['title']); ?></a></td>
                                <td><span class="badge badge-<?php echo $post['status']; ?>"><?php echo escape($post['status']); ?></span></td>
                                <td><?php echo formatDate($post['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div class="dashboard-section">
            <h2>Recent Products</h2>
            <?php if (empty($recentProducts)): ?>
                <p class="empty-state">No products yet. <a href="import-products.php">Import products</a></p>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentProducts as $product): ?>
                            <tr>
                                <td><a href="product-edit.php?id=<?php echo $product['id']; ?>"><?php echo escape($product['title']); ?></a></td>
                                <td><?php echo escape($product['category']); ?></td>
                                <td><?php echo formatDate($product['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
