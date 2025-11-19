<?php
require_once '../config.php';
require_once '../database.php';
require_once '../functions.php';

requireLogin();

$pageTitle = 'Blog Posts';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    db()->delete('posts', 'id = :id', ['id' => $id]);
    setFlash('success', 'Post deleted successfully.');
    redirect(BASE_URL . '/admin/posts.php');
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 20;
$totalPosts = db()->count('posts');
$pagination = paginate($totalPosts, $perPage, $page);

// Get posts
$posts = db()->fetchAll(
    'SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit OFFSET :offset',
    ['limit' => $perPage, 'offset' => $pagination['offset']]
);

include 'header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <h1>Blog Posts</h1>
        <a href="post-edit.php" class="btn btn-primary">Add New Post</a>
    </div>

    <?php if (empty($posts)): ?>
        <div class="empty-state">
            <h3>No blog posts yet</h3>
            <p>Create your first blog post to start sharing valuable content with your readers.</p>
            <a href="post-edit.php" class="btn btn-primary">Create First Post</a>
        </div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td>
                            <strong><a href="post-edit.php?id=<?php echo $post['id']; ?>"><?php echo escape($post['title']); ?></a></strong>
                            <?php if ($post['featured']): ?>
                                <span class="badge badge-featured">Featured</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo escape($post['category'] ?: '-'); ?></td>
                        <td><span class="badge badge-<?php echo $post['status']; ?>"><?php echo escape($post['status']); ?></span></td>
                        <td><?php echo number_format($post['views']); ?></td>
                        <td><?php echo formatDate($post['created_at'], 'M j, Y'); ?></td>
                        <td class="actions">
                            <a href="post-edit.php?id=<?php echo $post['id']; ?>" class="btn btn-sm">Edit</a>
                            <a href="<?php echo BASE_URL; ?>/blog/<?php echo $post['slug']; ?>" target="_blank" class="btn btn-sm">View</a>
                            <a href="?delete=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php echo renderPagination($pagination, BASE_URL . '/admin/posts.php'); ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
